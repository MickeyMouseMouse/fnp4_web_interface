<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'fnpw.inc';
require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
require_once(FNPW_PATH_PRIVATE . '/classes/Cli.inc');

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	// неизвестный метод запроса
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
		$cli->setCommand("system halt");
		$cli->executeCommand();
		$cli->disconnect();
		$session->delete();
	}
}