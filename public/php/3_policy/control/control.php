<?php
header('Content-Type: application/json; charset=utf-8');

// Определение конфигураций WEB-интерфейса
require_once 'fnpw.inc';

// этот модуль никогда не будет запускаться по методу POST
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
	http_response_code(405);
} else {
	require_once(FNPW_PATH_PRIVATE . '/classes/PHPSession.inc');
	// создаем экземпляр класса PHP_Session
	$session = PHP_Session::getInstance();
	// восстановим параметры сессии
	$resume = $session->resume();
	// Класс определения списка дополнительных политик
	require_once(FNPW_PATH_PRIVATE . '/classes/Policy.inc');
	// Инициализируем экземпляр класса FNP_Policy
	$policy = new FNP_Policy();

	// Выполним команду "policy list"
	if ($policy->setPolicy($session->getSessionId()) !== TRUE) {
		if (strpos($e->getMessage(), "timeout") !== false)
			http_response_code(401);
		else
			http_response_code(500);
		exit();
	}

	// Получим массив с данными (список дополнительных политик)
	$policy = $policy->getPolicy();

}

/**
 * Функция формирует шаблон HTML-страницы
 */
function controlPage($message, $policy) {
  $html  = '';

  $html .= FNP_Scripts::addCode("standartOnReady();");

   $html .= fnp_add_toolbar(2);

   $html .= fnp_add_policymenu(0);

   if ($message) {
    return $html .= fnp_add_notice($message);
  }

  $html .= fnp_add_bodyheader(_('Policies'), _('Control'));

  $html .= '<table id="current-policy" class="policy control">' . "\n";

  $html .= '<tr><td class="sectionHeading">' . _("Current policy") . '</td></tr>' . "\n";

  $html .= '<tr><td class="tableValue" align="right">';

  $html .= fnp_add_button_popup('showPolicy',_('Show'),'/m_policy/policy_show.php');

  $html .= fnp_add_button_popup('savePolicy',_('Save'),'/m_policy/policy_save.php');

  $html .= fnp_add_button_location(
    '/m_policy/policy_download.php','downloadPolicy',_('Download')
  );

  $html .= '<div style="display: inline-block; width: 30px;"></div>';

  $html .= fnp_add_button_popup(
    'rollbackPolicy', _('Rollback'), '/m_policy/policy_rollback.php',
    _('Revert the current policy to previous state'), true
  );

  $html .= fnp_add_button_popup(
    "defaultPolicy", _("Reset"),
    "/m_policy/policy_default.php", _("Reset policy to default")
  );

  $html .= '</td></tr>' . "\n";

  $html .= '</table>' . "\n";

  $html .= '<table id="additional-policy" class="policy control">' . "\n";

  $html .= '<tr><td class="sectionHeading" colspan="5">'
      . _("Additional policies") . '</td></tr>' . "\n";

  $html .= '<tr><td class="tableLabel" align="right" colspan="5">';

  $html .= fnp_add_button_popup('uploadPolicy',_('Upload'),'/m_policy/policy_upload');

  $html .= '</td></tr>' . "\n";

  $html .= '<tr><td class="tableLabel" width="5%"></td>' . "\n";

  $html .= '<td class="tableLabel" align="center" width="25%">'
      . _('Policy name') . '</td>' . "\n";

  $html .= '<td class="tableLabel" align="center" width="25%">'
      . _('Last update') . '</td>' . "\n";

  $html .= '<td class="tableLabel" align="center" width="25%">'
      . _('Comment') . '</td>' . "\n";

  $html .= '<td class="tableLabel" align="center" width="20%">'
      . _('Actions') . '</td></tr>' . "\n";

  for ($i=0, $item=1, $len=count($policy['name']); $i < $len; $i++, $item++) {

    $policy_name = $policy['name'][$i];
    $policy_comment = $policy['cmnt'][$i];

    $html .= '<tr><td class="tableValue" align="center">' . $item . '</td>' . "\n";

    $html .= '<td class="tableValue" align="center">';
    if (strlen($policy_name) > 32) {
      $html .= wordwrap($policy_name, 32, "<br>", true);
    } else {
      $html .= $policy_name;
    }
    $html .= '</td>' . "\n";

    if ($policy['stat'][$i] == 1) {
      $html .= '<td class="tableValue" align="center" valign="middle">';
      $html .= $policy['time'][$i];
      $html .= '</td>' . "\n";
    } else {
      $html .= '<td class="tableValue" align="center" valign="middle">';
      $html .= '<span class="disableText">' . _("Invalid policy") . '</span>';
      $html .= '</td>' . "\n";
    }

    $html .= '<td class="tableValue" align="center">';
    if (strlen($policy_comment) > 32) {
      $html .= wordwrap($policy_comment, 32, "<br>", true);
    } else {
      $html .= $policy_comment;
    }
    $html .= '</td>' . "\n";

    $html .= '<td class="tableValue" align="center">';

    $html .= FNP_Scripts::addCode("addContextMenu($item);");

    $html .= '<div id="split-button" class="ui-buttonset">' . "\n";

    $html .= '<button id="rerun" class="ui-button ui-widget ui-state-default'
      . ' ui-button-text-only ui-corner-left split-button" title="'
      . _('Apply additional policy') . "\" onClick=\"policyLoad('$policy_name');\">"
      . '<span class="ui-button-text">' . _('Apply') . '</span></button>';

    $html .= "<button id=\"select-$item\" class=\"ui-button ui-widget"
      . ' ui-state-default ui-button-icon-only ui-corner-right split-button"'
      . ' title="' . _('Select an action') . '">'
      . '<span class="ui-button-icon-primary ui-icon ui-icon-triangle-1-s"></span>'
      . '<span class="ui-button-text">' . _('Select an action') . '</span>'
      . '</button>' . "\n";

    $html .= "<ul id=\"context-menu-$item\" class=\"context-menu\">\n";

    $html .= "<li onClick=\"showGetDialog('showPolicy' + 'DialogId',"
      . "'/m_policy/policy_show.php?aps=" . $policy_name . "');\">"
      . "<span class='ui-icon ui-icon-comment'></span>" . _('Show') . "</li>\n";

    $html .= "<li class=ui-menu-divider></li>";

    $html .= "<li onClick=\"showGetDialog('editPolicyDialogId',"
      . "'/m_policy/policy_edit.php?aps=$policy_name&amp;comment="
      . enquote($policy_comment, true) . "');\">"
      . "<span class='ui-icon ui-icon-pencil'></span>" . _('Rename') . "</li>\n";

    $html .= "<li onClick=\"showGetDialog('removePolicy' + 'DialogId',"
      . "'/m_policy/policy_remove.php?aps="  . $policy_name . "');\">"
      . "<span class='ui-icon ui-icon-trash'></span>" . _('Delete') . "</li>\n";

    $html .= "<li onClick=\"javascript:window.location.href='"
      . "/m_policy/policy_download.php?aps=$policy_name';return false;\">"
      . "<span class='ui-icon ui-icon-disk'></span>" . _('Download') . "</li>\n";

    $html .= "</ul>\n";
    $html .= "</div>\n";

    $html .= '</td></tr>' . "\n";
  }

  $html .= '<tr><td class="tableValue" colspan="5">&nbsp;</td></tr>' . "\n";

  $html .= '<tr><td class="tableLabel" align="left" valign="middle" colspan="5">';

  $html .= sprintf(
    _("Used: %d&nbsp;&nbsp;&nbsp;Free: %d"),$policy['used'],$policy['free']
  );
  $html .= '</td></tr>' . "\n";

  $html .= '</table>' . "\n";

  $html .= fnp_form_button_help(null,null,null,'#policy');

  return $html;
}