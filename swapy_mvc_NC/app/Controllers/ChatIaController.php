<?php

namespace App\Controllers;

class ChatIaController extends BaseController
{
    /**
     * Clave de API de Groq.
     * Pega aquí tu API key (la que generaste en https://console.groq.com/keys).
     */
    private string $groqApiKey;

    private string $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private string $modelo  = 'openai/gpt-oss-20b';

    public function __construct()
    {
        $this->groqApiKey = (string) env('GROQ_API_KEY');
    }

    public function responder()
    {
        $this->response->setContentType('application/json');

        try {
            $session = \Config\Services::session();
            if (!$session->get('isLoggedIn')) {
                return $this->response->setJSON(['ok' => false, 'error' => 'Debes iniciar sesión.']);
            }

            if ($this->groqApiKey === '') {
                return $this->response->setJSON(['ok' => false, 'error' => 'La IA no está configurada. Define GROQ_API_KEY en .env.']);
            }

            $mensajeUsuario = trim((string) $this->request->getPost('mensaje'));
            $idProducto     = (int) ($this->request->getPost('id_producto') ?? 0);
            $historialJson  = (string) ($this->request->getPost('historial') ?? '[]');
            $idConversacion = (int) ($this->request->getPost('id_conversacion') ?? 0);

            if ($mensajeUsuario === '' || $idProducto <= 0) {
                return $this->response->setJSON(['ok' => false, 'error' => 'Faltan datos del mensaje o del producto.']);
            }

            // 1. Traer el producto desde la base de datos
            $productoModel = new \App\Models\ProductoModel();
            $producto = $productoModel->find($idProducto);

            if (!$producto) {
                return $this->response->setJSON(['ok' => false, 'error' => 'Producto no encontrado.']);
            }

            $nombreProducto = $producto['nombre_producto'] ?? 'el producto';
            $descProducto   = $producto['desc_producto'] ?? '';
            $valorProducto  = $producto['valor_estimado'] ?? 0;

            // 2. Armar el prompt de sistema: la IA actúa como el dueño/vendedor del producto
            $promptSistema = "Eres una persona real que está vendiendo/intercambiando un producto en Swapy, "
                . "una plataforma colombiana de intercambio de artículos. Estás chateando directamente con un posible "
                . "comprador interesado en tu producto. Habla de forma natural, cercana y coloquial, como lo haría "
                . "cualquier persona en Colombia negociando por WhatsApp. No te identifiques como inteligencia artificial "
                . "ni menciones que eres un modelo de lenguaje, bajo ninguna circunstancia.\n\n"
                . "Datos de tu producto:\n"
                . "- Nombre: {$nombreProducto}\n"
                . "- Descripción: {$descProducto}\n"
                . "- Valor de referencia para el intercambio: \${$valorProducto} COP\n\n"
                . "Tu personalidad como vendedor:\n"
                . "- Eres amable pero defiendes el valor de tu producto.\n"
                . "- Puedes aceptar contraofertas razonables (hasta un 15-20% menos del valor de referencia).\n"
                . "- Si te ofrecen mucho menos, puedes negociar a medio camino o explicar por qué no puedes bajar más.\n"
                . "- Si la persona acepta un precio o intercambio, confírmalo con entusiasmo.\n"
                . "- Responde siempre en español, en mensajes cortos como los de un chat real (no más de 3-4 líneas).\n"
                . "- No repitas el nombre del producto en cada mensaje, ya están hablando de él.";

            // 3. Reconstruir el historial reciente que manda el frontend
            $historial = json_decode($historialJson, true);
            if (!is_array($historial)) {
                $historial = [];
            }

            $mensajes = [
                ['role' => 'system', 'content' => $promptSistema],
            ];

            // Limitar a los últimos 20 mensajes para no exceder contexto innecesariamente
            $historialReciente = array_slice($historial, -20);
            foreach ($historialReciente as $m) {
                $rol = ($m['rol'] ?? '') === 'usuario' ? 'user' : 'assistant';
                $texto = trim((string) ($m['texto'] ?? ''));
                if ($texto !== '') {
                    $mensajes[] = ['role' => $rol, 'content' => $texto];
                }
            }

            $mensajes[] = ['role' => 'user', 'content' => $mensajeUsuario];

            // 4. Llamar a la API de Groq
            $payload = [
                'model'       => $this->modelo,
                'messages'    => $mensajes,
                'temperature' => 0.8,
                // Este modelo usa parte de la salida para razonar antes de responder.
                'max_tokens'  => 800,
            ];

            $ch = curl_init($this->groqUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->groqApiKey,
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $respuestaCruda = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                log_message('error', 'Error cURL Groq: ' . $curlError);
                return $this->response->setJSON(['ok' => false, 'error' => 'No se pudo conectar con el servicio de IA.']);
            }

            $data = json_decode($respuestaCruda, true);

            if ($httpCode !== 200 || empty($data['choices'][0]['message']['content'])) {
                log_message('error', 'Respuesta inesperada de Groq: ' . $respuestaCruda);
                return $this->response->setJSON(['ok' => false, 'error' => 'El servicio de IA no respondió correctamente.']);
            }

            $respuestaIa = trim($data['choices'][0]['message']['content']);

            if ($idConversacion > 0) {
                ConversacionController::guardarMensaje($idConversacion, (int) $session->get('id_usuario'), $mensajeUsuario, 'usuario');
                ConversacionController::guardarMensaje($idConversacion, 0, $respuestaIa, 'ia');
            }

            return $this->response->setJSON([
                'ok' => true,
                'respuesta' => $respuestaIa,
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'Error en ChatIaController::responder: ' . $e->getMessage());
            return $this->response->setJSON(['ok' => false, 'error' => 'Error del servidor.']);
        }
    }
}