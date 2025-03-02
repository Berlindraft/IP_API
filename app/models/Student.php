<?php
require_once __DIR__ . '/../config/Database.php';

class Student {
    private $conn;
    private $table = "students";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addStudent($name, $midterm, $final) {
        $query = "INSERT INTO " . $this->table . " (name, midterm_score, final_score) VALUES (:name, :midterm, :final)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":midterm", $midterm);
        $stmt->bindParam(":final", $final);
        return $stmt->execute();
    }

    public function getAllStudents() {
        $query = "SELECT id, name, midterm_score, final_score, (0.4 * midterm_score + 0.6 * final_score) AS final_grade, 
                 CASE WHEN (0.4 * midterm_score + 0.6 * final_score) >= 75 THEN 'Pass' ELSE 'Fail' END AS status 
                 FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudent($id) {
        $query = "SELECT id, name, midterm_score, final_score, (0.4 * midterm_score + 0.6 * final_score) AS final_grade, 
                 CASE WHEN (0.4 * midterm_score + 0.6 * final_score) >= 75 THEN 'Pass' ELSE 'Fail' END AS status 
                 FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStudent($id, $midterm, $final) {
        $query = "UPDATE " . $this->table . " SET midterm_score = :midterm, final_score = :final WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":midterm", $midterm);
        $stmt->bindParam(":final", $final);
        return $stmt->execute();
    }

    public function deleteStudent($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
