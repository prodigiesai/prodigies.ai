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

namespace application\modules\favorite\widgets;

use application\modules\favorite\services\FavoriteStorageService;
use \Yii;

class FavoriteWidget extends \CWidget
{
    /** @var \CActiveRecord */
    public $model;

    public $type = 1;

    /** @var FavoriteStorageService */
    private $storage;

    private static $_list;

    private static $registerAssetFlag = false;

    /**
     * Returns the fully qualified name of this class.
     * @return string the fully qualified name of this class.
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->registerAssets();

        $this->storage = new FavoriteStorageService();

        $modelName = get_class($this->model);
        $modelId = $this->model->id;

        $list = $this->getList();

        $active = isset($list[$modelName][$modelId]) ? 'active' : '';

        if($active){
            $title = \CHtml::encode(tt('Remove from favorites', 'favorite'));
        } else {
            $title = \CHtml::encode(tt('Add as favorite', 'favorite'));
        }

        if($this->type == 1){
            echo <<< HTML
<div class="fav-wrapper">
    <div class="flexbox">
        <div class="fav-btn">
            <span class="favme fas fa fa-heart $active" data-model-name="$modelName" data-model-id="$modelId" title="$title"></span>
        </div>
    </div>
</div>
HTML;
        } elseif ($this->type == 2) {
            echo <<< HTML
    <div class="flexbox">
        <div class="fav-btn">
            <span class="favme fas fa fa-heart $active" data-model-name="$modelName" data-model-id="$modelId" title="$title"></span>
        </div>
    </div>
HTML;
        }
    }

    private function getList()
    {
        if(empty(self::$_list)){
            self::$_list = $this->storage->getList();
        }
        return self::$_list;
    }

    public function registerAssets()
    {
        if(self::$registerAssetFlag){
            return true;
        }

        $urlAdd = Yii::app()->createUrl('/favorite/main/add');
        $urlRemove = Yii::app()->createUrl('/favorite/main/remove');

        $csrfTokenName = Yii::app()->request->csrfTokenName;
        $csrfToken = Yii::app()->request->csrfToken;

        $strRemove = \CJavaScript::encode(tt('Remove from favorites', 'favorite'));
        $strAdd = \CJavaScript::encode(tt('Add as favorite', 'favorite'));

        $script = <<< JS
        
        //css https://codepen.io/mapk/pen/ZOQqaQ
        //js https://stackoverflow.com/questions/3626350/jquery-making-a-favorite-button-with-function
        
        var oreFavorite = {
            toggle: function(el){
                if(el.hasClass('active')){
                    oreFavorite.remove(el);
                } else {
                    oreFavorite.add(el);
                }
            },
    
            add: function(el) {
                $.ajax({
                  url: "$urlAdd",
                  method: 'post',
                  dataType: 'json',
                  data: { 
                      model_id: el.data('modelId'), 
                      model_name: el.data('modelName'),
                      $csrfTokenName: '$csrfToken'
                  },
                  success: function(data){
                          if(data.status == 'ok'){
                              el.addClass('active').attr('title',$strRemove);
                              message(data.msg);
                          } else {
                              error(data.msg);
                          }
                      }
                });
            },
            
            remove: function(el) {
                $.ajax({
                    url: "$urlRemove",
                    method: 'post',
                    dataType: 'json',
                    data: { 
                      model_id: el.data('modelId'), 
                      model_name: el.data('modelName'),
                      $csrfTokenName: '$csrfToken'
                    },
                    success: function(data){
                          if(data.status == 'ok'){
                              el.removeClass('active').attr('title', $strAdd);
                              message(data.msg);
                          } else {
                              error(data.msg);
                          }
                    }
                });
            }
        };
        
        // Favorite Button - Heart
        $('body').on('click', '.favme', function() {
            oreFavorite.toggle($(this));
        });

        /* when a user clicks, toggle the 'is-animating' class */
        $(".favme").on('click touchstart', function(){
          $(this).toggleClass('is_animating');
        });
        
        /* when the animation is over, remove the class*/
        $(".favme").on('animationend', function(){
          $(this).toggleClass('is_animating');
        });
JS;

        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCssFile($baseUrl . '/favorite.css?2');
            if(in_array(Yii::app()->theme->name, [\Themes::THEME_ATLAS_NAME])){
                Yii::app()->clientScript->registerCssFile($baseUrl . '/font-awesome/css/font-awesome.min.css');
            }
            Yii::app()->clientScript->registerCoreScript('jquery');

            Yii::app()->clientScript->registerScript('init-fav-js', $script, \CClientScript::POS_READY);
        } else {
            throw new \Exception('FavoriteWidget - Error: Couldn\'t find assets to publish.');
        }

        self::$registerAssetFlag = true;
    }
}