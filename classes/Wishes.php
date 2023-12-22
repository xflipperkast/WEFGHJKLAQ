<?php
require_once 'Database.php';

class Wishes {
    private $conn;
    private $table_name = "wishes";

    public $id;
    public $user_id;
    public $wish;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Methode voor het toevoegen van een wens
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, wish=:wish";

        $stmt = $this->conn->prepare($query);

        // schoon de input op
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->wish = htmlspecialchars(strip_tags($this->wish));

        // bind de waarden
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":wish", $this->wish);

        // voer de query uit
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Methode voor het lezen van wensen
    public function read($user_id) {
        $query = "SELECT id, user_id, wish FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
    }
    
    public function readall() {
        $query = "SELECT id, user_id, wish FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    

    // Methode voor het bijwerken van een wens
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET wish = :wish WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // schoon de input op
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->wish = htmlspecialchars(strip_tags($this->wish));

        // bind de waarden
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":wish", $this->wish);

        // voer de query uit
        if ($stmt->execute()) {
            return $this->wish; // Return the updated wish value
        }
        

        return false;
    }

    // Methode voor het verwijderen van een wens
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // schoon de input op
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind de waarde
        $stmt->bindParam(":id", $this->id);

        // voer de query uit
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Andere benodigde methoden...
}
?>
