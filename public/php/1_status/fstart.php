<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';
require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
require_once(FNPW_PATH_PRIVATE . '/classes/Cli.inc');

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	$session = PHP_Session::getInstance();
	$resume = $session->resume();
	if (!$session->getUserInfo()['user_logged']) { // пользователь должен быть залогинен
		http_response_code(401);
		exit();
	}
	$cli = new FNP_Cli($session->getSessionId());
	if ($cli->connect() === FNP_Cli::ERR_SOCK) {
		http_response_code(500);
	} else {
		if ($_GET['cmd']== "start") {
			$cli->setCommand("filter start");
		} elseif ($_GET['cmd']== "stop") {
			$cli->setCommand("filter stop");
		} elseif ($_GET['cmd']== "restart") {
			$cli->setCommand("filter restart");
		}
		$cli->executeCommand();
		$cli->disconnect();
	}
}