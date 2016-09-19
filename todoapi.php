<?php 

header('Content-Type: application/json');

$host = 'localhost';
$dbname = '';
$dbuser = '';
$dbpass = '';

$conn = new PDO('mysql:host='. $host .';dbname='. $dbname, $dbuser, $dbpass);

$method = strtolower($_SERVER['REQUEST_METHOD']);

switch ($method) {
	case 'post':
		if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
			http_response_code(400);
			die();
		}

		$add = $conn->prepare("INSERT INTO todo (name) values (:name)");
		$add->execute([
			'name' => $_POST['name'],
		]);

		http_response_code(200);
		die();
		break;

	case 'get':
		$get = $conn->query("SELECT * from todo");
		if ($get->rowCount() == 0)
		{
			echo json_encode([]);
			die();
		}

		echo json_encode($get->fetchAll(PDO::FETCH_OBJ));
		die();
		break;

	case 'delete':
		parse_str(file_get_contents('php://input'), $payload);
		if (!isset($payload['id']) || empty(trim($payload['id'])))  {
			http_response_code(400);
			die();
		} else {
			$delete = $conn->prepare('DELETE FROM todo WHERE id = :id');
			$delete->execute([
				'id' => $payload[id],
			]);

			http_response_code(200);
			die();
		}

		break;
	default:
		http_response_code(405);
		break;
}