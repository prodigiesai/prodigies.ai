<?php
if (0) {
    echo $form->dropDownListControlGroup($dataModel, 'empty_flag', PaidBooking::getEmptyFlagDays(), array('class' => 'span5'));
    echo $form->textFieldControlGroup($dataModel, 'discount_guest');
}

?>

<div class="form-group">
    <?php echo $form->labelEx($model, 'color_theme'); ?>
    <?php echo $form->dropDownList($model, 'color_theme', Themes::getColorThemesList(Themes::THEME_BASIS_NAME), array('style' => 'width: 400px;')); ?>
    <?php echo $form->error($model, 'color_theme'); ?>
</div><br/>

<div class="panel panel-default">
    <div class="panel-heading"><?= tt('Index page') ?></div>
    <div class="panel-body">
        <?php
        echo $form->checkboxControlGroup($dataModel, 'i_enable_slider_and_pd');
        echo $form->checkboxControlGroup($dataModel, 'i_enable_best_ads');
        echo $form->checkboxControlGroup($dataModel, 'i_enable_feature');
        echo $form->checkboxControlGroup($dataModel, 'i_enable_last_news');
        echo $form->checkboxControlGroup($dataModel, 'i_enable_contact');
        echo '<hr>';
        echo $form->textFieldControlGroup($dataModel, 'i_vk');
        echo $form->textFieldControlGroup($dataModel, 'i_facebook');
        echo $form->textFieldControlGroup($dataModel, 'i_twitter');
        echo '<hr>';
        echo $form->textFieldControlGroup($dataModel, 'i_lng');
        echo $form->textFieldControlGroup($dataModel, 'i_lat');
        echo $form->textFieldControlGroup($dataModel, 'i_zoom');
        ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><?= tt('Widgets') ?></div>

    <div class="panel-body">
        <?= AdminLteHelper::getLink(tt('Widget "Popular destinations"'),
            Yii::app()->createUrl('/themes/backend/widget/popularDest', array('id' => $model->id)),
            'fa fa-gear', array('class' => 'btn btn-primary')
        ) ?>

        <?= AdminLteHelper::getLink(tt('Widget "Best listings"'),
            Yii::app()->createUrl('/themes/backend/widgethot/edit', array('id' => $model->id)),
            'fa fa-gear', array('class' => 'btn btn-primary')
        ) ?>
    </div>
</div>
