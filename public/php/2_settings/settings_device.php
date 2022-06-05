<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	// Класс PHP-сессии
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// Класс, содержащий информацию об настройках системы
	require_once(FNPW_PATH_PRIVATE . '/classes/Settings.inc');
	// Класс, содержащий информацию о системе
	require_once(FNPW_PATH_PRIVATE . '/classes/System.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// создаем экземпляр класса FNP_Settings
	$settings = new FNP_Settings();
	// создаем экземпляр класса FNP_System
	$system = new FNP_System();

	// выполним действия по установке параметров
	try {
		// установка параметров конфигурации
		$settings->setConfig($session->getSessionId());
		// получим массив с данными (список дополнительной конфигурации)
		$conf = $settings->getConfig();
		// установка параметров системного времени 
		$settings->setTime($session->getSessionId());
		// получим массив с данными (системное время и настройки NTP)
		$time = $settings->getTime();
		// установка сведений о системе
		$system->setSystem($session->getSessionId());
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	$response = ["device_name" => $system->device_name,
		"device_comment" => $system->device_comment,
		"date_time" => $time['date'] . " " . $time['time'],
		"time_zone" => $time['time_zone'] . ", " . $time['gmt_zone'],
		"ntp" => $time['ntp'], "ntp_log" => $time['ntp_log'],
		"ntp_timeout" => $time['ntp_tout']];
	
	if ($time['ntp_srv'] == '0.0.0.0')
		$response += ["ntp_server" => "undefine"];
	else
		$response += ["ntp_server" => $time['ntp_srv']];
	
	echo json_encode($response);
}
