<?php
$this->pageTitle = Yii::app()->name . ' - ' . tc('Error');
$this->breadcrumbs = array(
    tc('Error'),
);
?>

<div class="title highlight-left-right">
    <div>
        <h1><?php echo tc('Error'); ?><?php echo CHtml::encode($code); ?></h1>
    </div>
</div>
<div class="clear"></div><br/>

<div class="flash-error">
    <?php echo CHtml::encode($message); ?>
</div>