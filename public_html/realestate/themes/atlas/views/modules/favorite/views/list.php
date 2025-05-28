<?php
$this->pageTitle .= ' - ' . tt('Favorites');
$this->breadcrumbs = array(
    tt('Favorites'),
);

if (isset($_GET['is_ajax'])) {
    Yii::app()->clientScript->registerCoreScript('jquery');
    Yii::app()->clientScript->registerCoreScript('jquery.ui');
    Yii::app()->clientScript->registerCoreScript('rating');
    Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/rating/jquery.rating.css');
}

foreach ($listCriteria as $data){
    echo CHtml::tag('h2', [], $data['title']);

    switch ($data['modelName']){
        case Apartment::class:
            $this->widget('application.modules.apartments.components.ApartmentsWidget', array(
                'criteria' => $data['criteria'],
                //'widgetTitle' => Yii::t('common', 'Special offers'),
                //'isH1Widget' => true,
            ));
            break;
    }
}

?>