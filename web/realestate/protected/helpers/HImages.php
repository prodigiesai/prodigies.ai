<?php

class HImages
{

    public static function getImagesForIndexSlider()
    {
        $sliderImgs = array();
        $paidImgs = (issetModule('paidservices')) ? PaidServices::getImgForSlider() : array();

        if (Yii::app()->theme->name == Themes::THEME_ATLAS_NAME) {
            $width = 663;
            $height = 380;

            if (Yii::app()->controller->useAdditionalView == Themes::ADDITIONAL_VIEW_FULL_WIDTH_SLIDER) {
                $width = 1300;
            }
            if (Yii::app()->controller->useAdditionalView == Themes::ADDITIONAL_VIEW_FULL_WIDTH_SLIDER) {
                $height = 410;
            }
        } elseif (Yii::app()->theme->name == Themes::THEME_BASIS_NAME) {
            $width = (issetModule('seo')) ? 667 : 1200;
            $height = 500;
        } else {
            return $sliderImgs;
        }

        $key = 0;

        if (issetModule('slider')) {
            $imagesSlider = Slider::model()->getActiveImages();

            if ($imagesSlider && count($imagesSlider)) {
                foreach ($imagesSlider as $imgSlider) {
                    if ($imgSlider->img) {

                        $tmp = $imgSlider->getTitle();
                        $title = (isset($tmp) && $tmp) ? $tmp : '';
                        $link = isset($imgSlider->url) ? $imgSlider->url : '';
                        $title = str_replace(array("\r", "\n"), '', $title);

                        $sliderImgs[$key]['url'] = $link;
                        $sliderImgs[$key]['title'] = $title;
                        # без тумбочек ломается вёрстка в темах
                        $sliderImgs[$key]['src'] = Yii::app()->request->baseUrl . "/" . Slider::model()->sliderPath . "/" . $imgSlider->getThumb($width, $height);
                        $sliderImgs[$key]['width'] = $width;
                        $sliderImgs[$key]['height'] = $height;

                        $key++;

                        unset($tmp);
                    }
                }
            }
        } else {
            for ($i = 1; $i <= 3; $i++) {

                $sliderImgs[$key]['url'] = '';
                $sliderImgs[$key]['title'] = '';
                $sliderImgs[$key]['src'] = Yii::app()->theme->baseUrl . '/images/slider/' . $i . '.jpg';
                $sliderImgs[$key]['width'] = $width;
                $sliderImgs[$key]['height'] = $height;

                $key++;
            }
        }

        $fullImgsSliderArr = CMap::mergeArray($paidImgs, $sliderImgs);

        if(param('shuffleSlider')){
            shuffle($fullImgsSliderArr);
        }

        return $fullImgsSliderArr;
    }

}