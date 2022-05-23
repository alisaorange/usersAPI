<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных
include_once '../config/database.php';

// создание объекта товара
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// получаем отправленные данные
$data = json_decode(file_get_contents("php://input"));

// убеждаемся, что данные не пусты
if (
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->phone)
) {

    // устанавливаем значения свойств товара
    $user->name = $data->name;
    $user->email = $data->email;
    $user->phone = $data->phone;

    // создание товара
    if($user->create()){

        // установим код ответа - 201 создано
        http_response_code(201);

        // сообщим пользователю
        echo json_encode(array("message" => "Пользователь был создан."), JSON_UNESCAPED_UNICODE);
    }

    // если не удается создать товар, сообщим пользователю
    else {

        // установим код ответа - 503 сервис недоступен
        http_response_code(503);

        // сообщим пользователю
        echo json_encode(array("message" => "Не удалось создать пользователя."), JSON_UNESCAPED_UNICODE);
    }
} else { // сообщим пользователю что данные неполные

    // установим код ответа - 400 неверный запрос
    http_response_code(400);

    // сообщим пользователю
    echo json_encode(array("message" => "Невозможно создать пользователя. Данные неполные.".json_encode($data)), JSON_UNESCAPED_UNICODE);
}
?>
