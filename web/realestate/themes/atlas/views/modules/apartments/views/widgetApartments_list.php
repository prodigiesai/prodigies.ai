<?php
//$route = Controller::getCurrentRoute();

$showSorter = $this->showSorter && $sorterLinks && $count;

$getForMapSwitch = $getForSwitch = HSite::getBaseSwitchUrl($showSorter);
if (isset($getForMapSwitch['is_ajax'])) {
    unset($getForMapSwitch['is_ajax']);
}

$urlsSwitching = array(
    'block' => z_add_url_get(array('ls' => 'block') + $getForSwitch),
    'table' => z_add_url_get(array('ls' => 'table') + $getForSwitch),
    'map' => z_add_url_get(array('ls' => 'map') + $getForMapSwitch),
);

if (!param('useGoogleMap', 0) && !param('useYandexMap', 0) && !param('useOSMMap',
        0) || Yii::app()->controller->useAdditionalView == Themes::ADDITIONAL_VIEW_FULL_WIDTH_MAP) {
    unset($urlsSwitching['map']);
}

if (empty($this->urlSwitching['block'])) {
    unset($urlsSwitching['block']);
}
if (empty($this->urlSwitching['table'])) {
    unset($urlsSwitching['table']);
}
if (empty($this->urlSwitching['map']) && isset($urlsSwitching['map'])) {
    unset($urlsSwitching['map']);
}

$modeListShow = $this->modeListShow ? $this->modeListShow : User::getModeListShow($urlsSwitching);

if (!Yii::app()->request->isAjaxRequest) {

    Yii::app()->clientScript->registerScript('search-vars-params-funct', "

	var updateText = '" . Yii::t('common', 'Loading ...') . "';
	var resultBlock = 'appartment_box';
	var bg_img = '" . Yii::app()->theme->baseUrl . "/images/pages/opacity.png';

	var useGoogleMap = " . param('useGoogleMap', 0) . ";
	var useYandexMap = " . param('useYandexMap', 0) . ";
	var useOSMap = " . param('useOSMMap', 0) . ";
		
	$(function () {
		$('div#appartment_box').on('mouseover mouseout', 'div.appartment_item', function(event){
			if (event.type == 'mouseover') {
			 $(this).find('div.apartment_item_edit').show();
			} else {
			 $(this).find('div.apartment_item_edit').hide();
			}
		});

		if(modeListShow == 'map'){
			list.apply();
		}
	});
	
    var urlsSwitching = " . CJavaScript::encode($urlsSwitching) . ";
	var modeListShow = " . CJavaScript::encode($modeListShow) . ";
	
    function setListShow(mode){
        modeListShow = mode;
        reloadApartmentList(urlsSwitching[mode]);
    };
", CClientScript::POS_END, array(), true);

}
?>

<?php /*if (Yii::app()->request->isAjaxRequest && $route != 'site/index') : ?>
	<?php if(isset($this->breadcrumbs) && $this->breadcrumbs):?>
		<?php
			$this->widget('zii.widgets.CBreadcrumbs', array(
				'homeLink' => CHtml::link(Yii::t('zii','Home'),Yii::app()->homeUrl, array('class' => 'path')),
				'links'=>$this->breadcrumbs,
				'separator' => ' / ',
				'activeLinkTemplate' => '<a class="path" href="{url}">{label}</a>',
				'inactiveLinkTemplate' => '<a href="javascript: void(0);">{label}</a>',
			));
		?>
	<?php endif;?>
<?php endif; */ ?>

<div class="catalog" id="appartment_box">
    <?php if ($callFromWidget): ?>
        <?php if (!empty($customWidgetTitle)): ?>
            <div class="title highlight-left-right">
                <div><h2><?php echo $customWidgetTitle; ?></h2></div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php if ($this->showWidgetTitle) : ?>
            <div class="title highlight-left-right">
                <div>
                    <h2>
                        <?php
                        if ($this->widgetTitle !== null) {
                            echo $this->widgetTitle . ($count && $showCount ? ' (' . $count . ')' : '');
                        } else {
                            echo tt('Apartments list',
                                    'apartments') . ($count && $showCount ? ' (' . $count . ')' : '');
                        }
                        ?>
                    </h2>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="clear"></div>

    <?php if ($this->showSwitcher && $urlsSwitching) { ?>
        <div class="change_list_show">
            <a href="<?php echo $urlsSwitching['block']; ?>" <?php if ($modeListShow == 'block') {
                echo 'class="active_ls"';
            } ?>
               onclick="setListShow('block'); return false;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/pages/block.png" alt="block"/>
            </a>

            <a href="<?php echo $urlsSwitching['table']; ?>" <?php if ($modeListShow == 'table') {
                echo 'class="active_ls"';
            } ?>
               onclick="setListShow('table'); return false;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/pages/table.png" alt="table"/>
            </a>

            <?php if (array_key_exists('map', $urlsSwitching)) : ?>
                <a href="<?php echo $urlsSwitching['map']; ?>" <?php if ($modeListShow == 'map') {
                    echo 'class="active_ls"';
                } ?> >
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/pages/map.png"
                         alt="<?php echo tc('Map'); ?>"/>
                </a>
            <?php endif; ?>
        </div>
    <?php } ?>
    <div class="clear"></div>
    <?php if ($showSorter): ?>
        <div class="sorting">
            <?php foreach ($sorterLinks as $link): ?>
                <?php echo $link; ?>
            <?php endforeach; ?>
        </div>
        <div class="clear"></div>
    <?php endif; ?>

    <?php if ($pages && 0): ?>
        <div class="pagination">
            <?php
            $this->widget('itemPaginatorAtlas',
                array(
                    'pages' => $pages,
                    'maxButtonCount' => 6,
                    'header' => '',
                    'selectedPageCssClass' => 'current',
                    'htmlOptions' => array(
                        'class' => '',
                    ),
                    'htmlOption' => array('onClick' => 'reloadApartmentList(this.href); return false;'),
                )
            );
            ?>
        </div>
        <div class="clear"></div><br/>
    <?php endif; ?>

    <?php if ($count): ?>
        <?php
        if ($modeListShow == 'block') {
            $this->render('widgetApartments_list_block', array('criteria' => $criteria, 'numBlocks' => $this->numBlocks));
        } elseif ($modeListShow == 'map' && (param('useGoogleMap', 0) || param('useYandexMap', 0) || param('useOSMMap',
                    0))) {
            $this->render('widgetApartments_list_map', array('criteria' => $criteria));
        } else {
            $this->render('widgetApartments_list_table', array('criteria' => $criteria));
        }
        ?>
        <div class="clear"></div>
    <?php endif; ?>
</div>

<?php if (!$count): ?>
    <div class="empty"><?php echo Yii::t('module_apartments', 'Apartments list is empty.'); ?></div>
    <div class="clear"></div>
<?php endif; ?>

<?php if ($pages): ?>
    <div class="pagination">
        <?php
        $this->widget('itemPaginatorAtlas',
            array(
                'pages' => $pages,
                'maxButtonCount' => 6,
                'header' => '',
                'selectedPageCssClass' => 'current',
                'htmlOptions' => array(
                    'class' => '',
                ),
                'htmlOption' => array('onClick' => 'reloadApartmentList(this.href); return false;'),
            )
        );
        ?>
    </div>
    <div class="clear"></div>
<?php endif; ?>

<?php
$isRTL = Lang::isRTLLang(Yii::app()->language);
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/common/js/flexslider2/css/flexslider.css');
if ($isRTL) {
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/common/js/flexslider2/css/flexslider-rtl.css');
}

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/flexslider2/js/jquery.flexslider.js',
    CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/flexslider2/js/jquery.easing.js',
    CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/flexslider2/js/jquery.mousewheel.js',
    CClientScript::POS_END);

Yii::app()->clientScript->registerScript('init-ap-image-items-flexslider2', '
	function initApImagesItemsSlider() {
		var useRTL = ' . ($isRTL ? 'true' : 'false') . ';

		$(".flexslider-apartment-image").flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: true,
			slideshow: false,
			rtl: useRTL,
			controlsContainer: $(".custom-controls-container"),
			customDirectionNav: $(".custom-navigation a"),
			initDelay: 0,
			start: function(slider){				
				/*$(".flexslider-apartment-image").removeClass("flexslider-loading-image");*/
				$(".flex-viewport").css({"overflow":"visible"});
				
				var slide_count = slider.count - 1;

				$(slider)
				.find("li:not(.clone)")
				.find("img.lazy:eq(0)")
				.each(function() {
					var src = $(this).attr("data-src");
					$(this).attr("src", src).removeAttr("data-src");
				});
			},
			before: function(slider) { // Fires asynchronously with each slider animation
				var slides = slider.slides,
				  index = slider.animatingTo,
				  $slide = $(slides[index]),
				  $img = $slide.find("img[data-src]"),
				  current = index,
				  nxt_slide = current + 1,
				  prev_slide = current - 1;

				$slide
				.parent()
				.find("li:not(.clone)")
				.find(\'img.lazy:eq(\' + current + \'), img.lazy:eq(\' + prev_slide + \'), img.lazy:eq(\' + nxt_slide + \')\')
				.each(function() {
					var src = $(this).attr("data-src");
					$(this).attr("src", src).removeAttr("data-src");
				});
			}
		});
	}
', CClientScript::POS_END, array(), true);

Yii::app()->clientScript->registerScript('ap-image-items-flexslider2', '
	initApImagesItemsSlider();
', CClientScript::POS_READY);
?>

<?php if (Yii::app()->request->isAjaxRequest): ?>
    <script>
        initApImagesItemsSlider();
    </script>
<?php endif; ?>
