<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';
// Модуль определений
require_once(FNPW_PATH_PRIVATE . '/etc/common.inc');

// этот модуль никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// Класс, содержащий информацию параметров интерфейса
	require_once(FNPW_PATH_PRIVATE . '/classes/Interface.inc');
	// Класс определений правил фильтрации
	require_once(FNPW_PATH_PRIVATE . '/classes/Rules.inc');
	// Класс настроек и записей сессий
	require_once(FNPW_PATH_PRIVATE . '/classes/Session.inc');
	// Класс с информацией о системе
	require_once(FNPW_PATH_PRIVATE . '/classes/System.inc');

	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// Создадим экземпляр класса FNP_Interface
	$interface = new FNP_Interface();
	// Создадим экземпляр класса FNP_Rules
	$rules = new FNP_Rules();
	// Создадим экземпляр класса FNP_Session
	$sess = new FNP_Session_Setting();
	// создадим экземпляр класса FNP_System
	$system_config = new FNP_System_Config();

	try { // выполнение действий по установке параметров
		// system show type=config
		$system_config->setSystem($session->getSessionId());
	} catch (Exception $e) { // перехват существующих исключений
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	// выполним команду "interface filter show"
	if ($interface->setFilter($session->getSessionId()) !== true) {
		http_response_code(500);
		exit();
	}
	// получим массив с параметрами фильтрующих интерфейсов
	$fltr = $interface->getFilter();

	// Выполним команду "rule show"
	if ($rules->setRules($session->getSessionId()) !== TRUE) {
		http_response_code(500);
		exit();
	}

	// Получим массив с набором правил
	$result = $rules->getRules();

	// Инициализация экземпляра класса FNP_Rules_Rule
	$rule = new FNP_Rules_Rule();

	// Разберем данные общих правил
	if ($rule->setRule($result) !== TRUE) {
		http_response_code(500);
		exit();
	}

	require_once "check_two_rules_for_anomaly.php";

	$table = [];
	$global_rule;
	for ($i = 0; $i < $rule->rule_num; $i++) {
		if ($rule->active == "disable") {
			$rule->nextRule();
			continue;
		}
		
		$rule_number = $rule->name;
		
		if ($rule->action == 'accept')
			$rule_action = Action::ACCEPT;
		elseif ($rule->action == 'drop')
			$rule_action = Action::DROP;
		else
			$rule_action = Action::DENY;
		
		if ($rule->ip_proto == 'any')
			$rule_protocol = Protocol::ANY;
		elseif ($rule->ip_proto == 'tcp')
			$rule_protocol = Protocol::TCP;
		elseif ($rule->ip_proto == 'udp')
			$rule_protocol = Protocol::UDP;
		
		$ip_mask = explode("/", $rule->src_ip4);
		$src_ip = $ip_mask[0];
		if (count($ip_mask) == 2)
			$src_mask = getMaskLength($ip_mask[1]);
		else
			$src_mask = 32;
		
		$ports = getPorts($rule->src_port);
		$src_port_start = $ports[0];
		$src_port_end = $ports[1];
		
		$ip_mask = explode("/", $rule->dst_ip4);
		$dst_ip = $ip_mask[0];
		if (count($ip_mask) == 2)
			$dst_mask = getMaskLength($ip_mask[1]);
		else
			$dst_mask = 32;
	
		$ports = getPorts($rule->dst_port);
		$dst_port_start = $ports[0];
		$dst_port_end = $ports[1];
		
		if ($rule_number == 0) {
			$global_rule = new Rule(
				0, $rule_action, $rule_protocol,
				new Address($src_ip, $src_mask, $src_port_start, $src_port_end, false),
				new Address($dst_ip, $dst_mask, $dst_port_start, $dst_port_end, false)
			);
		} else {
			$filtering_rule = new Rule(
				$rule_number, $rule_action, $rule_protocol,
				new Address($src_ip, $src_mask, $src_port_start, $src_port_end, false),
				new Address($dst_ip, $dst_mask, $dst_port_start, $dst_port_end, false)
			);
			array_push($table, $filtering_rule);
		}
		
		$rule->nextRule();
	}
	array_push($table, $global_rule);
	
	$anomalies = [];
	for ($i = 0; $i < count($table) - 1; $i++) {
		for ($j = $i + 1; $j < count($table); $j++) {
			$result = check_rules($table[$i], $table[$j]);
			if ($result["anomaly"] == "shading" or $result["anomaly"] == "redundancy") {
				if ($result["problem_rule"] == $table[$i]->number)
					$other_rule = $table[$j]->number;
				else
					$other_rule = $table[$i]->number;
				
				array_push($anomalies, [
					"anomaly" => $result["anomaly"],
					"problem_rule" => $result["problem_rule"],
					"other" => $other_rule
				]);
			}
			if ($result["anomaly"] == "merge") {
				array_push($anomalies, [
					"anomaly" => "merge",
					"rule1" => $table[$i]->number,
					"rule2" => $table[$j]->number,
					"field" => $result["field"]
				]);
			}
		}
	}
	
	if (count($anomalies) == 0)
		echo json_encode(["anomalies" => "no"]);
	else
		echo json_encode(["anomalies" => $anomalies]);
}

function getMaskLength($mask) {
	$parts = explode(".", $mask);
	$result = 0;
	for ($i = 0; $i < count($parts); $i++) {
		$value = (int)$parts[$i];
		for ($j = 7; $j >= 0; $j--) {
			if ((int)($value & (1 << $j)) == 0)
				return $result;
			else {
				$result += 1;
			}
		}
	}
	return $result;
}


function getPorts($ports) {
	if ($ports == "any")
		return [0, MAX_PORT];
	
	$parts = explode("-", $ports);
	if (count($parts) == 1)
		return [(int)$parts[0], (int)$parts[0]];
	else
		return [(int)$parts[0], (int)$parts[1]];
}
