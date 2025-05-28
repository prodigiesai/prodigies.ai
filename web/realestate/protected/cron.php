<?php
$yiic = dirname(__FILE__) . '/../framework/yiic.php';
$config = dirname(__FILE__) . '/config/cron.php';

define('ROOT_PATH', dirname(__FILE__));
define('ALREADY_INSTALL_FILE', ROOT_PATH . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'already_install');

if (!file_exists(dirname(__FILE__) . '/config/main.php')) {
    define('IS_FREE', true);
}

require_once($yiic);
