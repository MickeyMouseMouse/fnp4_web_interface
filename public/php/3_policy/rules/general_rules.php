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

	$response = [];

	$is_apr = false;
	for ($i = 0; $i < $rule->rule_num; $i++) {
		if ($rule->apr != "no") {
			$is_apr = true;
			break;
		}
		$rule->nextRule(); // переходим к следующему правилу
	}
	$response += ["is_apr" => $is_apr];
	if ($is_apr)
		$response += ["cols" => "12"];
	else
		$response += ["cols" => "11"];

	$rule->seekRule(0);

	$rules = [];
	// Вывод списка общих правил фильтрации
	for ($i = 0; $i < $rule->rule_num; $i++) {
		$rule_info = ["active" => $rule->active, "name" => $rule->name,
			"action" => $rule->action];
		
		if ($rule->is_log == 'enable' || $rule->is_log == 'packet')
			$rule_info += ["is_log_packet" => "enabled"];
		else
			$rule_info += ["is_log_packet" => "disabled"];
		
		if ($rule->is_log == 'enable' || $rule->is_log == 'session')
			$rule_info += ["is_log_session" => "enabled"];
		else
			$rule_info += ["is_log_session" => "disabled"];
		
		$rule_info += ["is_alarm" => $rule->is_alarm];
		
		$protocol = '';
		if ($rule->eth_proto != 'any')
			$protocol .= $rule->eth_proto;
		if ($rule->ip_proto != 'any')
			$protocol .= $rule->ip_proto;
		$rule_info += ["protocol" => $protocol];
		
		// Источник
		$source = '';
		// Interface
		$eth = '';
		if ($rule->src_if != 'any') {
			$entry = explode(",", $rule->src_if);
			for ($j = 0; $j < count($entry); $j++) {
				$eth .= ($j == 0) ? "" : ",";
				$eth .= $fltr[$entry[$j]]["conf_name"];
			}
			$source .= getInnerString($eth, "I");
		}
		unset($eth);
		// Resource
		if ($rule->src_resource != 'none') {
			if ($source != "")
				$source .= "\n";
			$source .= getInnerString($rule->src_resource, "R");
		}
		// Object: Host, Net, Net-Group
		if ($rule->src_object != 'none') {
			if ($source != "")
				$source .= "\n";
			$source .= getInnerString($rule->src_object, "O");
		}
		// Address: IP4, IP6, MAC
		$address = '';
		if ($rule->src_ip4 != 'any') {
			$address .= (strlen($address == 0)) ? $rule->src_ip4 : ",$rule->src_ip4";
		}
		if ($rule->src_ip6 != 'any') {
			$address .= (strlen($address == 0)) ? $rule->src_ip6 : ",$rule->src_ip6";
		}
		if ($rule->src_mac != 'any') {
			$address .= (strlen($address == 0)) ? $rule->src_mac : ",$rule->src_mac";
		}
		if (strlen($address) > 0) {
			if ($source != "")
				$source .= "\n";
			$source .= getInnerString($address, "A");
		}
		unset($address);
		// Service
		if ($rule->src_service != 'none') {
			if ($source != "")
				$source .= "\n";
			$source .= getInnerString($rule->src_service, "S");
		}
		// Port
		if ($rule->src_port != 'any') {
			if ($source != "")
				$source .= "\n";
			$source .= getInnerString($rule->src_port, "P");
		}
		$rule_info += ["source" => $source];

		// Приемник
		$dest = '';
		// Interface
		$eth = '';
		if ($rule->dst_if != 'any') {
			$entry = explode(",", $rule->dst_if);
			for ($j = 0; $j < count($entry); $j++) {
				$eth .= ($j == 0) ? "" : ",";
				$eth .= $fltr[$entry[$j]]["conf_name"];
			}
			$dest .= getInnerString($eth, "I");
		}
		unset($eth);
		// Resource
		if ($rule->dst_resource != 'none') {
			if ($dest != "")
				$dest .= "\n";
			$dest .= getInnerString($rule->dst_resource, "R");
		}
		// Object: Host, Net, Net-Group
		if ($rule->dst_object != 'none') {
			if ($dest != "")
				$dest .= "\n";
			$dest .= getInnerString($rule->dst_object, "O");
		}
		// Address: IP4, IP6, MAC
		$address = '';
		if ($rule->dst_ip4 != 'any') {
			$address .= (strlen($address == 0)) ? $rule->dst_ip4 : ",$rule->dst_ip4";
		}
		if ($rule->dst_ip6 != 'any') {
			$address .= (strlen($address == 0)) ? $rule->dst_ip6 : ",$rule->dst_ip6";
		}
		if ($rule->dst_mac != 'any') {
			$address .= (strlen($address == 0)) ? $rule->dst_mac : ",$rule->dst_mac";
		}
		if (strlen($address) > 0) {
			if ($dest != "")
				$dest .= "\n";
			$dest .= getInnerString($address, "A");
		}
		unset($address);
		// Service
		if ($rule->dst_service != 'none') {
			if ($dest != "")
				$dest .= "\n";
			$dest .= getInnerString($rule->dst_service, "S");
		}
		// Port
		if ($rule->dst_port != 'any') {
			if ($dest != "")
				$dest .= "\n";
			$dest .= getInnerString($rule->dst_port, "P");
		}
		$rule_info += ["destination" => $dest];

		$rule_info += ["apr" => $rule->apr];
		
		$rule_info += ["comment" => cutstr($rule->comment)];
		
		if ($rule->name == 0) // глобальное правило
			$response += ["global_rule" => $rule_info];
		else
			array_push($rules, $rule_info);
		
		// позиционирование на следующее правило
		$rule->nextRule();
	}
	
	$response += ["rules" => $rules];
	
	echo json_encode($response);
}

function getInnerString($value, $label) {
	// разберем строку на отдельные элементы
	$entry = explode(",", $value);
	$num = count($entry);
	$result = "$label:";
	// пройдемся по списку элементов
	for ($i = 0; $i < $num; $i++) {
		$result .= cutstr($entry[$i]);
		if ($i != $num - 1)
			$result .= " ";
	}
	return $result;
}

function cutstr($string) {
	$retval = $string;
	// mb_strlen() правильно определяет длину строки в многобайтовой кодировке
	if (mb_strlen($string) > 32) {
		$retval  = mb_substr($string,0,32);
		$retval .= '..';
	}
  return $retval;
}