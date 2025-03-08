<?php
require_once __DIR__ . '/../service/StudentService.php';

class StudentController {
    private $studentService;

    public function __construct() {
        $this->studentService = new StudentService();
    }

    public function addStudent($data) {
        if (!isset($data['name']) || !isset($data['midterm']) || !isset($data['final'])) {
            echo json_encode(["error" => "Invalid input"]);
            return;
        }

        $result = $this->studentService->addStudent($data['name'], $data['midterm'], $data['final']);
        echo json_encode($result ? ["message" => "Student added successfully"] : ["error" => "Failed to add student"]);
    }

    public function updateStudent($data, $id) {
        if (!is_numeric($id)) {
            echo json_encode(["error" => "Student ID must be a number"]);
            return;
        }

        $result = $this->studentService->updateStudent($id, $data);
        echo json_encode($result ? ["message" => "Student updated successfully"] : ["error" => "Failed to update student"]);
    }

    public function getAllStudents() {
        echo json_encode($this->studentService->getAllStudents());
    }

    public function getStudent($id) {
        $student = $this->studentService->getStudent($id);
        echo json_encode($student ? $student : ["error" => "Student not found"]);
    }

    public function deleteStudent($id) {
        if (!is_numeric($id)) {
            echo json_encode(["error" => "Student ID must be a number"]);
            return;
        }

        $result = $this->studentService->deleteStudent($id);
        echo json_encode($result ? ["message" => "Student deleted successfully"] : ["error" => "Failed to delete student"]);
    }

    public function getFinalGradeById($id) {
        $student = $this->studentService->getStudent($id);
        if ($student) {
            $finalGrade = $this->studentService->calculateFinalGrade($student['st_midterm'], $student['st_final']);
            $status = $this->studentService->getPassFailStatus($finalGrade);
            echo json_encode(["final_grade" => $finalGrade, "status" => $status]);
        } else {
            echo json_encode(["error" => "Student not found"]);
        }
    }

    public function getAllFinalGrades() {
        $students = $this->studentService->getAllStudents();
        $finalGrades = [];
        foreach ($students as $student) {
            $finalGrades[] = [
                "id" => $student['st_id'],
                "name" => $student['st_name'],
                "final_grade" => $student['finalGrade'],
                "status" => $student['status']
            ];
        }
        echo json_encode($finalGrades);
    }
}
?>
