<?php
$addClass = $lastClass = '';

$isLast = ($p % $this->numBlocks) ? false : true;
$lastClass = ($isLast) ? 'right_null' : '';

if (!empty($item->date_up_search) && !is_null($item->date_up_search)) {
    $addClass = 'up_in_search';
}
?>

<div class="appartment_item block <?php echo $addBlockClass; ?> <?php echo $lastClass; ?>"
     data-lat="<?php echo $item->lat; ?>" data-lng="<?php echo $item->lng; ?>"
     data-ap-id="<?php echo $item->id; ?>">
    <div class="title_block">
        <?php
        $title = CHtml::encode($item->getStrByLang('title'));

        $description = '';
        if ($item->canShowInView('description')) {
            $description = $item->getStrByLang('description');
        }

        echo CHtml::link(HApartment::getTitleForView($item), $item->getUrl(), array('title' => $title));

        ?>
    </div>
    <div class="before-image">
        <div class="image_block">

            <?php if (Yii::app()->user->checkAccess('backend_access') || (param('useUserads') && $item->isOwner())): ?>
                <div class="apartment_item_edit">
                    <a href="<?php echo $item->getEditUrl(); ?>">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/doc_edit.png"
                             alt="<?php echo tt('Update apartment', 'apartments'); ?>"
                             title="<?php echo tt('Update apartment', 'apartments'); ?>">
                    </a>
                </div>
            <?php endif; ?>

            <?php if (array_key_exists($item->id, $countImagesArr) && $countImagesArr[$item->id] > 1): ?>
                <div class="apartment_count_img">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/photo_count.png" alt="photo count">
                    <strong><?php echo $countImagesArr[$item->id]; ?></strong>
                </div>
            <?php endif; ?>

            <div class="apartment_type"><?php echo HApartment::getNameByType($item->type); ?></div>

            <?php if ($item->is_special_offer): ?>
                <div class="like" title="<?php echo tc('Special offer!'); ?>"></div>
            <?php endif; ?>

            <?php /*if($item->rating):?>
					<div class="rating">
						<?php
						$this->widget('CStarRating',array(
							'model'=>$item,
							'attribute' => 'rating',
							'readOnly'=>true,
							'id' => 'rating_' . $item->id,
							'name'=>'rating'.$item->id,
							'cssFile' => Yii::app()->theme->baseUrl.'/css/rating/rating.css',
							'minRating' => Comment::MIN_RATING,
							'maxRating' => Comment::MAX_RATING,
						));
						?>
					</div>
				<?php endif;*/ ?>

            <?php if ($item->rating): ?>
                <?php $countComments = (isset($item->countComments) && $item->countComments) ? $item->countComments : 0; ?>

                <div class="rating item-small-block-rating">
                    <div class="item-rating-grade">
                        <?php //echo number_format((float)round($item->rating, 1, PHP_ROUND_HALF_DOWN), 1, '.', '');?>
                        <?php echo $item->rating; ?>
                    </div>
                    <?php if ($countComments): ?>
                        <div class="item-view-all-comments">
                            <a href="<?php echo $item->getUrl(); ?>">
                                <?php echo Yii::t('common', '{n} review|{n} reviews', $countComments); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($item->images) && !empty($item->images)): ?>
                <!--<div class="flexslider flexslider-apartment-image flexslider-loading-image">-->
                <div class="flexslider flexslider-apartment-image">
                    <ul class="slides">
                        <?php $im = 1; ?>
                        <?php foreach ($item->images as $image) : ?>
                            <?php if ($im > 4) {
                                break;
                            } ?>
                            <li>
                                <?php
                                $imgTag = CHtml::image(Yii::app()->theme->baseUrl . '/images/ajax-loader-wild.gif', Images::getAlt($image), array(
                                    'class' => 'apartment_type_img',
                                    'title' => Images::getAlt($image),
                                    'alt' => Images::getAlt($image),
                                    'class' => 'lazy',
                                    'data-src' => Images::getThumbUrl($image, 303, 170),
                                ));
                                echo CHtml::link($imgTag, $item->getUrl(), array('title' => Images::getAlt($image)));
                                ?>
                            </li>
                            <?php $im++; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="custom-navigation">
                    <a href="#" class="flex-prev"><?php Yii::t('bootstrap', 'Previous'); ?></a>
                    <div class="custom-controls-container"></div>
                    <a href="#" class="flex-next"><?php Yii::t('bootstrap', 'Next'); ?></a>
                </div>
            <?php else: ?>
                <?php
                $res = Images::getMainThumb(610, 342, $item->images);
                $imgAlt = (isset($res['alt']) && $res['alt']) ? $res['alt'] : CHtml::encode($item->getStrByLang('title'));

                $img = CHtml::image($res['thumbUrl'], $imgAlt, array(
                    'title' => $item->getStrByLang('title'),
                    'class' => 'apartment_type_img',
                    'alt' => $imgAlt,
                ));
                echo CHtml::link($img, $item->getUrl(), array('title' => $item->getStrByLang('title')));
                ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="clear"></div>

    <div class="mini_block_full_description <?php echo $addClass; ?>">
        <div class="mini_location_description">
            <?php echo HApartment::getLocationString($item); ?>
        </div>
        <?php if ($item->canShowInView('description')) { ?>
            <div class="desc">
                <?php
                if (utf8_strlen($description) > 110)
                    $description = utf8_substr($description, 0, 110) . '...';

                echo $description;

                //echo truncateText($description, 40);
                ?>
            </div>
        <?php } ?>

        <?php if ($item->canShowInView('price')) { ?>
            <div class="desc">
                <div class="price">
                    <?php
                    if ($item->is_price_poa)
                        echo tt('is_price_poa', 'apartments');
                    else
                        echo $item->getPrettyPrice();
                    ?>
                </div>
            </div>
        <?php } ?>

        <div class="clear"></div>

        <?php if (issetModule('comparisonList')): ?>
            <?php
            $inComparisonList = false;
            if (in_array($item->id, Yii::app()->controller->apInComparison))
                $inComparisonList = true;
            ?>
            <div class="row compare-check-control" id="compare_check_control_<?php echo $item->id; ?>">
                <?php
                $checkedControl = '';

                if ($inComparisonList)
                    $checkedControl = ' checked = checked ';
                ?>
                <input type="checkbox" name="compare<?php echo $item->id; ?>"
                       class="compare-check compare-float-left"
                       id="compare_check<?php echo $item->id; ?>" <?php echo $checkedControl; ?>>

                <a href="<?php echo ($inComparisonList) ? Yii::app()->createUrl('comparisonList/main/index') : 'javascript:void(0);'; ?>"
                   data-rel-compare="<?php echo ($inComparisonList) ? 'true' : 'false'; ?>"
                   id="compare_label<?php echo $item->id; ?>" class="compare-label">
                    <?php echo ($inComparisonList) ? tt('In the comparison list', 'comparisonList') : tt('Add to a comparison list ', 'comparisonList'); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="clear"></div>

        <?php if ($item->square || $item->berths): ?>
            <dl class="mini_desc">
                <?php $showBerth = false; ?>
                <?php if ($item->canShowInView('berths')): ?>
                    <?php $showBerth = true; ?>
                    <dt>
                        <span class="icon-bedroom icon-mrgr"></span>
                    </dt>
                    <dd><?php echo Yii::t('module_apartments', 'berths') . ': ' . CHtml::encode($item->berths); ?></dd>

                <?php endif; ?>
                <?php if ($item->canShowInView('square')): ?>
                    <dt>
                        <span class="icon-square icon-mrgr"></span>
                    </dt>
                    <dd><?php echo Yii::t('module_apartments', 'total square: {n}', $item->square) . " " . tc('site_square'); ?></dd>
                <?php endif; ?>
            </dl>
        <?php endif; ?>

    </div>

    <?php
    if (issetModule('favorite')) {
        $this->widget(\application\modules\favorite\widgets\FavoriteWidget::className(), array(
            'model' => $item,
            'type' => 1
        ));
    }
    ?>
</div>