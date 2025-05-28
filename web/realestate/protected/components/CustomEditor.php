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

Yii::import('application.extensions.editMe.widgets.ExtEditMe');

class CustomEditor extends ExtEditMe
{

    public $onlyLoadAssets = false;

    public function init()
    {
        if (empty($this->toolbar)) {
            // даем пользователям ограниченый набор форматирования
            $this->toolbar = array(
                array('Source', '-', 'Bold', 'Italic', 'Underline', 'Strike'),
                //array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'),
                array('NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                //array('Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor'),
                array('Link', 'Unlink'),
            );

            if (Yii::app()->user->checkAccess('backend_access')) {
                $this->toolbar = array(
                    array('Source', '-', 'Bold', 'Italic', 'Underline', 'Strike'),
                    //array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'),
                    array('NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                    array('Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor'),
                    array('Image', 'Link', 'Unlink', 'SpecialChar'),
                );

                $this->allowedContent = true;
            }
        }

        if (Yii::app()->user->checkAccess('upload_from_wysiwyg')) { // if admin - enable upload image
            $this->filebrowserBrowseUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/browse.php?opener=ckeditor&type=files';
            $this->filebrowserImageBrowseUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/browse.php?opener=ckeditor&type=images';
            $this->filebrowserFlashBrowseUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/browse.php?opener=ckeditor&type=flash';
        }

        $langs = CJavaScript::encode(Lang::getActiveLangs());

        $currentLang = Yii::app()->language;

        $STATUS_SHOW = LangWidgetOpt::STATUS_SHOW;
        $STATUS_DESTROY = LangWidgetOpt::STATUS_DESTROY;

        $urlSetStatus = Yii::app()->createUrl('/lang/main/ajaxSetWidgetStatus');

        $js = <<< JS
        
        var WYSIWYG_CONF = {};

        var SITE_LANGS = $langs;
        
        function WYSIWYG_apply(id, save) {
            
            for(lang in SITE_LANGS)
            {
                var inst = id + '_' + lang;
                $('#'+inst).ckeditor(WYSIWYG_CONF);
                
                $('#apply_btn_'+inst).hide();
                $('#destroy_btn_'+inst).show();
            }
            
            if(save){
                WYSIWYG_set_status(id, $STATUS_SHOW);
            }
        }
        
        function WYSIWYG_destroy(id, save) {
            
            for(lang in SITE_LANGS)
            {
                var inst = id + '_' + lang;
                
                if (CKEDITOR.instances[inst]) {
                    CKEDITOR.instances[inst].destroy();
                    $('#'+inst).addClass('form-control');
                }
                $('#apply_btn_'+inst).show();
                $('#destroy_btn_'+inst).hide();
            }
            
            if(save){
                WYSIWYG_set_status(id, $STATUS_DESTROY);
            }
        }
        
        function WYSIWYG_set_status(id, status) {
            var el = $('#apply_btn_'+id+'_{$currentLang}');
            
            var data = {
                status: status,
                model_id: el.data('mid'),
                model_name: el.data('mname')
            }
            
            $.ajax({
                url: '$urlSetStatus',
                data: data,
                dataType: 'json',
                success: function(data) {
                    if(data.status == 'ok'){
                      message(data.msg);
                    } else {
                      error(data.msg);
                    }
                }
            });  
        }
JS;


        Yii::app()->clientScript->registerScript('WYSIWYG_INIT', $js, CClientScript::POS_BEGIN);

        return parent::init();
    }
}
