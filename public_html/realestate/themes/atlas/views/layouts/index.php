<?php $this->beginContent('//layouts/main'); ?>
<?php $useAdditionalView = Yii::app()->controller->useAdditionalView; ?>

<?php if (!$useAdditionalView): ?>
    <?php Yii::app()->controller->renderPartial('//site/_slider_homepage', array('useFullWidthSlider' => false)); ?>
    <?php Yii::app()->controller->renderPartial('//site/index-search'); ?>

    <div class="clear"></div>
    </div> <!-- /bg ( from layouts/main.php ) -->
<?php elseif ($useAdditionalView == Themes::ADDITIONAL_VIEW_SEARCH_ONLY): ?>
    <?php Yii::app()->controller->renderPartial('//site/inner-search', array('showHideFilter' => false)); ?>
    <div class="clear"></div>
    </div> <!-- /bg ( from layouts/main.php ) -->
<?php else: ?>
    <div class="clear"></div>
    </div> <!-- /bg ( from layouts/main.php ) -->

    <?php if ($useAdditionalView == Themes::ADDITIONAL_VIEW_FULL_WIDTH_SLIDER) : ?>
        <?php Yii::app()->controller->renderPartial('//site/_slider_homepage', array('useFullWidthSlider' => true)); ?>
    <?php else: ?>
        <?php Yii::app()->controller->renderPartial('//site/_map_with_search_form_homepage'); ?>
    <?php endif; ?>
    <div class="clear"></div>
<?php endif; ?>

<?php if (issetModule('advertising')) : ?>
    <?php $this->renderPartial('//modules/advertising/views/advert-top', array()); ?>
<?php endif; ?>

    <!-- content -->
    <div class="content<?php echo ($useAdditionalView == Themes::ADDITIONAL_VIEW_SEARCH_ONLY) ? ' index-search-only' : ''; ?>">
        <div class="main-content-wrapper">
            <?php
            foreach (Yii::app()->user->getFlashes() as $key => $message) {
                if ($key == 'error' || $key == 'success' || $key == 'notice') {
                    echo "<div class='flash-{$key}'>{$message}</div>";
                }
            }
            ?>

            <?php echo $content; ?>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
<?php $this->endContent(); ?>