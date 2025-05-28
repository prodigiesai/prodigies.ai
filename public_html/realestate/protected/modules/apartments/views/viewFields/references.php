<?php

$content = '<div class="references_view additional">';

$prev = '';
$column1 = 0;
$column2 = 0;
$column3 = 0;

$i = 0;
foreach ($data->references as $item) {
    if ($item['type'] == ReferenceCategories::TYPE_STANDARD) {
        if ($item['title']) {
            if ($prev != $item['style']) {
                $column2 = 0;
                $column3 = 0;
                if ($i != 0) {
                    $content .= '<div class="clear"></div>';
                }
            }
            $style = $item['style'];
            $$style++;
            $prev = $item['style'];
            $content .= '<div class="' . $item['style'] . ' add_field col-md-3 col-sm-4">';
            $content .= '<div class="viewapartment-subheader">' . CHtml::encode($item['title']) . '</div>';
            $content .= '<ul class="apartment-description-ul list-unstyled">';
            foreach ($item['values'] as $key => $value) {
                if ($value) {
                    if (param('useReferenceLinkInView') && ((!isset($isPrintable)) || !$isPrintable)) {
                        $content .= '<li><span>' . CHtml::link(CHtml::encode($value), $this->createAbsoluteUrl('/service-' . $key), array('class' => 'service-reference-link-in-view')) . '</span></li>';
                    } else {
                        $content .= '<li><span>' . CHtml::encode($value) . '</span></li>';
                    }
                }
            }
            $content .= '</ul>';
            $content .= '</div>';
            if (($item['style'] == 'column2' && $column2 == 2) || $item['style'] == 'column3' && $column3 == 3) {
                $content .= '<div class="clear"></div>';
            }
        }
    }
    $i++;
}
$content .= '</div><div class="clear"></div>';

HFormEditor::renderViewRow(tc('References'), $content);
?>