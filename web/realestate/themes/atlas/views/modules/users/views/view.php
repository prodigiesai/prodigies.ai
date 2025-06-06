<?php
$title = $model->getNameForType() . ', ' . $model->getTypeName();
$this->pageTitle .= ' - ' . $title;
$this->breadcrumbs = array(
    tc('Users') => array('/users/main/search'),
    $title,
);

?>

    <div class="title highlight-left-right">
        <div>
            <h1><?php echo $title; ?></h1>
        </div>
    </div>

    <div class="user_page">
        <p class="meta">
            <?php
            if (issetModule('tariffPlans') && issetModule('paidservices')) {
                if (TariffPlans::checkAllowShowPhone())
                    echo '<span>' . CHtml::link(tc('Show phone'), 'javascript: void(0);', array('onclick' => 'getPhoneNum(this, ' . $model->id . ');')) . '</span>';
            } else {
                echo '<span>' . CHtml::link(tc('Show phone'), 'javascript: void(0);', array('onclick' => 'getPhoneNum(this, ' . $model->id . ');')) . '</span>';
            }

            ?>
        </p>

        <?php if (issetModule('messages') && $model->id != Yii::app()->user->id && !Yii::app()->user->isGuest): ?>
            <p class="meta">
                <span><?php echo '<span>' . CHtml::link(tt('Send message', 'messages'), Yii::app()->createUrl('/messages/main/read', array('id' => $model->id))) . '</span>'; ?></span>
            </p>
        <?php endif; ?>

        <p>
            <?php
            $model->renderAva(false, '', true);
            $additionalInfo = 'additional_info_' . Yii::app()->language;
            if (isset($model->$additionalInfo) && !empty($model->$additionalInfo)) {
                echo CHtml::encode(truncateText($model->$additionalInfo, 40));
            }

            ?>
        </p>

    </div>

    <div class="clear"></div>
    <br>

<?php
$this->widget('application.modules.apartments.components.ApartmentsWidget', array(
    'criteria' => $criteria,
    'widgetTitle' => tt('all_member_listings', 'apartments') . ' ' . $userName,
));

Yii::app()->clientScript->registerScript('generate-phone-users-view-all', '
	function getPhoneNum(elem, id){
		$.get(\'' . Yii::app()->controller->createUrl('/apartments/main/generatephone') . '?from=userlist&id=\' + id + \'\', function(data) {
			$(elem).closest("span").html(data);
		});
	}
', CClientScript::POS_END);

?>