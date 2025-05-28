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

Yii::import('bootstrap.widgets.BsActiveForm');

class CustomActiveForm extends CActiveForm
{

    private $_summaryAttributes = array();

    public function errorSummary($models, $header = null, $footer = null, $htmlOptions = array())
    {
        if (!$this->enableAjaxValidation && !$this->enableClientValidation)
            return CustomCHtml::errorSummary($models, $header, $footer, $htmlOptions);

        if (!isset($htmlOptions['id']))
            $htmlOptions['id'] = $this->id . '_es_';
        $html = CustomCHtml::errorSummary($models, $header, $footer, $htmlOptions);
        if ($html === '') {
            if ($header === null)
                $header = '<p>' . Yii::t('yii', 'Please fix the following input errors:') . '</p>';
            if (!isset($htmlOptions['class']))
                $htmlOptions['class'] = CustomCHtml::$errorSummaryCss;
            $htmlOptions['style'] = isset($htmlOptions['style']) ? rtrim($htmlOptions['style'], ';') . ';display:none' : 'display:none';
            $html = CHtml::tag('div', $htmlOptions, $header . "\n<ul><li>dummy</li></ul>" . $footer);
        }

        $this->summaryID = $htmlOptions['id'];
        foreach (is_array($models) ? $models : array($models) as $model)
            foreach ($model->getSafeAttributeNames() as $attribute)
                $this->_summaryAttributes[] = CHtml::activeId($model, $attribute);

        return $html;
    }

    //Copy of CActive form original, but using CustomCHtml::error() without encoding
    public function error($model,$attribute,$htmlOptions=array(),$enableAjaxValidation=true,$enableClientValidation=true)
    {
        if(!$this->enableAjaxValidation)
            $enableAjaxValidation=false;
        if(!$this->enableClientValidation)
            $enableClientValidation=false;

        if(!isset($htmlOptions['class']))
            $htmlOptions['class']=$this->errorMessageCssClass;

        if(!$enableAjaxValidation && !$enableClientValidation)
            return CustomCHtml::error($model,$attribute,$htmlOptions);

        $id=CHtml::activeId($model,$attribute);
        $inputID=isset($htmlOptions['inputID']) ? $htmlOptions['inputID'] : $id;
        unset($htmlOptions['inputID']);
        if(!isset($htmlOptions['id']))
            $htmlOptions['id']=$inputID.'_em_';

        $option=array(
            'id'=>$id,
            'inputID'=>$inputID,
            'errorID'=>$htmlOptions['id'],
            'model'=>get_class($model),
            'name'=>$attribute,
            'enableAjaxValidation'=>$enableAjaxValidation,
        );

        $optionNames=array(
            'validationDelay',
            'validateOnChange',
            'validateOnType',
            'hideErrorMessage',
            'inputContainer',
            'errorCssClass',
            'successCssClass',
            'validatingCssClass',
            'beforeValidateAttribute',
            'afterValidateAttribute',
        );
        foreach($optionNames as $name)
        {
            if(isset($htmlOptions[$name]))
            {
                $option[$name]=$htmlOptions[$name];
                unset($htmlOptions[$name]);
            }
        }
        if($model instanceof CActiveRecord && !$model->isNewRecord)
            $option['status']=1;

        if($enableClientValidation)
        {
            $validators=isset($htmlOptions['clientValidation']) ? array($htmlOptions['clientValidation']) : array();
            unset($htmlOptions['clientValidation']);

            $attributeName = $attribute;
            if(($pos=strrpos($attribute,']'))!==false && $pos!==strlen($attribute)-1) // e.g. [a]name
            {
                $attributeName=substr($attribute,$pos+1);
            }

            foreach($model->getValidators($attributeName) as $validator)
            {
                if($validator->enableClientValidation)
                {
                    if(($js=$validator->clientValidateAttribute($model,$attributeName))!='')
                        $validators[]=$js;
                }
            }
            if($validators!==array())
                $option['clientValidation']=new CJavaScriptExpression("function(value, messages, attribute) {\n".implode("\n",$validators)."\n}");
        }

        if(empty($option['hideErrorMessage']) && empty($this->clientOptions['hideErrorMessage']))
            $html=CustomCHtml::error($model,$attribute,$htmlOptions);
        else
            $html='';
        if($html==='')
        {
            if(isset($htmlOptions['style']))
                $htmlOptions['style']=rtrim($htmlOptions['style'],';').';display:none';
            else
                $htmlOptions['style']='display:none';
            $html=CHtml::tag(CHtml::$errorContainerTag,$htmlOptions,'');
        }

        $this->attributes[$inputID]=$option;
        return $html;
    }

}
