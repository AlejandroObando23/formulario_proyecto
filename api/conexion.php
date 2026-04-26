<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
require __DIR__ . '/../vendor/autoload.php';
$mongoUri = getenv('MONGODB_URI');

if (!$mongoUri) {
    http_response_code(500);
    echo json_encode(['error' => 'La variable de entorno MONGODB_URI no está configurada']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // La URI está bien, usa tus credenciales root123
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);



    try {
        $client = new MongoDB\Client($mongoUri);

        // Sincronizado con tu captura de Compass: DB 'students', Collection 'Customer'
        $collection = $client->mi_base_de_datos->usuarios;


        // Cambiamos las llaves a inglés para que coincidan con tus otros registros en Compass
        $new_customer = [
            'name' => $_POST['nombre'],    // Cambiado 'nombre' por 'name'
            'email' => $_POST['email'],
            'phone' => $_POST['telefono'],  // Cambiado 'telefono' por 'phone'
            'date' => $_POST['fecha'],
            'time' => $_POST['hora'],
            'hair_style' => $_POST['corte'],
            'hair_type' => $_POST['cabello'],
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];

        $resultado = $collection->insertOne($new_customer);
        if ($resultado->getInsertedCount() == 1) {
            echo json_encode(['message' => '¡Registro exitoso en MongoDB Atlas a través de PHP!']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar el registro en la base de datos.']);
        }


    } catch (Exception $e) {
        // Tip: En producción no muestres el mensaje de error completo, pero para clase está bien
        http_response_code(500);
        echo json_encode(['error' => 'Error al conectar o insertar en MongoDB: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Acceso no autorizado.']);
}