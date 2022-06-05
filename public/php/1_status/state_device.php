<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

if ($_SERVER['REQUEST_METHOD'] != 'GET') { // неизвестный метод запроса
	http_response_code(405);
} else {
	// Класс PHP-сессии
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// Класс с информацией о системе
	require_once(FNPW_PATH_PRIVATE . '/classes/System.inc');
	// Класс с настройками системы
	require_once(FNPW_PATH_PRIVATE . '/classes/Settings.inc');
	// Класс c параметрами интерфейсов
	require_once(FNPW_PATH_PRIVATE . '/classes/Interface.inc');
	// Класс определения списка дополнительных политик
	require_once(FNPW_PATH_PRIVATE . '/classes/Policy.inc');
	
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// создадим экземпляр класса FNP_System
	$system = new FNP_System();
	// создадим экземпляр класса FNP_Settings
	$setting = new FNP_Settings();
	// создадим экземпляр класса FNP_Interface
	$interface = new FNP_Interface();
	// создание экземпляра класса FNP_Policy
	$policy = new FNP_Policy();
	
	// выполним действия по установке параметров
	try {
		// установка сведений о системе
		$system->setSystem($session->getSessionId());
		// установка системного времени
		$setting->setTime($session->getSessionId());
		// установка параметров управляющего интерфейса
		$interface->setControl($session->getSessionId());
		// выполнение команды "policy list"
		$policy->setPolicy($session->getSessionId());
		// получим массив с данными (список дополнительной конфигурации)
		$conf = $setting->getConfig();
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}
	
	// получение списка дополнительных политик
	$policy = $policy->getPolicy();
	
	$response = ["conf" => $conf["name"], "policy" => $policy["name"]];
	
	$response += ["device_name" => $system->device_name,
		"device_comment" => $system->device_comment, "cpu" => $system->cpu,
		"core_num" => $system->core_num, "memory" => $system->memory,
		"release" => $system->release, "serial_number" => $system->serial_number];
	
	$tmp = $system->eth_num;
	for ($i = 0; $i < $system->eth_num; $i++) {
		if ($i == 0)
			$tmp .= ": ";
		else
			$tmp .= ", ";
		$tmp .= $system->eth_name[$i];
	}
	$response += ["eth" => $tmp, "sdate" => $setting->sdate,
		"stime" => $setting->stime, "timeout" => $system->timeout,
		"ip_addr_mask" => $interface->ethc["detc"]["addr"] . "/" . $interface->ethc["detc"]["mask"]];
	
	switch ($interface->ethc["detc"]["carrier"]) {
	case 0:
		$response += ["carrier_media_mode_status" => "off"];
		break;
	case 1:
		$response += ["carrier_media_mode_status" => "on"];
		$info = $interface->ethc["detc"]["media"];
		if (strlen($interface->ethc["detc"]["mode"]) > 0)
			$info .= '/' . $interface->ethc["detc"]["mode"];
		$response += ["carrier_media_mode_info" => $info];
		break;
	case 2:
		$response += ["carrier_media_mode_status" => "na "];
		$info .= '(' . $interface->ethc["detc"]["media"] . ')';
		if (strlen($interface->ethc["detc"]["mode"]) > 0)
			$info .= '/(' . $interface->ethc["detc"]["mode"] . ')';
		$response += ["carrier_media_mode_info" => $info];
		break;
	}
	
	if ($interface->ethc["conf"]["lagg_state"]) {
		$response += ["port_aggregation_status" => "on"];
		$response += ["port_aggregation_info" =>
			$interface->ethc["conf"]["lagg_proto"] . ', ' . $interface->ethc["conf"]["lagg_ethf"]];
	} else {
		$response += ["port_aggregation_status" => "off"];
	}
	
	$status = "";
	switch ($system->is_filtd) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["packet_filtering" => $status];
	
	$status = "";
	switch ($system->is_csd) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["integrity_checking" => $status];
	
	$status = "";
	switch ($system->is_authd) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["authorization" => $status];
	
	$status = "";
	switch ($system->is_logd) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["registration" => $status];
	
	$status = "";
	switch ($system->is_had) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["high_availability" => $status];
	
	$status = "";
	switch ($system->is_shd) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["remote_administration" => $status];
	
	$status = "";
	switch ($system->is_web) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["web_interface" => $status];
	
	$status = "";
	switch ($system->is_snmpd) {
	case 0:
		$status .= 'off';
		break;
	case 1:
		$status .= 'process';
		break;
	case 3:
		$status .= 'on';
		break;
	}
	$response += ["snmp_interface" => $status];
	
	if ($system->delayed_reboot) {
		$response += ["delayed_reboot_status" => "on"];
		$response += ["delayed_reboot_timeout" => $system->delayed_reboot];
	} else {
		$response += ["delayed_reboot_status" => "off"];
	}
	
	echo json_encode($response);
}