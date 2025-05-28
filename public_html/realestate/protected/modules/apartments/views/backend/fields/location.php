<div id="ore-form-location">

    <?php
    if ($model->parent_id && $model->isChild() && $model->parent) {
        echo '<p>';
        echo tt('Address', 'apartments') . ': ' . HApartment::getLocationString($model->parent, ', ', true);
        echo '</p>';
        echo '</div>';
        return;
    }
    $callFrom = (isset($callFrom)) ? $callFrom : null;
    ?>

    <?php
    if (issetModule('location')) {
        include_once '_location_items.php';
    } else {
        include_once '_location_city_only.php';
    }
    ?>

</div>

<?php
Yii::app()->clientScript->registerScript('switch-city', '
	var isMetro = ' . ((issetModule('metroStations')) ? 1 : 0) . ';
	var isCustomCity = ' . (($model->customCity) ? 1 : 0) . '

	function switchCity() {
		if (isCustomCity){
			$("#locationCity").show();
			$("#customCity").hide();
			$("#isCustomCity").val(0);
			isCustomCity = 0;
			if(isMetro && $("#ap_metro option").length)
				$("#metro-block-apartment").show();
		} else {
			$("#locationCity").hide();
			$("#customCity").show();
			$("#isCustomCity").val(1);
			isCustomCity = 1;
			if(isMetro)
				$("#metro-block-apartment").hide();
		}

	}
', CClientScript::POS_END);
