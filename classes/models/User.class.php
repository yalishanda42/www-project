<?php

/**
 * A basic system user.
 */
class User {

    public $id;
    public $username;
    public $email;
    public $password;

    private $conn;
    const DB_TABLENAME = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $tablename = self::DB_TABLENAME;

        $query = "INSERT INTO $tablename
                  SET
                      username = :username
                      email = :email,
                      password = :password";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        return $stmt->execute();
    }

    public function emailExists() {
        $tablename = self::DB_TABLENAME;

        $query = "SELECT id, username, password
                  FROM $tablename
                  WHERE email = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if ($rows_count <= 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = (int)$row['id'];
        $this->username = $row['username'];
        $this->password = $row['password'];

        return true;
    }

    public function idExists() {
        $tablename = self::DB_TABLENAME;

        $query = "SELECT email, username, password
                  FROM $tablename
                  WHERE id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if ($rows_count <= 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->email = $row['email'];
        $this->username = $row['username'];
        $this->password = $row['password'];

        return true;
    }

    public function toArray() {
        return [
            "id" => (int)$this->id,
            "username" => $this->username,
            "email" => $this->email,
       ];
    }

    public function update() {
        $tablename = self::DB_TABLENAME;

        $password_set = !empty($this->password) ? ", password = :password" : "";

        $query = "UPDATE $tablename
                  SET
                      username = :username,
                      email = :email
                      {$password_set}
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);

        if(!empty($this->password)){
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}