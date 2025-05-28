<div>
    <?php $form = $this->beginWidget('CustomActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'htmlOptions' => array(
            'class' => 'search well',
        )
    )); ?>
    <div class="row-fluid">
        <?php
        $searchFields = array(
            'id', 'status', 'status_owner', 'title', 'type', 'obj_type_id', 'price', 'floor', 'square', 'rooms'
        );

        $locationArray = issetModule('location') ? array('country', 'region', 'city') : array('city_one');

        if (issetModule('metroStations'))
            $locationArray[] = 'metro';

        $searchFields = CArray::merge($locationArray, $searchFields);

        $countColumn = 2;
        $countAllFields = count($searchFields) + (isset($addedFields) ? count($addedFields) : 0);
        $fieldsInColumn = round($countAllFields / $countColumn);

        $i = 0;
        $s = 0;
        $column = 0;
        $divOpen = true;
        echo '<div class="col-md-6">';
        foreach ($searchFields as $field) {
            $this->renderPartial('//../modules/apartments/views/backend/search/_' . $field, array(
                'model' => $model,
                'form' => $form,
            ));
            $i++;
            $s++;
            if ($i >= $fieldsInColumn) {
                $i = 0;
                $column++;
                if ($column < $countColumn) {
                    echo '</div><div class="col-md-6">';
                } elseif ($s >= $countAllFields) {
                    $divOpen = false;
                    echo '</div>';
                }
            }
        }

        if (isset($addedFields) && $addedFields && count($addedFields)) {
            foreach ($addedFields as $adField) {
                ?>
                <div class="form-group">
                    <div class=""><?php echo $adField['label']; ?>:</div>
                    <?php if (isset($adField['listData']) && $adField['listData']): ?>
                        <?php echo CHtml::dropDownList('Apartment[' . $adField['field'] . ']', $model->{$adField['field']}, CMap::mergeArray(array("" => ""), $adField['listData'])); ?>
                    <?php else: ?>
                        <?php echo CHtml::textField('Apartment[' . $adField['field'] . ']', $model->{$adField['field']}); ?>
                    <?php endif; ?>
                </div>
                <?php

                if ($i >= $fieldsInColumn) {
                    $i = 0;
                    $column++;
                    if ($column < $countColumn) {
                        echo '</div><div class="col-md-6">';
                    } elseif ($s >= $countAllFields) {
                        $divOpen = false;
                        echo '</div>';
                    }
                }
            }
        }
        if ($divOpen) {
            echo '</div>';
        }

        ?>
    </div>
    <div class="clear"></div>

    <div class="rowold buttons">
        <?php echo CHtml::submitButton(tc('Apply'), array('class' => 'my-listings-button-search button-blue')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->