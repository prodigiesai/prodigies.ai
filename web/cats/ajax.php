<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
/*
 * CATS
 * AJAX Delegation Module
 *
 * CATS Version: 0.9.7.4
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 *
 * A properly formatted POST string will look like this:
 *
 *    f=myFunction&arg=myArgument&...
 *
 *
 * $Id: ajax.php 3431 2007-11-06 21:10:12Z will $
 */


include_once('./config.php');
include_once(LEGACY_ROOT . '/constants.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');
include_once(LEGACY_ROOT . '/lib/Session.php'); /* Depends: MRU, Users, DatabaseConnection. */
include_once(LEGACY_ROOT . '/lib/AJAXInterface.php');
include_once(LEGACY_ROOT . '/lib/CATSUtility.php');


header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// Modernized: Remove legacy magic quotes handling
$_GET = array_map('stripslashes_if_needed', $_GET);
$_POST = array_map('stripslashes_if_needed', $_POST);
$_REQUEST = array_map('stripslashes_if_needed', $_REQUEST);

// Helper function to check and apply stripslashes only when needed
function stripslashes_if_needed($value) {
    return is_string($value) ? stripslashes($value) : $value;
}

if (!isset($_REQUEST['f']) || empty($_REQUEST['f']))
{
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="', AJAX_ENCODING, '"?>', "\n";
    echo(
        "<data>\n" .
        "    <errorcode>-1</errorcode>\n" .
        "    <errormessage>No function specified.</errormessage>\n" .
        "</data>\n"
    );

    die();
}

if (strpos($_REQUEST['f'], ':') === false)
{
    $function = preg_replace("/[^A-Za-z0-9]/", "", $_REQUEST['f']);
    
    $filename = sprintf('ajax/%s.php', $function);
}
else
{
    /* Split function parameter into module name and function name. */
    $parameters = explode(':', $_REQUEST['f']);
    
    $module = preg_replace("/[^A-Za-z0-9]/", "", $parameters[0]);
    $function = preg_replace("/[^A-Za-z0-9]/", "", $parameters[1]);
    
    $filename = sprintf('modules/%s/ajax/%s.php', $module, $function);
}

if (!is_readable($filename))
{
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="', AJAX_ENCODING, '"?>', "\n";
    echo(
        "<data>\n" .
        "    <errorcode>-1</errorcode>\n" .
        "    <errormessage>Invalid function name.</errormessage>\n" .
        "</data>\n"
    );

    die();
}

$filters = array();

if (!isset($_REQUEST['nobuffer']))
{
    include_once(LEGACY_ROOT . '/lib/Hooks.php');

    ob_start();
    include($filename);
    $output = ob_get_clean();

    if (!eval(Hooks::get('AJAX_HOOK'))) return;

    if (!isset($_REQUEST['nospacefilter']))
    {
        $output = preg_replace('/^\s+/m', '', $output);
    }

    foreach ($filters as $filter)
    {
        eval($filter);
    }

    echo($output);
}
else
{
    include($filename);
}


?>
