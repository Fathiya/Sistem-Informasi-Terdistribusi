<?php



require("StudentSimpan.php");

set_exception_handler(function ($ekeeee) {
	$code = $ekeeee->getCode() ?: 400;
	header("Content-Type: application/json", NULL, $code);
	echo json_encode(["error" => $ekeeee->getMessage()]);
	exit;
});

$verb = $_SERVER['REQUEST_METHOD'];
$url_pieces = explode('/', $_SERVER['PATH_INFO']);
$storage = new StudentSimpan();

if($url_pieces[1] != 'students') {
	throw new Exception('Unknown endpoint', 404);
}

switch($verb) {
	case 'GET':
		if(isset($url_pieces[2])) {
			try {
				$data = $storage->getOne($url_pieces[2]);
			} catch (UnexpectedValueException $ekeeee) {
				throw new Exception("Resource does not exist", 404);
			}
		} else {
			$data = $storage->getAll();
		}
		break;
	case 'POST':
	case 'PUT':
		$pr = json_decode(file_get_contents("php://input"), true);
		if (!$pr) {
			throw new Exception("data hilang atau invalid");
		}
		if ($verb == 'PUT') {
			$id = $url_pieces[2];
			$student = $storage->update($id, $pr);
			$status = 204;
		} else {
			$student = $storage->create($pr);
			$status = 201;
		}

		header("Location: " . $student['url'], null,$status);
		exit;
		break;
	case 'DELETE':
		$id = $url_pieces[2];
		$storage->remove($id);
		header("Location: http://localhost/webservices/rest/reststudent.php/students", null, 204);
		exit;
		break;
	default: 
		throw new Exception('Method Not Supported', 405);
}

header("Content-Type: application/json");
echo json_encode($data);