<?php
$showHideFilter = (isset($showHideFilter)) ? $showHideFilter : true;
$compact = ($showHideFilter == false) ? false : param("useCompactInnerSearchForm", false);

$searchUrl = isset($this->aData['searchUrl']) ? $this->aData['searchUrl'] : Yii::app()->controller->createUrl('/quicksearch/main/mainsearch');
?>
    <form id="search-form" action="<?php echo $searchUrl; ?>"
          method="get">
        <?php
        if (isset($this->userListingId) && $this->userListingId) {
            echo CHtml::hiddenField('userListingId', $this->userListingId);
        }

        $loc = (issetModule('location')) ? "-loc" : "";
        ?>
        <div class="filtr<?php if ($compact) echo ' collapsed' ?>">
            <div id="search_form" class="inner_form">
                <?php $this->renderPartial('//site/_search_form', array(
                    'isInner' => 1,
                    'compact' => $compact,
                ));
                ?>
            </div>
            <!--<div class="clear"></div>-->

            <div class="inner_search_button_row">
                <a href="javascript: void(0);" onclick="doSearchAction();" id="btnleft"
                   class="link_blue inner_search_button"><?php echo Yii::t('common', 'Search'); ?></a>
            </div>

            <div class="clear"></div>

            <?php if ($showHideFilter): ?>
                <a href="javascript: void(0);">
                    <div class="hide_filtr"></div>
                </a>
            <?php endif; ?>
        </div>
    </form>

<?php
$this->renderPartial('//site/_search_js', ['isInner' => 1]);
?>