<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

// этот модуль никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	// Класс PHP-сессии
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// класс проверки контрольных сумм
	require_once(FNPW_PATH_PRIVATE . '/classes/Checksum.inc');
	
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// инициализация класса FNP_Checksum
	$checksum = new FNP_Checksum();

	// выполним действия по установке параметров
	try {
		// установка контрольных сумм
		$checksum->setChecksums($session->getSessionId());
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	// получение массива со списком контрольных сумм
	$checksum = $checksum->getChecksum();
	$valid = [];
	$invalid = [];
	$success = true;
	for ($i = 0; $i < count($checksum); $i++) {
		if ($checksum[$i]["is_valid"])
			$valid += [$i => ["filename" => $checksum[$i]["filename"],
				"checksum" => $checksum[$i]["checksum"]]];
		else {
			$invalid += [$i => ["filename" => $checksum[$i]["filename"],
				"checksum" => $checksum[$i]["checksum"]]];
			$success = false;
		}
	}
	if ($success)
		$invalid = null;
	echo json_encode(["valid" => $valid, "invalid" => $invalid]);
}