<?php
/* @var $this SearchFormWidget */

use application\modules\apartments\widgets\SearchFormWidget;

//        $activeList = Apartment::getModerationStatusArray();
//        $activeListOwner = Apartment::getApartmentsStatusArray();
//        $objTypes = CArray::merge(array(0 => ''), ApartmentObjType::getList());
//        $types = HApartment::getTypesArray(false, HApartment::isDisabledType());

$badges = null;
if($this->isShowBadges){
    $badges = SearchFormWidget::getBadges($this->model);
}

$searchFields = $this->searchFields;

$locationArray = issetModule('location') ? array('country', 'region', 'city') : array('city_one');

if (issetModule('metroStations')) {
    $locationArray[] = 'metro';
}

$searchFields = \CArray::merge($locationArray, $searchFields);

if (issetModule('paidservices')) {
    $searchFields[] = 'searchPaidService';
}

$countAllFields = count($searchFields) + (isset($addedFields) ? count($addedFields) : 0);
$fieldsInColumn = round($countAllFields / $this->column);

$i = 0;
$s = 0;
$column = 0;
$divOpen = true;

$path = Yii::getPathOfAlias('application.modules.apartments.views.backend.search');

$wrapperStyle = $this->isShowForm ? '' : 'style="display: none;"';
?>
<div class="search well" <?php echo $wrapperStyle ?> id="search_ap">
    <h4><?php echo tt('Filter for listings\' list', 'infopages') ?></h4>

    <?php
    if($this->isUseForm) {
        echo '<form action="'.$this->action.'" method="get" id="ap_filter">';
    }
    ?>

        <div class="row row-fluid">
            <?php

            echo '<div class="col-sm-4">';
            foreach ($searchFields as $field) {
                require $path.'/_'.$field.'.php';
                $i++;
                $s++;
                if ($i >= $fieldsInColumn) {
                    $i = 0;
                    $column++;
                    if ($column < $this->column) {
                        echo '</div><div class="col-sm-4">';
                    } elseif ($s >= $countAllFields) {
                        $divOpen = false;
                        echo '</div>';
                    }
                }
            }

            if (isset($addedFields) && $addedFields && count($addedFields)) {
                foreach ($addedFields as $adField) {
                    echo '<div class="form-group">';
                    echo '<div>'.$adField['label'].'</div>';
                    if (isset($adField['listData']) && $adField['listData']) {
                        echo \CHtml::dropDownList('Apartment['.$adField['field'].']', $this->model->{$adField['field']},
                            \CMap::mergeArray(array("" => ""), $adField['listData']));
                    } else {
                        echo \CHtml::textField('Apartment['.$adField['field'].']', $this->model->{$adField['field']});
                    }
                    echo '</div>';

                    if ($i >= $fieldsInColumn) {
                        $i = 0;
                        $column++;
                        if ($column < $this->column) {
                            echo '</div><div class="col-sm-4">';
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

        <?php
        if($this->isShowApplyButton){
            echo AdminLteHelper::getSubmitButton(tc('Apply')).' ';
        }

        if($this->isShowClearButton){
            if ($badges) {
                echo AdminLteHelper::getLink(tc('Clear all filter'),
                    Yii::app()->createUrl('/apartments/backend/main/admin', array('resetFilters' => 1)), 'fa fa-trash',
                    array('class' => 'btn btn-warning bg-yellow'), true);
            } else {
                echo AdminLteHelper::getLink(tc('Clear all filter'), '#', 'fa fa-trash',
                    array('class' => 'btn btn-warning bg-yellow', 'onclick' => 'clearSearch(); return false;'), true);
            }
        }

        if($this->isUseForm){
            echo '</form>';
        }
        ?>
</div>

<?php

if ($badges) {
    echo '<div id="search_badge">';
    echo implode(' ', $badges);
    echo '  ' . CHtml::link('<span class="fa fa-trash"></span> &nbsp; ' . tc('Clear all filter'), Yii::app()->createUrl('/apartments/backend/main/admin', array('resetFilters' => 1)), array(
            'class' => 'btn btn-primary',
        ));
    echo '</div>';
}



