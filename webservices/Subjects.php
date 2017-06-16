<?php
require_once('koneksi.php');
require_once('MysqliDb.php');

class Subjects {
    private $db;

    public function Subjects() {
        $this->db = new MysqliDb(HOST, USERNAME, PASSWORD, DATABASE);
    }

    public function getSubjects() {
        $cols = ['code', 'title'];
        $subjects = $this->db->get('subjects', null, $cols);
        return $subjects;
    }

    public function getSubjectByCode($code) {
        $this->db->where('code', $code);
        $subject = $this->db->getOne("subjects");
        if ($subject == null) {
            throw new Exception("mahasiswa tidak di temukan");
        } else {
            unset($subject['id']);
            return $subject;
        }
    }
}