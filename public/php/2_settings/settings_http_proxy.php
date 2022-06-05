<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	require_once(FNPW_PATH_PRIVATE . '/classes/System.inc');

	$session = PHP_Session::getInstance();
	$resume = $session->resume();
	$system = new FNP_System();
	$proxy = new FNP_System_Proxy();
	//$proxy_acl = new FNP_System_Proxy_Acl();
	$dns = new FNP_System_DNS();

	try { // выполним действия по установке параметров
		$system->setSystem($session->getSessionId());
		$proxy->show($session->getSessionId());
		//$proxy_acl->show($session->getSessionId());
		$dns->show($session->getSessionId());
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	$response= ["proxy_state" => $proxy->state, "proxy_interface" => $proxy->interface,
		"ip" => $proxy->address, "mask" => $proxy->mask, "port" => $proxy->port,
		"dns" => $dns->list];
	
	$eths = [];
	for ($i = 0; $i < $system->eth_num; $i++)
		array_push($eths, $system->eth_name[$i]);
	$response += ["eths" => $eths];
	
	echo json_encode($response);
}