<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.38.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

//Yii::import('zii.widgets.grid.CDataColumn');

Yii::import('bootstrap.widgets.BsDataColumn');

class CustomDataColumn extends BsDataColumn
{

    /**
     * @var boolean whether the htmlOptions values should be evaluated.
     */
    public $evaluateHtmlOptions = false;

    /**
     * Renders a data cell.
     * @param integer $row the row number (zero-based)
     * Overrides the method 'renderDataCell()' of the abstract class CGridColumn
     */
    public function renderDataCell($row)
    {
        $data = $this->grid->dataProvider->data[$row];
        $options = $this->htmlOptions;

        if (is_array($this->evaluateHtmlOptions) && !empty($this->evaluateHtmlOptions)) {
            foreach ($this->htmlOptions as $key => $value) {
                if (in_array($key, $this->evaluateHtmlOptions)) {
                    $options[$key] = $this->evaluateExpression($value, array('row' => $row, 'data' => $data));
                }
            }
        }

        if ($this->cssClassExpression !== null) {
            $class = $this->evaluateExpression($this->cssClassExpression, array('row' => $row, 'data' => $data));
            if (!empty($class)) {
                if (isset($options['class']))
                    $options['class'] .= ' ' . $class;
                else
                    $options['class'] = $class;
            }
        }

        echo CHtml::openTag('td', $options);
        $this->renderDataCellContent($row, $data);
        echo '</td>';
    }
}
