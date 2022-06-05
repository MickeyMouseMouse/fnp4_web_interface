<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';
require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
require_once(FNPW_PATH_PRIVATE . '/classes/Cli.inc');

// создаем экземпляр класса PHP_Session
$session = PHP_Session::getInstance();
// восстановим параметры сессии
$resume = $session->resume();

if (!defined('FNPW_TEST')) {
	http_response_code(500);
	exit();
}

// по запросу 'POST' выполним команду и вернем результат
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	// неизвестный метод запроса
	http_response_code(405);
} else {
	// инициализация команды для отправки на сервер
	$cmd = '';
	// получим команду из запроса
	if (isset($_POST['cmd'])) {
		$cmd = $_POST['cmd'];
	} else {
		http_response_code(400);
		exit();
	}
	
	// инициализируем экземпляр класса FNP_Cli
	$cli = new FNP_Cli($session->getSessionId());
	
	// проверим команду на == "exit"
	if(strcasecmp($cmd, "exit") == 0) {
		echo json_encode(["type" => "error", "result" => "The command is rejected"]);
	} else if ($cli->connect() === FNP_Cli::ERR_SOCK) {
		http_response_code(500);
	} else if ($cli->setCommand($cmd) === FNP_Cli::ERR_SHD) {
		echo json_encode(["type" => "error", "result" => "Invalid command string"]);
	} else if ($cli->executeCommand() === TRUE) {
		$parts = explode("-", $cli->getResult());
		switch($parts[1]) {
			case "I":
				$result_type = "Info";
				break;
			case "E":
				$result_type = "Error";
				break;
			case "W":
				$result_type = "Warning";
				break;
		}
		echo json_encode(["type" => $result_type, "result" => $parts[count($parts) - 1]]);
	} else {
		if ($cli->getTimeout()) { // тайм-аут неактивности
			$cli->disconnect();
			http_response_code(401);
			exit();
		}
		http_response_code(500);
	}
	$cli->disconnect();
}
