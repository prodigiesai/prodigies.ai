<?php
$this->pageTitle .= ' - ' . tc('My balance');
$this->breadcrumbs = array(
    tc('Control panel') => Yii::app()->createUrl('/usercpanel'),
    tc('My balance'),
);

$user = HUser::getModel();
?>

    <div class="title highlight-left-right">
        <div>
            <h1><?php echo tc('My balance'); ?></h1>
        </div>
    </div>
    <div class="clear"></div><br/>

<?php
echo '<strong>' . tc('On the balance') . ': ' . $user->balance . ' ' . Currency::getDefaultCurrencyName() . '</strong>';
echo '<br /><br />';

echo CHtml::link(tt('Replenish the balance'), Yii::app()->createUrl('/paidservices/main/index', array('paid_id' => PaidServices::ID_ADD_FUNDS)), array('class' => 'fancy mgp-open-ajax button-blue'));