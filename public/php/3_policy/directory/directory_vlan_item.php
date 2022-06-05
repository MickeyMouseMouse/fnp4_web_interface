<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

// сценарий никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
  http_response_code(405);
} else {
  // значение имени объекта из URL
  if (isset($_GET['name']))
    $cmd = 'directory show name=' . $_GET['name'];
  else
    exit();

  require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
  // Класс определения объектов справочника
  require_once(FNPW_PATH_PRIVATE . '/classes/Directory.inc');
  // Класс определения параметров интерфейса
  require_once(FNPW_PATH_PRIVATE . '/classes/Interface.inc');
  // создаем экземпляр класса PHP_Session
  $session = PHP_Session::getInstance();
  // восстановим параметры сессии
  $resume = $session->resume();
  // создание экземпляра класса FNP_Directory
  $directory = new FNP_Directory();
  // создание экземпляра класса FNP_Directory_Vlan_Group
  $vlan = new FNP_Directory_Vlan_Group();

  // выполнение действий по установке параметров
  try {
    // выполнение команды "directory show"
    $directory->setDirectory($session->getSessionId(), $cmd);
    // получение коллекции объектов справочника
    $result = $directory->getDirectory();
  } catch (Exception $e) {
    if (strpos($e->getMessage(), "timeout") !== false)
      http_response_code(401);
    else
      http_response_code(500);
    exit();
 }

  $vlan->setObject($result);
  $response = [
    "item_name" => $vlan->name,
    "id" => $vlan->vid,
    "comment" => $vlan->comment
  ];
  
  echo json_encode($response);
}
