<form id="search-form" class="forma"
      action="<?php echo Yii::app()->controller->createUrl('/quicksearch/main/mainsearch'); ?>" method="get">
    <div class="searchform-back">
        <div class="searchform-index align-left">
            <div class="index-header-form" id="search_form">
                <?php $this->renderPartial('//site/_search_form', array('isInner' => 0)); ?>
            </div>

            <div class="index-search-button-line">
                <a href="javascript: void(0);" id="more-options-link"><?php echo tc('More options'); ?></a>
                <a href="javascript: void(0);" onclick="doSearchAction();" id="btnleft"
                   class="index-btnsrch link_blue"><?php echo tc('Search'); ?></a>
            </div>
        </div>
    </div>
</form>

<?php
$this->renderPartial('//site/_search_js', ['isInner' => 0]);
?>



