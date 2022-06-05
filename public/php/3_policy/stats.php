<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

// этот модуль никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD']!= 'GET') {
	http_response_code(405);
} else {
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	
	// Класс определения правил политики
	require_once(FNPW_PATH_PRIVATE . '/classes/Rules.inc');
	// Инициализируем экземпляр класса FNP_Rules_Stats
	$statistics = new FNP_Rules_Stats();

	// Выполним команду "rule stats show"
	if ($statistics->setRuleStats($session->getSessionId()) !== TRUE) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	// Получим массив с данными
	$stats = $statistics->getRuleStats();
	
	$rules = [];
	for ($i = 0; $i < count($stats['name']); $i++) {
		array_push($rules, [
			"action" => $stats['act'][$i],
			"name" => $stats['name'][$i],
			"time" => $stats['time'][$i],
			"packets" => $stats['pkts'][$i],
			"bytes" => $stats['bts'][$i],
			"comment" => cutstr($stats['comment'][$i])
		]);
	}
	
	echo json_encode(["stats" => $rules]);
}

function cutstr($string) {
	$retval = $string;
	if (mb_strlen($string) > 32) {
		$retval  = mb_substr($string,0,32);
		$retval .= '..';
	}
	return $retval;
}
