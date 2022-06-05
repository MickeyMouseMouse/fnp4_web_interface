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
  // создание экземпляра класса FNP_Interface
  $interface = new FNP_Interface();
  // создание экземпляра класса FNP_Directory_Host
  $host = new FNP_Directory_Host();

  // выполнение действий по установке параметров
  try {
    // выполнение команды "directory show"
    $directory->setDirectory($session->getSessionId(), $cmd);
    // получение коллекции объектов справочника
    $result = $directory->getDirectory();
    // выполнение команды "interface filter show"
    $interface->setFilter($session->getSessionId());
    // получение массива с параметрами фильтрующих интерфейсов
    $fltr = $interface->getFilter();
  // перехват существующих исключений
  } catch (Exception $e) {
    if (strpos($e->getMessage(), "timeout") !== false)
      http_response_code(401);
    else
      http_response_code(500);
    exit();
 }

  $host->setObject($result);
  $response = [
    "item_name" => $host->name,
    "ip4" => ($host->ip4 == 'none') ? '' : $host->ip4,
    "ip6" => ($host->ip6 == 'none') ? '' : $host->ip6,
    "mac" => ($host->mac == 'any') ? '' : $host->mac,
    "vlan" => ($host->vlan == 'any') ? '' : $host->vlan,
    "comment" => $host->comment
  ];
  
  if ($host->interface == 'any')
    $response += ["interface" => "any"];
  else {
    $result = "";
    // разбор строки по разделителю
    $entry = explode(",", $host->interface);
    // проход по указанным в объекте интерфейсам
    for ($n = 0; $n < count($entry); $n++) {
      // проход по существующим в системе фильтрующим интерфейсам
      for ($m = 0; $m < count($fltr); $m++) {
        if ($m == $entry[$n]) { // индекс цикла совпадает с номером интерфейса объекта
          $result .= ($n == 0) ? $fltr[$m]["conf_name"] : ',' . $fltr[$m]["conf_name"];
        }
      }
    }
    $response += ["interface" => $result];
  }
  
  echo json_encode($response);
}
