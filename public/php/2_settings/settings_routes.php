<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	require_once(FNPW_PATH_PRIVATE . '/classes/Routes.inc');
	$session = PHP_Session::getInstance();
	$resume = $session->resume();
	$routes = new FNP_Routes();

  try {
	// выполним команду "system route show"
	$routes->setRoutes($session->getSessionId());
  } catch (Exception $e) { // перехватим существующие исключения
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
  }

	echo json_encode($routes);
}