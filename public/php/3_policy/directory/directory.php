<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

// сценарий никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	require_once(FNPW_PATH_PRIVATE . '/classes/Policy.inc');
	// Класс определения объектов справочника
	require_once(FNPW_PATH_PRIVATE . '/classes/Directory.inc');
	// Класс определения параметров интерфейса
	require_once(FNPW_PATH_PRIVATE . '/classes/Interface.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// создание экземпляра класса FNP_Policy
	$policy = new FNP_Policy();
	// создание экземпляра класса FNP_Directory
	$directory = new FNP_Directory();
	// создание экземпляра класса FNP_Interface
	$interface = new FNP_Interface();
	// создание экземпляра класса FNP_Directory_Time
	$time = new FNP_Directory_Time();
	// создание экземпляра класса FNP_Directory_Domain_Group
	$domain = new FNP_Directory_Domain_Group();
	// создание экземпляра класса FNP_Directory_Vlan_Group
	$vlan = new FNP_Directory_Vlan_Group();
	// создание экземпляра класса FNP_Directory_Host
	$host = new FNP_Directory_Host();
	// создание экземпляра класса FNP_Directory_Net
	$net = new FNP_Directory_Net();
	// создание экземпляра класса FNP_Directory_Net_Group
	$netgroup = new FNP_Directory_Net_Group();
	// создание экземпляра класса FNP_Directory_Service
	$service = new FNP_Directory_Service();
	// создание экземпляра класса FNP_Directory_Resource
	$resource = new FNP_Directory_Resource();

	// выполнение команды "policy list"
	if ($policy->setPolicy($session->getSessionId()) !== TRUE) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}
	// получение списка дополнительных политик
	$policy = $policy->getPolicy();

	// выполнение действий по установке параметров
	try {
		// выполнение команды "directory show"
		$directory->setDirectory($session->getSessionId());
		// получение коллекции объектов справочника
		$result = $directory->getDirectory();
		// выполнение команды "interface filter show"
		$interface->setFilter($session->getSessionId());
		// получение массива с параметрами фильтрующих интерфейсов
		$fltr = $interface->getFilter();
		// установка параметров для объекта типа "интервал времени"
		$time->setObject($result);
		// установка параметров для объекта типа "группа доменных имен"
		$domain->setObject($result);
		// установка параметров для объекта типа "группа vlan"
		$vlan->setObject($result);
		// установка параметров для объекта типа "хост"
		$host->setObject($result);
		// установка параметров для объекта типа "сеть"
		$net->setObject($result);
		// установка параметров для объекта типа "группа сетевых объектов"
		$netgroup->setObject($result);
		// установка параметров для объекта типа "сервис"
		$service->setObject($result);
		// установка параметров для объекта типа "ресурс"
		$resource->setObject($result);
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	$directory = [];
	
	$timeItems = [];
	for ($i = 0; $i < count($time->objects); $i++) {
		array_push($timeItems, ["label" => $time->name]);
		$time->nextObject();
	}
	array_push($directory, ["label" => "time_interval", "items" => $timeItems]);

	$domainItems = [];
	for ($i = 0; $i < count($domain->objects); $i++) {
		array_push($domainItems, ["label" => $domain->name]);
		$domain->nextObject();
	}
	array_push($directory, ["label" => "domain", "items" => $domainItems]);

	$vlanItems = [];
	for ($i = 0; $i < count($vlan->objects); $i++) {
		array_push($vlanItems, ["label" => $vlan->name]);
		$vlan->nextObject();
	}
	array_push($directory, ["label" => "vlan", "items" => $vlanItems]);

	$hostItems = [];
	for ($i = 0; $i < count($host->objects); $i++) {
		array_push($hostItems, ["label" => $host->name]);
		$host->nextObject();
	}
	array_push($directory, ["label" => "host", "items" => $hostItems]);

	$netItems = [];
	for ($i = 0; $i < count($net->objects); $i++) {
		array_push($netItems, ["label" => $net->name]);
		$net->nextObject();
	}
	array_push($directory, ["label" => "net", "items" => $netItems]);

	$netgroupItems = [];
	for ($i = 0; $i < count($netgroup->objects); $i++) {
		array_push($netgroupItems, ["label" => $netgroup->name]);
		$netgroup->nextObject();
	}
	array_push($directory, ["label" => "net_group", "items" => $netgroupItems]);

	$serviceItems = [];
	for ($i = 0; $i < count($service->objects); $i++) {
		array_push($serviceItems, ["label" => $service->name]);
		$service->nextObject();
	}
	array_push($directory, ["label" => "service", "items" => $serviceItems]);

	$resourceItems = [];
	for ($i = 0; $i < count($resource->objects); $i++) {
		array_push($resourceItems, ["label" => $resource->name]);
		$resource->nextObject();
	}
	array_push($directory, ["label" => "resource", "items" => $resourceItems]);
	
	$policies = [];
	for ($i = 0; $i < count($policy["name"]); $i++) {
		array_push($policies, $policy["name"][$i]);
	}
	
	echo json_encode(["directory" => $directory, "policies" =>  $policies]);
}
