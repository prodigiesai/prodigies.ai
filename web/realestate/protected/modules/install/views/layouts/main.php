<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700,500&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic'
          rel='stylesheet' type='text/css'>

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css"
          media="screen"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css"
          media="print"/>

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css"/>
    <link media="screen" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css"
          rel="stylesheet"/>

    <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>

    <title><?php echo tFile::getT('module_install', 'Open Real Estate') . ' - ' . tFile::getT('module_install', 'Installation in 1 step'); ?></title>

    <style type="text/css">
        body {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
        }

        #page {
            width: 800px;
            margin: 0 auto;
        }

        div.logo {
            float: left;
            margin-left: -20px;
        }

        div.content {
            padding-top: 5px;
        }

        #footer {
            color: gray;
            font-size: 10px;
            border-top: 1px solid #aaa;
            margin-top: 10px;
        }

        h1 {
            color: black;
            font-size: 1.6em;
            font-weight: bold;
            margin: 0.5em 0;
        }

        h2 {
            color: black;
            font-size: 1.25em;
            font-weight: bold;
            margin: 0.3em 0;
        }

        h3 {
            color: black;
            font-size: 1.1em;
            font-weight: bold;
            margin: 0.2em 0;
        }

        table.result {
            background: #E6ECFF none repeat scroll 0 0;
            border-collapse: collapse;
            width: 100%;
        }

        table.result th {
            background: #CCD9FF none repeat scroll 0 0;
            text-align: left;
        }

        table.result th, table.result td {
            border: 1px solid #BFCFFF;
            padding: 0.2em;
        }

        td.passed {
            background-color: #60BF60;
            border: 1px solid silver;
            padding: 2px;
        }

        td.warning {
            background-color: #FFFFBF;
            border: 1px solid silver;
            padding: 2px;
        }

        td.failed {
            background-color: #FF8080;
            border: 1px solid silver;
            padding: 2px;
        }

        .install_box {
            background-color: #EDF4FF;
            margin: 5px 0;
            padding: 5px;
            border: 1px solid #CCCCCC;
        }

        .install_color {
            background-color: #EDF4FF;
            border: 1px solid #CCCCCC;
            padding-left: 20px;
        }

        .padding-left5 {
            padding-left: 5px;
        }

        .install .install_color input, .install .install_color textarea {
            width: 200px;
            height: 20px;
        }

        div.form .row.buttons {
            margin: 15px 0 0 0;
        }

        div.form .row, .form-group {
            margin: 0px 0;
        }

        div.form input, div.form textarea, div.form select {
            margin: 0 0 0.5em 0;
        }

        div.form .license-block {
            margin: 10px 0 0 0;
        }

        div.form .site-description textarea { /*width: 465px;*/
            width: 100%;
            height: 50px;
            resize: none;
        }

        div.form fieldset {
            border: 1px solid #ccc;
            margin: 10px 0 15px 5px;
            padding: 10px;
        }

        .span-12 {
            width: 450px;
        }

        div.form legend {
            font-size: 24px;
            padding: 0 5px;
        }

        div.form label {
            font-size: 14px;
            font-weight: normal;
        }

        div.content {
            width: 100% !important;
        }

        .install_color .row {
            padding-left: 5px;
        }

        .install-select-lang .span-6 a {
            font-size: 12px;
        }

        .custom-info-block a {
            display: inline;
            padding: 5px 15px;
            border: 2px solid #000000;
            -moz-border-radius: 0;
            -webkit-border-radius: 0;
            border-radius: 0;
            text-align: center;
            text-decoration: none;
            color: #000000;
            margin: 0 8px;
        }

        .custom-info-block a:first-child {
            margin: 0;
        }

        .custom-info-block a:hover {
            background-color: #2b77b3;
            color: #fff;
            border: 2px solid #2b77b3;
        }

        .template-block-row {
            display: inline;
            width: 210px;
            padding-right: 10px;
            float: left;
        }

        .template-block-row:last-child {
            padding-right: 0;
        }

        .template-block-row input {
            width: 12px !important;
            height: 0px !important;
        }

        .template-name-row {
            display: inline !important;
            text-transform: uppercase;
        }

        .template-buy-link {
            color: #000;
            text-decoration: underline;
            text-transform: none !important;
            padding-left: 10px;
        }

        .template-block-row input[type=radio] {
            width: 15px !important;
            height: 15px !important;
        }
        #container {
            width: 960px;
            margin: 0 auto;
        }
        div.content {
            float: left;
            width: 960px;
        }
        div.footer {
            width: 100%;
            height: 70px;
        }
        .footer {
            float: left;
            width: 960px;
            margin: 30px 0 0 0;
            border-top: 1px solid #cfcfcf;
            background: url(../images/pages/footer_bg.jpg) repeat-x top;
            background-color: #efefef;
            background-image: -moz-linear-gradient(top, #efefef 0%, #fdfdfd 100%);
            background-image: -webkit-linear-gradient(top, #efefef 0%, #fdfdfd 100%);
            background-image: -o-linear-gradient(top, #efefef 0%, #fdfdfd 100%);
            background-image: -ms-linear-gradient(top, #efefef 0% ,#fdfdfd 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#efefef', endColorstr='#fdfdfd',GradientType=0 );
            background-image: linear-gradient(top, #efefef 0% ,#fdfdfd 100%);
        }
        .hidden {
            display: none;
        }
        .row input[type="submit"]:hover, .button-blue:hover {
            background: url("../images/design/active.gif") repeat-x scroll 0 0 transparent;
            background-color: #5b90df;
            background-image: -moz-linear-gradient(top, #5b90df 0%, #274f8c 100%);
            background-image: -webkit-linear-gradient(top, #5b90df 0%, #274f8c 100%);
            background-image: -o-linear-gradient(top, #5b90df 0%, #274f8c 100%);
            background-image: -ms-linear-gradient(top, #5b90df 0% ,#274f8c 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5b90df', endColorstr='#274f8c',GradientType=0 );
            background-image: linear-gradient(top, #5b90df 0% ,#274f8c 100%);
        }
        .row input[type="submit"], .button-blue {
            background: url("../images/design/active.gif") repeat-x scroll 0 0 transparent;
            background-color: #274f8c;
            background-image: -moz-linear-gradient(top, #274f8c 0%, #5b90df 100%);
            background-image: -webkit-linear-gradient(top, #274f8c 0%, #5b90df 100%);
            background-image: -o-linear-gradient(top, #274f8c 0%, #5b90df 100%);
            background-image: -ms-linear-gradient(top, #274f8c 0% ,#5b90df 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#274f8c', endColorstr='#5b90df',GradientType=0 );
            background-image: linear-gradient(top, #274f8c 0% ,#5b90df 100%);
            color: #FFFFFF !important;
            display: block;
            font: 14px 'Roboto', Arial, sans-serif;
            font-weight: normal;
            font-size: 14px;
            margin: 0 10px 0 0;
            padding: 5px 20px;
            text-align: center;
            border: 0;
            cursor: pointer;
        }
        div.logo a {
            color: #acacac;
        }
        div.logo {
            float: left;
            margin: 15px 0 10px;
            margin-left: 0px;
            background:  url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARQAAABGCAYAAAANQWsxAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAYdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuOWwzfk4AAAEPSURBVHhe7dcxbQMBFAXB9FGQBGAgmJAJGJGlEEjOrrd6d+UU84sF8KT/8f1zB7hERoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoBFRoDF63wBnPR5eA/KL8BJz8PtNSh/AFcwKMBlvDzARe6Pf4+1FXYSboNQAAAAAElFTkSuQmCC') no-repeat 70px top;
        }
        div.logo a {
            color: #acacac;
            display: block;
            position: relative;
            text-decoration: none;
            overflow: hidden;
        }
        div.logo-img {
            float: left;
        }
        div.logo-img img {
            border: 0 none;
            height: auto;
            max-width: 100%;
            vertical-align: middle;
        }
        div.logo-text {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 21px;
            color: #000;
            margin-top: 40px;
            float: left;
        }
        body {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 14px;
            line-height: 24px;
            color: #000;
            background-color: #ffffff;
            margin: 0 auto;
        }
    </style>
</head>

<body>
<div id="container">
    <div class="logo">
        <a title="" href="<?php echo Yii::app()->controller->createAbsoluteUrl('/'); ?>">
            <div class="logo-img"><img width="77" height="70" alt=""
                                       src="<?php echo Yii::app()->theme->baseUrl; ?>/images/pages/logo-open-ore.png"/>
            </div>
            <div class="logo-text"><?php echo (defined('ORE_VERSION_NAME')) ? ORE_VERSION_NAME : 'Open Real Estate CMS' ;?></div>
        </a>
    </div>

    <?php
    if (!isFree() && Yii::app()->controller->action->id != 'index') {
        $this->widget('application.modules.lang.components.langSelectorWidget', array(
            'type' => 'links',
            'languages' => array(
                'en' => array(
                    'name_iso' => 'en',
                    'name' => 'English',
                    'flag_img' => 'us.png'
                ),
                'ru' => array(
                    'name_iso' => 'ru',
                    'name' => 'Русский',
                    'flag_img' => 'ru.png'
                ),
                'de' => array(
                    'name_iso' => 'de',
                    'name' => 'Deutsch',
                    'flag_img' => 'de.png'
                ),
                'es' => array(
                    'name_iso' => 'es',
                    'name' => 'Spanish',
                    'flag_img' => 'es.png'
                ),
                'ar' => array(
                    'name_iso' => 'ar',
                    'name' => 'Arab',
                    'flag_img' => 'sa.png'
                ),
                'tr' => array(
                    'name_iso' => 'tr',
                    'name' => 'Türk',
                    'flag_img' => 'tr.png'
                ),
            )
        ));
    }

    ?>
    <div class="content install">
        <?php echo $content; ?>
        <div class="clear"></div>
    </div>

    <div class="footer">
        <p class="slogan">
            &copy;&nbsp;2011-<?php echo date('Y'); ?>, <?php echo tFile::getT('module_install', 'Open Real Estate')?>, <?php echo ORE_VERSION; ?></p>
    </div>

    <?php
    if ($this->isAssetsIsWritable()) {
        $this->widget('ext.magnific-popup.EMagnificPopup', array(
            'target' => 'a.fancy',
            'type' => 'image',
            'options' => array(
                'closeOnContentClick' => true,
                'mainClass' => 'mfp-img-mobile',
                'callbacks' => array(
                    'close' => 'js:function(){
							var capClick = $(".get-new-ver-code");
							if(typeof capClick !== "undefined")	capClick.click();
						}
						',
                ),
            ),
        ));

        $this->widget('ext.magnific-popup.EMagnificPopup', array(
                'target' => '.mgp-open-inline',
                'type' => 'inline',
                'options' => array(
                    'preloader' => false,
                    'focus' => '#name',
                    'callbacks' => array(
                        'beforeOpen' => 'js:function() {
								if($(window).width() < 700) {
								  this.st.focus = false;
								} else {
								  this.st.focus = "#name";
								}
							  }
							',
                        'close' => 'js:function(){
								var capClick = $(".get-new-ver-code");
								if(typeof capClick !== "undefined")	capClick.click();
							}
							',
                    ),
                ),
            )
        );
    }

    ?>
</div>
</body>
</html>