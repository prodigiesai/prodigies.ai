<?php
/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2004-2014 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2011      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2014      Cedric GROSS         <c.gross@kreiz-it.fr>
 * Copyright (C) 2018-2019  Frédéric France         <frederic.france@netlogic.fr>
 * Copyright (C) 2023 Florian HENRY <florian.henry@scopen.fr>
 * Copyright (C) 2024		MDW							<mdeweerd@users.noreply.github.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */


/**
 *  \file       htdocs/comm/action/peruser.php
 *  \ingroup    agenda
 *  \brief      Tab of calendar events per user
 */

// Load Dolibarr environment
require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/comm/action/class/actioncomm.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/user/class/usergroup.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/agenda.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';


if (!isset($conf->global->AGENDA_MAX_EVENTS_DAY_VIEW)) {
	$conf->global->AGENDA_MAX_EVENTS_DAY_VIEW = 3;
}

$action = GETPOST('action', 'aZ09');

$disabledefaultvalues = GETPOSTINT('disabledefaultvalues');

$filter = GETPOST("search_filter", 'alpha', 3) ? GETPOST("search_filter", 'alpha', 3) : GETPOST("filter", 'alpha', 3);
$filtert = GETPOSTINT("search_filtert", 3) ? GETPOSTINT("search_filtert", 3) : GETPOSTINT("filtert", 3);
$usergroup = GETPOSTINT("search_usergroup", 3) ? GETPOSTINT("search_usergroup", 3) : GETPOSTINT("usergroup", 3);
//if (! ($usergroup > 0) && ! ($filtert > 0)) $filtert = $user->id;
//$showbirthday = empty($conf->use_javascript_ajax)?GETPOST("showbirthday","int"):1;
$showbirthday = 0;

// If not choice done on calendar owner, we filter on user.
/*if (empty($filtert) && empty($conf->global->AGENDA_ALL_CALENDARS))
{
	$filtert = $user->id;
}*/

$sortfield = GETPOST('sortfield', 'aZ09comma');
$sortorder = GETPOST('sortorder', 'aZ09comma');
$page = GETPOSTISSET('pageplusone') ? (GETPOSTINT('pageplusone') - 1) : GETPOSTINT("page");
if (empty($page) || $page == -1) {
	$page = 0;
}     // If $page is not defined, or '' or -1
$limit = GETPOSTINT('limit') ? GETPOSTINT('limit') : $conf->liste_limit;
$offset = $limit * $page;
if (!$sortorder) {
	$sortorder = "ASC";
}
if (!$sortfield) {
	$sortfield = "a.datec";
}

$socid = GETPOSTINT("search_socid") ? GETPOSTINT("search_socid") : GETPOSTINT("socid");
if ($user->socid) {
	$socid = $user->socid;
}
if ($socid < 0) {
	$socid = '';
}

$canedit = 1;
if (!$user->hasRight('agenda', 'myactions', 'read')) {
	accessforbidden();
}
if (!$user->hasRight('agenda', 'allactions', 'read')) {
	$canedit = 0;
}
if (!$user->hasRight('agenda', 'allactions', 'read') || $filter == 'mine') {  // If no permission to see all, we show only affected to me
	$filtert = $user->id;
}

$mode = 'show_peruser';
$resourceid = GETPOSTINT("search_resourceid") ? GETPOSTINT("search_resourceid") : GETPOSTINT("resourceid");
$year = GETPOSTINT("year") ? GETPOSTINT("year") : idate("Y");
$month = GETPOSTINT("month") ? GETPOSTINT("month") : idate("m");
$week = GETPOSTINT("week") ? GETPOSTINT("week") : idate("W");
$day = GETPOSTINT("day") ? GETPOSTINT("day") : idate("d");
$pid = GETPOSTISSET("search_projectid") ? GETPOSTINT("search_projectid", 3) : GETPOSTINT("projectid", 3);
$status = GETPOSTISSET("search_status") ? GETPOST("search_status", 'aZ09') : GETPOST("status", 'aZ09'); // status may be 0, 50, 100, 'todo', 'na' or -1
$type = GETPOSTISSET("search_type") ? GETPOST("search_type", 'alpha') : GETPOST("type", 'alpha');
$maxprint = ((GETPOSTINT("maxprint") != '') ? GETPOSTINT("maxprint") : $conf->global->AGENDA_MAX_EVENTS_DAY_VIEW);
$optioncss = GETPOST('optioncss', 'aZ'); // Option for the css output (always '' except when 'print')
$search_categ_cus = GETPOSTINT("search_categ_cus", 3) ? GETPOSTINT("search_categ_cus", 3) : 0;
// Set actioncode (this code must be same for setting actioncode into peruser, listacton and index)
if (GETPOST('search_actioncode', 'array:aZ09')) {
	$actioncode = GETPOST('search_actioncode', 'array:aZ09', 3);
	if (!count($actioncode)) {
		$actioncode = '0';
	}
} else {
	$actioncode = GETPOST("search_actioncode", "alpha", 3) ? GETPOST("search_actioncode", "alpha", 3) : (GETPOST("search_actioncode", "alpha") == '0' ? '0' : ((!getDolGlobalString('AGENDA_DEFAULT_FILTER_TYPE') || $disabledefaultvalues) ? '' : getDolGlobalString('AGENDA_DEFAULT_FILTER_TYPE')));
}

$dateselect = dol_mktime(0, 0, 0, GETPOSTINT('dateselectmonth'), GETPOSTINT('dateselectday'), GETPOSTINT('dateselectyear'));
if ($dateselect > 0) {
	$day = GETPOSTINT('dateselectday');
	$month = GETPOSTINT('dateselectmonth');
	$year = GETPOSTINT('dateselectyear');
}

$tmp = !getDolGlobalString('MAIN_DEFAULT_WORKING_HOURS') ? '9-18' : $conf->global->MAIN_DEFAULT_WORKING_HOURS;
$tmp = str_replace(' ', '', $tmp); // FIX 7533
$tmparray = explode('-', $tmp);
$begin_h = GETPOSTISSET('begin_h') ? GETPOSTINT('begin_h') : ($tmparray[0] != '' ? $tmparray[0] : 9);
$end_h   = GETPOSTISSET('end_h') ? GETPOSTINT('end_h') : ($tmparray[1] != '' ? $tmparray[1] : 18);
if ($begin_h < 0 || $begin_h > 23) {
	$begin_h = 9;
}
if ($end_h < 1 || $end_h > 24) {
	$end_h = 18;
}
if ($end_h <= $begin_h) {
	$end_h = $begin_h + 1;
}

$tmp = !getDolGlobalString('MAIN_DEFAULT_WORKING_DAYS') ? '1-5' : $conf->global->MAIN_DEFAULT_WORKING_DAYS;
$tmp = str_replace(' ', '', $tmp); // FIX 7533
$tmparray = explode('-', $tmp);
$begin_d = GETPOSTISSET('begin_d') ? GETPOSTINT('begin_d') : ($tmparray[0] != '' ? $tmparray[0] : 1);
$end_d   = GETPOSTISSET('end_d') ? GETPOSTINT('end_d') : ($tmparray[1] != '' ? $tmparray[1] : 5);
if ($begin_d < 1 || $begin_d > 7) {
	$begin_d = 1;
}
if ($end_d < 1 || $end_d > 7) {
	$end_d = 7;
}
if ($end_d < $begin_d) {
	$end_d = $begin_d + 1;
}

if ($status == '' && !GETPOSTISSET('search_status')) {
	$status = ((!getDolGlobalString('AGENDA_DEFAULT_FILTER_STATUS') || $disabledefaultvalues) ? '' : $conf->global->AGENDA_DEFAULT_FILTER_STATUS);
}

if (empty($mode) && !GETPOSTISSET('mode')) {
	$mode = getDolGlobalString('AGENDA_DEFAULT_VIEW', 'show_peruser');
}

if (GETPOST('viewcal', 'alpha') && $mode != 'show_day' && $mode != 'show_week' && $mode != 'show_peruser') {
	$mode = 'show_month';
	$day = '';
} // View by month
if (GETPOST('viewweek', 'alpha') || $mode == 'show_week') {
	$mode = 'show_week';
	$week = ($week ? $week : idate("W"));
	$day = ($day ? $day : idate("d"));
} // View by week
if (GETPOST('viewday', 'alpha') || $mode == 'show_day') {
	$mode = 'show_day';
	$day = ($day ? $day : idate("d"));
} // View by day

$object = new ActionComm($db);

// Load translation files required by the page
$langs->loadLangs(array('users', 'agenda', 'other', 'commercial'));

// Initialize technical object to manage hooks of page. Note that conf->hooks_modules contains array of hook context
$hookmanager->initHooks(array('agenda'));

$result = restrictedArea($user, 'agenda', 0, '', 'myactions');
if ($user->socid && $socid) {
	$result = restrictedArea($user, 'societe', $socid);
}

$search_status = $status;


/*
 * Actions
 */

// None


/*
 * View
 */

$parameters = array(
	'socid' => $socid,
	'status' => $status,
	'year' => $year,
	'month' => $month,
	'day' => $day,
	'type' => $type,
	'maxprint' => $maxprint,
	'filter' => $filter,
	'filtert' => $filtert,
	'showbirthday' => $showbirthday,
	'canedit' => $canedit,
	'optioncss' => $optioncss,
	'actioncode' => $actioncode,
	'pid' => $pid,
	'resourceid' => $resourceid,
	'usergroup' => $usergroup,
);
$reshook = $hookmanager->executeHooks('beforeAgendaPerUser', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) {
	setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
}

$form = new Form($db);
$companystatic = new Societe($db);

$help_url = 'EN:Module_Agenda_En|FR:Module_Agenda|ES:M&oacute;dulo_Agenda|DE:Modul_Terminplanung';
llxHeader('', $langs->trans("Agenda"), $help_url);

$now = dol_now();
$nowarray = dol_getdate($now);
$nowyear = $nowarray['year'];
$nowmonth = $nowarray['mon'];
$nowday = $nowarray['mday'];


// Define list of all external calendars (global setup)
$listofextcals = array();

$prev = dol_get_first_day_week($day, $month, $year);
$first_day   = $prev['first_day'];
$first_month = $prev['first_month'];
$first_year  = $prev['first_year'];

$week = $prev['week'];

$day = (int) $day;
$next = dol_get_next_week($day, $week, $month, $year);
$next_year  = $next['year'];
$next_month = $next['month'];
$next_day   = $next['day'];

$max_day_in_month = idate("t", dol_mktime(0, 0, 0, $month, 1, $year));

$tmpday = $first_day;
//print 'xx'.$prev_year.'-'.$prev_month.'-'.$prev_day;
//print 'xx'.$next_year.'-'.$next_month.'-'.$next_day;

$title = $langs->trans("DoneAndToDoActions");
if ($status == 'done') {
	$title = $langs->trans("DoneActions");
}
if ($status == 'todo') {
	$title = $langs->trans("ToDoActions");
}

$param = '';
if ($actioncode || GETPOSTISSET('search_actioncode')) {
	if (is_array($actioncode)) {
		foreach ($actioncode as $str_action) {
			$param .= "&search_actioncode[]=".urlencode($str_action);
		}
	} else {
		$param .= "&search_actioncode=".urlencode($actioncode);
	}
}
if ($resourceid > 0) {
	$param .= "&search_resourceid=".urlencode((string) ($resourceid));
}

if ($status || GETPOSTISSET('status') || GETPOSTISSET('search_status')) {
	$param .= "&search_status=".urlencode($status);
}
if ($filter) {
	$param .= "&search_filter=".urlencode($filter);
}
if ($filtert) {
	$param .= "&search_filtert=".urlencode($filtert);
}
if ($usergroup > 0) {
	$param .= "&search_usergroup=".urlencode((string) ($usergroup));
}
if ($socid > 0) {
	$param .= "&search_socid=".urlencode((string) ($socid));
}
if ($showbirthday) {
	$param .= "&search_showbirthday=1";
}
if ($pid) {
	$param .= "&search_projectid=".urlencode((string) ($pid));
}
if ($type) {
	$param .= "&search_type=".urlencode($type);
}
if ($mode != 'show_peruser') {
	$param .= '&mode='.urlencode((string) $mode);
}
if ($begin_h != '') {
	$param .= '&begin_h='.((int) $begin_h);
}
if ($end_h != '') {
	$param .= '&end_h='.((int) $end_h);
}
if ($begin_d != '') {
	$param .= '&begin_d='.((int) $begin_d);
}
if ($end_d != '') {
	$param .= '&end_d='.((int) $end_d);
}
if ($search_categ_cus != 0) {
	$param .= '&search_categ_cus='.urlencode((string) ($search_categ_cus));
}
$param .= "&maxprint=".urlencode((string) ($maxprint));

$paramnoactionodate = $param;

$prev = dol_get_first_day_week($day, $month, $year);
//print "day=".$day." month=".$month." year=".$year;
//var_dump($prev); exit;
$prev_year  = $prev['prev_year'];
$prev_month = $prev['prev_month'];
$prev_day   = $prev['prev_day'];
$first_day  = $prev['first_day'];
$first_month = $prev['first_month'];
$first_year = $prev['first_year'];

$week = $prev['week'];

$day = (int) $day;
$next = dol_get_next_week($first_day, $week, $first_month, $first_year);
$next_year  = $next['year'];
$next_month = $next['month'];
$next_day   = $next['day'];

// Define firstdaytoshow and lastdaytoshow. Warning: lastdaytoshow is last second to show + 1
// $firstdaytoshow and lastdaytoshow become a gmt dates to use to search/compare because first_xxx are in tz idea and we used tzuserrel
$firstdaytoshow = dol_mktime(0, 0, 0, $first_month, $first_day, $first_year, 'tzuserrel');
$nb_weeks_to_show = (getDolGlobalString('AGENDA_NB_WEEKS_IN_VIEW_PER_USER')) ? ((int) $conf->global->AGENDA_NB_WEEKS_IN_VIEW_PER_USER * 7) : 7;
$lastdaytoshow = dol_time_plus_duree($firstdaytoshow, $nb_weeks_to_show, 'd');
//print $firstday.'-'.$first_month.'-'.$first_year;
//print dol_print_date($firstdaytoshow, 'dayhour', 'gmt');
//print dol_print_date($lastdaytoshow,'dayhour', 'gmt');

$max_day_in_month = idate("t", dol_mktime(0, 0, 0, $month, 1, $year, 'gmt'));

$tmpday = $first_day;
$picto = 'calendarweek';

// Show navigation bar
$nav = '<div class="navselectiondate inline-block nowraponall">';
$nav .= "<a href=\"?year=".$prev_year."&amp;month=".$prev_month."&amp;day=".$prev_day.$param."\"><i class=\"fa fa-chevron-left\" title=\"".dol_escape_htmltag($langs->trans("Previous"))."\"></i></a> &nbsp; \n";
$nav .= " <span id=\"month_name\">".dol_print_date(dol_mktime(0, 0, 0, $first_month, $first_day, $first_year), "%Y").", ".$langs->trans("Week")." ".$week;
$nav .= " </span>\n";
$nav .= " &nbsp; <a href=\"?year=".$next_year."&amp;month=".$next_month."&amp;day=".$next_day.$param."\"><i class=\"fa fa-chevron-right\" title=\"".dol_escape_htmltag($langs->trans("Next"))."\"></i></a>\n";
if (empty($conf->dol_optimize_smallscreen)) {
	$nav .= " &nbsp; <a href=\"?year=".$nowyear."&amp;month=".$nowmonth."&amp;day=".$nowday.$param.'" class="datenowlink">'.$langs->trans("Today").'</a> ';
}
$nav .= '</div>';
$nav .= $form->selectDate($dateselect, 'dateselect', 0, 0, 1, '', 1, 0);
$nav .= ' <button type="submit" class="liste_titre button_search" name="button_search_x" value="x"><span class="fa fa-search"></span></button>';

// Must be after the nav definition
$param .= '&year='.urlencode((string) ($year)).'&month='.urlencode((string) ($month)).($day ? '&day='.urlencode((string) ($day)) : '');
//print 'x'.$param;


$paramnoaction = preg_replace('/action=[a-z_]+/', '', $param);

$head = calendars_prepare_head($paramnoaction);

print '<form method="POST" id="searchFormList" class="listactionsfilter" action="'.$_SERVER["PHP_SELF"].'">'."\n";

$showextcals = $listofextcals;
// Legend
if ($conf->use_javascript_ajax) {
	$s = '';
	$s .= '<script type="text/javascript">'."\n";
	$s .= 'jQuery(document).ready(function () {'."\n";
	$s .= 'jQuery("#check_mytasks").click(function() { jQuery(".family_mytasks").toggle(); jQuery(".family_other").toggle(); });'."\n";
	$s .= 'jQuery("#check_birthday").click(function() { jQuery(".family_birthday").toggle(); });'."\n";
	$s .= 'jQuery(".family_birthday").toggle();'."\n";
	if ($mode == "show_week" || $mode == "show_month" || empty($mode)) {
		$s .= 'jQuery( "td.sortable" ).sortable({connectWith: ".sortable",placeholder: "ui-state-highlight",items: "div:not(.unsortable)", receive: function( event, ui ) {';
	}
	$s .= '});'."\n";
	$s .= '</script>'."\n";
	if (!empty($conf->use_javascript_ajax)) {
		$s .= '<div class="nowrap clear float"><input type="checkbox" id="check_mytasks" name="check_mytasks" checked disabled> '.$langs->trans("LocalAgenda").' &nbsp; </div>';
		if (is_array($showextcals) && count($showextcals) > 0) {
			foreach ($showextcals as $val) {
				$htmlname = md5($val['name']);
				$s .= '<script type="text/javascript">'."\n";
				$s .= 'jQuery(document).ready(function () {'."\n";
				$s .= '		jQuery("#check_ext'.$htmlname.'").click(function() {';
				$s .= ' 		/* alert("'.$htmlname.'"); */';
				$s .= ' 		jQuery(".family_ext'.$htmlname.'").toggle();';
				$s .= '		});'."\n";
				$s .= '});'."\n";
				$s .= '</script>'."\n";
				$s .= '<div class="nowrap float"><input type="checkbox" id="check_ext'.$htmlname.'" name="check_ext'.$htmlname.'" checked> '.$val ['name'].' &nbsp; </div>';
			}
		}

		//$s.='<div class="nowrap float"><input type="checkbox" id="check_birthday" name="check_birthday"> '.$langs->trans("AgendaShowBirthdayEvents").' &nbsp; </div>';

		// Calendars from hooks
		$parameters = array();
		$reshook = $hookmanager->executeHooks('addCalendarChoice', $parameters, $object, $action);
		if (empty($reshook)) {
			$s .= $hookmanager->resPrint;
		} elseif ($reshook > 1) {
			$s = $hookmanager->resPrint;
		}
	}
}

$mode = 'show_peruser';
$massactionbutton = '';


$viewmode = '<div class="navmode inline-block">';

$viewmode .= '<a class="btnTitle'.($mode == 'list' ? ' btnTitleSelected' : '').' reposition" href="'.DOL_URL_ROOT.'/comm/action/list.php?mode=show_list&restore_lastsearch_values=1'.$paramnoactionodate.'">';
//$viewmode .= '<span class="fa paddingleft imgforviewmode valignmiddle btnTitle-icon">';
$viewmode .= img_picto($langs->trans("List"), 'object_calendarlist', 'class="imgforviewmode pictoactionview block"');
//$viewmode .= '</span>';
$viewmode .= '<span class="valignmiddle text-plus-circle btnTitle-label hideonsmartphone inline-block width75 divoverflow">'.$langs->trans("ViewList").'</span></a>';

$viewmode .= '<a class="btnTitle'.($mode == 'show_month' ? ' btnTitleSelected' : '').' reposition" href="'.DOL_URL_ROOT.'/comm/action/index.php?mode=show_month&year='.dol_print_date($object->datep, '%Y').'&month='.dol_print_date($object->datep, '%m').'&day='.dol_print_date($object->datep, '%d').$paramnoactionodate.'">';
//$viewmode .= '<span class="fa paddingleft imgforviewmode valignmiddle btnTitle-icon">';
$viewmode .= img_picto($langs->trans("ViewCal"), 'object_calendarmonth', 'class="pictoactionview block"');
//$viewmode .= '</span>';
$viewmode .= '<span class="valignmiddle text-plus-circle btnTitle-label hideonsmartphone inline-block width75 divoverflow">'.$langs->trans("ViewCal").'</span></a>';

$viewmode .= '<a class="btnTitle'.($mode == 'show_week' ? ' btnTitleSelected' : '').' reposition" href="'.DOL_URL_ROOT.'/comm/action/index.php?mode=show_week&year='.dol_print_date($object->datep, '%Y').'&month='.dol_print_date($object->datep, '%m').'&day='.dol_print_date($object->datep, '%d').$paramnoactionodate.'">';
//$viewmode .= '<span class="fa paddingleft imgforviewmode valignmiddle btnTitle-icon">';
$viewmode .= img_picto($langs->trans("ViewWeek"), 'object_calendarweek', 'class="pictoactionview block"');
//$viewmode .= '</span>';
$viewmode .= '<span class="valignmiddle text-plus-circle btnTitle-label hideonsmartphone inline-block width75 divoverflow">'.$langs->trans("ViewWeek").'</span></a>';

$viewmode .= '<a class="btnTitle'.($mode == 'show_day' ? ' btnTitleSelected' : '').' reposition" href="'.DOL_URL_ROOT.'/comm/action/index.php?mode=show_day&year='.dol_print_date($object->datep, '%Y').'&month='.dol_print_date($object->datep, '%m').'&day='.dol_print_date($object->datep, '%d').$paramnoactionodate.'">';
//$viewmode .= '<span class="fa paddingleft imgforviewmode valignmiddle btnTitle-icon">';
$viewmode .= img_picto($langs->trans("ViewDay"), 'object_calendarday', 'class="pictoactionview block"');
//$viewmode .= '</span>';
$viewmode .= '<span class="valignmiddle text-plus-circle btnTitle-label hideonsmartphone inline-block width75 divoverflow">'.$langs->trans("ViewDay").'</span></a>';

$viewmode .= '<a class="btnTitle'.($mode == 'show_peruser' ? ' btnTitleSelected' : '').' reposition marginrightonly" href="'.DOL_URL_ROOT.'/comm/action/peruser.php?mode=show_peruser&year='.dol_print_date($object->datep, '%Y').'&month='.dol_print_date($object->datep, '%m').'&day='.dol_print_date($object->datep, '%d').$paramnoactionodate.'">';
//$viewmode .= '<span class="fa paddingleft imgforviewmode valignmiddle btnTitle-icon">';
$viewmode .= img_picto($langs->trans("ViewPerUser"), 'object_calendarperuser', 'class="pictoactionview block"');
//$viewmode .= '</span>';
$viewmode .= '<span class="valignmiddle text-plus-circle btnTitle-label hideonsmartphone inline-block width75 divoverflow" title="'.dolPrintHTML($langs->trans("ViewPerUser")).'">'.$langs->trans("ViewPerUser").'</span></a>';

// Add more views from hooks
$parameters = array();
$object = null;
$reshook = $hookmanager->executeHooks('addCalendarView', $parameters, $object, $action);
if (empty($reshook)) {
	$viewmode .= $hookmanager->resPrint;
} elseif ($reshook > 1) {
	$viewmode = $hookmanager->resPrint;
}

$viewmode .= '</div>';

$viewmode .= '<span class="marginrightonly"></span>';


$newparam = '';
$newcardbutton = '';
if ($user->hasRight('agenda', 'myactions', 'create') || $user->hasRight('agenda', 'allactions', 'create')) {
	$tmpforcreatebutton = dol_getdate(dol_now(), true);

	$newparam .= '&month='.urlencode(str_pad((string) $month, 2, "0", STR_PAD_LEFT)).'&year='.((int) $tmpforcreatebutton['year']);
	if ($begin_h !== '') {
		$newparam .= '&begin_h='.((int) $begin_h);
	}
	if ($end_h !== '') {
		$newparam .= '&end_h='.((int) $end_h);
	}
	if ($begin_d !== '') {
		$newparam .= '&begin_d='.((int) $begin_d);
	}
	if ($end_d !== '') {
		$newparam .= '&end_d='.((int) $end_d);
	}

	$urltocreateaction = DOL_URL_ROOT.'/comm/action/card.php?action=create';
	$urltocreateaction .= '&apyear='.$tmpforcreatebutton['year'].'&apmonth='.$tmpforcreatebutton['mon'].'&apday='.$tmpforcreatebutton['mday'].'&aphour='.$tmpforcreatebutton['hours'].'&apmin='.$tmpforcreatebutton['minutes'];
	$urltocreateaction .= '&backtopage='.urlencode($_SERVER["PHP_SELF"].($newparam ? '?'.$newparam : ''));

	$newcardbutton .= dolGetButtonTitle($langs->trans("AddAction"), '', 'fa fa-plus-circle', $urltocreateaction);
}

$num = '';

print_barre_liste($langs->trans("Agenda"), $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, $massactionbutton, $num, -1, 'object_action', 0, $nav.'<span class="marginleftonly"></span>'.$newcardbutton, '', $limit, 1, 0, 1, $viewmode);

$link = '';
//print load_fiche_titre('', $link.' &nbsp; &nbsp; '.$nav.' '.$newcardbutton, '');


$s .= "\n".'<!-- Div to calendars selectors -->'."\n";

// Local calendar
$newtitle = '<div class="nowrap clear inline-block minheight30">';
$newtitle .= '<input type="checkbox" id="check_mytasks" name="check_mytasks" checked disabled> '.$langs->trans("LocalAgenda").' &nbsp; ';
$newtitle .= '</div>';
//$newtitle=$langs->trans($title);

$s = $newtitle;

// Calendars from hooks
$parameters = array();
$reshook = $hookmanager->executeHooks('addCalendarChoice', $parameters, $object, $action);
if (empty($reshook)) {
	$s .= $hookmanager->resPrint;
} elseif ($reshook > 1) {
	$s = $hookmanager->resPrint;
}

$s .= "\n".'<!-- End div to calendars selectors -->'."\n";

print $s;

print '<div class="liste_titre liste_titre_bydiv centpercent">';
print_actions_filter($form, $canedit, $search_status, $year, $month, $day, $showbirthday, 0, $filtert, 0, $pid, $socid, $action, -1, $actioncode, $usergroup, '', $resourceid, $search_categ_cus);
print '</div>';


// Get event in an array
$eventarray = array();


// DEFAULT CALENDAR + AUTOEVENT CALENDAR + CONFERENCEBOOTH CALENDAR
$sql = "SELECT";
if ($usergroup > 0) {
	$sql .= " DISTINCT";
}
$sql .= " a.id, a.label,";
$sql .= " a.datep,";
$sql .= " a.datep2,";
$sql .= " a.percent,";
$sql .= " a.fk_user_author,a.fk_user_action,";
$sql .= " a.transparency, a.priority, a.fulldayevent, a.location,";
$sql .= " a.fk_soc, a.fk_contact, a.fk_element, a.elementtype, a.fk_project,";
$sql .= " ca.code, ca.libelle as type_label, ca.color, ca.type as type_type, ca.picto as type_picto";
$sql .= " FROM ".MAIN_DB_PREFIX."c_actioncomm as ca, ".MAIN_DB_PREFIX."actioncomm as a";
// We must filter on resource table
if ($resourceid > 0) {
	$sql .= ", ".MAIN_DB_PREFIX."element_resources as r";
}
// We must filter on assignment table
if ($filtert > 0 || $usergroup > 0) {
	$sql .= " INNER JOIN ".MAIN_DB_PREFIX."actioncomm_resources as ar";
	$sql .= " ON ar.fk_actioncomm = a.id AND ar.element_type='user'";
	if ($filtert > 0) {
		$sql .= " AND ar.fk_element = ".((int) $filtert);
	}
	if ($usergroup > 0) {
		$sql .= " INNER JOIN ".MAIN_DB_PREFIX."usergroup_user as ugu ON ugu.fk_user = ar.fk_element AND ugu.fk_usergroup = ".((int) $usergroup);
	}
}

$sql .= " WHERE a.fk_action = ca.id";
$sql .= " AND a.entity IN (".getEntity('agenda').")";
// Condition on actioncode
if (!empty($actioncode)) {
	if (!getDolGlobalString('AGENDA_USE_EVENT_TYPE')) {
		if ($actioncode == 'AC_NON_AUTO') {
			$sql .= " AND ca.type != 'systemauto'";
		} elseif ($actioncode == 'AC_ALL_AUTO') {
			$sql .= " AND ca.type = 'systemauto'";
		} else {
			if ($actioncode == 'AC_OTH') {
				$sql .= " AND ca.type != 'systemauto'";
			}
			if ($actioncode == 'AC_OTH_AUTO') {
				$sql .= " AND ca.type = 'systemauto'";
			}
		}
	} else {
		if ($actioncode == 'AC_NON_AUTO') {
			$sql .= " AND ca.type != 'systemauto'";
		} elseif ($actioncode == 'AC_ALL_AUTO') {
			$sql .= " AND ca.type = 'systemauto'";
		} else {
			if (is_array($actioncode)) {
				$sql .= " AND ca.code IN (".$db->sanitize("'".implode("','", $actioncode)."'", 1).")";
			} else {
				$sql .= " AND ca.code IN (".$db->sanitize("'".implode("','", explode(',', $actioncode))."'", 1).")";
			}
		}
	}
}
if ($resourceid > 0) {
	$sql .= " AND r.element_type = 'action' AND r.element_id = a.id AND r.resource_id = ".((int) $resourceid);
}
if ($pid) {
	$sql .= " AND a.fk_project = ".((int) $pid);
}
// If the internal user must only see his customers, force searching by him
$search_sale = 0;
if (!$user->hasRight('societe', 'client', 'voir')) {
	$search_sale = $user->id;
}
// Search on sale representative
if ($search_sale && $search_sale != '-1') {
	if ($search_sale == -2) {
		$sql .= " AND NOT EXISTS (SELECT sc.fk_soc FROM ".MAIN_DB_PREFIX."societe_commerciaux as sc WHERE sc.fk_soc = a.fk_soc)";
	} elseif ($search_sale > 0) {
		$sql .= " AND EXISTS (SELECT sc.fk_soc FROM ".MAIN_DB_PREFIX."societe_commerciaux as sc WHERE sc.fk_soc = a.fk_soc AND sc.fk_user = ".((int) $search_sale).")";
	}
}
// Search on socid
if ($socid) {
	$sql .= " AND a.fk_soc = ".((int) $socid);
}

if ($mode == 'show_day') {
	$sql .= " AND (";
	$sql .= " (a.datep BETWEEN '".$db->idate(dol_mktime(0, 0, 0, $month, $day, $year, 'tzuserrel'))."'";
	$sql .= " AND '".$db->idate(dol_mktime(23, 59, 59, $month, $day, $year, 'tzuserrel'))."')";
	$sql .= " OR ";
	$sql .= " (a.datep2 BETWEEN '".$db->idate(dol_mktime(0, 0, 0, $month, $day, $year, 'tzuserrel'))."'";
	$sql .= " AND '".$db->idate(dol_mktime(23, 59, 59, $month, $day, $year, 'tzuserrel'))."')";
	$sql .= " OR ";
	$sql .= " (a.datep < '".$db->idate(dol_mktime(0, 0, 0, $month, $day, $year, 'tzuserrel'))."'";
	$sql .= " AND a.datep2 > '".$db->idate(dol_mktime(23, 59, 59, $month, $day, $year, 'tzuserrel'))."')";
	$sql .= ")";
} else {
	// To limit array
	$sql .= " AND (";
	$sql .= " (a.datep BETWEEN '".$db->idate($firstdaytoshow - (60 * 60 * 24 * 2))."'"; // Start 2 day before $firstdaytoshow
	$sql .= " AND '".$db->idate($lastdaytoshow + (60 * 60 * 24 * 2))."')"; // End 2 day after $lastdaytoshow
	$sql .= " OR ";
	$sql .= " (a.datep2 BETWEEN '".$db->idate($firstdaytoshow - (60 * 60 * 24 * 2))."'";
	$sql .= " AND '".$db->idate($lastdaytoshow + (60 * 60 * 24 * 2))."')";
	$sql .= " OR ";
	$sql .= " (a.datep < '".$db->idate($firstdaytoshow - (60 * 60 * 24 * 2))."'";
	$sql .= " AND a.datep2 > '".$db->idate($lastdaytoshow + (60 * 60 * 24 * 2))."')";
	$sql .= ")";
}
if ($type) {
	$sql .= " AND ca.id = ".((int) $type);
}
if ($status == '0') {
	$sql .= " AND a.percent = 0";
}
if ($status === 'na') {
	// Not applicable
	$sql .= " AND a.percent = -1";
}
if ($status == '50') {
	// Running already started
	$sql .= " AND (a.percent > 0 AND a.percent < 100)";
}
if ($status == 'done' || $status == '100') {
	$sql .= " AND (a.percent = 100)";
}
if ($status == 'todo') {
	$sql .= " AND (a.percent >= 0 AND a.percent < 100)";
}
// Search in categories, -1 is all and -2 is no categories
if ($search_categ_cus != -1) {
	if ($search_categ_cus == -2) {
		$sql .= " AND NOT EXISTS (SELECT ca.fk_actioncomm FROM ".MAIN_DB_PREFIX."categorie_actioncomm as ca WHERE ca.fk_actioncomm = a.id)";
	} elseif ($search_categ_cus > 0) {
		$sql .= " AND EXISTS (SELECT ca.fk_actioncomm FROM ".MAIN_DB_PREFIX."categorie_actioncomm as ca WHERE ca.fk_actioncomm = a.id AND ca.fk_categorie IN (".$db->sanitize($search_categ_cus)."))";
	}
}
// Sort on date
$sql .= $db->order("fk_user_action, datep");
//print $sql;

dol_syslog("comm/action/peruser.php", LOG_DEBUG);
$resql = $db->query($sql);
if ($resql) {
	$num = $db->num_rows($resql);

	$i = 0;
	while ($i < $num) {
		$obj = $db->fetch_object($resql);
		//print $obj->fk_user_action.' '.$obj->id."<br>";

		// Discard auto action if option is on
		if (getDolGlobalString('AGENDA_ALWAYS_HIDE_AUTO') && $obj->code == 'AC_OTH_AUTO') {
			$i++;
			continue;
		}

		$datep = $db->jdate($obj->datep);
		$datep2 = $db->jdate($obj->datep2);


		// Create a new object action
		$event = new ActionComm($db);
		$event->id = $obj->id;
		$event->datep = $datep; // datep and datef are GMT date
		$event->datef = $datep2;
		$event->type_code = $obj->code;
		$event->type_color = $obj->color;
		$event->label = $obj->label;
		$event->percentage = $obj->percent;
		$event->authorid = $obj->fk_user_author; // user id of creator
		$event->userownerid = $obj->fk_user_action; // user id of owner
		$event->priority = $obj->priority;
		$event->fulldayevent = $obj->fulldayevent;
		$event->location = $obj->location;
		$event->transparency = $obj->transparency;

		$event->fk_project = $obj->fk_project;

		$event->socid = $obj->fk_soc;
		$event->contact_id = $obj->fk_contact;

		$event->fk_element = $obj->fk_element;
		$event->elementtype = $obj->elementtype;

		// Defined date_start_in_calendar and date_end_in_calendar property
		// They are date start and end of action but modified to not be outside calendar view.
		$event->date_start_in_calendar = $datep;
		if ($datep2 != '' && $datep2 >= $datep) {
			$event->date_end_in_calendar = $datep2;
		} else {
			$event->date_end_in_calendar = $datep;
		}

		//print '<br>'.$i.' - eventid='.$event->id.' '.dol_print_date($event->date_start_in_calendar, 'dayhour').' '.dol_print_date($firstdaytoshow, 'dayhour').' - '.dol_print_date($event->date_end_in_calendar, 'dayhour').' '.dol_print_date($lastdaytoshow, 'dayhour').'<br>'."\n";

		// Check values
		if ($event->date_end_in_calendar < $firstdaytoshow ||
		$event->date_start_in_calendar >= $lastdaytoshow) {
			// This record is out of visible range
			unset($event);
		} else {
			//print $i.' - eventid='.$event->id.' '.dol_print_date($event->date_start_in_calendar, 'dayhour').' - '.dol_print_date($event->date_end_in_calendar, 'dayhour').'<br>'."\n";
			$event->fetch_userassigned(); // This load $event->userassigned

			if ($event->date_start_in_calendar < $firstdaytoshow) {
				$event->date_start_in_calendar = $firstdaytoshow;
			}
			if ($event->date_end_in_calendar >= $lastdaytoshow) {
				$event->date_end_in_calendar = ($lastdaytoshow - 1);
			}

			// Add an entry in actionarray for each day
			$daycursor = $event->date_start_in_calendar;
			$annee = dol_print_date($daycursor, '%Y', 'tzuserrel');
			$mois = dol_print_date($daycursor, '%m', 'tzuserrel');
			$jour = dol_print_date($daycursor, '%d', 'tzuserrel');
			//print $daycursor.' '.dol_print_date($daycursor, 'dayhour', 'gmt').' '.$event->id.' -> '.$annee.'-'.$mois.'-'.$jour.'<br>';

			// Loop on each day covered by action to prepare an index to show on calendar
			$loop = true;
			$j = 0;
			$daykey = dol_mktime(0, 0, 0, $mois, $jour, $annee, 'gmt');
			do {
				//print 'Add event into eventarray for daykey='.$daykey.'='.dol_print_date($daykey, 'dayhour', 'gmt').' '.$event->id.' '.$event->datep.' '.$event->datef.'<br>';

				$eventarray[$daykey][] = $event;
				$j++;

				$daykey += 60 * 60 * 24;
				if ($daykey > $event->date_end_in_calendar) {
					$loop = false;
				}
			} while ($loop);

			//print 'Event '.$i.' id='.$event->id.' (start='.dol_print_date($event->datep).'-end='.dol_print_date($event->datef);
			//print ' startincalendar='.dol_print_date($event->date_start_in_calendar).'-endincalendar='.dol_print_date($event->date_end_in_calendar).') was added in '.$j.' different index key of array<br>';
		}
		$i++;
	}
	$db->free($resql);
} else {
	dol_print_error($db);
}

$maxnbofchar = 18;
$cachethirdparties = array();
$cachecontacts = array();
$cacheusers = array();

// Define theme_datacolor array
$color_file = DOL_DOCUMENT_ROOT."/theme/".$conf->theme."/theme_vars.inc.php";
if (is_readable($color_file)) {
	include $color_file;
}
if (!is_array($theme_datacolor)) {
	$theme_datacolor = array(array(120, 130, 150), array(200, 160, 180), array(190, 190, 220));
}


$newparam = $param; // newparam is for birthday links
$newparam = preg_replace('/showbirthday=/i', 'showbirthday_=', $newparam); // To avoid replacement when replace day= is done
$newparam = preg_replace('/mode=show_month&?/i', '', $newparam);
$newparam = preg_replace('/mode=show_week&?/i', '', $newparam);
$newparam = preg_replace('/day=[0-9]+&?/i', '', $newparam);
$newparam = preg_replace('/month=[0-9]+&?/i', '', $newparam);
$newparam = preg_replace('/year=[0-9]+&?/i', '', $newparam);
$newparam = preg_replace('/viewweek=[0-9]+&?/i', '', $newparam);
$newparam = preg_replace('/showbirthday_=/i', 'showbirthday=', $newparam); // Restore correct parameter
$newparam .= '&viewweek=1';

echo '<input type="hidden" name="actionmove" value="mupdate">';
echo '<input type="hidden" name="backtopage" value="'.dol_escape_htmltag($_SERVER['PHP_SELF']).'?'.dol_escape_htmltag($_SERVER['QUERY_STRING']).'">';
echo '<input type="hidden" name="newdate" id="newdate">';


// Line header with list of days

//print "begin_d=".$begin_d." end_d=".$end_d;

$currentdaytoshow = $firstdaytoshow;
echo '<div class="div-table-responsive">';
//print dol_print_date($currentdaytoshow, 'dayhour', 'gmt');

while ($currentdaytoshow < $lastdaytoshow) {
	echo '<table class="centpercent noborder nocellnopadd cal_month">';

	echo '<tr class="liste_titre">';
	echo '<td class="nopaddingtopimp nopaddingbottomimp nowraponsmartphone">';

	if ($canedit && $mode == 'show_peruser') {
		// Filter on days
		print '<span class="hideonsmartphone" title="'.$langs->trans("VisibleDaysRange").'">';
		print img_picto('', 'clock', 'class="fawidth30 inline-block marginleftonly"');
		print $langs->trans("DaysOfWeek").'</span>';
		print "\n";
		print '<div class="ui-grid-a  inline-block"><div class="ui-block-a nowraponall">';
		print '<input type="number" class="short" name="begin_d" value="'.$begin_d.'" min="1" max="7">';
		if (empty($conf->dol_use_jmobile)) {
			print ' - ';
		} else {
			print '</div><div class="ui-block-b">';
		}
		print '<input type="number" class="short" name="end_d" value="'.$end_d.'" min="1" max="7">';
		print '</div></div>';
	}

	print '</td>';
	$i = 0; // 0 = sunday,
	while ($i < 7) {
		if (($i + 1) < $begin_d || ($i + 1) > $end_d) {
			$i++;
			continue;
		}
		echo '<td align="center" colspan="'.($end_h - $begin_h).'">';
		echo '<span class="bold spandayofweek">'.$langs->trans("Day".(($i + (isset($conf->global->MAIN_START_WEEK) ? $conf->global->MAIN_START_WEEK : 1)) % 7)).'</span>';
		print "<br>";
		if ($i) {
			print dol_print_date(dol_time_plus_duree($currentdaytoshow, $i, 'd'), 'day', 'tzuserrel');
		} else {
			print dol_print_date($currentdaytoshow, 'day', 'tzuserrel');
		}
		echo "</td>\n";
		$i++;
	}
	echo "</tr>\n";

	echo '<tr class="liste_titre">';
	echo '<td>';

	// Filter on hours
	print '<span class="hideonsmartphone" title="'.$langs->trans("VisibleTimeRange").'">';
	print img_picto('', 'clock', 'class="fawidth30 inline-block marginleftonly"');
	print $langs->trans("Hours").'</span>';
	print "\n";
	print '<div class="ui-grid-a inline-block"><div class="ui-block-a nowraponall">';
	print '<input type="number" class="short" name="begin_h" value="'.$begin_h.'" min="0" max="23">';
	if (empty($conf->dol_use_jmobile)) {
		print ' - ';
	} else {
		print '</div><div class="ui-block-b">';
	}
	print '<input type="number" class="short" name="end_h" value="'.$end_h.'" min="1" max="24">';
	if (empty($conf->dol_use_jmobile)) {
		print ' '.$langs->trans("H");
	}
	print '</div></div>';

	echo '</td>';
	$i = 0;
	while ($i < 7) {
		if (($i + 1) < $begin_d || ($i + 1) > $end_d) {
			$i++;
			continue;
		}
		for ($h = $begin_h; $h < $end_h; $h++) {
			echo '<td class="center">';
			print '<small style="font-family: courier">'.sprintf("%02d", $h).'</small>';
			print "</td>";
		}
		echo "</td>\n";
		$i++;
	}
	echo "</tr>\n";


	// Define $usernames
	$usernames = array(); //init
	$usernamesid = array();
	/* Use this to have list of users only if users have events */
	if (getDolGlobalString('AGENDA_SHOWOWNERONLY_ONPERUSERVIEW')) {
		foreach ($eventarray as $daykey => $notused) {
			// Get all assigned users for each event
			foreach ($eventarray[$daykey] as $index => $event) {
				$event->fetch_userassigned();
				$listofuserid = $event->userassigned;
				foreach ($listofuserid as $userid => $tmp) {
					if (!in_array($userid, $usernamesid)) {
						$usernamesid[$userid] = $userid;
					}
				}
			}
		}
	} else {
		/* Use this list to have for all users */
		$sql = "SELECT u.rowid, u.lastname as lastname, u.firstname, u.statut, u.login, u.admin, u.entity";
		$sql .= " FROM ".$db->prefix()."user as u";
		if (isModEnabled('multicompany') && getDolGlobalInt('MULTICOMPANY_TRANSVERSE_MODE')) {
			$sql .= " WHERE u.rowid IN (";
			$sql .= " SELECT ug.fk_user FROM ".$db->prefix()."usergroup_user as ug";
			$sql .= " WHERE ug.entity IN (".getEntity('usergroup').")";
			if ($usergroup > 0) {
				$sql .= " AND ug.fk_usergroup = ".((int) $usergroup);
			}
			$sql .= ")";
		} else {
			if ($usergroup > 0) {
				$sql .= " LEFT JOIN ".$db->prefix()."usergroup_user as ug ON u.rowid = ug.fk_user";
			}
			$sql .= " WHERE u.entity IN (".getEntity('user').")";
			if ($usergroup > 0) {
				$sql .= " AND ug.fk_usergroup = ".((int) $usergroup);
			}
		}
		$sql .= " AND u.statut = 1";
		if ($filtert > 0) {
			$sql .= " AND u.rowid = ".((int) $filtert);
		}
		if ($usergroup > 0) {
			$sql .= " AND ug.fk_usergroup = ".((int) $usergroup);
		}
		if ($user->socid > 0) {
			// External users should see only contacts of their company
			$sql .= " AND u.fk_soc = ".((int) $user->socid);
		}

		//print $sql;
		$resql = $db->query($sql);
		if ($resql) {
			$num = $db->num_rows($resql);
			$i = 0;
			if ($num) {
				while ($i < $num) {
					$obj = $db->fetch_object($resql);
					$usernamesid[$obj->rowid] = $obj->rowid;
					$i++;
				}
			}
		} else {
			dol_print_error($db);
		}
	}
	//var_dump($usernamesid);
	foreach ($usernamesid as $id) {
		$tmpuser = new User($db);
		$result = $tmpuser->fetch($id);
		$usernames[] = $tmpuser;
	}

	// Load array of colors by type
	$colorsbytype = array();
	$labelbytype = array();
	$sql = "SELECT code, color, libelle as label FROM ".MAIN_DB_PREFIX."c_actioncomm ORDER BY position";
	$resql = $db->query($sql);
	while ($obj = $db->fetch_object($resql)) {
		$colorsbytype[$obj->code] = $obj->color;
		$labelbytype[$obj->code] = $obj->label;
	}

	// Loop on each user to show calendar
	$todayarray = dol_getdate($now, 'fast');
	$sav = $tmpday;
	$showheader = true;
	$var = false;
	foreach ($usernames as $username) {
		//if ($username->login != 'admin') continue;

		$var = !$var;

		echo "<tr>";
		echo '<td class="tdoverflowmax100 cal_current_month cal_peruserviewname'.($var ? ' cal_impair' : '').'">';
		print '<span class="paddingrightimp">';
		print $username->getNomUrl(-1, '', 0, 0, 20, 1, '', 'paddingleft');
		print '</span>';
		print '</td>';
		$tmpday = $sav;

		// Lopp on each day of week
		$i = 0;
		for ($iter_day = 0; $iter_day < 8; $iter_day++) {
			if (($i + 1) < $begin_d || ($i + 1) > $end_d) {
				$i++;
				continue;
			}

			// Show days of the current week
			$curtime = dol_time_plus_duree($currentdaytoshow, $iter_day, 'd');
			// $curtime is a gmt time, but we want the day, month, year in user TZ
			$tmpday = dol_print_date($curtime, "%d", "tzuserrel");
			$tmpmonth = dol_print_date($curtime, "%m", "tzuserrel");
			$tmpyear = dol_print_date($curtime, "%Y", "tzuserrel");
			//var_dump($curtime.' '.$tmpday.' '.$tmpmonth.' '.$tmpyear);

			$style = 'cal_current_month';
			if ($iter_day == 6) {
				$style .= ' cal_other_month';
			}
			$today = 0;
			if ($todayarray['mday'] == $tmpday && $todayarray['mon'] == $tmpmonth && $todayarray['year'] == $tmpyear) {
				$today = 1;
			}
			if ($today) {
				$style = 'cal_today_peruser';
			}

			show_day_events2($username, $tmpday, $tmpmonth, $tmpyear, 0, $style, $eventarray, 0, $maxnbofchar, $newparam, 1, 300, $showheader, $colorsbytype, $var);

			$i++;
		}
		echo "</tr>\n";
		$showheader = false;
	}

	echo "</table>\n";
	echo "<br>";

	$currentdaytoshow = dol_time_plus_duree($currentdaytoshow, 7, 'd');
}

echo '</div>';

if (getDolGlobalString('AGENDA_USE_EVENT_TYPE') && getDolGlobalString('AGENDA_USE_COLOR_PER_EVENT_TYPE')) {
	$langs->load("commercial");
	print '<br>'.$langs->trans("Legend").': <br>';
	foreach ($colorsbytype as $code => $color) {
		if ($color) {
			print '<div style="float: left; padding: 2px; margin-right: 6px;"><div style="'.($color ? 'background: #'.$color.';' : '').'width:16px; float: left; margin-right: 4px;">&nbsp;</div>';
			print $langs->trans("Action".$code) != "Action".$code ? $langs->trans("Action".$code) : $labelbytype[$code];
			//print $code;
			print '</div>';
		}
	}
	//$color=sprintf("%02x%02x%02x",$theme_datacolor[0][0],$theme_datacolor[0][1],$theme_datacolor[0][2]);
	print '<div style="float: left; padding: 2px; margin-right: 6px;"><div class="peruser_busy" style="width:16px; float: left; margin-right: 4px;">&nbsp;</div>';
	print $langs->trans("Other");
	print '</div>';
	/* TODO Show this if at least one cumulated event
	print '<div style="float: left; padding: 2px; margin-right: 6px;"><div style="background: #222222; width:16px; float: left; margin-right: 4px;">&nbsp;</div>';
	print $langs->trans("SeveralEvents");
	print '</div>';
	*/
}

print "\n".'</form>';
print "\n";

// Add js code to manage click on a box
print '<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".onclickopenref").click(function() {
		console.log("We click on a class onclickopenref");

		var ref=$(this).attr(\'ref\');
		var res = ref.split("_");
		var userid = res[1];
		var year = res[2];
		var month = res[3];
		var day = res[4];
		var hour = res[5];
		var min = res[6];
		var ids = res[7];
		if (ids == \'none\') /* No event */
		{
			/* alert(\'no event\'); */
			url = "'.DOL_URL_ROOT.'/comm/action/card.php?action=create&assignedtouser="+userid+"&datep="+year+month+day+hour+min+"00&backtopage='.urlencode($_SERVER["PHP_SELF"].'?year='.$year.'&month='.$month.'&day='.$day.($begin_h !== '' ? '&begin_h='.$begin_h : '').($end_h !== '' ? '&end_h='.$end_h : '').($begin_d !== '' ? '&begin_d='.$begin_d : '').($end_d !== '' ? '&end_d='.$end_d : '')).'"
			window.location.href = url;
		}
		else if (ids.indexOf(",") > -1)	/* There is several events */
		{
			/* alert(\'several events\'); */
			url = "'.DOL_URL_ROOT.'/comm/action/list.php?mode=show_list&search_actioncode="+jQuery("#search_actioncode").val()+"&search_status="+jQuery("#selectsearch_status").val()+"&filtert="+userid+"&dateselectyear="+year+"&dateselectmonth="+month+"&dateselectday="+day;
			window.location.href = url;
		}
		else	/* One event */
		{
			/* alert(\'one event\'); */
			url = "'.DOL_URL_ROOT.'/comm/action/card.php?action=view&id="+ids
			window.location.href = url;
		}
	});
});
</script>';

// End of page
llxFooter();
$db->close();




/**
 * Show event line of a particular day for a user
 *
 * @param   User    $username		Login
 * @param   int		$day            Day
 * @param   int		$month          Month
 * @param   int		$year           Year
 * @param   int		$monthshown     Current month shown in calendar view
 * @param   string	$style          Style to use for this day
 * @param   array	$eventarray    	Array of events
 * @param   int		$maxprint       Nb of actions to show each day on month view (0 means no limit)
 * @param   int		$maxnbofchar    Nb of characters to show for event line
 * @param   string	$newparam       Parameters on current URL
 * @param   int		$showinfo       Add extended information (used by day view)
 * @param   int		$minheight      Minimum height for each event. 60px by default.
 * @param	boolean	$showheader		Show header
 * @param	array	$colorsbytype	Array with colors by type
 * @param	bool	$var			true or false for alternat style on tr/td
 * @return	void
 */
function show_day_events2($username, $day, $month, $year, $monthshown, $style, &$eventarray, $maxprint = 0, $maxnbofchar = 16, $newparam = '', $showinfo = 0, $minheight = 60, $showheader = false, $colorsbytype = array(), $var = false)
{
	global $db;
	global $user, $conf, $langs, $hookmanager, $action;
	global $filter, $filtert, $status, $actioncode; // Filters used into search form
	global $theme_datacolor; // Array with a list of different we can use (come from theme)
	global $cachethirdparties, $cachecontacts, $cacheusers, $cacheprojects, $colorindexused;
	global $begin_h, $end_h;

	$cases1 = array(); // Color first half hour
	$cases2 = array(); // Color second half hour
	$cases3 = array(); // Color third half hour
	$cases4 = array(); // Color 4th half hour

	$i = 0;
	$numother = 0;
	$numbirthday = 0;
	$numical = 0;
	$numicals = array();
	//$ymd = sprintf("%04d", $year).sprintf("%02d", $month).sprintf("%02d", $day);

	$colorindexused[$user->id] = 0; // Color index for current user (user->id) is always 0
	$nextindextouse = count($colorindexused); // At first run this is 0, so first user has 0, next 1, ...
	//if ($username->id && $day==1) {
	//var_dump($eventarray);
	//}
	//var_dump("------ username=".$username->login." for day=".$day);

	// We are in a particular day for $username, now we scan all events
	foreach ($eventarray as $daykey => $notused) {
		$annee = dol_print_date($daykey, '%Y', 'tzuserrel');
		$mois =  dol_print_date($daykey, '%m', 'tzuserrel');
		$jour =  dol_print_date($daykey, '%d', 'tzuserrel');
		//var_dump("daykey=$daykey day=$day jour=$jour, month=$month mois=$mois, year=$year annee=$annee");


		if ($day == $jour && (int) $month == (int) $mois && $year == $annee) {	// Is it the day we are looking for when calling function ?
			//var_dump("day=$day jour=$jour month=$month mois=$mois year=$year annee=$annee");

			// Scan all event for this date
			foreach ($eventarray[$daykey] as $index => $event) {
				//print 'daykey='.$daykey.'='.dol_print_date($daykey, 'dayhour', 'gmt').' '.$year.'-'.$month.'-'.$day.' -> This event: '.$event->id.' '.$index.' is open for this daykey '.$annee.'-'.$mois.'-'.$jour."<br>\n";
				//var_dump($event);

				$keysofuserassigned = array_keys($event->userassigned);
				$ponct = ($event->date_start_in_calendar == $event->date_end_in_calendar);

				if (!in_array($username->id, $keysofuserassigned)) {
					continue; // We discard record if event is from another user than user we want to show
				}
				//if ($username->id != $event->userownerid) continue;	// We discard record if event is from another user than user we want to show

				$parameters = array();
				$reshook = $hookmanager->executeHooks('formatEvent', $parameters, $event, $action); // Note that $action and $object may have been modified by some hooks
				if ($reshook < 0) {
					setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
				}

				// Define $color (Hex string like '0088FF') and $cssclass of event
				$color = -1;
				$cssclass = '';
				$colorindex = -1;
				if (in_array($user->id, $keysofuserassigned)) {
					$cssclass = 'family_mytasks';

					if (empty($cacheusers[$event->userownerid])) {
						$newuser = new User($db);
						$newuser->fetch($event->userownerid);
						$cacheusers[$event->userownerid] = $newuser;
					}
					//var_dump($cacheusers[$event->userownerid]->color);

					// We decide to choose color of owner of event (event->userownerid is user id of owner, event->userassigned contains all users assigned to event)
					if (!empty($cacheusers[$event->userownerid]->color)) {
						$color = $cacheusers[$event->userownerid]->color;
					}

					if (getDolGlobalString('AGENDA_USE_COLOR_PER_EVENT_TYPE')) {
						$color = $event->type_color;
					}
				} elseif ($event->type_code == 'ICALEVENT') {
					$numical++;
					if (!empty($event->icalname)) {
						if (!isset($numicals[dol_string_nospecial($event->icalname)])) {
							$numicals[dol_string_nospecial($event->icalname)] = 0;
						}
						$numicals[dol_string_nospecial($event->icalname)]++;
					}

					$color = $event->icalcolor;
					$cssclass = (!empty($event->icalname) ? 'family_ext'.md5($event->icalname) : 'family_other unsortable');
				} elseif ($event->type_code == 'BIRTHDAY') {
					$numbirthday++;
					$colorindex = 2;
					$cssclass = 'family_birthday unsortable';
					$color = sprintf("%02x%02x%02x", $theme_datacolor[$colorindex][0], $theme_datacolor[$colorindex][1], $theme_datacolor[$colorindex][2]);
				} else {
					$numother++;
					$color = ($event->icalcolor ? $event->icalcolor : -1);
					$cssclass = (!empty($event->icalname) ? 'family_ext'.md5($event->icalname) : 'family_other');

					if (empty($cacheusers[$event->userownerid])) {
						$newuser = new User($db);
						$newuser->fetch($event->userownerid);
						$cacheusers[$event->userownerid] = $newuser;
					}
					//var_dump($cacheusers[$event->userownerid]->color);

					// We decide to choose color of owner of event (event->userownerid is user id of owner, event->userassigned contains all users assigned to event)
					if (!empty($cacheusers[$event->userownerid]->color)) {
						$color = $cacheusers[$event->userownerid]->color;
					}

					if (getDolGlobalString('AGENDA_USE_COLOR_PER_EVENT_TYPE')) {
						$color = $event->type_color;
					}
				}

				if ($color < 0) {	// Color was not set on user card. Set color according to color index.
					// Define color index if not yet defined
					$idusertouse = ($event->userownerid ? $event->userownerid : 0);
					if (isset($colorindexused[$idusertouse])) {
						$colorindex = $colorindexused[$idusertouse]; // Color already assigned to this user
					} else {
						$colorindex = $nextindextouse;
						$colorindexused[$idusertouse] = $colorindex;
						if (!empty($theme_datacolor[$nextindextouse + 1])) {
							$nextindextouse++; // Prepare to use next color
						}
					}
					// Define color
					$color = sprintf("%02x%02x%02x", $theme_datacolor[$colorindex][0], $theme_datacolor[$colorindex][1], $theme_datacolor[$colorindex][2]);
				}

				// Define all rects with event (cases1 is first quarter hour, cases2 is second quarter hour, cases3 is second thirds hour, cases4 is 4th quarter hour)
				for ($h = $begin_h; $h < $end_h; $h++) {
					//if ($username->id == 1 && $day==1) print 'h='.$h;
					$newcolor = ''; //init
					if (empty($event->fulldayevent)) {
						$a = dol_mktime((int) $h, 0, 0, $month, $day, $year, 'tzuserrel', 0);
						$b = dol_mktime((int) $h, 15, 0, $month, $day, $year, 'tzuserrel', 0);
						$b1 = dol_mktime((int) $h, 30, 0, $month, $day, $year, 'tzuserrel', 0);
						$b2 = dol_mktime((int) $h, 45, 0, $month, $day, $year, 'tzuserrel', 0);
						$c = dol_mktime((int) $h + 1, 0, 0, $month, $day, $year, 'tzuserrel', 0);

						$dateendtouse = $event->date_end_in_calendar;
						if ($dateendtouse == $event->date_start_in_calendar) {
							$dateendtouse++;
						}

						//print dol_print_date($event->date_start_in_calendar,'dayhour').'-'.dol_print_date($a,'dayhour').'-'.dol_print_date($b,'dayhour').'<br>';

						if ($event->date_start_in_calendar < $b && $dateendtouse > $a) {
							$busy = $event->transparency;
							$cases1[$h][$event->id]['busy'] = $busy;
							$cases1[$h][$event->id]['string'] = dol_print_date($event->date_start_in_calendar, 'dayhour', 'tzuserrel');
							if ($event->date_end_in_calendar && $event->date_end_in_calendar != $event->date_start_in_calendar) {
								$tmpa = dol_getdate($event->date_start_in_calendar, true);
								$tmpb = dol_getdate($event->date_end_in_calendar, true);
								if ($tmpa['mday'] == $tmpb['mday'] && $tmpa['mon'] == $tmpb['mon'] && $tmpa['year'] == $tmpb['year']) {
									$cases1[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'hour', 'tzuserrel');
								} else {
									$cases1[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'dayhour', 'tzuserrel');
								}
							}
							if ($event->label) {
								$cases1[$h][$event->id]['string'] .= ' - '.$event->label;
							}
							$cases1[$h][$event->id]['typecode'] = $event->type_code;
							$cases1[$h][$event->id]['color'] = $color;
							if ($event->fk_project > 0) {
								if (empty($cacheprojects[$event->fk_project])) {
									$tmpproj = new Project($db);
									$tmpproj->fetch($event->fk_project);
									$cacheprojects[$event->fk_project] = $tmpproj;
								}
								$cases1[$h][$event->id]['string'] .= ', '.$langs->trans("Project").': '.$cacheprojects[$event->fk_project]->ref.' - '.$cacheprojects[$event->fk_project]->title;
							}
							if ($event->socid > 0) {
								if (empty($cachethirdparties[$event->socid])) {
									$tmpthirdparty = new Societe($db);
									$tmpthirdparty->fetch($event->socid);
									$cachethirdparties[$event->socid] = $tmpthirdparty;
								}
								$cases1[$h][$event->id]['string'] .= ', '.$cachethirdparties[$event->socid]->name;
							}
							if ($event->contact_id > 0) {
								if (empty($cachecontacts[$event->contact_id])) {
									$tmpcontact = new Contact($db);
									$tmpcontact->fetch($event->contact_id);
									$cachecontacts[$event->contact_id] = $tmpcontact;
								}
								$cases1[$h][$event->id]['string'] .= ', '.$cachecontacts[$event->contact_id]->getFullName($langs);
							}
						}
						if ($event->date_start_in_calendar < $b1 && $dateendtouse > $b) {
							$busy = $event->transparency;
							$cases2[$h][$event->id]['busy'] = $busy;
							$cases2[$h][$event->id]['string'] = dol_print_date($event->date_start_in_calendar, 'dayhour', 'tzuserrel');
							if ($event->date_end_in_calendar && $event->date_end_in_calendar != $event->date_start_in_calendar) {
								$tmpa = dol_getdate($event->date_start_in_calendar, true);
								$tmpb = dol_getdate($event->date_end_in_calendar, true);
								if ($tmpa['mday'] == $tmpb['mday'] && $tmpa['mon'] == $tmpb['mon'] && $tmpa['year'] == $tmpb['year']) {
									$cases2[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'hour', 'tzuserrel');
								} else {
									$cases2[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'dayhour', 'tzuserrel');
								}
							}
							if ($event->label) {
								$cases2[$h][$event->id]['string'] .= ' - '.$event->label;
							}
							$cases2[$h][$event->id]['typecode'] = $event->type_code;
							$cases2[$h][$event->id]['color'] = $color;
							if ($event->fk_project > 0) {
								if (empty($cacheprojects[$event->fk_project])) {
									$tmpproj = new Project($db);
									$tmpproj->fetch($event->fk_project);
									$cacheprojects[$event->fk_project] = $tmpproj;
								}
								$cases2[$h][$event->id]['string'] .= ', '.$langs->trans("Project").': '.$cacheprojects[$event->fk_project]->ref.' - '.$cacheprojects[$event->fk_project]->title;
							}
							if ($event->socid > 0) {
								if (empty($cachethirdparties[$event->socid])) {
									$tmpthirdparty = new Societe($db);
									$tmpthirdparty->fetch($event->socid);
									$cachethirdparties[$event->socid] = $tmpthirdparty;
								}
								$cases2[$h][$event->id]['string'] .= ', '.$cachethirdparties[$event->socid]->name;
							}
							if ($event->contact_id > 0) {
								if (empty($cachecontacts[$event->contact_id])) {
									$tmpcontact = new Contact($db);
									$tmpcontact->fetch($event->contact_id);
									$cachecontacts[$event->contact_id] = $tmpcontact;
								}
								$cases2[$h][$event->id]['string'] .= ', '.$cachecontacts[$event->contact_id]->getFullName($langs);
							}
						}
						if ($event->date_start_in_calendar < $b2 && $dateendtouse > $b1) {
							$busy = $event->transparency;
							$cases3[$h][$event->id]['busy'] = $busy;
							$cases3[$h][$event->id]['string'] = dol_print_date($event->date_start_in_calendar, 'dayhour', 'tzuserrel');
							if ($event->date_end_in_calendar && $event->date_end_in_calendar != $event->date_start_in_calendar) {
								$tmpa = dol_getdate($event->date_start_in_calendar, true);
								$tmpb = dol_getdate($event->date_end_in_calendar, true);
								if ($tmpa['mday'] == $tmpb['mday'] && $tmpa['mon'] == $tmpb['mon'] && $tmpa['year'] == $tmpb['year']) {
									$cases3[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'hour', 'tzuserrel');
								} else {
									$cases3[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'dayhour', 'tzuserrel');
								}
							}
							if ($event->label) {
								$cases3[$h][$event->id]['string'] .= ' - '.$event->label;
							}
							$cases3[$h][$event->id]['typecode'] = $event->type_code;
							$cases3[$h][$event->id]['color'] = $color;
							if ($event->fk_project > 0) {
								if (empty($cacheprojects[$event->fk_project])) {
									$tmpproj = new Project($db);
									$tmpproj->fetch($event->fk_project);
									$cacheprojects[$event->fk_project] = $tmpproj;
								}
								$cases3[$h][$event->id]['string'] .= ', '.$langs->trans("Project").': '.$cacheprojects[$event->fk_project]->ref.' - '.$cacheprojects[$event->fk_project]->title;
							}
							if ($event->socid > 0) {
								if (empty($cachethirdparties[$event->socid])) {
									$tmpthirdparty = new Societe($db);
									$tmpthirdparty->fetch($event->socid);
									$cachethirdparties[$event->socid] = $tmpthirdparty;
								}
								$cases3[$h][$event->id]['string'] .= ', '.$cachethirdparties[$event->socid]->name;
							}
							if ($event->contact_id > 0) {
								if (empty($cachecontacts[$event->contact_id])) {
									$tmpcontact = new Contact($db);
									$tmpcontact->fetch($event->contact_id);
									$cachecontacts[$event->contact_id] = $tmpcontact;
								}
								$cases3[$h][$event->id]['string'] .= ', '.$cachecontacts[$event->contact_id]->getFullName($langs);
							}
						}
						if ($event->date_start_in_calendar < $c && $dateendtouse > $b2) {
							$busy = $event->transparency;
							$cases4[$h][$event->id]['busy'] = $busy;
							$cases4[$h][$event->id]['string'] = dol_print_date($event->date_start_in_calendar, 'dayhour', 'tzuserrel');
							if ($event->date_end_in_calendar && $event->date_end_in_calendar != $event->date_start_in_calendar) {
								$tmpa = dol_getdate($event->date_start_in_calendar, true);
								$tmpb = dol_getdate($event->date_end_in_calendar, true);
								if ($tmpa['mday'] == $tmpb['mday'] && $tmpa['mon'] == $tmpb['mon'] && $tmpa['year'] == $tmpb['year']) {
									$cases4[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'hour', 'tzuserrel');
								} else {
									$cases4[$h][$event->id]['string'] .= '-'.dol_print_date($event->date_end_in_calendar, 'dayhour', 'tzuserrel');
								}
							}
							if ($event->label) {
								$cases4[$h][$event->id]['string'] .= ' - '.$event->label;
							}
							$cases4[$h][$event->id]['typecode'] = $event->type_code;
							$cases4[$h][$event->id]['color'] = $color;
							if ($event->fk_project > 0) {
								if (empty($cacheprojects[$event->fk_project])) {
									$tmpproj = new Project($db);
									$tmpproj->fetch($event->fk_project);
									$cacheprojects[$event->fk_project] = $tmpproj;
								}
								$cases4[$h][$event->id]['string'] .= ', '.$langs->trans("Project").': '.$cacheprojects[$event->fk_project]->ref.' - '.$cacheprojects[$event->fk_project]->title;
							}
							if ($event->socid > 0) {
								if (empty($cachethirdparties[$event->socid])) {
									$tmpthirdparty = new Societe($db);
									$tmpthirdparty->fetch($event->socid);
									$cachethirdparties[$event->socid] = $tmpthirdparty;
								}
								$cases4[$h][$event->id]['string'] .= ', '.$cachethirdparties[$event->socid]->name;
							}
							if ($event->contact_id > 0) {
								if (empty($cachecontacts[$event->contact_id])) {
									$tmpcontact = new Contact($db);
									$tmpcontact->fetch($event->contact_id);
									$cachecontacts[$event->contact_id] = $tmpcontact;
								}
								$cases4[$h][$event->id]['string'] .= ', '.$cachecontacts[$event->contact_id]->getFullName($langs);
							}
						}
					} else {
						$busy = $event->transparency;
						$cases1[$h][$event->id]['busy'] = $busy;
						$cases2[$h][$event->id]['busy'] = $busy;
						$cases3[$h][$event->id]['busy'] = $busy;
						$cases4[$h][$event->id]['busy'] = $busy;
						$cases1[$h][$event->id]['string'] = $event->label;
						$cases2[$h][$event->id]['string'] = $event->label;
						$cases3[$h][$event->id]['string'] = $event->label;
						$cases4[$h][$event->id]['string'] = $event->label;
						$cases1[$h][$event->id]['typecode'] = $event->type_code;
						$cases2[$h][$event->id]['typecode'] = $event->type_code;
						$cases3[$h][$event->id]['typecode'] = $event->type_code;
						$cases4[$h][$event->id]['typecode'] = $event->type_code;
						$cases1[$h][$event->id]['color'] = $color;
						$cases2[$h][$event->id]['color'] = $color;
						$cases3[$h][$event->id]['color'] = $color;
						$cases4[$h][$event->id]['color'] = $color;
					}
				}
				$i++;
			}

			break; // We found the date we were looking for. No need to search anymore.
		}
	}

	// Now output $casesX from start hour to end hour
	for ($h = $begin_h; $h < $end_h; $h++) {
		$color1 = '';
		$color2 = '';
		$color3 = '';
		$color4 = '';
		$style1 = '';
		$style2 = '';
		$style3 = '';
		$style4 = '';
		$string1 = '&nbsp;';
		$string2 = '&nbsp;';
		$string3 = '&nbsp;';
		$string4 = '&nbsp;';
		$title1 = '';
		$title2 = '';
		$title3 = '';
		$title4 = '';
		if (isset($cases1[$h]) && $cases1[$h] != '') {
			//$title1.=count($cases1[$h]).' '.(count($cases1[$h])==1?$langs->trans("Event"):$langs->trans("Events"));
			if (count($cases1[$h]) > 1) {
				$title1 .= count($cases1[$h]).' '.(count($cases1[$h]) == 1 ? $langs->trans("Event") : $langs->trans("Events"));
			}

			if (!getDolGlobalString('AGENDA_NO_TRANSPARENT_ON_NOT_BUSY')) {
				$style1 = 'peruser_notbusy';
			} else {
				$style1 = 'peruser_busy';
			}
			foreach ($cases1[$h] as $id => $ev) {
				if ($ev['busy']) {
					$style1 = 'peruser_busy';
				}
			}
		}
		if (isset($cases2[$h]) && $cases2[$h] != '') {
			//$title2.=count($cases2[$h]).' '.(count($cases2[$h])==1?$langs->trans("Event"):$langs->trans("Events"));
			if (count($cases2[$h]) > 1) {
				$title2 .= count($cases2[$h]).' '.(count($cases2[$h]) == 1 ? $langs->trans("Event") : $langs->trans("Events"));
			}

			if (!getDolGlobalString('AGENDA_NO_TRANSPARENT_ON_NOT_BUSY')) {
				$style2 = 'peruser_notbusy';
			} else {
				$style2 = 'peruser_busy';
			}
			foreach ($cases2[$h] as $id => $ev) {
				if ($ev['busy']) {
					$style2 = 'peruser_busy';
				}
			}
		}
		if (isset($cases3[$h]) && $cases3[$h] != '') {
			//$title3.=count($cases3[$h]).' '.(count($cases3[$h])==1?$langs->trans("Event"):$langs->trans("Events"));
			if (count($cases3[$h]) > 1) {
				$title3 .= count($cases3[$h]).' '.(count($cases3[$h]) == 1 ? $langs->trans("Event") : $langs->trans("Events"));
			}

			if (!getDolGlobalString('AGENDA_NO_TRANSPARENT_ON_NOT_BUSY')) {
				$style3 = 'peruser_notbusy';
			} else {
				$style3 = 'peruser_busy';
			}
			foreach ($cases3[$h] as $id => $ev) {
				if ($ev['busy']) {
					$style3 = 'peruser_busy';
				}
			}
		}
		if (isset($cases4[$h]) && $cases4[$h] != '') {
			//$title4.=count($cases3[$h]).' '.(count($cases3[$h])==1?$langs->trans("Event"):$langs->trans("Events"));
			if (count($cases4[$h]) > 1) {
				$title4 .= count($cases4[$h]).' '.(count($cases4[$h]) == 1 ? $langs->trans("Event") : $langs->trans("Events"));
			}

			if (!getDolGlobalString('AGENDA_NO_TRANSPARENT_ON_NOT_BUSY')) {
				$style4 = 'peruser_notbusy';
			} else {
				$style4 = 'peruser_busy';
			}
			foreach ($cases4[$h] as $id => $ev) {
				if ($ev['busy']) {
					$style4 = 'peruser_busy';
				}
			}
		}

		$ids1 = '';
		$ids2 = '';
		$ids3 = '';
		$ids4 = '';
		if (!empty($cases1[$h]) && is_array($cases1[$h]) && count($cases1[$h]) && array_keys($cases1[$h])) {
			$ids1 = implode(', ', array_keys($cases1[$h]));
		}
		if (!empty($cases2[$h]) && is_array($cases2[$h]) && count($cases2[$h]) && array_keys($cases2[$h])) {
			$ids2 = implode(', ', array_keys($cases2[$h]));
		}
		if (!empty($cases3[$h]) && is_array($cases3[$h]) && count($cases3[$h]) && array_keys($cases3[$h])) {
			$ids3 = implode(',', array_keys($cases3[$h]));
		}
		if (!empty($cases4[$h]) && is_array($cases4[$h]) && count($cases4[$h]) && array_keys($cases4[$h])) {
			$ids4 = implode(',', array_keys($cases4[$h]));
		}

		if ($h == $begin_h) {
			echo '<td class="'.$style.'_peruserleft cal_peruser'.($var ? ' cal_impair '.$style.'_impair' : '').'">';
		} else {
			echo '<td class="'.$style.' cal_peruser'.($var ? ' cal_impair '.$style.'_impair' : '').'">';
		}
		// only 1 event
		if (!empty($cases1[$h]) && is_array($cases1[$h]) && count($cases1[$h]) == 1) {
			$output = array_slice($cases1[$h], 0, 1);
			$title1 = $langs->trans("Ref").' '.$ids1.($title1 ? ' - '.$title1 : '');
			if ($output[0]['string']) {
				$title1 .= ($title1 ? ' - ' : '').$output[0]['string'];
			}
			if ($output[0]['color']) {
				$color1 = $output[0]['color'];
			}
		} elseif (!empty($cases1[$h]) && is_array($cases1[$h]) && count($cases1[$h]) > 1) {
			$title1 = $langs->trans("Ref").' '.$ids1.($title1 ? ' - '.$title1 : '');
			$color1 = '222222';
		}

		// only 1 event
		if (!empty($cases2[$h]) && is_array($cases2[$h]) && count($cases2[$h]) == 1) {
			$output = array_slice($cases2[$h], 0, 1);
			$title2 = $langs->trans("Ref").' '.$ids2.($title2 ? ' - '.$title2 : '');
			if ($output[0]['string']) {
				$title2 .= ($title2 ? ' - ' : '').$output[0]['string'];
			}
			if ($output[0]['color']) {
				$color2 = $output[0]['color'];
			}
		} elseif (!empty($cases2[$h]) && is_array($cases2[$h]) && count($cases2[$h]) > 1) {
			$title2 = $langs->trans("Ref").' '.$ids2.($title2 ? ' - '.$title2 : '');
			$color2 = '222222';
		}

		// only 1 event
		if (!empty($cases3[$h]) && is_array($cases3[$h]) && count($cases3[$h]) == 1) {
			$output = array_slice($cases3[$h], 0, 1);
			$title3 = $langs->trans("Ref").' '.$ids3.($title3 ? ' - '.$title3 : '');
			if ($output[0]['string']) {
				$title3 .= ($title3 ? ' - ' : '').$output[0]['string'];
			}
			if ($output[0]['color']) {
				$color3 = $output[0]['color'];
			}
		} elseif (!empty($cases3[$h]) && is_array($cases3[$h]) && count($cases3[$h]) > 1) {
			$title3 = $langs->trans("Ref").' '.$ids3.($title3 ? ' - '.$title3 : '');
			$color3 = '222222';
		}

		// only 1 event
		if (!empty($cases4[$h]) && is_array($cases4[$h]) && count($cases4[$h]) == 1) {
			$output = array_slice($cases4[$h], 0, 1);
			$title4 = $langs->trans("Ref").' '.$ids3.($title4 ? ' - '.$title4 : '');
			if ($output[0]['string']) {
				$title4 .= ($title4 ? ' - ' : '').$output[0]['string'];
			}
			if ($output[0]['color']) {
				$color4 = $output[0]['color'];
			}
		} elseif (!empty($cases4[$h]) && is_array($cases4[$h]) && count($cases4[$h]) > 1) {
			$title4 = $langs->trans("Ref").' '.$ids4.($title4 ? ' - '.$title4 : '');
			$color4 = '222222';
		}

		print '<table class="nobordernopadding case centpercent">';
		print '<tr>';
		print '<td ';
		if ($style1 == 'peruser_notbusy') {
			print 'style="border: 1px solid #'.($color1 ? $color1 : "888").' !important" ';
		} elseif ($color1) {
			print($color1 ? 'style="background: #'.$color1.';"' : '');
		}
		print 'class="';
		print($style1 ? $style1.' ' : '');
		print 'onclickopenref center'.($title2 ? ' classfortooltip' : '').($title1 ? ' cursorpointer' : '').'" ref="ref_'.$username->id.'_'.sprintf("%04d", $year).'_'.sprintf("%02d", $month).'_'.sprintf("%02d", $day).'_'.sprintf("%02d", $h).'_00_'.($ids1 ? $ids1 : 'none').'"'.($title1 ? ' title="'.$title1.'"' : '').'>';
		print $string1;
		print '</td>';

		print '<td ';
		if ($style2 == 'peruser_notbusy') {
			print 'style="border: 1px solid #'.($color2 ? $color2 : "888").' !important" ';
		} elseif ($color2) {
			print($color2 ? 'style="background: #'.$color2.';"' : '');
		}
		print 'class="';
		print($style2 ? $style2.' ' : '');
		print 'onclickopenref center'.($title2 ? ' classfortooltip' : '').($title1 ? ' cursorpointer' : '').'" ref="ref_'.$username->id.'_'.sprintf("%04d", $year).'_'.sprintf("%02d", $month).'_'.sprintf("%02d", $day).'_'.sprintf("%02d", $h).'_15_'.($ids2 ? $ids2 : 'none').'"'.($title2 ? ' title="'.$title2.'"' : '').'>';
		print $string2;
		print '</td>';

		print '<td ';
		if ($style3 == 'peruser_notbusy') {
			print 'style="border: 1px solid #'.($color3 ? $color3 : "888").' !important" ';
		} elseif ($color3) {
			print($color3 ? 'style="background: #'.$color3.';"' : '');
		}
		print 'class="';
		print($style3 ? $style3.' ' : '');
		print 'onclickopenref center'.($title2 ? ' classfortooltip' : '').($title3 ? ' cursorpointer' : '').'" ref="ref_'.$username->id.'_'.sprintf("%04d", $year).'_'.sprintf("%02d", $month).'_'.sprintf("%02d", $day).'_'.sprintf("%02d", $h).'_30_'.($ids3 ? $ids3 : 'none').'"'.($title3 ? ' title="'.$title3.'"' : '').'>';
		print $string3;
		print '</td>';

		print '<td ';
		if ($style4 == 'peruser_notbusy') {
			print 'style="border: 1px solid #'.($color4 ? $color4 : "888").' !important" ';
		} elseif ($color4) {
			print($color4 ? 'style="background: #'.$color4.';"' : '');
		}
		print 'class="';
		print($style4 ? $style4.' ' : '');
		print 'onclickopenref center'.($title3 ? ' classfortooltip' : '').($title4 ? ' cursorpointer' : '').'" ref="ref_'.$username->id.'_'.sprintf("%04d", $year).'_'.sprintf("%02d", $month).'_'.sprintf("%02d", $day).'_'.sprintf("%02d", $h).'_45_'.($ids4 ? $ids4 : 'none').'"'.($title4 ? ' title="'.$title4.'"' : '').'>';
		print $string4;
		print '</td>';

		print '</tr>';
		print '</table>';
		print '</td>';
	}
}
