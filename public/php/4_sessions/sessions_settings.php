<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// Класс настроек и записей сессий
	require_once(FNPW_PATH_PRIVATE . '/classes/Session.inc');
	// создадим экземпляр класса FNP_Session
	$sess_set = new FNP_Session_Setting();

	// выполним действия по установке параметров
	try {
		// выполним команду "session show"
		$sess_set->setSession($session->getSessionId());
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	// получим массив с данными (настройки сессии)
	echo json_encode($sess_set->getSession());
}
