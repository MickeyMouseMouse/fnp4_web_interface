<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций веб-интерфейса
require_once 'fnpw.inc';
// Класс PHP-сессии
require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');

// создаем экземпляр класса PHP_Session
$session = PHP_Session::getInstance();

// восстановим параметры сессии
$resume = $session->resume();

// получим информацию о пользователе
$uinfo = $session->getUserInfo();

if ($uinfo['user_logged']) {
	// пользователь уже вошел в систему (проверка по cookie)
	echo json_encode(["username" => $uinfo["username"], "privilege" => $uinfo["epriv"]]);
} else { // пользователь еще не авторизован
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		// неизвестный метод запроса
		http_response_code(405);
	} else {
		// выполним действия по авторизации пользователя с обработкой исключений
		try {
			// проверим имя пользователя
			$session->setUsername($_POST['uname']);
			// проверим пароль
			$session->setPassword($_POST['upwd']);
			// выполним регистрацию пользователя
			$session->login();
			// обновим информацию о сессии пользователя
			$uinfo = $session->getUserInfo();
			// запомним параметры сессии
			$session->save();
			// создадим каталог для хранения временных файлов сессии
			$session->makeDir();
		} catch (Exception $e) {
			// авторизация провалилась
			http_response_code(401);
			exit();
		}
		// авторизация прошла успешно
		echo json_encode(["username" => $uinfo["username"], "privilege" => $uinfo["epriv"]]);
	}
}