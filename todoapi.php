<?php 

header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'todo';
$dbuser = 'root';
$dbpass = '55656354';

$conn = new PDO('mysql:host='. $host .';dbname='. $dbname, $dbuser, $dbpass);

$method = strtolower($_SERVER['REQUEST_METHOD']);


if ($method == 'post') {
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

} else {

	$get = $conn->query("SELECT id, name from todo");
	if ($get->rowCount() == 0)
	{
		echo json_encode([]);
		die();
	}

	echo json_encode($get->fetchAll(PDO::FETCH_OBJ));
	die();
}

?>