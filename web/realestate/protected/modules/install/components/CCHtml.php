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

class CCHtml extends CHtml
{

    public static function radioButtonList($name, $select, $data, $htmlOptions = array())
    {
        $template = isset($htmlOptions['template']) ? $htmlOptions['template'] : '{input} {label} {imageposition}';
        $separator = isset($htmlOptions['separator']) ? $htmlOptions['separator'] : "<br/>\n";
        unset($htmlOptions['template'], $htmlOptions['separator']);

        $labelOptions = isset($htmlOptions['labelOptions']) ? $htmlOptions['labelOptions'] : array();
        unset($htmlOptions['labelOptions']);

        $items = array();
        $baseID = self::getIdByName($name);
        $allowedTemplates = Themes::getAllowedTemplatesList();

        foreach ($data as $value => $label) {
            $imagePosition = $label;
            $checked = !strcmp($label, $select);
            $htmlOptions['value'] = $imagePosition;
            $htmlOptions['id'] = $baseID . '_' . $imagePosition;

            if (!in_array($label, $allowedTemplates)) {
                $htmlOptions['disabled'] = 'disabled';
                $label .= ' ' . CHtml::link(
                        tFile::getT('module_install', 'Buy'),
                        (Yii::app()->language == 'ru')
                            ? 'https://open-real-estate.info/ru/open-real-estate-modules'
                            : 'https://open-real-estate.info/en/open-real-estate-modules',
                        array('class' => 'template-buy-link', 'target' => '_blank')
                    );
            }
            $option = self::radioButton($name, $checked, $htmlOptions);

            $label = self::label($label, $htmlOptions['id'], $labelOptions);
            $items[] = strtr($template, array('{input}' => $option, '{label}' => $label, '{imageposition}' => $imagePosition));
        }
        return self::tag('span', array('id' => $baseID), implode($separator, $items));
    }

    public static function activeRadioButtonList($model, $attribute, $data, $htmlOptions = array())
    {
        self::resolveNameID($model, $attribute, $htmlOptions);
        $selection = self::resolveValue($model, $attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($htmlOptions);
        $name = $htmlOptions['name'];
        unset($htmlOptions['name']);

        if (array_key_exists('uncheckValue', $htmlOptions)) {
            $uncheck = $htmlOptions['uncheckValue'];
            unset($htmlOptions['uncheckValue']);
        } else
            $uncheck = '';

        $hiddenOptions = isset($htmlOptions['id']) ? array('id' => self::ID_PREFIX . $htmlOptions['id']) : array('id' => false);
        if (!empty($htmlOptions['disabled']))
            $hiddenOptions['disabled'] = $htmlOptions['disabled'];
        $hidden = $uncheck !== null ? self::hiddenField($name, $uncheck, $hiddenOptions) : '';

        return $hidden . self::radioButtonList($name, $selection, $data, $htmlOptions);
    }
}
