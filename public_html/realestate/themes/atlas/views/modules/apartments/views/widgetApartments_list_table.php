<?php

$dataProvider = new CActiveDataProvider('Apartment', array(
    'criteria' => $criteria,
    'pagination' => false,
));

$canShowAddress = isset($dataProvider->data[0]) ? $dataProvider->data[0]->canShowInView("address") : false;
?>
    <style>
        .grid-view table.items {
            border-collapse: collapse;
            width: 100%;
        }

        @media screen and (max-width: 800px) {
            #ap-view-table-list td:nth-of-type(1):before {
                content: "<?php echo CHtml::encode(tc('Main photo'));?>: ";
            }

            #ap-view-table-list td:nth-of-type(2):before {
                content: "<?php echo CHtml::encode(tt('Type', 'apartments'));?>: ";
            }

            #ap-view-table-list td:nth-of-type(3):before {
                content: "<?php echo CHtml::encode(tt('Apartment title', 'apartments'));?>: ";
            }
        <?php if ($canShowAddress): ?>
            #ap-view-table-list td:nth-of-type(4):before {
                content: "<?php echo CHtml::encode(tt('Address', 'apartments'));?>: ";
            }
        <?php endif; ?>
            #ap-view-table-list td:nth-of-type(<?php echo $canShowAddress ? 5 : 4; ?>):before {
                content: "<?php echo CHtml::encode(tt('Object type', 'apartments'));?>: ";
            }

            #ap-view-table-list td:nth-of-type(<?php echo $canShowAddress ? 6 : 5; ?>):before {
                content: "<?php echo CHtml::encode(tt('Square', 'apartments'));?>: ";
            }

            #ap-view-table-list td:nth-of-type(<?php echo $canShowAddress ? 7 : 6; ?>):before {
                content: "<?php echo CHtml::encode(tt('Price', 'apartments'));?>: ";
            }

            #ap-view-table-list td:nth-of-type(<?php echo $canShowAddress ? 8 : 7; ?>):before {
                content: "<?php echo CHtml::encode(tt('Floor', 'apartments'));?>: ";
            }
        }
    </style>

<?php
$this->widget('NoBootstrapGridView', array(
        'id' => 'ap-view-table-list',
        'afterAjaxUpdate' => 'function(){attachStickyTableHeader();}',
        'dataProvider' => $dataProvider,
        'rowCssClassExpression' => '$data->getRowCssClass()',
        'enablePagination' => false,
        'selectionChanged' => 'js:function(id) {
						$currentGrid = $("#"+id);
						$rows = $currentGrid.find(".items").children("tbody").children();
						$selKey = $.fn.yiiGridView.getSelection(id);

						if ($selKey.length > 0) {
							$.each($currentGrid.find(".keys").children("span"), function(i,el){
								if ($(this).text() == $selKey) {
									$(this).attr("data-rel", "selected");
								}
								else {
									$(this).removeAttr("data-rel");
								}
							});
						}

						$.each($currentGrid.find(".keys").children("span"), function(i,el){
							var attr = $(this).attr("data-rel");
							if (typeof attr !== "undefined" && attr !== false) {
								$currentGrid.find(".items").children("tbody").children("tr").eq(i).addClass("selected");
							}
							else {
								$currentGrid.find(".items").children("tbody").children("tr").eq(i).removeClass("selected");
							}
						});

						return false;
					}',
        'template' => '{items}{pager}',
        'columns' => array(
            array(
                'header' => '',
                'type' => 'raw',
                'value' => 'CHtml::link(Apartment::returnMainThumbForGrid($data), $data->getUrl(), array("title" => $data->getStrByLang("title")))',
                'htmlOptions' => array('class' => 'ap-view-table-photo'),
            ),
            array(
                'header' => tt('Type', 'apartments'),
                'value' => 'HApartment::getNameByType($data->type)',
                'htmlOptions' => array('class' => 'ap-view-table-type'),
            ),
            array(
                'header' => tt('Apartment title', 'apartments'),
                'type' => 'raw',
                'value' => 'CHtml::link($data->getTitle(), $data->url)',
                'htmlOptions' => array('class' => 'ap-view-table-title'),
            ),
            array(
                'header' => tt('Address', 'apartments'),
                'type' => 'raw',
                'value' => 'HApartment::getLocationString($data, ", ", true)',
                'visible' => $canShowAddress,
                'htmlOptions' => array('class' => 'ap-view-table-address'),
            ),
            array(
                'header' => tt('Object type', 'apartments'),
                'type' => 'raw',
                'value' => '$data->getObjType4table()',
                'htmlOptions' => array('class' => 'ap-view-table-obj-type'),
            ),
            array(
                'header' => tt('Square', 'apartments'),
                'type' => 'raw',
                'value' => '$data->getSquareString()',
                'htmlOptions' => array('class' => 'ap-view-table-square'),
            ),
            array(
                'header' => tt('Price', 'apartments'),
                'type' => 'raw',
                'value' => '$data->getPrettyPrice()',
                'htmlOptions' => array('class' => 'ap-view-table-price'),
            ),
            array(
                'header' => tt('Floor', 'apartments'),
                'type' => 'raw',
                'value' => '$data->floor == 0 ? tc("floors").":&nbsp;".$data->floor_total : $data->floor."/".$data->floor_total ;',
                'htmlOptions' => array('class' => 'ap-view-table-floor'),
            ),
        )
    )
);