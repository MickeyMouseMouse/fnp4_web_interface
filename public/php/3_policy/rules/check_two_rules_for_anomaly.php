<?php
class Protocol {
    const TCP = 0;
    const UDP = 1;
	const ANY = 2;
	public static function getProtocol($index) {
		if ($index == 0)
			return "TCP";
		elseif ($index == 1)
			return "UDP";
		elseif ($index == 2)
			return "ANY";
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

define("MAX_PORT_VALUE", 65535);

class Address {
	private string $ip;
	private int $mask_len;
	private int $port_start;
	private int $port_end;
	private bool $is_any;
	
	function __construct($ip, $mask_len, $port_start, $port_end, $is_any) {
		$this->ip = $ip;
		$this->mask_len = $mask_len;
		$this->port_start = $port_start;
		$this->port_end = $port_end;
		$this->is_any = $is_any;
	}
	
	public function __toString() {
		if ($this->is_any) return "any";
		if ($this->port_start == 0 and $this->port_end == MAX_PORT_VALUE)
			$port = "any";
		elseif ($this->port_start == $this->port_end)
			$port = $this->port_end;
		else
			$port = $this->port_start . "-" . $this->port_end;
		return $this->ip . "/" . $this->mask_len . ":" . $port;
	}
	
	public function __equals($other) {
		return $this->ip == $other->ip and $this->mask_len == $other->mask_len and
			$this->port_start == $other->port_start and $this->port_end == $other->port_end and
			$this->is_any == $other->is_any;
	}
	
	public function maskToInt() {
		return (1 << 32) - (1 << (32 - $this->mask_len));
	}

	public function ipToInt() {
		$parts = array_reverse(explode(".", $this->ip));
		$result = 0;
		for ($i = 0; $i < count($parts); $i++)
			$result += (int)$parts[$i] * (2 ** (8 * $i));
		return $result;
	}
	
	public function hostToInt() {
		return $this->ipToInt() & ~$this->maskToInt();
	}

	public function netToInt() {
		return $this->ipToInt() & $this->maskToInt();
	}
	
	public function isSubset($other) {
		if ($other->is_any)
			return True;
		if ($this->is_any)
			return False;
		if ($this->port_end > $other->port_end or $this->port_start < $other->port_start)
			return False;
		if ($other->hostToInt())
			return False;
		if ($other->mask_len > $this->mask_len)
			return False;
		if ($other->netToInt() == ($this->ipToInt() & $other->maskToInt()))
			return True;
		return False;
	}
	
	public function canMerge($other) {
		return $this->ip == $other->ip and
			$this->mask_len == $other->mask_len and
			$this->is_any == $other->is_any and
			max($this->port_start, $other->port_start) <= min($this->port_end, $other->port_end) + 1;
	}
}

class Rule {
	private string $number;
	private int $action;
	private int $protocol;
	private Address $src_addr;
	private Address $dest_addr;
	
	function __construct($number, $action, $protocol, $src_addr, $dest_addr) {
		$this->number = $number;
		$this->protocol = $protocol;
		$this->action = $action;
		$this->src_addr = $src_addr;
		$this->dest_addr = $dest_addr;
	}
	
	public function getNumber() { return $this->number; }
	
	public function getAction() { return $this->action; }
	
	public function getProtocol() { return $this->protocol; }
	
	public function getSrcAddr() { return $this->src_addr; }
	
	public function getDestAddr() { return $this->dest_addr; }
	
	public function __toString() {
		return "Number: " . $this->number . "; Action: " . Action::getAction($this->action) .
		"; Protocol: " . Protocol::getProtocol($this->protocol) . "; src_ip: " . $this->src_addr .
		"; dest_ip: " . $this->dest_addr;
	}
	
	public function isSubset($other) {
		if ($other->protocol != Protocol::ANY and $this->protocol != $other->protocol)
			return False;
		if (!$this->getSrcAddr()->isSubset($other->getSrcAddr()))
			return False;
		if (!$this->getDestAddr()->isSubset($other->getDestAddr()))
			return False;
		return True;
	}
}

function check_rules($rule_1, $rule_2) {
	if ($rule_1->getNumber() < $rule_2->getNumber() or $rule_2->getNumber() == 0) {
		$first = $rule_1;
		$second = $rule_2;
	} else {
		$first = $rule_2;
		$second = $rule_1;
	}
	
	// case 1
	if ($second->isSubset($first)) {
		if ($first->getAction() == $second->getAction())
			return ["anomaly" => "redundancy", "problem_rule" => $second->getNumber()];
		else
			return ["anomaly" => "shading", "problem_rule" => $second->getNumber()];
	}
	
	// case 2
	if ($first->getAction() == $second->getAction() and $first->isSubset($second)) {
		return ["anomaly" => "redundancy", "problem_rule" => $first->getNumber()];
	}

	// case 3
	$canMerge = ($rule_1->getAction() == $rule_2->getAction() and
		$rule_1->getProtocol() == $rule_2->getProtocol());
			
	$canMergeBySrcAddr = $rule_1->getDestAddr() == $rule_2->getDestAddr() and
		$rule_1->getSrcAddr()->canMerge($rule_2->getSrcAddr());
			
	$canMergeByDestAddr = $rule_1->getSrcAddr() == $rule_2->getSrcAddr() and
		$rule_1->getDestAddr()->canMerge($rule_2->getDestAddr());
	
	if ($canMerge and ($canMergeBySrcAddr or $canMergeByDestAddr)) {
		return ["anomaly" => "merge"];
	}
	
	// default case
	return ["anomaly" => "no"];
}

?>
