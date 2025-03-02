<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentController {
    private $studentRepository;

    public function __construct() {
        $this->studentRepository = new StudentRepository();
    }

    public function addStudent($data) {
        if (!isset($data['name']) || !isset($data['midterm']) || !isset($data['final'])) {
            return json_encode(["error" => "Invalid input"]);
        }
        $this->studentRepository->addStudent($data['name'], $data['midterm'], $data['final']);
        return json_encode(["message" => "Student added successfully"]);
    }

    public function getAllStudents() {
        return json_encode($this->studentRepository->getAllStudents());
    }

    public function getStudent($id) {
        return json_encode($this->studentRepository->getStudent($id));
    }

    public function updateStudent($id, $data) {
        if (!isset($data['midterm']) || !isset($data['final'])) {
            return json_encode(["error" => "Invalid input"]);
        }
        $this->studentRepository->updateStudent($id, $data['midterm'], $data['final']);
        return json_encode(["message" => "Student updated successfully"]);
    }

    public function deleteStudent($id) {
        $this->studentRepository->deleteStudent($id);
        return json_encode(["message" => "Student deleted successfully"]);
    }
}

?>
