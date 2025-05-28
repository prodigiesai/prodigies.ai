<div class="block_entries">
    <?php if (!$entries): ?>
        <div class="empty"><?php echo tt('Entries list is empty.', 'entries'); ?></div>
    <?php else: ?>
        <?php if (isset($showWidgetTitle) && $showWidgetTitle): ?>
            <div class="title highlight-left-right">
                <div><h1><?php echo $customWidgetTitle; ?></h1></div>
            </div>
        <?php endif; ?>

        <div class="b_entries">
            <?php foreach ($entries as $item) : ?>
                <?php $src = false; ?>

                <?php if ($item->image): ?>
                    <?php $src = $item->image->getThumb(531, 256); ?>
                <?php endif; ?>
                <div class="b_entries__item <?php echo (!$src) ? 'b_entries__item_no_src' : ''; ?>">
                    <?php if ($src) : ?>
                        <?php
                        $tagAlt = CHtml::encode($item->getStrByLang('title'));
                        if (issetModule('seo') && isset($item->image->image_seo) && $item->image->image_seo->getStrByLang('alt')) {
                            $tagAlt = CHtml::encode($item->image->image_seo->getStrByLang('alt'));
                        }
                        ?>

                        <?php echo CHtml::image(
                            Yii::app()->getBaseUrl() . '/uploads/entries/' . $src,
                            $tagAlt
                        );
                        ?>
                    <?php endif; ?>

                    <div class="b_entries__item_post <?php echo (!$src) ? 'b_entries__item_post_no_src' : ''; ?>">
                        <div class="title">
                            <?php echo CHtml::link(CHtml::encode($item->getStrByLang('title')), $item->getUrl()); ?>
                        </div>
                        <br/>
                        <div class="posted"><span class="date"><?php echo $item->dateCreatedLong; ?></span></div>
                        <div class="new_desc">
                            <?php echo $item->getAnnounce(); ?>
                        </div>
                        <?php echo CHtml::link(tt('Read more &raquo;', 'entries'), $item->getUrl(), array('class' => 'read_more')); ?>
                    </div>
                </div>
                <div class="clear"></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<div class="clear"></div>

<?php if ($pages && $pages->pageCount > 1): ?>
    <div class="pagination">
        <?php
        $this->widget('itemPaginatorAtlas',
            array(
                'pages' => $pages,
                'header' => '',
                'selectedPageCssClass' => 'current',
                'htmlOptions' => array(
                    'class' => ''
                )
            )
        );
        ?>
    </div>
    <div class="clear"></div>
<?php endif; ?>