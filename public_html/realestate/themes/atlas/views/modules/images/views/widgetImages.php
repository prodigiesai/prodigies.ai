<?php
$count = 1;
if ($this->images) {
    $countAll = count($this->images);

    if ($this->useFotorama) {
        ?>

        <div id="imgHolder" style="opacity: 1;">
            <div class="fotorama"
                 data-width="100%"
                 data-maxheight="600"
                 data-nav="thumbs"
                 data-thumbwidth="112px"
                 data-thumbheight="87px"
                 data-thumbmargin="15"
                 data-allowfullscreen="true"
                 data-arrows="always">

                <?php
                foreach ($this->images as $image) {
                    $imgTag = CHtml::image(Images::getThumbUrl($image, 112, 87), Images::getAlt($image));
                    echo CHtml::link($imgTag, Images::getFullSizeUrl($image), array(
                        'title' => Images::getAlt($image),
                        'data-caption' => $count . ' / ' . $countAll
                    ));
                    $count++;
                }
                ?>
            </div>
        </div>

    <?php } else { ?>
        <div id="imgHolder" style="opacity: 1;"></div>
        <div class="jcarousel-wrapper">
            <div class="mini_gallery jcarousel" data-jcarousel="true">
                <ul style="left: 0px; top: 0px;" id="jcarousel">
                    <?php foreach ($this->images as $image) : ?>
                        <li>
                            <?php
                            $imgTag = CHtml::image(Images::getThumbUrl($image, 69, 66), Images::getAlt($image), array(
                                'onclick' => 'setImgGalleryIndex("' . $count . '");',
                            ));
                            echo CHtml::link($imgTag, Images::getFullSizeUrl($image), array(
                                'data-gal' => 'prettyPhoto[img-gallery]',
                                'title' => Images::getAlt($image),
                                'alt' => Images::getAlt($image),
                            ));
                            $count++;
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="jcarousel-prev jcarousel-control-prev"></div>
            <div class="jcarousel-next jcarousel-control-next"></div>
        </div>
        <?php
    }
}
?>