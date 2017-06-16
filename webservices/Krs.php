<?php
require_once('koneksi.php');
require_once('MysqliDb.php');

class Krs {
    private $db;

    public function Krs() {
        $this->db = new MysqliDb(HOST, USERNAME, PASSWORD, DATABASE);
    }

    public function getKrs() {
        $k = $this->db->rawQuery('SELECT * FROM krs JOIN students, subjects WHERE krs.id_student = students.id AND krs.id_subject = subjects.id');
        return $k;
    }

    public function getKrsByStudentNim($nim) {
        $k = $this->db->rawQuery('SELECT c.code AS code, c.title AS title, a.semester AS semester FROM krs AS a JOIN students AS b, subjects AS c WHERE a.id_student = b.id AND a.id_subject = c.id AND b.nim = "' . $nim . '"');
        if ($k == null) {
            return null;
        } else {
            return $k;
        }
    }

    public function getAttendedList($code) {
        $attendedList = $this->db->rawQuery('SELECT b.nim AS nim, b.name AS name FROM krs AS a JOIN students AS b, subjects AS c WHERE a.id_student = b.id AND a.id_subject = c.id AND c.code = "' . $code . '"');
        if ($attendedList == null) {
            return null;
        } else {
            return $attendedList;
        }
    }
}
?>