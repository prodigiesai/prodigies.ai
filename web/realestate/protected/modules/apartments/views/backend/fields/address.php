<?php
$style = '';

if($model->parent_id && $model->isChild()){
    $style = 'style="display: none;"';
}

echo '<div class="ore-lang-field-address" '.$style.'>';
if ($model->canShowInForm('address')) {
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'address',
        'type' => 'string'
    ));
}
echo '</div>';
?>