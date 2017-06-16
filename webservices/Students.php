<?php
require_once('koneksi.php');
require_once('MysqliDb.php');

class Students {
    private $db;

    public function Students() {
        $this->db = new MysqliDb(HOST, USERNAME, PASSWORD, DATABASE);
    }

    public function getStudents() {
        $cols = ['nim',  'name'];
        $students = $this->db->get('students', null, $cols);
        return $students;
    }

    public function getStudentByNim($nim) {
        $this->db->where("nim", $nim);
        $student = $this->db->getOne("students");
        if ($student == null) {
            throw new Exception("mahasiswa tidak di temukan");
        } else {
            unset($student['id']);
            return $student;
        }
    }
}