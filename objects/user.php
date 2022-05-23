<?php
class User {

    // подключение к базе данных и таблице 'products'
    private $conn;
    private $table_name = "user";

    // свойства объекта
    public $id;
    public $name;
    public $email;
    public $phone;

    // конструктор для соединения с базой данных
    public function __construct($db){
        $this->conn = $db;
    }

    // метод read() - получение товаров
    function read(){

        // выбираем все записи
        $query = "SELECT
                p.id, p.name, p.email, p.phone
            FROM
                " . $this->table_name . " p
            ORDER BY
                p.name DESC";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // выполняем запрос
        $stmt->execute();

        return $stmt;
    }

    // метод create - создание товаров
    function create(){

        // запрос для вставки (создания) записей
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, email=:email, phone=:phone";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));

        // привязка значений
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // используется при заполнении формы обновления товара
    function readOne() {

        // запрос для чтения одной записи (товара)
        $query = "SELECT
                p.id, p.name, p.email, p.phone
            FROM
                " . $this->table_name . " p
            WHERE
                p.id = ?
            LIMIT
                0,1";

        // подготовка запроса
        $stmt = $this->conn->prepare( $query );

        // привязываем id товара, который будет обновлен
        $stmt->bindParam(1, $this->id);

        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
    }

    // метод update() - обновление товара
    function update(){

        // запрос для обновления записи (товара)
        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                email = :email,
                phone = :phone
            WHERE
                id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));

        // привязываем значения
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // метод delete - удаление товара
    function delete(){

        // запрос для удаления записи (товара)
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем id записи для удаления
        $stmt->bindParam(1, $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}
?>