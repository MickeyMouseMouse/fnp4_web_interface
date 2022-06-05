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
	// Класс, содержащий информацию параметров интерфейса
	require_once(FNPW_PATH_PRIVATE . '/classes/Interface.inc');
	// Класс, содержащий информацию о процессах фильтрации
	require_once(FNPW_PATH_PRIVATE . '/classes/Filter.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// создадим экземпляра класса FNP_Interface
	$interface = new FNP_Interface();
	// создадим экземпляр класса FNP_Filter
	$filter = new FNP_Filter();

	// выполним действия по установке параметров
	try {
		// установка настроек фильтрующего интерфейса
		$interface->setFilter($session->getSessionId());
		// получим массив с данными (список элементов доступа)
		$fltr = $interface->getFilter();
		// установка параметров пакетного фильтра
		$filter->setFilter($session->getSessionId());
		// получим массив с данными (информация о трафике)
		$filter = $filter->getFilter();
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}
	
	$response = ["start_time" => $filter["fstart"], "uptime" => $filter["fuptime"]];
	if ($filter["ethf_num"] > 0) {
		$ethf_names = [];
		for ($i = 0; $i < $filter["ethf_num"]; $i++) {
			array_push($ethf_names, $fltr[$i]["conf_name"]);
			
			$data = [];
			if ($fltr[$i]["carrier"] == 0)
				$data += ["status" => "off"];
			elseif ($fltr[$i]["carrier"] == 1)
				$data += ["status" => "on"];
			else
				$data += ["status" => "na"];
			
			$data += [
				"total_received_packets" => $filter['i_traff'][$i]["total"]["packets"]["received"],
				"total_received_bytes" => $filter['i_traff'][$i]["total"]["bytes"]["received"]
			];
			
			$data += [
				"eth2_packets" => $filter['i_traff'][$i]["eth2"]["packets"]["received"],
				"eth2_bytes" => $filter['i_traff'][$i]["eth2"]["bytes"]["received"]
			];
			
			$data += [
				"e802311c_packets" => $filter['i_traff'][$i]["e802311c"]["packets"]["received"],
				"e802311c_bytes" => $filter['i_traff'][$i]["e802311c"]["bytes"]["received"]
			];
			
			$data += [
				"e8023raw_packets" => $filter['i_traff'][$i]["e8023raw"]["packets"]["received"],
				"e8023raw_bytes" => $filter['i_traff'][$i]["e8023raw"]["bytes"]["received"]
			];
			
			$data += [
				"e8023snap_packets" => $filter['i_traff'][$i]["e8023snap"]["packets"]["received"],
				"e8023snap_bytes" => $filter['i_traff'][$i]["e8023snap"]["bytes"]["received"]
			];
			
			$data += [
				"arp_packets" => $filter['i_traff'][$i]["arp"]["packets"]["received"],
				"arp_bytes" => $filter['i_traff'][$i]["arp"]["bytes"]["received"]
			];
			
			$data += [
				"rarp_packets" => $filter['i_traff'][$i]["rarp"]["packets"]["received"],
				"rarp_bytes" => $filter['i_traff'][$i]["rarp"]["bytes"]["received"]
			];
			
			$data += [
				"ip_packets" => $filter['i_traff'][$i]["ip"]["packets"]["received"],
				"ip_bytes" => $filter['i_traff'][$i]["ip"]["bytes"]["received"]
			];
			
			$data += [
				"ipv6_packets" => $filter['i_traff'][$i]["ipv6"]["packets"]["received"],
				"ipv6_bytes" => $filter['i_traff'][$i]["ipv6"]["bytes"]["received"]
			];
			
			$data += [
				"tcp_packets" => $filter['i_traff'][$i]["tcp"]["packets"]["received"],
				"tcp_bytes" => $filter['i_traff'][$i]["tcp"]["bytes"]["received"]
			];
			
			$data += [
				"udp_packets" => $filter['i_traff'][$i]["udp"]["packets"]["received"],
				"udp_bytes" => $filter['i_traff'][$i]["udp"]["bytes"]["received"]
			];
			
			$data += [
				"icmp_packets" => $filter['i_traff'][$i]["icmp"]["packets"]["received"],
				"icmp_bytes" => $filter['i_traff'][$i]["icmp"]["bytes"]["received"]
			];
			
			$data += [
				"icmpv6_packets" => $filter['i_traff'][$i]["icmpv6"]["packets"]["received"],
				"icmpv6_bytes" => $filter['i_traff'][$i]["icmpv6"]["bytes"]["received"]
			];
			
			$data += [
				"total_sent_packets" => $filter['i_traff'][$i]["total"]["packets"]["sent"],
				"total_sent_bytes" => $filter['i_traff'][$i]["total"]["bytes"]["sent"]
			];
				
			$data += [
				"total_dropped_packets" => $filter['i_traff'][$i]["total"]["packets"]["dropped"],
				"total_dropped_bytes" => $filter['i_traff'][$i]["total"]["bytes"]["dropped"]
			];
				
			$data += [
				"total_broken_packets" => $filter['i_traff'][$i]["total"]["packets"]["broken"],
				"total_broken_bytes" => $filter['i_traff'][$i]["total"]["bytes"]["broken"]
			];
			
			$response += [$fltr[$i]["conf_name"] => $data];
		}
		$response += ["ethf_names" => $ethf_names];
	}
	
	echo json_encode($response);
}