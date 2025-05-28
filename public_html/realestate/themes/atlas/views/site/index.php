<?php if (isset($page) && $page): ?>
    <?php if ($page->widget && $page->widget_position == InfoPages::POSITION_TOP): ?>
        <?php $this->renderPartial('_index_view_widget', array('widget' => $page->widget, 'page' => $page, 'widgetTitles' => $page->widget_titles)); ?>
        <div class="clear"></div><br/>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($page) && $page): ?>
    <div class="welcome">
        <div class="title highlight-left-right">
            <div>
                <?php
                if ($page->title) {
                    echo '<h1>' . CHtml::encode($page->title) . '</h1>';
                }
                ?>
            </div>
        </div>

        <?php
        if ($page->body) {
            echo HSite::parseText($page->body);
        }
        ?>
    </div>
<?php endif; ?>

<?php
$entriesIndex = Entries::getLastNews();

if ($entriesIndex) : ?>
    <div class="entries">
        <div class="title highlight-left-right">
            <div>
                <h2><?php echo tt('News', 'entries'); ?></h2>
            </div>
        </div>

        <?php
        $total = count($entriesIndex);
        $counter = 0;
        ?>
        <?php foreach ($entriesIndex as $entries) : ?>
            <?php $counter++; ?>
            <?php $announce = ($entries->getAnnounce()) ? $entries->getAnnounce() : '&nbsp;'; ?>

            <div class="new">
                <div class="title">
                    <?php //echo CHtml::link(truncateText($entries->getStrByLang('title'), 4), $entries->getUrl());?>
                    <?php echo CHtml::link($entries->getStrByLang('title'), $entries->getUrl()); ?>
                </div>

                <?php
                $class = 'no-image-text';
                if ($entries->image) {
                    $src = $entries->image->getThumb(80, 60);
                    if ($src) {
                        $class = 'text';
                        echo CHtml::image(Yii::app()->getBaseUrl() . '/uploads/entries/' . $src, $entries->getStrByLang('title'),
                            array('class' => 'float-left')
                        );
                    }
                }

                ?>

                <div class="<?php echo $class; ?>">
                    <?php
                    if ($class == 'text') {
                        //echo truncateText($announce, 10);
                        echo truncateText($announce, 25);
                    } else {
                        //echo truncateText($announce, 15);
                        echo truncateText($announce, 40);
                    }

                    ?>
                </div>
                <div class="clear"></div>
            </div>

            <?php if ($counter != $total): ?>
                <div class="dotted_line"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
    <div class="clear"></div>

<?php if (isset($page) && $page): ?>
    <?php if ($page->widget && $page->widget_position == InfoPages::POSITION_BOTTOM): ?>
        <?php $this->renderPartial('_index_view_widget', array('widget' => $page->widget, 'page' => $page, 'widgetTitles' => $page->widget_titles)); ?>
    <?php endif; ?>
<?php endif; ?>