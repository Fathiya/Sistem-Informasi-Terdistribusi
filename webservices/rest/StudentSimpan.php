<?php
require_once('../koneksi.php');
require_once('../MysqliDb.php');

class StudentSimpan {
	private $db;
	protected $students;

	public function StudentSimpan(){
        $this->db = new MysqliDb(HOST, USERNAME, PASSWORD, DATABASE);

		$co = ['nim', 'name'];
        $students = $this->db->get('students', null, $co);

		foreach ($students as $student) {
			$this->students[$student['nim']] = [
				"url" => $this->makeUrlFromIndex($student['nim']),
				"name" => $student['name'],
				
			];
		}
		
	}

	public function create($data) {
		if (isset($data['name']) && isset($data['nim'])) {
			$insert = $this->db->insert('students', $data);
			if ($insert) {
				$data['url'] = $this->makeUrlFromIndex($data['nim']);
				return $data;
			} else {
				throw new UnexpectedValueException("Could not create student, database query error");
			}
		}

		throw new UnexpectedValueException("Could not create student");
	}
	
	public function getAll() {
		return $this->students;
	}

	public function getOne($nim) {
		$this->db->where("nim", $nim);
        $student = $this->db->getOne("students");
        if ($student == null) {
            throw new Exception("Student not found");
        } else {
        	unset($student['id']);
			$student['url'] = $this->makeUrlFromIndex($student['nim']);
            return $student;
        }
	}

	protected function makeUrlFromIndex($nim)
	{
		return "http://localhost/webservices/rest/reststudent.php/students/" . $nim;
	}

	public function remove($nim) {
		$this->db->where('nim', $nim);
		if ($this->db->delete('students')) {
			return true;
		} else {
			return false;
		}
	}

	public function update($nim, $data) {
		$this->db->where('nim', $nim);
		$this->db->update('students', $data);

		$student = $this->getOne($nim);
		if ($student == null) {
			throw new UnexpectedValueException("Could not update student");
		} else {
			return $student;
		}
	}

}