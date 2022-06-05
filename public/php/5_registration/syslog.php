<?php
header('Content-Type: charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

// этот модуль никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	// Класс PHP-сессии
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// создадим экземпляр класса FNP_Cli
	$cli = new FNP_Cli($session->getSessionId());

	if ($cli->connect() === FNP_Cli::ERR_SOCK) { // ошибка соединения с сервером
		http_response_code(500);
		exit();
	}

	// зададим команду
	$cli->setCommand("log syslog show");

	// отправим запрос командному серверу
	$result = $cli->executeCommand();
	if ($result !== TRUE) {
		if ($cli->getTimeout()) { // тайм-аут неактивности
			$cli->disconnect();
			http_response_code(401);
			exit();
		}
		$cli->disconnect();
		http_response_code(500);
		exit();
	}

	$result = $cli->getResult();
	$cli->disconnect();
	
	$strings = explode("\n", $result);
	$logs = "";
	for ($i = 2; $i < count($strings); $i++) {
		if (strlen($strings[$i]) == 0)
			continue;
		$logs .= $strings[$i] . "\n";
	}
	
	echo $logs;
}