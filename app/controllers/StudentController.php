<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentController {
    private $studentRepository;

    public function __construct() {
        $this->studentRepository = new StudentRepository();
    }

    public function addStudent($data) {
        if (!isset($data['name']) || !isset($data['midterm']) || !isset($data['final'])) {
            echo json_encode(["error" => "Invalid input"]);
            return;
        }
        $finalGrade = $this->calculateFinalGrade($data['midterm'], $data['final']);
        $result = $this->studentRepository->addStudent($data['name'], $data['midterm'], $data['final'], $finalGrade);
        if ($result) {
            echo json_encode(["message" => "Student added successfully"]);
        } else {
            echo json_encode(["error" => "Failed to add student"]);
        }
    }

    public function updateStudent($data, $id) {
        if (!is_numeric($id)) {
            echo json_encode(["error" => "Student ID is required and must be a number"]);
            return;
        }

        $student = $this->studentRepository->getStudent($id);
        if (!$student) {
            echo json_encode(["error" => "Student not found"]);
            return;
        }

        $midterm = isset($data['midterm']) ? (float)$data['midterm'] : (float)$student['st_midterm'];
        $final = isset($data['final']) ? (float)$data['final'] : (float)$student['st_final'];
        $finalGrade = $this->calculateFinalGrade($midterm, $final);

        $entity = [
            'id' => $id,
            'midterm' => $midterm,
            'final' => $final,
            'finalGrade' => $finalGrade
        ];

        try {
            $this->studentRepository->updateStudentRecord($entity);
            echo json_encode(["message" => "Student updated successfully"]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function getAllStudents() {
        $students = $this->studentRepository->getAllStudents();
        foreach ($students as &$student) {
            $student['status'] = $this->getPassFailStatus($student['st_final']);
        }
        echo json_encode($students);
    }

    public function getStudent($id) {
        $student = $this->studentRepository->getStudent($id);
        if ($student) {
            $student['status'] = $this->getPassFailStatus($student['st_final']);
            echo json_encode($student);
        } else {
            echo json_encode(["error" => "Student not found"]);
        }
    }

    public function deleteStudent($id) {
        $result = $this->studentRepository->deleteStudent($id);
        if ($result) {
            echo json_encode(["message" => "Student deleted successfully"]);
        } else {
            echo json_encode(["error" => "Failed to delete student"]);
        }
    }

    public function getFinalGradeById($id) {
        $student = $this->studentRepository->getStudent($id);
        if ($student) {
            $finalGrade = $student['st_final'];
            $status = $this->getPassFailStatus($finalGrade);
            echo json_encode(["final_grade" => $finalGrade, "status" => $status]);
        } else {
            echo json_encode(["error" => "Student not found"]);
        }
    }

    public function getAllFinalGrades() {
        $students = $this->studentRepository->getAllStudents();
        $finalGrades = [];
        foreach ($students as $student) {
            $finalGrades[] = [
                "id" => $student['st_id'],
                "name" => $student['st_name'],
                "final_grade" => $student['st_final'],
                "status" => $this->getPassFailStatus($student['st_final'])
            ];
        }
        echo json_encode($finalGrades);
    }

    private function getPassFailStatus($finalGrade) {
        return $finalGrade >= 75 ? 'Pass' : 'Fail';
    }

    private function calculateFinalGrade($midterm, $final) {
        return (0.4 * $midterm) + (0.6 * $final);
    }
}
?>
