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

abstract class PopUnit
{
    const TYPE_DEFAULT = 'default';
    const TYPE_INFOPAGES = 'infopages';
    const TYPE_LOCATIONS = 'locations';
    const TYPE_CITIES = 'one_cities';

    protected $itemsId = array();
    protected $themeId;

    /** @var Themes */
    protected $theme;

    /** @var PopDataForm */
    protected $model;

    protected $errors = array();

    public function __construct($theme)
    {
        if ($theme instanceof Themes) {
            $this->themeId = $theme->id;
            $this->theme = $theme;
        } elseif (is_numeric($theme)) {
            $this->themeId = $theme;
            if (!$this->getThemeInstance()) {
                throw new CHttpException('Bad theme param', 400);
            }
        }
    }

    public static function getTypeList($popUnit = null)
    {
        $list = array();

        if ($popUnit && isset($popUnit->theme) && $popUnit->theme->title == 'basis') {
            $list = array(
                self::TYPE_DEFAULT => tc('Default'),
            );
        }

        $list[self::TYPE_INFOPAGES] = tt('InfoPages', 'seo');

        if (issetModule('location')) {
            $list[self::TYPE_LOCATIONS] = tc('City');
        } else {
            $list[self::TYPE_CITIES] = tc('City');
        }

        return $list;
    }

    public function getThemeInstance()
    {
        if (!isset($this->theme)) {
            $this->theme = Themes::model()->findByPk($this->themeId);
        }
        return $this->theme;
    }

    public function getItemsId()
    {
        return array();
    }

    public function getItemsModels()
    {
        $itemsId = $this->getItemsId();

        if(!$itemsId){
            return [];
        }

        $criteria = new CDbCriteria();
        $criteria->compare('t.id', $itemsId);
        $criteria->order = 'field(t.id, ' . implode(',', $itemsId) . ')';

        $model = $this->getModelByType();

        return $model->findAll($criteria);
    }

    public function addItem($itemId)
    {
        $itemModel = $this::getModelByPk($itemId);

        $itemsId = $this::getItemsId();

        if ($itemModel) {
            $itemsId[] = $itemId;
            $itemsId = array_unique($itemsId);

            if ($this->theme->setInJson($this->getKeyItemsId(), $itemsId, true)) {
                return true;
            } else {
                $this->errors[] = 'Error save data';
            }
        } else {
            $this->errors[] = 'Not found model id = ' . $itemId;
        }

        return false;
    }

    public function delItem($itemId)
    {
        $keyItemsId = $this->getKeyItemsId();

        $itemsId = $this->theme->getFromJson($keyItemsId, []);

        if (($key = array_search($itemId, $itemsId)) !== false) {
            unset($itemsId[$key]);

            if ($itemsId) {
                return $this->theme->setInJson($keyItemsId, $itemsId, true);
            } else {
                return $this->theme->deleteInJson($keyItemsId);
            }
        }

        return true;
    }

    public static function getModelByPk($id)
    {
        return null;
    }

    public static function getType()
    {
        return '-';
    }

    public function getModelByType()
    {
        switch ($this::getType()) {
            case self::TYPE_LOCATIONS:
                if (issetModule('location')) {
                    return new City();
                } else {
                    return new ApartmentCity();
                }

            case self::TYPE_CITIES:
                return new ApartmentCity();

            case self::TYPE_INFOPAGES:
                return new InfoPages();
        }

        return null;
    }

    public function getUploadListingForItemId($itemId, $limit = 15)
    {
        $criteria = new CDbCriteria();

        if (Themes::getParamJson('pd_upload') == DolphinDataForm::UPLOAD_BY_CRITERIA) {
            switch ($this::getType()) {
                case self::TYPE_LOCATIONS:
                    $criteria->compare('t.loc_city', $itemId);
                    break;

                case self::TYPE_CITIES:
                    $criteria->compare('t.city_id', $itemId);
                    break;

                case self::TYPE_INFOPAGES:
                    /** @var InfoPages $model */
                    $model = $this::getModelByPk($itemId);
                    if ($model) {
                        $criteria = $model->getCriteriaForAdList();
                    }
                    break;
            }
        }

        $criteria->addCondition('t.deleted = 0 AND t.owner_active = 1 AND t.active = 1');
        $criteria->order = 't.date_up_search DESC, t.sorter DESC';
        $criteria->limit = $limit;

        return HApartment::findAllWithCache($criteria);
    }

    public function getKeyItemsId()
    {
        return $this::getType();
    }


    public function getItemsString()
    {
        $itemsId = $this->getItemsId();

        $str = '';

        if ($itemsId) {
            $criteria = new CDbCriteria();
            $criteria->compare('t.id', $itemsId);
            $criteria->order = 'field(t.id, ' . implode(',', $itemsId) . ')';

            $model = $this->getModelByType();

            $items = $model->findAll($criteria);

            foreach ($items as $item) {
                $str .= $this->getItemString(new PopUnitItem($item));
            }
        }

        return $str;
    }

    public function getItemString(PopUnitItem $item)
    {
        $str = '';

        $editUrl = $item->getEditUrl();

        $str .= '<li key="' . $item->getId() . '" class="ui-state-default">';
        $str .= '<div class="media">';
        $str .= '<div class="media-left">';
        $str .= '<a href="' . $editUrl . '">';
        $str .= '<img class="media-object" src="' . $item->getImageSrc() . '" alt="' . $item->getTitle() . '">';
        $str .= '</a>';
        $str .= '</div>';
        $str .= '<div class="media-body">';
        $str .= '<h4 class="media-heading">' . $item->getTitle() . '</h4>';
        $str .= CHtml::link('<i class="fa fa-edit"></i> ' . tt('Edit item'), $editUrl, array('target' => '_blank'));
        $str .= '<br/>';
        $str .= CHtml::link('<i class="fa fa-trash"></i> ' . tt('Delete row'), 'javascript:;', array(
            'class' => 'pd_del',
            'onclick' => 'js: pdDelItem(' . $item->getId() . '); return false;',
        ));
        $str .= '</div>';
        $str .= '</div>';
        $str .= '</li>';

        return $str;
    }

    public function renderForm(PopDataForm $model)
    {
        return;
    }

    public function getHelpString()
    {
        return '';
    }

    public function getErrorsString()
    {
        return implode('<br>', $this->errors);
    }

    public function registerJs()
    {
        $successMsg = tc('Success');
        $addUrl = Yii::app()->createUrl('/themes/backend/widget/ajaxPdAddItem');
        $delUrl = Yii::app()->createUrl('/themes/backend/widget/ajaxPdDelItem');

        $saveSortUrl = Yii::app()->createUrl('/themes/backend/widget/ajaxPdSaveSort');
        $typeUnit = $this::getType();
        $themeId = $this->themeId;

        $js = <<< JS
        
function pdAddItem() {
    
    var data = {
        item_id: $('#item_id').val(),
        type: '$typeUnit',
        theme_id: $themeId
    };
    
    $.ajax({
        url: '{$addUrl}',
        data: data,
        dataType: 'json',
        success: function(data) {
           if(data.status == 'ok'){
               pdShowItem(data.html);
               message(data.msg);
           } else {
               error(data.msg);
           }
        }
    });  
}

function pdDelItem(id) {
    var data = {
        item_id: id,
        type: '$typeUnit',
        theme_id: $themeId
    };
    
    $.ajax({
        url: '{$delUrl}',
        data: data,
        dataType: 'json',
        success: function(data) {
           if(data.status == 'ok'){
               pdShowItem(data.html);
               message(data.msg);
           } else {
               error(data.msg);
           }
        }
    }); 
    return false;
}

function pdShowItem(html) {
    if(html){
        $('#sortable1').html(html).show();
    } else {
        $('#sortable1').hide();
    }
    pdSort();
}

function pdSort(){
    $("#sortable1").sortable({
        connectWith: ".connectedSortable",
        placeholder: "ui-state-highlight",
        items: "li:not(.ui-state-disabled)",
        update: function( event, ui ) {
           if (this === ui.item.parent()[0]) {
               var sort = $('#sortable1').sortable('toArray', { attribute: 'key' });
        
               if(tmpSort != sort){
                   pdSaveSort(sort);
                   tmpSort = sort;
               }
           }
       }
    });
}

$(function() {
    //$("#pd_item").select2().trigger("change");
    $('#pd_add_item').on('click', pdAddItem);
    pdSort();
});

var tmpSort = [];

function pdSaveSort(sort) {
        var sort = $('#sortable1').sortable('toArray', {attribute: 'key'});
        
        var data = {
            type: '$typeUnit',
            theme_id: $themeId,
            sort: sort
        };

        if (tmpSort == sort) {
            message('{$successMsg}');
            return false;
        }

        $.ajax({
            url: '{$saveSortUrl}',
            dataType: 'json',
            data: data,
            type: 'get',
            success: function (data) {
                if (data.status == 'ok') {
                    message(data.msg);
                    tmpSort = sort;
                } else {
                    error(data.msg);
                }
            }
        });
    }
JS;

        Yii::app()->clientScript->registerScript('themes-pd-js', $js, CClientScript::POS_END);

    }
}