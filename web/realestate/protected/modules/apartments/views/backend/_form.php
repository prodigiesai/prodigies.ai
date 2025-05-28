<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');

if(Yii::app()->user->checkAccess('backend_access')){
    $autoSaveEnable = (int) param('autoSaveEnableAdmin', 1);
} else {
    $autoSaveEnable = (int) param('autoSaveEnableUser', 1);
}

$autoSaveInterval = (int) param('autoSaveInterval', 15) * 1000;
if(!$autoSaveInterval){
    $autoSaveInterval = 15000;
}

Yii::app()->clientScript->registerScript('redirectType', "
    $(document).ready(function() {
        var BASE_URL = " . CJavaScript::encode(Yii::app()->baseUrl) . ";

        $('.form').on('change', '#obj_type, #ap_type', function() {
            $('#update_overlay').show();
            $('#is_update').val(1);
            $('#Apartment-form').submit(); return false;
        });
        
        if($autoSaveEnable){
            setInterval(function() { 
                $('#is_auto_save').val(1);
                
                $.ajax({
                    type: 'post',
                    url: $('#Apartment-form').attr('action'),
                    data: $('#Apartment-form').serialize(),
                    success: function () {
                        message(".CJavaScript::encode(tc('Autosave is done')).");
                    },
                    complete: function () {
                        $('#is_auto_save').val(0);
                    },
                }); 
            }, $autoSaveInterval);
        }        

    });
	", CClientScript::POS_BEGIN, array(), true);

?>

    <div class="form">
        <div id="update_overlay"><p><?php echo tc('Loading content...'); ?></p></div>

        <?php
        $ajaxValidation = false;
        if (!$model->isNewRecord) {
            $htmlOptions = array('enctype' => 'multipart/form-data', 'class' => 'well form-disable-button-after-submit');
        } else {
            $htmlOptions = array('class' => 'well form-disable-button-after-submit');
        }

        /** @var $form BootActiveForm */
        $form = $this->beginWidget('CustomForm', array(
            'id' => $this->modelName . '-form',
            'enableAjaxValidation' => $ajaxValidation,
            'htmlOptions' => $htmlOptions,
        ));

        ?>

        <?php if (!$model->isNewRecord) { ?>
            <p>
                <strong><?php echo tt('Apartment ID', 'apartments'); ?></strong>: <?php echo $model->id; ?>
            </p>
        <?php } ?>

        <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

        <?php echo $form->errorSummary($model); ?>

        <?php
        $activeOriginal = $model->active;
        if ($model->active == Apartment::STATUS_DRAFT) {
            $model->active = Apartment::STATUS_ACTIVE;
        }
        ?>

        <?php echo $form->labelEx($model, 'active'); ?>
        <?php echo $form->dropDownList($model, 'active', Apartment::getAvalaibleStatusArray(), array('class' => 'width150')); ?>
        <?php echo $form->error($model, 'active'); ?>
        <br/>

        <?php echo $form->labelEx($model, 'owner_active'); ?>
        <?php
        echo $form->dropDownList($model, 'owner_active', Apartment::getApartmentsStatusArray(), array('class' => 'width150'));
        ?>
        <?php echo $form->error($model, 'owner_active'); ?>
        <br/>

        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTabs">
                <li class="active"><a href="#tab-main" data-toggle="tab"><?php echo tc('General'); ?></a></li>

                <?php if ($model->objType->with_obj) : ?>
                    <li><a href="#tab-childs"
                           data-toggle="tab"><?php echo tc('child_section_' . $model->objType->id); ?></a></li>
                <?php endif; ?>

                <li><a href="#tab-extended" data-toggle="tab"><?php echo tc('Addition'); ?></a></li>

                <li><a href="#tab-images" data-toggle="tab"><?php echo tc('Photos for listing'); ?></a></li>

                <li><a href="#tab-panorama" data-toggle="tab"><?php echo tc('Panorama'); ?></a></li>
                <li><a href="#tab-videos" data-toggle="tab"><?php echo tc('Videos for listing'); ?></a></li>

                <li><a href="#tab-documents" data-toggle="tab"><?php echo tc('Documents'); ?></a></li>

                <?php
                if (!$model->isNewRecord && (param('useGoogleMap', 1) || param('useYandexMap', 1) || param('useOSMMap', 1))
                ) {

                    ?>
                    <li><a href="#tab-map" data-toggle="tab" onclick="reInitMap();"><?php echo tc('Map'); ?></a></li>
                <?php } ?>
            </ul>
        </div>

        <?php if ($model->objType->with_obj) : ?>
            <div class="tab-pane" id="tab-childs">
                <?php require '_tab_childs.php' ?>
            </div>
        <?php endif; ?>

        <div class="tab-pane" id="tab-documents">
            <br/>
            <?php
            $this->renderPartial('//modules/apartments/views/__table_documents_edit', array('apartment' => $model, 'showDeleteButton' => true));

            ?>
            <br/>
            <?php echo $form->labelEx($model, 'document_file'); ?>
            <?php echo $form->fileField($model, 'document_file'); ?>
            <?php echo $form->error($model, 'document_file'); ?>
            <?php echo $form->error($model, 'documentUpload'); ?>
            <div class="padding-bottom10">
                <br/>
                <div>
                <span class="label label-info">
                    <?php echo Yii::t('module_apartments', 'Supported file: {supportExt}.', array('{supportExt}' => $supportdocumentext)); ?>
                </span>
                </div>
                <br/>
                <div>
                <span class="label label-info">
                    <span><?php echo tc('Documents_upload_help'); ?></span>
                </span>
                </div>
            </div>
            <br/>
        </div>

        <div class="tab-pane" id="tab-map">
            <?php
            if (!$model->isNewRecord && (param('useGoogleMap', 1) || param('useYandexMap', 1) || param('useOSMMap', 1)) ) {
                if (param('useGoogleMap', 1) || param('useYandexMap', 1) || param('useOSMMap', 1)) {
                    echo '<div class="flash-notice">' . tc('Click on the map to set the location of an object or move an existing marker.') . '</div>';
                }

                if (param('useGoogleMap', 1)) {

                    ?>

                    <div class="clear">&nbsp;</div>
                    <div id="gmap">
                        <?php echo $this->actionGmap($model->id, $model); ?>
                    </div>
                    <div class="clear">&nbsp;</div>
                    <?php
                } elseif (param('useYandexMap', 1)) {

                    ?>
                    <div class="clear">&nbsp;</div>
                    <div id="ymap">
                        <?php echo $this->actionYmap($model->id, $model); ?>
                    </div>
                    <div class="clear">&nbsp;</div>
                    <?php
                } elseif (param('useOSMMap', 1)) {

                    ?>
                    <div class="clear">&nbsp;</div>
                    <div id="osmap">
                        <?php echo $this->actionOSmap($model->id, $model); ?>
                    </div>
                    <div class="clear">&nbsp;</div>
                <?php }

                ?>
                <div class="form-inline">
                    <?php
                    echo CHtml::textField('address_for_map', '', array('class' => 'width300')) . '&nbsp;';
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'icon' => 'search',
                        'label' => tc('Set a marker by address'),
                        'htmlOptions' => array(
                            'onclick' => "findByAddress(); return false;",
                            'class' => 'btn btn-warning inline',
                        )
                    ));
                    ?>
                    &nbsp;
                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'danger',
                        'icon' => 'remove',
                        'label' => tc('Remove marker'),
                        'htmlOptions' => array(
                            'onclick' => "removeMarker(); return false;",
                            'class' => 'btn btn-danger inline',
                        )
                    ));

                    ?>
                </div>
                <br/>
            <?php } ?>
        </div>

        <div class="tab-pane" id="tab-images">
            <div class="flash-notice"><?php echo tc('You can change the order of photos, holding and dragging the left area of the block.'); ?></div>
            <?php
            $this->widget('application.modules.images.components.AdminImagesWidget', array(
                'objectId' => $model->id,
            ));

            ?>
        </div>

        <div class="tab-pane" id="tab-panorama">
            <?php
            $this->renderPartial('_tab_panorama_edit', array(
                'model' => $model,
                'form' => $form,
            ));

            ?>
        </div>

            <div class="tab-pane" id="tab-videos">
                <div class="flash-notice"><?php echo tc('You can upload a video or code.'); ?></div>
                <?php
                if ($model->video) {

                    ?>
                    <link href="//vjs.zencdn.net/5.8/video-js.min.css" rel="stylesheet">
                    <script src="//vjs.zencdn.net/5.8/video.min.js"></script>

                <?php
                $videoHtml = array();
                $count = 0;

                foreach ($model->video as $video) {
                if ($video->isFile()) {
                if ($video->isFileExists()) {

                ?>
                    <div class="video-file-block">
                        <video id="realty-video-<?php echo $video->id; ?>" class="video-js vjs-default-skin" controls
                               preload="auto" width="615" height="254">
                            <source src="<?php echo $video->getFileUrl(); ?>" type="video/mp4">
                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a web browser
                                that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                    video</a>
                            </p>
                        </video>
                    </div>

                    <?php
                    Yii::app()->clientScript->registerScript('player-' . $video->id . '', '
									var realtyPlayer' . $video->id . ' = videojs("realty-video-' . $video->id . '", { /* Options */ }, function() {
									});
								', CClientScript::POS_END);
                    /* echo '<div>'.CHtml::button(tc('Delete'), array(
                      'onclick' => 'document.location.href="'.Yii::app()->controller->createUrl('deletevideo', array('id' => $video->id, 'apId' => $model->id)).'";'
                      )).'</div>'; */

                    echo '<div><br/>';
                    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button',
                        'type' => 'danger',
                        'icon' => 'remove white',
                        'label' => tc('Delete'),
                        'htmlOptions' => array(
                            'onclick' => 'document.location.href="' . Yii::app()->controller->createUrl('deletevideo', array('id' => $video->id, 'apId' => $model->id)) . '";',
                        )
                    ));
                    echo '</div><br/>';
                }
                }
                    if ($video->isHtml()) {
                        echo '<div class="video-html-block" id="video-block-html-' . $count . '"></div>';

                        echo '<div>';
                        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button',
                            'type' => 'danger',
                            'icon' => 'remove white',
                            'label' => tc('Delete'),
                            'htmlOptions' => array(
                                'onclick' => 'document.location.href="' . Yii::app()->controller->createUrl('deletevideo', array('id' => $video->id, 'apId' => $model->id)) . '";',
                            )
                        ));
                        echo '</div><br/>';

                        $videoHtml[$count] = CHtml::decode($video->video_html);
                        $count++;
                    }
                }

                    $script = '';
                    if ($videoHtml) {
                        foreach ($videoHtml as $key => $value) {
                            if (!$value) {
                                $value = '';
                            }
                            $script .= '$("#video-block-html-' . $key . '").html("' . CJavaScript::quote($value) . '");';
                        }
                    }
                    if ($script) {
                        Yii::app()->clientScript->registerScript('chrome-xss-alert-preventer', $script, CClientScript::POS_READY);
                    }
                }

                ?>

                <?php
                if ($model->video) {
                    /* echo '<div>'.CHtml::button(tc('Add'), array(
                      'onclick' => '$(".add-video").toggle();',
                      )).'</div><br/>'; */

                    echo '<div>';
                    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button',
                        'type' => 'success',
                        'icon' => 'plus white',
                        'label' => tc('Add'),
                        'htmlOptions' => array(
                            'onclick' => '$(".add-video").toggle();',
                        )
                    ));
                    echo '</div><br/>';

                    Yii::app()->clientScript->registerScript('hide-add', '
						$(".add-video").hide();
					', CClientScript::POS_READY);
                }

                ?>

                <div class="add-video">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'video_html'); ?>
                        <?php echo $form->textArea($model, 'video_html', array('class' => 'form-control width500 height100')); ?>
                        <br/>
                        <?php echo $form->error($model, 'video_html'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'video_file'); ?>
                        <?php echo $form->fileField($model, 'video_file'); ?>
                        <div class="padding-bottom10">
                        <span class="label label-info">
                            <?php echo Yii::t('module_apartments', 'Supported file: {supportExt}.', array('{supportExt}' => $supportvideoext)); ?>
                        </span>
                            <br/>
                            <span class="label label-info">
                            <?php echo Yii::t('module_apartments', 'videoMaxSite: {size}.', array('{size}' => formatBytes($supportvideomaxsize))); ?>
                        </span>
                        </div>
                        <?php echo $form->error($model, 'video_file'); ?>
                    </div>
                </div>
            </div>

        <?php
        $this->renderPartial('__form', array(
            'form' => $form,
            'model' => $model,
            'seasonalPricesModel' => $seasonalPricesModel,
        ));

        ?>

        <?php $this->endWidget(); ?><!-- form -->
    </div>

<?php
if (issetModule('paidservices')) { // test
    echo '<div class="well current-ad-paid-services">';
    echo '<h4>' . tc('Current paid services') . '</h4>';
    echo HApartment::getPaidHtml($model, true, true);
    echo '</div>';
}

if (issetModule('seo') && !$model->isNewRecord && $activeOriginal != Apartment::STATUS_DRAFT) {
    $this->widget('application.modules.seo.components.SeoWidget', array(
        'model' => $model,
    ));
}

?>

<?php
// reInit google map (for preventing incorrect work in hidden tab)
Yii::app()->clientScript->registerScript('gmap-init', '
		var useYandexMap = ' . param('useYandexMap', 1) . ';
		var useGoogleMap = ' . param('useGoogleMap', 1) . ';
		var useOSMap = ' . param('useOSMMap', 1) . ';

		var lang = "' . Yii::app()->language . '";

		var address = "";

        function addAddressString(string){
            if(typeof string == "undefined" || string.length == 0){
                return "";
            }

            var sep = address.length > 0 ? ", " : "";

            return sep + string;
        }

		function reInitMap(){
		    address = "";

            if($("#Apartment_customCity").is(":visible") && $("#Apartment_customCity").length){
                address += addAddressString($("#ap_country option:selected").html());
                address += addAddressString($("#Apartment_customCity").val());
            }
            else {
                if($("#Apartment_city_id").length){
                    address += addAddressString($("#Apartment_city_id option:selected").html());
                } else {
                    address += addAddressString($("#ap_country option:selected").html());
                    address += addAddressString($("#ap_city option:selected").html());
                }
            }

			if ($("#id_Apartmentaddress_"+lang).val()) {
				address += addAddressString($("#id_Apartmentaddress_"+lang).val());
			}
			else if ($("input[name=\'Apartment[address_"+lang+"]\']").val()) {
				address += addAddressString($("input[name=\'Apartment[address_"+lang+"]\']").val());
			}

			$("#address_for_map").val(address);

			// place code to end of queue
			if(useGoogleMap){
				setTimeout(function(){
					var tmpGmapCenter = mapGMap.getCenter();

					google.maps.event.trigger($("#googleMap")[0], "resize");
					mapGMap.setCenter(tmpGmapCenter);
				}, 0);
			}

			if(useYandexMap){
				setTimeout(function(){
					ymaps.ready(function () {
						globalYMap.container.fitToViewport();
						globalYMap.setCenter(globalYMap.getCenter());
					});
				}, 0);
			}

			if(useOSMap){
				setTimeout(function(){
					L.Util.requestAnimFrame(mapOSMap.invalidateSize,mapOSMap,!1,mapOSMap._container);
				}, 0);
			}
		}
		
		function findByAddress(){
			var address = $("#address_for_map").val();
			if(!address){
				error("' . tc('Please enter address') . '");
				return false;
			}

			$.ajax({
				url: "' . Yii::app()->createUrl('/apartments/main/getGeo', array('id' => $model->id)) . '",
				data: { address: address },
				method: "get",
				dataType: "json",
				success: function(data) {
					if(data.status == "ok"){
						var newAddressLat = data.lat;
						var newAddressLng = data.lng;
						if(useGoogleMap && typeof markersGMap[' . $model->id . '] !== "undefined" && typeof mapGMap !== "undefined"){
							var latLng = new google.maps.LatLng(newAddressLat, newAddressLng);
							markersGMap[' . $model->id . '].setPosition(latLng);
							mapGMap.setCenter(latLng);
						}
						if(useYandexMap && typeof placemark !== "undefined" && typeof globalYMap !== "undefined"){
							placemark.geometry.setCoordinates([newAddressLat, newAddressLng]);
							globalYMap.setCenter([newAddressLat, newAddressLng]);
						}
						if(useOSMap && typeof markersOSMap[' . $model->id . '] !== "undefined" && typeof mapOSMap !== "undefined"){
							var newLatLng = new L.LatLng(newAddressLat, newAddressLng);
							markersOSMap[' . $model->id . '].setLatLng(newLatLng);
							mapOSMap.setView(newLatLng);
						}
						message(data.msg);
					} else {
						error(data.msg);
					}
				}
			});

			return;
		}
		
		function removeMarker(){
			$.ajax({
				url: "' . Yii::app()->createUrl('/apartments/main/removeGeo', array('id' => $model->id)) . '",
				method: "get",
				dataType: "json",
				success: function(data) {
				    if(data.status == "ok"){
						if(useGoogleMap && typeof markersGMap[' . $model->id . '] !== "undefined" && typeof mapGMap !== "undefined"){
							markersGMap[' . $model->id . '].setMap(null);
						}
						if(useYandexMap && typeof placemark !== "undefined" && typeof globalYMap !== "undefined"){
							globalYMap.geoObjects.removeAll();
						}
						if(useOSMap && typeof markersOSMap[' . $model->id . '] !== "undefined" && typeof mapOSMap !== "undefined"){
						    mapOSMap.removeLayer(markersOSMap[' . $model->id . ']);
						}
						message(data.msg);
					} else {
						error(data.msg);
					}
				}
			});
			return;
		}

		function setMarker(lat, lng){
			$.ajax({
				type:"POST",
				url:"' . Yii::app()->controller->createUrl('savecoords', array('id' => $model->id)) . '",
				data:({lat: lat, lng: lng}),
				cache:false
			})
		}
	', CClientScript::POS_END);

Yii::app()->clientScript->registerScript('show-special', '
		//special-calendar
		if(!$("#Apartment_is_special_offer").is(":checked")){
			$(".special-calendar").hide();
		}
		$("#Apartment_is_special_offer").bind("change", function(){
			if($(this).is(":checked")){
				$(".special-calendar").show();
			} else {
				$(".special-calendar").hide();
			}
		});

		// price poa
		if($("#Apartment_is_price_poa").is(":checked")){
			$("#price_fields").hide();
		}
		$("#Apartment_is_price_poa").bind("change", function(){
			if($(this).is(":checked")){
				$("#price_fields").hide();
			} else {
				$("#price_fields").show();
			}
		});
	', CClientScript::POS_READY);

?>