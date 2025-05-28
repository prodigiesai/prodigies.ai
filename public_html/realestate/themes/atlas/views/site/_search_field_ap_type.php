<div class="<?php echo $divClass; ?>">
    <?php if ($this->searchShowLabel) { ?>
        <div class="<?php echo $textClass; ?>"><?php echo tt('Search in section', 'common'); ?>:</div>
    <?php } ?>
    <div class="<?php echo $controlClass; ?>">
        <?php
        echo CHtml::dropDownList(
            'apType',
            isset($this->apType) ? CHtml::encode($this->apType) : '',
            HApartment::getTypesForSearch(true),
            array('class' => $fieldClass)
        );

        Yii::app()->clientScript->registerScript('apTypeFocusSubmit', '
				jQuery(function($) {
					focusSubmit($("select#apType"));
				});
			', CClientScript::POS_END, array(), true);
        ?>
    </div>
</div>
