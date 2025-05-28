<?php
$this->breadcrumbs = array(
    Yii::t('common', 'User managment') => array('admin'),
    $model->email . ($model->username != '' ? ' (' . $model->username . ')' : ''),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(Yii::t('common', 'User managment'), array('admin')),
    AdminLteHelper::getEditMenuLink(tt('Edit user'), array('update', 'id' => $model->id), array('visible' => Yii::app()->user->checkAccess("admin"))),
    AdminLteHelper::getMenuLink(
        tt('member_listings', 'apartments'),
        array('/apartments/backend/main/admin', 'Apartment[ownerEmail]' => $model->email),
        'fa fa-th-list',
        array('linkOptions' => array('class'=>'btn btn-secondary bg-dark-gray'))
    ),
    AdminLteHelper::getAddMenuLink(tt('Add user'), array('create')),
);

if(HUser::isAllowSendMessage($model)){
    $this->menu[] = AdminLteHelper::getMenuLink(
        tt('Message', 'messages'),
        array('/messages/backend/main/read', 'id' => $model->id),
        'fa fa-envelope',
        array('linkOptions' => array('class'=>'btn btn-info bg-aqua'))
    );
}

$this->menu[] =     AdminLteHelper::getDeleteMenuLink(tt('Delete user'), '#', array(
    'linkOptions' => array(
        'submit' => array('delete', 'id' => $model->id),
        'confirm' => tc('Are you sure you want to delete this item?'),
        'csrf' => true,
    ),
    'visible' => $model->role != User::ROLE_ADMIN,
));

$model->scenario = 'backend';

$this->adminTitle = $model->email . ($model->username != '' ? ' (' . $model->username . ')' : '');

?>

<?php
$this->widget('CustomDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'label' => CHtml::encode($model->getAttributeLabel('type')),
            'value' => $model->getTypeName(),
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n"
        ),
        array(
            'label' => CHtml::encode($model->getAttributeLabel('role')),
            'value' => $model->getRoleName(),
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n"
        ),
        'username',
        'email',
        'phone',
        array(
            'label' => CHtml::encode($model->getAttributeLabel('balance')),
            'value' => $model->balance,
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n",
            'visible' => (issetModule('paidservices')) ? true : false,
        ),
        array(
            'label' => CHtml::encode($model->getAttributeLabel('additional_info')),
            'value' => $model->getAdditionalInfo(),
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n"
        ),
        array(
            'label' => tt('Status'),
            'value' => ($model->active) ? tt('Active') : tt('Inactive'),
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n"
        ),
        array(
            'label' => CHtml::encode($model->getAttributeLabel('is_use_api')),
            'value' => User::getUseApiOptions($model->is_use_api),
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n",
        ),
        array(
            'label' => CHtml::encode($model->getAttributeLabel('api_email')),
            'value' => $model->email,
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n",
        ),
        array(
            'label' => CHtml::encode($model->getAttributeLabel('api_password')),
            'value' => $model->api_token,
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n",
        ),
        'date_created',
        'last_login_date',
        'last_ip_addr',
    ),
));

?>

<?php
if (issetModule('tariffPlans') && issetModule('paidservices')) {
    $this->widget('application.modules.tariffPlans.components.userTariffInfoWidget', array('userId' => $model->id, 'showChangeTariffLnk' => false));
}

?>
