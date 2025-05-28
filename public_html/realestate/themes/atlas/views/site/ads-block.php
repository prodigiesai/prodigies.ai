<div id="ore-ads-block">
    <div>
        <ul>
            <li class="ads-block-buy">
                <?php
                $linkTitle = Yii::t('module_install', 'Buy', array(), 'messagesInFile', Yii::app()->language);
                $linkHref = (Yii::app()->language == 'ru') ? 'http://open-real-estate.info/ru/download-open-real-estate' : 'http://open-real-estate.info/en/download-open-real-estate';

                echo CHtml::link(
                    '<span class="download"></span>' . $linkTitle,
                    $linkHref,
                    array(
                        'class' => 'button green',
                        'target' => '_blank',
                    )
                );
                ?>
            </li>
            <?php if (isFree()): ?>
                <li class="ads-block-demo-pro">
                    <?php
                    echo CHtml::link(
                        Yii::t('module_install', 'PRO version demo', array(), 'messagesInFile', Yii::app()->language),
                        'http://re-pro.monoray.net/',
                        array(
                            'class' => 'button green',
                            'target' => '_blank',
                        )
                    );
                    ?>
                </li>

                <li class="ads-block-addons">
                    <?php
                    echo CHtml::link(
                        Yii::t('module_install', 'Add-ons', array(), 'messagesInFile', Yii::app()->language),
                        (Yii::app()->language == 'ru') ? 'http://open-real-estate.info/ru/open-real-estate-modules' : 'http://open-real-estate.info/en/open-real-estate-modules',
                        array(
                            'class' => 'button cyan',
                            'target' => '_blank',
                        )
                    );
                    ?>
                </li>
            <?php endif; ?>
            <li class="ads-block-about">
                <?php
                echo CHtml::link(
                    Yii::t('module_install', 'About product', array(), 'messagesInFile', Yii::app()->language),
                    (Yii::app()->language == 'ru') ? 'http://open-real-estate.info/ru/about-open-real-estate' : 'http://open-real-estate.info/en/about-open-real-estate',
                    array(
                        'class' => 'button cyan',
                        'target' => '_blank',
                    )
                );
                ?>
            </li>
            <li class="ads-block-contact">
                <?php
                echo CHtml::link(
                    Yii::t('module_install', 'Contact us', array(), 'messagesInFile', Yii::app()->language),
                    (Yii::app()->language == 'ru') ? 'http://open-real-estate.info/ru/contact-us' : 'http://open-real-estate.info/en/contact-us',
                    array(
                        'class' => 'button cyan',
                        'target' => '_blank',
                    )
                );
                ?>
            </li>

            <?php if (Yii::app()->user->isGuest) { ?>
                <li class="item-login">
                    <?php
                    echo CHtml::link(
                        Yii::t('module_install', 'Log in', array(), 'messagesInFile', Yii::app()->language),
                        Yii::app()->createUrl('/login'),
                        array(
                            'class' => 'button orange',
                        )
                    );
                    ?>
                </li>
                <li class="item-login-admin-panel">
                    <?php
                    echo CHtml::link(
                        Yii::t('module_install', 'Admin panel', array(), 'messagesInFile', Yii::app()->language),
                        Yii::app()->createUrl('/login', array('inadminpanel' => 1)),
                        array(
                            'class' => 'button orange',
                        )
                    );
                    ?>
                </li>
            <?php } ?>

            <?php if (!isFree()): ?>
                <?php if (!basicVersion()): ?>
                    <li class="ads-block-change-template">
                        <?php
                        $themeList = Themes::getTemplatesList();

                        echo CHtml::dropDownList('template', Themes::getParam('title'), $themeList, array(
                            'onchange' => 'js: changeThemeTemplate();',
                            'empty' => Yii::t('module_install', 'Theme', array(), 'messagesInFile', Yii::app()->language),
                            'id' => 'template_preview'
                        ));

                        $isShownHint = HCookie::get('is_show_hint_tpl');

                        if(!$isShownHint || ($isShownHint && $isShownHint < HSite::$demoShowChangeTemplateCountMax)){
                            ?>
                            <ul>
                                <li class="dwnl-arrow dwnl-arrow-active">
                                    <p><?php echo Yii::t('module_install', 'Click here to change template', array(), 'messagesInFile', Yii::app()->language);?></p>
                                </li>
                            </ul>
                            <?php
                            if(!$isShownHint){
                                $isShownHint = 0;
                            }
                            $isShownHint++;
                            HCookie::set('is_show_hint_tpl', $isShownHint);
                        }
                        ?>
                    </li>
                <?php endif; ?>
                <li class="ads-block-change-theme">
                    <?php
                    $themeList = Themes::getColorThemesList(Themes::THEME_ATLAS_NAME);
                    //deb($themeList);

                    echo CHtml::dropDownList('theme', Themes::getParam('color_theme'), $themeList, array(
                        'onchange' => 'js: changeThemeTemplate();',
                        'empty' => Yii::t('module_install', 'Color theme', array(), 'messagesInFile', Yii::app()->language),
                        'id' => 'theme_preview'
                    ));
                    ?>
                </li>
                <li class="ads-block-additional-view">
                    <?php
                    $themeList = Themes::getAdditionalViewList(true);

                    echo CHtml::dropDownList('additional_view', Yii::app()->controller->useAdditionalView, $themeList, array(
                        'empty' => Yii::t('module_install', 'Additionally', array(), 'messagesInFile', Yii::app()->language),
                        'onchange' => 'js: changeThemeTemplate();',
                        'id' => 'additional_view_preview'
                    ));
                    ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php
Yii::app()->getClientScript()->registerScript('atlas-ads-block-js', "
    function changeThemeTemplate() {
        let url, theme, template, additional_view;

        url = location.href;
        template = document.getElementById('template_preview');
        theme = document.getElementById('theme_preview');
        additional_view = document.getElementById('additional_view_preview');

        if (template) {
            url = URL_add_parameter(url, 'template', template.options[template.selectedIndex].value);
        }
        if (theme) {
            url = URL_add_parameter(url, 'theme', theme.options[theme.selectedIndex].value);
        }
        if (additional_view) {
            url = URL_add_parameter(url, 'additional_view', additional_view.options[additional_view.selectedIndex].value);
        }

        location.href = url;
    }

    function changeTemplate(template) {
        location.href = URL_add_parameter(location.href, 'template', template);
    }

    function changeTheme(theme) {
        location.href = URL_add_parameter(location.href, 'theme', theme);
    }

    function changeAdditionalView(additional_view) {
        location.href = URL_add_parameter(location.href, 'additional_view', additional_view);
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

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/header-demo-block.css');