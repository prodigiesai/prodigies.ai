<?php
$numBlocks = isset($numBlocks) ? $numBlocks : 3;
$addBlockClass = ($numBlocks == 2) ? 'block-two-numBlocks' : '';

if (empty($apartments)) {
    $apartments = HApartment::findAllWithCache($criteria);
}

$findIds = $countImagesArr = array();
foreach ($apartments as $item) {
    $findIds[] = $item->id;
}

if (count($findIds) > 0) {
    $countImagesArr = Images::getApartmentsCountImages($findIds);
}

$p = 1;

foreach ($apartments as $item) {
    include 'widgetApartments_list_block_item.php';
    $p++;
}