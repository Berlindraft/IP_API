<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class StudentService {
    private $studentRepository;

    public function __construct() {
        $this->studentRepository = new StudentRepository();
    }

    public function calculateFinalGrade($midterm, $final) {
        return (0.4 * $midterm) + (0.6 * $final);
    }

    public function getPassFailStatus($finalGrade) {
        return $finalGrade >= 75 ? 'Pass' : 'Fail';
    }

    public function addStudent($name, $midterm, $final) {
        try {
            $finalGrade = $this->calculateFinalGrade($midterm, $final);
            return $this->studentRepository->addStudent($name, $midterm, $final, $finalGrade);
        } catch (Exception $e) {
            error_log("Error adding student: " . $e->getMessage());
            return false;
        }
    }

    public function updateStudent($id, $data) {
        try {
            $student = $this->studentRepository->getStudent($id);
            if (!$student) {
                throw new Exception("Student not found");
            }

            $midterm = isset($data['midterm']) ? (float)$data['midterm'] : (float)$student['st_midterm'];
            $final = isset($data['final']) ? (float)$data['final'] : (float)$student['st_final'];
            $finalGrade = $this->calculateFinalGrade($midterm, $final);

            $this->studentRepository->updateStudentRecord([
                'id' => $id,
                'midterm' => $midterm,
                'final' => $final,
                'finalGrade' => $finalGrade
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error updating student: " . $e->getMessage());
            return false;
        }
    }

    public function getAllStudents() {
        try {
            $students = $this->studentRepository->getAllStudents();
            foreach ($students as &$student) {
                $finalGrade = $this->calculateFinalGrade($student['st_midterm'], $student['st_final']);
                $student['finalGrade'] = $finalGrade;
                $student['status'] = $this->getPassFailStatus($finalGrade);
            }
            return $students;
        } catch (Exception $e) {
            error_log("Error fetching students: " . $e->getMessage());
            return [];
        }
    }

    public function getStudent($id) {
        try {
            $student = $this->studentRepository->getStudent($id);
            if ($student) {
                $finalGrade = $this->calculateFinalGrade($student['st_midterm'], $student['st_final']);
                $student['finalGrade'] = $finalGrade;
                $student['status'] = $this->getPassFailStatus($finalGrade);
            }
            return $student;
        } catch (Exception $e) {
            error_log("Error fetching student: " . $e->getMessage());
            return null;
        }
    }

    public function deleteStudent($id) {
        try {
            return $this->studentRepository->deleteStudent($id);
        } catch (Exception $e) {
            error_log("Error deleting student: " . $e->getMessage());
            return false;
        }
    }
}
?>
