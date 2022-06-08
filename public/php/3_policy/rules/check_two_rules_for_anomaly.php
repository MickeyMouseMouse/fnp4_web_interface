<?php
class Protocol {
    const ANY = 0;
	const TCP = 1;
    const UDP = 2;
	const ICMP = 3;
	public static function getProtocol($index) {
		if ($index == 0)
			return "ANY";
		elseif ($index == 1)
			return "TCP";
		elseif ($index == 2)
			return "UDP";
		elseif ($index == 3)
			return "ICMP";
	} 
}

class Action {
    const ACCEPT = 0;
    const DENY = 1;
	const DROP = 2;
	public static function getAction($index) {
		if ($index == 0)
			return "ACCEPT";
		elseif ($index == 1)
			return "DENY";
		elseif ($index == 2)
			return "DROP";
	} 
}

define("MAX_PORT", 65535);

class Address {
	public string $ip_address;
	public int $ip;
	public int $mask_len;
	public int $mask;
	public int $net;
	public int $host;
	public int $port_start;
	public int $port_end;
	public bool $is_any;
	
	function __construct($ip, $mask_len, $port_start, $port_end, $is_any) {
		$this->ip_address = $ip;
		$this->ip = $this->ipToInt($ip);
		
		$this->mask_len = $mask_len;
		$this->mask = $this->maskToInt($mask_len);
		
		$this->net = $this->ip & $this->mask;
		$this->host = $this->ip & ~$this->mask;
		
		$this->port_start = $port_start;
		$this->port_end = $port_end;
		$this->is_any = $is_any;
	}
	
	public function __toString() {
		if ($this->is_any) return "any";
		if ($this->port_start == 0 and $this->port_end == MAX_PORT)
			$port = "any";
		elseif ($this->port_start == $this->port_end)
			$port = $this->port_end;
		else
			$port = $this->port_start . "-" . $this->port_end;
		return $this->ip_address . "/" . $this->mask_len . ":" . $port;
	}
	
	public function __equals($other) {
		return $this->ip_address == $other->ip_address and
			$this->mask_len == $other->mask_len and
			$this->port_start == $other->port_start and
			$this->port_end == $other->port_end and
			$this->is_any == $other->is_any;
	}

	public function ipToInt($ip) {
		$parts = array_reverse(explode(".", $ip));
		$result = 0;
		for ($i = 0; $i < count($parts); $i++)
			$result += (int)$parts[$i] * (2 ** (8 * $i));
		return $result;
	}
	
	public function maskToInt($mask_len) {
		return (1 << 32) - (1 << (32 - $mask_len));
	}
	
	public function isSubset($other) {
		if ($other->is_any)
			return True;
		if ($this->is_any)
			return False;
		if ($other->mask_len > $this->mask_len)
			return False;
		if ($this->port_end > $other->port_end or
				$this->port_start < $other->port_start)
			return False;
		return $other->net == ($this->ip & $other->mask);
	}
	
	public function combineNets($other) {
		if (!($this->mask_len == $other->mask_len and
			$this->mask_len != 32 and
			$this->is_any == $other->is_any and
			$this->port_start == $other->port_start and
			$this->port_end == $other->port_end)) {
			return False;
		}
		return ($this->net & $other->net) == ($this->ip & $this->maskToInt($this->mask_len - 1));
	}

	public function combinePorts($other) {
		$max_port_start = max($this->port_start, $other->port_start);
		$min_port_end = min($this->port_end, $other->port_end);
		return $this->ip == $other->ip and
			$this->mask_len == $other->mask_len and
			$this->is_any == $other->is_any and
			$max_port_start <= $min_port_end + 1;
	}
}

class Rule {
	public string $number;
	public int $action;
	public int $protocol;
	public Address $src_addr;
	public Address $dst_addr;
	
	function __construct($number, $action, $protocol, $src_addr, $dst_addr) {
		$this->number = $number;
		$this->protocol = $protocol;
		$this->action = $action;
		$this->src_addr = $src_addr;
		$this->dst_addr = $dst_addr;
	}
	
	public function __toString() {
		return "Number: " . $this->number . "; Action: " .
			Action::getAction($this->action) . "; Protocol: " .
			Protocol::getProtocol($this->protocol) . "; src_ip: " .
			$this->src_addr . "; dst_ip: " . $this->dst_addr;
	}
	
	public function isSubset($other) {
		if ($other->protocol != Protocol::ANY and 
				$this->protocol != $other->protocol)
			return False;
		if (!$this->src_addr->isSubset($other->src_addr))
			return False;
		if (!$this->dst_addr->isSubset($other->dst_addr))
			return False;
		return True;
	}
}

function isGlobalRule($rule) {
	return $rule->number == 0;
}

function check_rules($rule_1, $rule_2) {
	if ($rule_1->number < $rule_2->number or isGlobalRule($rule_2)) {
		$first = $rule_1;
		$second = $rule_2;
	} else {
		$first = $rule_2;
		$second = $rule_1;
	}
	
	// case 1
	if ($second->isSubset($first)) {
		if ($first->action == $second->action)
			return ["anomaly" => "redundancy",
				"problem_rule" => $second->number];
		else
			return ["anomaly" => "shading",
				"problem_rule" => $second->number];
	}
	
	// case 2
	if ($first->action == $second->action and $first->isSubset($second)) {
		return ["anomaly" => "redundancy",
			"problem_rule" => $first->number];
	}

	// case 3
	if ($rule_1->action == $rule_2->action) {
		$srcAddrsEqual = $rule_1->src_addr == $rule_2->src_addr;
		$dstAddrsEqual = $rule_1->dst_addr == $rule_2->dst_addr;
		
		if ($rule_1->protocol != $rule_2->protocol) {
			// case 3.1
			if ($srcAddrsEqual and $dstAddrsEqual) {
				return ["anomaly" => "merge", "field" => "protocol"];
			}
		} else {		
			// case 3.2
			if ($dstAddrsEqual and
				($rule_1->src_addr->combinePorts($rule_2->src_addr) or
					$rule_1->src_addr->combineNets($rule_2->src_addr))) {
				return ["anomaly" => "merge", "field" => "source"];
			}
			
			// case 3.3
			if ($srcAddrsEqual and
				($rule_1->dst_addr->combinePorts($rule_2->dst_addr) or
					$rule_1->dst_addr->combineNets($rule_2->dst_addr))) {
				return ["anomaly" => "merge", "field" => "destination"];
			}
		}
	}
	
	// default case
	return ["anomaly" => "no"];
}

?>
