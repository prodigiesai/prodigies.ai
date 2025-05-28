<?php
if (param('descriptionUseEditor')) {
    $type = 'text-editor';
} else {
    $type = 'text';
}

$this->widget('application.modules.lang.components.langFieldWidget', array(
    'model' => $model,
    'field' => 'description',
    'type' => $type
));

echo '<div class="clear">&nbsp;</div>';
?>