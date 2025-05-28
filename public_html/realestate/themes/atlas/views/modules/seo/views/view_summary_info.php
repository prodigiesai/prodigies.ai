<?php
$this->pageTitle .= ' - ' . $cityModel->getStrByLang('name');

$widgetTitle = (!empty($widgetTitle)) ? $widgetTitle : $this->pageTitle;

if ($cityModel && $objTypeModel) {
    $cityName = (!empty($seoCity)) ? $seoCity->getStrByLang('title') : $cityModel->getStrByLang('name');
    $this->breadcrumbs = array(
        $cityName => Yii::app()->controller->createUrl('/seo/main/viewsummaryinfo', array('cityUrlName' => $cityUrlName)),
        $widgetTitle,
    );
} else {
    $this->breadcrumbs = array(
        $widgetTitle,
    );
}
?>

<?php if ($bodyText || isset($cityModel->image)) { ?>
    <div class="full-city-summary-info">
        <?php if ($cityModel->image) { ?>
            <?php $src = $cityModel->image->getFullThumbLink(); ?>
            <?php if ($src) { ?>
                <div class="city-image text-center">
                    <?php
                    $htmlOptions = array();
                    $htmlOptions['class'] = 'fancy';

                    $tagAlt = CHtml::encode($cityModel->getStrByLang('name'));
                    if (issetModule('seo') && isset($cityModel->image->image_seo) && $cityModel->image->image_seo->getStrByLang('alt')) {
                        $tagAlt = CHtml::encode($cityModel->image->image_seo->getStrByLang('alt'));
                    }
                    echo CHtml::link(CHtml::image($src, $tagAlt), $cityModel->image->fullHref(), $htmlOptions);
                    ?>
                </div>
                <div class="clear"></div>
            <?php } ?>
        <?php } ?>

        <?php if ($bodyText) echo $bodyText; ?>
    </div>
    <div class="clear"></div>
<?php } ?>

<?php
$this->widget('application.modules.apartments.components.ApartmentsWidget', array(
    'criteria' => $criteria,
    'count' => null,
    'showCount' => false,
    'widgetTitle' => $widgetTitle,
));