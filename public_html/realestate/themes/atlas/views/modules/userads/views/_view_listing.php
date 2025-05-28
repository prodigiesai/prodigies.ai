<li class="my-listing-blocks-item">
    <?php if (HSite::availableApartmentPaidServices($data)): ?>
        <div class="dropdown custom-dropdown-my-listing-paid-services">
            <button class="btn btn-default dropdown-toggle" type="button" id="menu1"
                    data-toggle="dropdown"><?php echo tc('Paid services'); ?>
                <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <?php echo HApartment::getPaidHtml($data, false, false, false, 'li'); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="dropdown custom-dropdown-my-listing-manage">
        <button class="btn btn-default dropdown-toggle" type="button" id="menu1"
                data-toggle="dropdown"><?php echo tc('Manage'); ?>
            <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
            <?php if ($data->isNotDeletedAndDraft()):?>
                <li role="presentation"><a role="menuitem" tabindex="-1" class="fancy"
                       href="<?php echo Yii::app()->createUrl("/apartments/main/viewDetailsViewsStats", array("id" => $data->id)); ?>"><?php echo tt('views_past_week', 'apartments'); ?></a>
                </li>
                <!--<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $data->getUrl(); ?>" target="_blank"><?php echo tc('View'); ?></a></li>-->

                <?php if (Yii::app()->user->type == User::TYPE_AGENCY): ?>
                    <li role="presentation"><a role="menuitem" tabindex="-1"
                       href="<?php echo Yii::app()->createUrl("userads/main/choosenewowner", array("id" => $data->id)); ?>"><?php echo tt('Set the owner of the listing', 'apartments'); ?></a>
                    </li>
                <?php endif; ?>
                <?php if (param("enableUserAdsCopy", 0)): ?>
                    <li role="presentation"><a role="menuitem" tabindex="-1"
                       onclick="javascript: ajaxRequest($(this).attr('href'), 'my-listing-blocks', 'listview'); initBootstrapConfirm(); return false;"
                       href="<?php echo Yii::app()->createUrl("/userads/main/clone", array("id" => $data->id)); ?>"><?php echo tc('Clone'); ?></a>
                    </li>
                <?php endif; ?>
            <?php endif;?>

            <li role="presentation"><a role="menuitem" tabindex="-1"
                href="<?php echo Yii::app()->createUrl("/userads/main/update", array("id" => $data->id)); ?>"><?php echo tc('Update'); ?></a>
            </li>
            <li role="presentation" class="divider"></li>
            <li role="presentation"><a role="menuitem" data-toggle="confirmation"
                   data-btn-ok-label="<?php echo tc('Yes'); ?>"
                   data-btn-cancel-label="<?php echo tc('No'); ?>"
                   data-title="<?php echo tt('Are you sure?', 'bookingcalendar'); ?>" tabindex="-1"
                   href="<?php echo Yii::app()->createUrl("/userads/main/delete", array("id" => $data->id)); ?>"><?php echo tc('Delete'); ?></a>
            </li>
        </ul>
    </div>

    <div class="clear"></div>
    <div class="summary-ap-description">
        <?php if (HSite::availableApartmentPaidServices($data)): ?>
            <div class="ap-paid-services">
                <?php echo HApartment::getPaidHtml($data, false, false, true, 'div'); ?>
            </div>
        <?php endif; ?>
        <div class="ap-summary-info">
            <?php echo GridHelper::getSummary($data, 'frontend'); ?>
        </div>
    </div>
    <div class="clear"></div>
</li>

<hr/>