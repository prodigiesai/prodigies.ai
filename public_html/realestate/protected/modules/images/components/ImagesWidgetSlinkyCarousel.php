<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.19.1
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

class ImagesWidgetSlinkyCarousel extends CWidget
{

    public $objectId;
    public $width;
    public $height;
    public $images;
    public $withMain = false;
    public $useFotorama = true;

    public $fx = '';

    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'views'))
                return $theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'views';
        }
        return Yii::getPathOfAlias('application.modules.images.views');
    }

    public function run()
    {
        $this->registerAssets();

        if (!$this->images) {
            $sql = 'SELECT id, file_name, id_object, file_name_modified, is_main FROM {{images}} WHERE id_object=:id ORDER BY sorter';
            $this->images = Images::model()->findAllBySql($sql, array(':id' => $this->objectId));
        }

        $this->render('widgetImagesSlinkyCarousel', array(
            'images' => $this->images,
        ));
    }

    public function registerAssets()
    {

        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/slick/slick.min.js', CClientScript::POS_BEGIN);
        Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/js/slick/slick.css');

        $script = <<< JS
        
        function mSlinkyInit() {
            $(".slinky-gallery").slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                  prevArrow: "<button type='button' class='slick-prev slick-arrow slow'><i class='fas fa-chevron-left'></i></button>",
                  nextArrow: "<button type='button' class='slick-next slick-arrow slow'><i class='fas fa-chevron-right'></i></button>",
            });
        }
        
        $(window).load(function() {
            $('.slides').on('setPosition', function () {
              $(this).find('.slick-slide').height('auto');
              var slickTrack = $(this).find('.slick-track');
              var slickTrackHeight = $(slickTrack).height();
              $(this).find('.slick-slide').css('height', slickTrackHeight + 'px');
            });
        });
JS;

        Yii::app()->clientScript->registerScript('SlickCarouselFunc', $script, CClientScript::POS_END);
        Yii::app()->clientScript->registerScript('SlickCarouselInit', "\r\n mSlinkyInit();\r\n", CClientScript::POS_END);

    }
}
