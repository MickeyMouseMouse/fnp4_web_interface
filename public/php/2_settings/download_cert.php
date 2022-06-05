<?php
// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

// этот модуль никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	if (!isset($_GET['cert'])) exit();
	
	// Класс PHP-сессии
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// Класс, содержащий информацию о системе
	require_once(FNPW_PATH_PRIVATE . '/classes/System.inc');
	// создаем экземпляр класса FNP_System
	$system = new FNP_System();
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();

	// выполним действия по установке параметров
	try {
		// установка сведений о системе
		$system->setSystem($session->getSessionId());
		// установка сертификата
		$system->setCertificate($session->getSessionId());
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	header("Content-Type: text/html");
	if ($_GET['cert'] == 'device') {
		$filename = "filename=" . $system->device_name . "_cert.pem";
		header("Content-Disposition: attachment; $filename");
		echo $system->dev_cert;
	} elseif ($_GET['cert'] == 'ca') {
		$filename = "filename=ca_chain.pem";
		header("Content-Disposition: attachment; $filename");
		echo $system->ca_cert;
	}
}