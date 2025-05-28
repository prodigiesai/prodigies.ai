<?php

class HDev extends CWidget
{
    public function run()
    {
        $this->registerAsset();

        echo '<div id="dev-ore-gear" onclick="$(\'#dev-ore-box\').toggle();"><i class="fa fa-gear"></i> dev</div>';

        echo '<div id="dev-ore-box" style="display: none;">';

        $themeList = Themes::getTemplatesList(true);

        echo CHtml::dropDownList('template', Themes::getParam('title'), $themeList, array(
            'onchange' => 'js: changeThemeTemplate();',
            'empty' => Yii::t('module_install', 'Theme', array(), 'messagesInFile', Yii::app()->language),
            'class' => 'form-control',
            'id' => 'template_preview'
        ));

        $themeList = Themes::getColorThemesList(Themes::THEME_DOLPHIN_NAME);

        echo CHtml::dropDownList('theme', Themes::getParam('color_theme'), $themeList, array(
            'onchange' => 'js: changeThemeTemplate();',
            'empty' => Yii::t('module_install', 'Color theme', array(), 'messagesInFile', Yii::app()->language),
            'class' => 'form-control',
            'id' => 'theme_preview'
        ));

        echo '</div>';
    }

    public function registerAsset()
    {
        echo <<< HTML
<style>
    #dev-ore-gear {
        z-index: 1000;
        position: fixed;
        width: 35px;
        height: 20px;
        background: #13fff8;
        right: 20px;
        top: 50px;
        line-height: 20px;
        padding: 0;
        border-radius: 40%;
        text-align: center;
        cursor: pointer;
        font-weight: bold;
        font-size: smaller;
    }
    
    #dev-ore-gear:hover {
        background: #20ebff;
    }
    
    #dev-ore-box {
        z-index: 1000;
        position: fixed;
        width: 150px;
        height: 100px;
        background: #fffafe;
        right: 20px;
        top: 100px;
        padding: 5px;
        border: 1px dotted #3c3c3c;
        border-radius: 10px;
        text-align: center;
    }
</style>
HTML;

        Yii::app()->getClientScript()->registerScript('re-dev-js', "
	function changeThemeTemplate() {
        let url, theme, template;

        url = location.href;
        template = document.getElementById('template_preview');
        theme = document.getElementById('theme_preview');

        if (template) {
            url = URL_add_parameter(url, 'template', template.options[template.selectedIndex].value);
        }

        if (theme) {
            url = URL_add_parameter(url, 'theme', theme.options[theme.selectedIndex].value);
        }

        location.href = url;
    }
    
    function URL_add_parameter(url, param, value) {
        var hash = {};
        var parser = document.createElement('a');

        parser.href = url;

        var parameters = parser.search.split(/\?|&/);

        for (var i = 0; i < parameters.length; i++) {
            if (!parameters[i])
                continue;

            var ary = parameters[i].split('=');
            hash[ary[0]] = ary[1];
        }

        hash[param] = value;

        var list = [];
        Object.keys(hash).forEach(function (key) {
            list.push(key + '=' + hash[key]);
        });

        parser.search = '?' + list.join('&');
        return parser.href;
    }		
", CClientScript::POS_END, array(), true);

    }
}