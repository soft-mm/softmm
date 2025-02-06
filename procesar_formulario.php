<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén el token de reCAPTCHA
    $recaptcha_response = $_POST['recaptcha_response'];

    // Tu clave secreta de reCAPTCHA
    $secret_key = '6LfVWs8qAAAAAAgTSe1AkV4NsM_lf73ynE1asFdH'; // Reemplaza con tu clave secreta

    // URL de la API de reCAPTCHA
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    // Datos para enviar a la API
    $data = [
        'secret' => $secret_key,
        'response' => $recaptcha_response,
    ];

    // Configuración de la solicitud HTTP
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    // Crear el contexto de la solicitud
    $context = stream_context_create($options);

    // Enviar la solicitud y obtener la respuesta
    $result = file_get_contents($url, false, $context);

    // Decodificar la respuesta JSON
    $resultado_json = json_decode($result);

    // Verificar si el token es válido
    if ($resultado_json->success && $resultado_json->score >= 0.5) {
        // El CAPTCHA fue exitoso, procesa el formulario
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];

        // Aquí puedes guardar los datos en una base de datos, enviar un correo, etc.
        echo "Formulario enviado correctamente. Nombre: $nombre, Email: $email";
    } else {
        // El CAPTCHA falló, muestra un error
        echo "Error: CAPTCHA no válido.";
    }
} else {
    // Si alguien intenta acceder directamente al archivo, redirige o muestra un error
    echo "Acceso no permitido.";
}
?>