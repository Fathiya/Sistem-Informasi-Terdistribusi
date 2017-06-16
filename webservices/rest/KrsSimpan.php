<?php
require_once('../koneksi.php');
require_once('../MysqliDb.php');
require_once('../Krs.php');

class KrsSimpan {
	private $db;
	private $k;
	protected $krs1;

	public function KrsSimpan() {
        $this->db = new MysqliDb(HOST, USERNAME, PASSWORD, DATABASE);
        $this->krs = new Krs();

        $krs1 = $this->krs->getKrs();

		foreach ($krs1 as $k) {
			$this->krs1[] = [
				"url" => $this->makeUrlFromIndex($k['code']),
				"NAME" => $k['name'],
				"NIM" => $k['nim'],
				"CODE" => $k['code'],
				"TITLE" => $k['title'],
				"SEMESTER" => $k['semester'],
			];
		}
	}

	public function create($data) {
		if (isset($data['nim']) && isset($data['code']) && isset($data['semester'])) {
			$this->db->where('nim', $data['nim']);
			$student = $this->db->getOne('students');

			$this->db->where('code', $data['code']);
			$subject = $this->db->getOne('subjects');

			if ($student == null) {
				throw new UnexpectedValueException("mahasiswa tidak di temukan");
			} elseif ($subject == null) {
				throw new UnexpectedValueException("mahasiswa tidak di temukan");
			} else {
				$data2 = [
					'id_sunject' => $subject['id'],
					'id_student' => $student['id'],
					'semester' => $data['semester']
				];
				$insert = $this->db->insert('krs', $data2);
				if ($insert) {
					$data['url'] = $this->makeUrlFromIndex($data['code']);
					return $data;
				} else {
					throw new UnexpectedValueException("database error");
				}
			}
		}

		throw new UnexpectedValueException("tidak dapat membuat krs");
	}

	public function getAll() {
		return $this->krs1;
	}

	public function get($id) {
		$student = $this->getByNim($id);
		$subject = $this->getByCode($id);
		if ($student != null) {
			return $student;
		} elseif ($subject != null) {
			return $subject;
		} else {
			if ($student == null) {
				throw new UnexpectedValueException("mahasiswa tidak di temukan");
			}
			if ($subject == null) {
				throw new UnexpectedValueException("mahasiswa tidak di temukan");
			}
		}
	}

	public function getByStudentId($id) {
		$this->db->where("id", $id);
        $student = $this->db->getOne("students");
        if ($student == null) {
            return null;
        } else {
	        $k = $this->krs->getKrsByStudentNim($student['nim']);
	        if ($k == null) {
	            throw new Exception("Krs tidak ditemukan");
	        } else {
				$k['url'] = $this->makeUrlFromIndex($k['nim']);
	            return $k;
	        }
	    }
	}


	public function getBySubjectId($id) {
		$this->db->where("id", $id);
        $subject = $this->db->getOne("subjects");
        if ($subject == null) {
            return null;
        } else {
	        $k = $this->krs->getAttendedList($subject['code']);
	        if ($k == null) {
	            return null;
	        } else {
				$k['url'] = $this->makeUrlFromIndex($k['code']);
	            return $k;
	        }
	    }
	}

	public function getByNim($nim) {
        $krs1 = $this->krs->getKrsByStudentNim($nim);
        if ($krs1 == null) {
            return null;
        } else {
        	foreach ($krs1 as $key => $k) {
				$krs1[$key]['url'] = $this->makeUrlFromIndex($k['code']);
        	}
            return $krs1;
        }
	}


	public function getByCode($code) {
        $krs1 = $this->krs->getAttendedList($code);
        if ($k == null) {
            return null;
        } else {
        	foreach ($krs1 as $key => $k) {
				$krs1[$key]['url'] = $this->makeUrlFromIndex($k['nim']);
        	}
            return $krs1;
        }
	}

	protected function makeUrlFromIndex($code)
	{
		return "http://localhost/webserivices/rest/restkrs.php/krs/" . $code;
	}

	public function remove($id) {
		$this->db->where('nim', $id);
		$student = $this->db->getOne('students');

		$this->db->where('code', $id);
		$subject = $this->db->getOne('subjects');

		if ($student != null) {
			$this->removeByNim($student['id']);
		} elseif ($subject != null) {
			$this->removeByCode($subject['id']);
		} else {
			if ($student == null) {
				throw new UnexpectedValueException("tidak dapat menghapus krs, mahasiswa tida di temukan");
			}
			if ($subject == null) {
				throw new UnexpectedValueException("tidak dapat menghapus krs, mahasiswa tida di temukan");
			}
		}
	}

	public function removeByNim($id) {
		$this->db->where('id_student', $id);
		if ($this->db->delete('krs')) {
			return true;
		} else {
			return false;
		}
	}

	public function removeByCode($id) {
		$this->db->where('is_subject', $id);
		if ($this->db->delete('krs')) {
			return true;
		} else {
			return false;
		}
	}

	public function update($id, $data) {
		$this->db->where('nim', $id);
		$student = $this->db->getOne('students');

		$this->db->where('code', $id);
		$subject = $this->db->getOne('subjects');

		if ($student != null) {
			$this->updateByStudentId($student['id'], $data);
		} elseif ($subject != null) {
			$this->updateBySubjectId($subject['id'], $data);
		} else {
			if ($student == null) {
				throw new UnexpectedValueException("tidak dapat update krs, mahsiswa tidak ditemukan");
			}
			if ($subject == null) {
				throw new UnexpectedValueException("tidak dapat update krs, mahsiswa tidak ditemukan");
			}
		}
	}

	public function updateByStudentId($id, $data) {
		$this->db->where('code', $data['code']);
		$subject = $this->db->getOne('subjects');

		if ($subject == null) {
			throw new UnexpectedValueException("tidak dapat update krs, subject tidak ditemukan");
		} else {
			$data2 = [];
			if (isset($data['code'])) {
				$data2 = [
					'id_subject' => $subject['id'],
				];
			}
			if (isset($data['semester'])) {
				$data2 = [
					'semester' => $data['semester'],
				];
			}

			$this->db->where('student_id', $id);
			$this->db->update('krs', $data2);

			$k = $this->getByCode($subject['code']);
			if ($k == null) {
				throw new UnexpectedValueException("tidak dapat update krs");
			} else {
				return $k;
			}
		}
	}

	public function updateBySubjectId($id, $data) {
		$this->db->where('nim', $data['nim']);
		$student = $this->db->getOne('students');

		if ($student == null) {
			throw new UnexpectedValueException("tidak dapat update krs, mahasiswa tidak di temuakan");
		} else {
			$data2 = [];
			if (isset($data['code'])) {
				$data2 = [
					'id_subject' => $subject['id'],
				];
			}
			if (isset($data['semester'])) {
				$data2 = [
					'semester' => $data['semester'],
				];
			}
			$this->db->where('id_subject', $id);
			$this->db->update('krs', $data2);

			$k = $this->getByNim($student['nim']);
			if ($k == null) {
				throw new UnexpectedValueException("tidak dapat update krs");
			} else {
				return $k;
			}
		}
	}

}