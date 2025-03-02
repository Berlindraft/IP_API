<?php
// filepath: /c:/xampp/htdocs/php_api/app/repositories/StudentRepository.php

require_once __DIR__ . '/../config/database.php';

class StudentRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addStudent($name, $midterm, $final) {
        $stmt = $this->db->prepare("INSERT INTO student (st_name, st_midterm, st_final) VALUES (:name, :midterm, :final)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':midterm', $midterm);
        $stmt->bindParam(':final', $final);
        return $stmt->execute();
    }

    public function getAllStudents() {
        $stmt = $this->db->query("SELECT * FROM student");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudent($id) {
        $stmt = $this->db->prepare("SELECT * FROM student WHERE st_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStudent($id, $midterm, $final) {
        $stmt = $this->db->prepare("UPDATE student SET st_midterm = :midterm, st_final = :final WHERE st_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':midterm', $midterm);
        $stmt->bindParam(':final', $final);
        return $stmt->execute();
    }

    public function deleteStudent($id) {
        $stmt = $this->db->prepare("DELETE FROM student WHERE st_id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}

?>