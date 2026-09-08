<?php

namespace App\Controllers;

class InformacionController extends BaseController
{
    public function pagina(string $slug)
    {
        $paginas = [
            'como-funciona' => [
                'grupo' => 'Plataforma',
                'titulo' => 'Cómo funciona',
                'intro' => 'Intercambia lo que ya no usas por algo que realmente necesitas.',
                'secciones' => [
                    ['titulo' => '1. Explora', 'texto' => 'Busca productos por categoría y encuentra publicaciones de la comunidad.'],
                    ['titulo' => '2. Conversa', 'texto' => 'Abre un chat con la persona propietaria del producto y acuerden el intercambio.'],
                    ['titulo' => '3. Confirma', 'texto' => 'Cuando ambas partes estén de acuerdo, confirma el intercambio y coordina la entrega.'],
                ],
            ],
            'explorar-articulos' => [
                'grupo' => 'Plataforma',
                'titulo' => 'Explorar artículos',
                'intro' => 'Encuentra productos disponibles para intercambiar dentro de Swapy.',
                'secciones' => [
                    ['titulo' => 'Catálogo', 'texto' => 'Navega por categorías, busca por nombre y revisa la descripción de cada producto.'],
                    ['titulo' => 'Conversaciones', 'texto' => 'Selecciona un producto para iniciar una conversación y negociar directamente.'],
                ],
            ],
            'categorias' => [
                'grupo' => 'Plataforma',
                'titulo' => 'Categorías',
                'intro' => 'Organizamos los productos para que encuentres lo que buscas rápidamente.',
                'secciones' => [
                    ['titulo' => 'Encuentra tu categoría', 'texto' => 'Explora tecnología, hogar, juegos, moda, deportes, mascotas, automóviles y otros artículos.'],
                    ['titulo' => 'Publica con claridad', 'texto' => 'Selecciona la categoría que mejor describa tu producto para facilitar el encuentro.'],
                ],
            ],
            'sobre-nosotros' => [
                'grupo' => 'Empresa',
                'titulo' => 'Sobre nosotros',
                'intro' => 'Swapy conecta personas para darle una segunda vida a los productos.',
                'secciones' => [
                    ['titulo' => 'Nuestra idea', 'texto' => 'Creamos una plataforma colombiana para intercambiar sin depender siempre del dinero.'],
                    ['titulo' => 'Nuestra comunidad', 'texto' => 'Promovemos intercambios claros, conversaciones respetuosas y consumo responsable.'],
                ],
            ],
            'blog' => [
                'grupo' => 'Empresa',
                'titulo' => 'Blog',
                'intro' => 'Ideas para intercambiar mejor y aprovechar lo que ya tienes.',
                'secciones' => [
                    ['titulo' => 'Próximamente', 'texto' => 'Aquí compartiremos consejos para publicar productos, negociar y preparar tus intercambios.'],
                ],
            ],
            'prensa' => [
                'grupo' => 'Empresa',
                'titulo' => 'Prensa',
                'intro' => 'Información institucional y novedades de Swapy.',
                'secciones' => [
                    ['titulo' => 'Contacto de prensa', 'texto' => 'Para solicitudes de información, utiliza el Centro de ayuda o el canal de contacto de la plataforma.'],
                ],
            ],
            'carreras' => [
                'grupo' => 'Empresa',
                'titulo' => 'Carreras',
                'intro' => 'Forma parte del equipo que hace posibles los intercambios.',
                'secciones' => [
                    ['titulo' => 'Oportunidades', 'texto' => 'Consulta el portal de empleados para conocer el proceso de acceso y gestión de intercambios.'],
                ],
            ],
            'centro-ayuda' => [
                'grupo' => 'Soporte',
                'titulo' => 'Centro de ayuda',
                'intro' => 'Encuentra orientación para usar Swapy con tranquilidad.',
                'secciones' => [
                    ['titulo' => '¿Necesitas ayuda?', 'texto' => 'Revisa las preguntas frecuentes o inicia sesión para enviar una solicitud al equipo de soporte.'],
                    ['titulo' => 'Intercambios', 'texto' => 'Recuerda describir con claridad el producto, el lugar y las condiciones acordadas.'],
                ],
            ],
            'terminos' => [
                'grupo' => 'Soporte',
                'titulo' => 'Términos de uso',
                'intro' => 'Reglas básicas para participar en la comunidad Swapy.',
                'secciones' => [
                    ['titulo' => 'Uso responsable', 'texto' => 'Publica información verdadera, respeta a las demás personas y cumple los acuerdos de intercambio.'],
                    ['titulo' => 'Seguridad', 'texto' => 'No compartas contraseñas ni datos sensibles en el chat. Coordina las entregas en lugares seguros.'],
                ],
            ],
            'privacidad' => [
                'grupo' => 'Soporte',
                'titulo' => 'Privacidad',
                'intro' => 'Tu información debe ser tratada con responsabilidad.',
                'secciones' => [
                    ['titulo' => 'Datos personales', 'texto' => 'Usamos los datos necesarios para gestionar tu cuenta, tus productos y tus conversaciones.'],
                    ['titulo' => 'Control', 'texto' => 'Puedes actualizar la información de tu perfil desde tu cuenta.'],
                ],
            ],
            'contacto' => [
                'grupo' => 'Soporte',
                'titulo' => 'Contacto',
                'intro' => 'Estamos aquí para ayudarte con tu experiencia en Swapy.',
                'secciones' => [
                    ['titulo' => 'Soporte', 'texto' => 'Inicia sesión y utiliza el módulo de soporte para enviar preguntas o reportar un problema.'],
                    ['titulo' => 'Comunidad', 'texto' => 'Para consultas sobre empleados, utiliza el portal de acceso correspondiente.'],
                ],
            ],
        ];

        if (!isset($paginas[$slug])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('informacion', $paginas[$slug]);
    }
}
