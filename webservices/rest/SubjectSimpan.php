<?php
require_once('../koneksi.php');
require_once('../MysqliDb.php');

class SubjectSimpan {
	private $db;
	protected $subjects;

	public function SubjectSimpan() {
        $this->db = new MysqliDb(HOST, USERNAME, PASSWORD, DATABASE);

		$co = ['code', 'title'];
        $subjects = $this->db->get('subjects', null, $co);

		foreach ($subjects as $subject) {
			$this->subjects[$subject['code']] = [
				"url" => $this->makeUrlFromIndex($subject['code']),
				"title" => $subject['title']
			];
		}
	}

	public function create($data) {
		if (isset($data['code']) && isset($data['title'])) {
			$insert = $this->db->insert('subjects', $data);
			if ($insert) {
				$data['url'] = $this->makeUrlFromIndex($data['code']);
				return $data;
			} else {
				throw new UnexpectedValueException("tidak dapat membuat subject, database query error");
			}
		}

		throw new UnexpectedValueException("tidak dapat membuat subject");
	}

	public function getAll() {
		return $this->subjects;
	}

	public function getOne($code) {
		$this->db->where("code", $code);
        $subject = $this->db->getOne("subjects");
        if ($subject == null) {
            throw new Exception("Subject not found");
        } else {
        	unset($subject['id']);
			$subject['url'] = $this->makeUrlFromIndex($subject['code']);
            return $subject;
        }
	}

	protected function makeUrlFromIndex($code)
	{
		return "http://localhost/webservices/rest/restsubject.php/subjects/" . $code;
	}

	public function remove($code) {
		$this->db->where('code', $code);
		if ($this->db->delete('subjects')) {
			return true;
		} else {
			return false;
		}
	}

	public function update($code, $data) {
		$this->db->where('code', $code);
		$this->db->update('subjects', $data);

		$subject = $this->getOne($code);
		if ($subject == null) {
			throw new UnexpectedValueException("tidak dapat update subject");
		} else {
			return $subject;
		}
	}

}