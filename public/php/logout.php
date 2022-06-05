<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';
require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');

// создаем экземпляр класса PHP_Session
$session = PHP_Session::getInstance();

// восстановим параметры сессии
$resume = $session->resume();

// создаем объект класса FNP_Cli
$cli = new FNP_Cli($session->getSessionId());

// устанавливаем соединение с командным сервером
if ($cli->connect() == FNP_Cli::ERR_SOCK) {
	http_response_code(500);
} else {
	// отправим командному серверу запрос завершения сеанса пользователя
	$report = $cli->logout();
}

// закроем соединение с сервером
$cli->disconnect();
// удалим сессию пользователя
$session->delete();
?>