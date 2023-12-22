<?php
require_once 'Database.php';

class Users {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Methode voor gebruikersregistratie
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, email=:email";
        $stmt = $this->conn->prepare($query);
    
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT); // Hash het wachtwoord
    
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashedPassword); // Gebruik de variabele voor het gehashte wachtwoord
        $stmt->bindParam(":email", $this->email);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Methode voor gebruikersinlog
    public function login() {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
    
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $hashedPassword = $row['password']; // Sla het gehashte wachtwoord op
    
            // Vergelijk het ingevoerde wachtwoord met het gehashte wachtwoord
            if (password_verify($this->password, $hashedPassword)) {
                return true;
            }
        }
        return false;
    }
    
    

    // Methode om e-mail te controleren
    public function emailExists() {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            return true;
        }

        return false;
    }

    // Inside your Users class
    public function getId() {
        return $this->id;
    }

}
?>
