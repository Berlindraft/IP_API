<?php
require_once __DIR__ . '/../config/database.php';

class StudentRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addStudent($name, $midterm, $final, $finalGrade) {
        $stmt = $this->db->prepare("INSERT INTO student (st_name, st_midterm, st_final, st_final_grade) VALUES (:name, :midterm, :final, :finalGrade)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':midterm', $midterm);
        $stmt->bindParam(':final', $final);
        $stmt->bindParam(':finalGrade', $finalGrade);
        return $stmt->execute();
    }

    public function getAllStudents() {
        $stmt = $this->db->query("SELECT * FROM student");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudent($id) {
        $stmt = $this->db->prepare("SELECT * FROM student WHERE st_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStudentRecord($entity) {
        if (!isset($entity['midterm']) || !is_numeric($entity['midterm'])) {
            throw new Exception("Midterm grade is required and must be a number");
        }

        if (!isset($entity['final']) || !is_numeric($entity['final'])) {
            throw new Exception("Final grade is required and must be a number");
        }

        if (!isset($entity['id']) || !is_numeric($entity['id'])) {
            throw new Exception("Student ID is required and must be a number");
        }

        $query = "UPDATE student SET st_midterm = :midterm, st_final = :final, st_final_grade = :finalGrade WHERE st_id = :id";
        $params = [
            ':midterm' => $entity['midterm'],
            ':final' => $entity['final'],
            ':finalGrade' => $entity['finalGrade'],
            ':id' => $entity['id']
        ];

        $stmt = $this->db->prepare($query);
        $result = $stmt->execute($params);

        if (!$result) {
            error_log("🚨 Update failed: " . implode(" | ", $stmt->errorInfo()));
            throw new Exception("Database update failed");
        }
    }

    public function deleteStudent($id) {
        $stmt = $this->db->prepare("DELETE FROM student WHERE st_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
