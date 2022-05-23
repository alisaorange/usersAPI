<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// подключение файла для соединения с базой и файл с объектом
include_once '../config/database.php';
include_once '../objects/user.php';

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$user = new User($db);

// установим свойство ID записи для чтения
$user->id = isset($_GET['id']) ? $_GET['id'] : die();

// прочитаем детали товара для редактирования
$user->readOne();

if ($user->name!=null) {

    // создание массива
    $user_arr = array(
        "id" =>  $user->id,
        "name" => $user->name,
        "email" => $user->email,
        "phone" => $user->phone
    );

    // код ответа - 200 OK
    http_response_code(200);

    // вывод в формате json
    echo json_encode($user_arr);
}

else {
    // код ответа - 404 Не найдено
    http_response_code(404);

    // сообщим пользователю, что товар не существует
    echo json_encode(array("message" => "Пользователь не существует."), JSON_UNESCAPED_UNICODE);
}
?>
