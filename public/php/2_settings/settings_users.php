<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	// Класс PHP-сессии
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// Класс с информацией о системе
	require_once(FNPW_PATH_PRIVATE . '/classes/System.inc');
	// Класс, содержащий информацию об настройках системы
	require_once(FNPW_PATH_PRIVATE . '/classes/Users.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	$uinfo = $session->getUserInfo();
	// создадим экземпляр класса FNP_System
	$system = new FNP_System();
	// создадим первый экземпляр класса FNP_Users
	$userlist = new FNP_Users();
	// создадим второй экземпляр класса FNP_Users
	$usershow = new FNP_Users();

	// выполним действия по установке параметров
	try {
		// установка сведений о системе
		$system->setSystem($session->getSessionId());
		// установка списка существующих пользователей
		$userlist->setUserList($session->getSessionId());
		// установка списка активных пользователей
		$usershow->setUserShow($session->getSessionId());
	} catch (Exception $e) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	

	echo json_encode();
}

/**
 * Функция формирует шаблон HTML-страницы
 *
 * @return string
 */  
function userPage($message, $uinfo, $system, $userlist, $usershow)
{
  // привилегия текущего пользователя
  $currentPriv = 'read';

  $html  = '';

  $html .= FNP_Scripts::addCode("usersOnReady();");

  // таблица с логотипом
  $html .= fnp_add_toolbar(1);

  // строка с подменю
  $html .= fnp_add_setsmenu(1);

  // информационное сообщение (если не пустое)
  if ($message) {
    $html .= fnp_add_notice($message);
    return $html;
  }

  // заголовок страницы
  $html .= fnp_add_bodyheader(_('Settings'), _('Administrators'));

  // таблица пользователей
  $html .= "<TABLE id='user-list' class='sets users'>\n";
  $html .= "<TR>\n<TD class='sectionHeading' colspan=5>"
    . _("Administrator list") . "</TD>\n</TR>\n";

  // заголовок полей данных пользователей
  $html .= "<TR>\n<TD class='tableLabel' align='center' valign='middle'>";
  if (($uinfo['username'] == ADMIN) && ($uinfo['epriv'] !== 'read')) {
    $html .= "<IMG src='/images/icons/16x16/usradd.png' width='16' "
      . "height='16' align='absmiddle' class='navImage editUser' "
      . "title='" . _("Add new administrator") . "'/>\n";
  } else {
    $html .= "&nbsp;";
  }
  $html .= "</TD>\n";

  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("Admin username") . "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("Privilegies") . "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("State") . "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _('Actions') . "&nbsp;</TD>\n</TR>\n";

  // список пользователей
  for ($i = 0; $i < $userlist->nusers; $i++) {
    // сохраним значение привилегий текущего пользователя
    if ($uinfo['username'] == $userlist->name) {
      $currentPriv = $userlist->privs;
    }
    // определим активность пользователя
    $style = "";
    if ($userlist->enable == "0") {
      $style = "color:red";
    }
    // номер пользователя
    $html .= "<TR>\n<TD class='tableValue' style='" . $style . "' "
      . "align='center' valign='middle'>" . sprintf("%d", $i + 1) . "</TD>\n";
    // имя пользователя
    $html .= "<TD class='tableValue' style='" . $style . "' "
      . "align='center' valign='middle'>" . $userlist->name . "</TD>\n";
    // привилегии
    $html .= "<TD class='tableValue' style='" . $style . "' "
      . "align='center' valign='middle'>" . $userlist->privs . "</TD>\n";
    // активность
    $html .= "<TD class='tableValue' style='" . $style . "' "
      . "align='center' valign='middle'>";
    if ($userlist->enable == "1") {
      $html .= '<SPAN class="enableText">' . _("enable") . '</SPAN>';
    } else {
      $html .= '<SPAN class="disableText">' . _("disable") . '</SPAN>';
    }
    $html .= "</TD>\n";

    // действия над пользователем
    $html .= "<TD class='tableValue' style='" . $style . "' align='center' "
      . "valign='middle'>\n";

    // если пользователь имеет права, отличные от режима "Чтение"
    if ($uinfo['epriv'] == 'admin' ) {
      // иконка редактирования данных пользователя
      $html .= "<IMG src='/images/icons/16x16/usredit.png' width='16' height='16' "
         . "align='absmiddle' class='navImage editUser' title='"
         . sprintf(_("Edit administrator '%s'"),$userlist->name)
         . "' name='" . $userlist->name . "'/>\n";
    // разрешим редактирование лишь собственной учетной записи
    } else if ($uinfo['username'] == $userlist->name) {
      // иконка редактирования данных пользователя
      $html .= "<IMG src='/images/icons/16x16/usredit.png' width='16' height='16' "
        . "align='absmiddle' class='navImage editUser' title='"
        . sprintf(_("Edit administrator '%s'"),$userlist->name)
        . "' name='" . $userlist->name . "'/>\n";
    }

    // удалить пользователя разрешено в режиме и с правами, отличными от 'read'
    if ((($uinfo['username'] == ADMIN) && $uinfo['epriv'] !== 'read')
     && (($userlist->name != ADMIN) && $uinfo['epriv'] !== 'read')) {
      // иконка удаления пользователя
      $html .= "<IMG src='/images/icons/16x16/usrdrop.png' width='16' height='16' "
        . "align='absmiddle' class='navImage removeUser' title='"
        . sprintf(_("Remove administrator '%s'"),$userlist->name)
        . "' name='" . $userlist->name . "'/>\n";
    } else {
      // if ($uinfo['username'] != $userlist->name)
        $html .= "&nbsp;";
    }
    $html .= "</TD>\n</TR>\n";

    // переходим к следующему пользователю
    $userlist->seekNextUser();
  }

  $html .= "</TABLE>\n";
  $html .= "<BR>\n";

  // таблица активных пользователей
  $html .= "<TABLE id='user-list-active' class='sets users'>\n";
  $html .= "<TR>\n<TD class='sectionHeading' colspan=6>"
    . _("Active administrator list") . "</TD>\n</TR>\n";

  // заголовок полей данных пользователей
  $html .= "<TR>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>";
  if ($currentPriv == 'admin' || $currentPriv == 'full') {
    $html .= "<IMG src='/images/icons/16x16/usrlist.png' width='16' ";
    $html .= "height='16' align='absmiddle' class='navImage clearUser' ";
    $html .= "title='" . _("Clear users") . "'/>";
  }
  $html .= "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("Admin username") . "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("Login time") . "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("From") . "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("Privilegies") . "</TD>\n";
  $html .= "<TD class='tableLabel' align='center' valign='middle'>"
    . _("Idle time") . "</TD></TR>\n";

  // список активных пользователей
  for ($i = 0; $i < $usershow->nusers; $i++) {
    // номер пользователя
    $html .= "<TR>\n<TD class='tableValue' align='center' valign='middle'>"
      . sprintf("%d", $i + 1) . "</TD>\n";
    // имя пользователя
    $html .= "<TD class='tableValue' align='center' valign='middle'>"
      . $usershow->name . "</TD>\n";
    // время авторизации
    $html .= "<TD class='tableValue' align='center' valign='middle'>"
      . $usershow->log_time . "</TD>\n";
    // источник
    $html .= "<TD class='tableValue' align='center' valign='middle'>"
      . $usershow->from . "</TD>\n";
    // привилегии
    $html .= "<TD class='tableValue' align='center' valign='middle'>"
      . $usershow->privs . "</TD>\n";
    // время неактивности
    $html .= "<TD class='tableValue' align='center' valign='middle'>"
      . $usershow->idle_time . "</TD>\n</TR>\n";

    // переходим к следующему пользователю
    $usershow->seekNextUser();
  }

  $html .= "</TABLE>\n";

  $html .= "<BR>\n";

  // Редактирование параметров SNMP
  $html .= "<TABLE id='users-snmp' class='sets users'>\n";
  $html .= "<TR>\n<TD class='sectionHeading'>" . _("SNMP user") . "</TD>\n</TR>\n";
  $html .= "<TR>\n<TD class='tableValue'  align='right'>\n";
  // Включение/отключение пользователя SNMP
  if ($system->is_snmpd) {
    // кнопка остановки фильтра
    $html .= fnp_add_button_popup("startButton", _("Disable"),
      "/m_sets/snmp.php?cmd=disable", _("Disable SNMP Interface"), 1);
  } else {
    // кнопка запуска фильтра
    $html .= fnp_add_button_popup("startButton", _("Enable"),
      "/m_sets/snmp.php?cmd=enable", _("Enable SNMP Interface"), 0);
  }
  // Смена пароля пользователя SNMP
  $html .= fnp_add_button_popup("changeSnmpPassword", _("Change password"),
     "/m_sets/snmp_change_pwd.php", _("Change SNMP user password"), TRUE);
  $html .= "</TD>\n</TR>\n";

  $html .= "</TABLE>\n";

  $html .= "<BR>\n";

  // кнопка вывода справки
  $html .= fnp_form_button_help(null,null,null,'#users');

  return $html;
}