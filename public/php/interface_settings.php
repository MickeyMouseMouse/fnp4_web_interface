<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'fnpw.inc';
require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');

$session = PHP_Session::getInstance();
$resume = $session->resume();
$uinfo = $session->getUserInfo();

$db_file = "interface_settings.db";
$exists = file_exists($db_file);
$db = new SQLite3($db_file);
if (!$exists) { // создать новую базу данных
	$db->exec("CREATE TABLE Locales (locale_id INTEGER PRIMARY KEY, locale_name TEXT)");
	$db->exec("INSERT INTO Locales (locale_name) VALUES ('ru')");
	$db->exec("INSERT INTO Locales (locale_name) VALUES ('en')");
	
	$db->exec("CREATE TABLE Settings (locale_id INTEGER, nat_visible INTEGER, debug_visible INTEGER)");
	$db->exec("INSERT INTO Settings (locale_id, nat_visible, debug_visible) VALUES (1, 1, 1)");
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$settings = $db->query("SELECT locale_name FROM Locales WHERE locale_id = " . 
		"(SELECT locale_id FROM Settings)")->fetchArray();
	$response = ["locale" => $settings['locale_name']];
	
	if ($uinfo["user_logged"]) {
		$settings = $db->query("SELECT nat_visible, debug_visible FROM Settings")->fetchArray();
		($settings['nat_visible']) ? $nat_visible = true : $nat_visible = false;
		($settings['debug_visible']) ? $debug_visible = true : $debug_visible = false;
		$response += ["natVisible" => $nat_visible, "debugVisible" => $debug_visible];
	}
	
	echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// проверка прав доступа
	if (!$uinfo["user_logged"] or $uinfo["epriv"] != "admin") {
		http_response_code(403);
		exit();
	}
	
	// обновление настроек
	if (isset($_POST['param']) && isset($_POST['newValue'])) {
		$newValue = $_POST['newValue'];
		if ($_POST['param'] == 'locale') {
			$locale_id = $db->query("SELECT locale_id FROM Locales " .
				"WHERE locale_name = '$newValue'")->fetchArray()['locale_id'];
			if ($locale_id == null) {
				http_response_code(400);
			} else {
				$db->exec("UPDATE Settings SET locale_id = '$locale_id'");
			}
		} elseif ($_POST['param'] == 'natVisible') {
			$db->exec("UPDATE Settings SET nat_visible = $newValue");
		} elseif ($_POST['param'] == 'debugVisible') {
			$db->exec("UPDATE Settings SET debug_visible = $newValue");
		}
	}
} else {
	http_response_code(405);
}

$db->close();
?>
