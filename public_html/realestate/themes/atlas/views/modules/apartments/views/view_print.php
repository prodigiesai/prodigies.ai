<div>
    <?php
    echo '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
    echo '<td>';
    ?>
    <div class="logo">
        <a title="<?php echo Yii::t('common', 'Go to main page'); ?>"
           href="<?php echo Yii::app()->controller->createAbsoluteUrl('/'); ?>">
            <div class="logo-img"><img width="77" height="70" alt=""
                                       src="<?php echo Yii::app()->theme->baseUrl; ?>/images/pages/logo-open-ore.png"/>
            </div>
            <div class="logo-text"><?php echo CHtml::encode(Yii::app()->name); ?></div>
        </a>
    </div>
    <?php
    echo '</td>';
    echo '<td align="right">';
    $url = $model->getUrl();
    $this->widget('application.extensions.qrcode.QRCodeGenerator', array(
        'data' => $url,
        'filename' => 'qr_' . md5($url) . '-' . $model->id . Yii::app()->language . '.png',
        'matrixPointSize' => 3,
        'fileUrl' => Yii::app()->getBaseUrl(true) . '/uploads',
        //'color' => array(33, 72, 131),
        'color' => array(0, 0, 0),
    ));
    echo '</td>';
    echo '</tr></table>';
    ?>

    <h1><?php echo CHtml::encode($model->getStrByLang('title')); ?></h1>

    <div class="print">
        <div>
            <?php
            if ($model->is_special_offer) {
                ?>
                <div class="is_special_offer_block">
                    <?php
                    echo '<h3>' . Yii::t('common', 'Special offer!') . '</h3>';

                    if (!empty($model->is_free_to) && !is_null($model->is_free_to)) {
                        echo '<div>';
                        echo '<strong>' . Yii::t('common', 'Is avaliable') . '</strong>';
                        echo ' ' . Yii::t('common', 'to');
                        echo ' ' . Booking::getDate($model->is_free_to, 1);
                        echo '</div><br />';
                    }
                    ?>
                </div>
                <?php
            }
            ?>

            <div>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td width="320px" valign="top">
                            <?php

                            $res = Images::getMainThumb(300, 200, $model->images);
                            echo CHtml::image($res['thumbUrl'], $model->getStrByLang('title'), array(
                                'title' => $model->getStrByLang('title'),
                            ));
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <br/>

            <?php
            $images = Images::getObjectThumbs(150, 100, $model->images);

            if ($images) {
                $countArr = count($images);
                $i = 1;

                if ($countArr) {
                    echo '<div>';
                    echo '<table cellpadding="0" cellspacing="0" border="0">';
                    echo '<tr>';
                    foreach ($images as $value) {

                        echo '<td>';
                        echo '<div style="height: 105px; width: 160px;">';
                        echo CHtml::image($value['thumbUrl'], '', array('style' => 'width: 150px; height: 100px;'));
                        echo '</div>';
                        echo '</td>';
                        if ($i % 6 == 0 && $countArr != $i) {
                            echo '</tr><tr>';
                        }

                        $i++;
                    }
                    echo '</tr>';
                    echo '</table>';
                    echo '</div><br />';
                }
            }
            ?>

            <div>
                <?php
                $this->renderPartial('//modules/apartments/views/_tab_general', array(
                    'data' => $model,
                    'isPrintable' => true,
                ));
                ?>
            </div>
            <br/>

            <?php
            $model->references = HApartment::getFullInformation($model->id, $model->type);
            $additionFields = HFormEditor::getExtendedFields();
            $existValue = HFormEditor::existValueInRows($additionFields, $model);

            if ($existValue) {
                echo '<div>';
                $this->renderPartial('//modules/apartments/views/_tab_addition', array(
                    'data' => $model,
                    'additionFields' => $additionFields,
                    'isPrintable' => true,
                ));
                echo '</div><br />';
            }
            ?>

            <?php if (param('useShowUserInfo')): ?>
                <?php $owner = $model->user; ?>
                <div>
                    <dl class="ap-descr">
                    <?php HFormEditor::renderViewRow(tc('Listing provided by'), ($model->parse_from) ? $model->parse_owner_info_name : $owner->getNameForType()); ?>

                    <?php
                    if ($model->canShowInView('phone')) {
                        $showMessage = true;
                        if (issetModule('tariffPlans') && issetModule('paidservices') && ($model->owner_id != Yii::app()->user->id)) {
                            if (Yii::app()->user->isGuest) {
                                $defaultTariffInfo = TariffPlans::getFullTariffInfoById(TariffPlans::DEFAULT_TARIFF_PLAN_ID);
                                if (!$defaultTariffInfo['showPhones']) {
                                    $phone = Yii::t('module_tariffPlans', 'Please <a href="{n}">login</a> to view', Yii::app()->controller->createUrl('/site/login'));
                                    $showMessage = false;
                                }
                            } else {
                                if (!TariffPlans::checkAllowShowPhone()) {
                                    $phone = Yii::t('module_tariffPlans', 'Please <a href="{n}">change the tariff plan</a> to view', Yii::app()->controller->createUrl('/tariffPlans/main/index'));
                                    $showMessage = false;
                                }
                            }
                        }

                        HFormEditor::renderViewRow(tc('Phone number'), $phone);

                        if ($showMessage) {
                            $hostname = IdnaConvert::checkDecode(str_replace(array('https://', 'http://', 'www.'), '', Yii::app()->getRequest()->getHostInfo()));
                            HFormEditor::renderViewRow('', Yii::t('common', 'Please tell the seller that you have found this listing here {n}', '<strong>' . $hostname . '</strong>'));
                        }
                    }
                    ?>
                    </dl>
                </div>
                <br />
            <?php endif; ?>

            <?php
            echo '<div>';
            $this->renderPartial('//modules/apartments/views/_tab_map', array(
                'data' => $model,
                'isPrintable' => true,
            ));
            echo '</div><br />';
            ?>
        </div>
    </div>
    <div class="wrapper" style="padding-top: 30px;">
        <div class="copyright">&copy;&nbsp;<?php echo CHtml::encode(Yii::app()->name) . ', ' . date('Y'); ?></div>
    </div>
</div>