<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.38.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */


class JobsCommand extends CConsoleCommand
{
    # php cron.php jobs test
    public function actionTest()
    {
        echo 'Test is passed.' . PHP_EOL;
        //echo date('Y-m-d H:i:s') . "\r\n";

        /*$mVal = array('actionTest' => 'ok');
        $file = fopen('/home/.../public_html/uploads/logs_cron.txt', 'a+');
        $sLogs = date("d.m.y H:i:s : ") . var_export($mVal, true) . "\n";
        fwrite($file, $sLogs);
        fclose($file);*/
    }


    # один раз в сутки в 6:00
    # 0 6 * * * php /home/.../protected/cron.php jobs importFromYandexRealty
    public function actionImportFromYandexRealty()
    {
        if (issetModule('yandexRealty')) {
            $yandexRealtyImport = new YandexRealtyImport();

            $yandexRealtyImport->deleteOldTmpFiles();
            $yandexRealtyImport->checkNeedUpdateData();
            $yandexRealtyImport->updateListings();
        }
    }
}
