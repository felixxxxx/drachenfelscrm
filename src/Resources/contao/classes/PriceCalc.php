<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2020 Leo Feyer
 *
 * @package   pluusdesign/CrmEasyanpassungen
 * @author    Benjamin Geiger
 * @license   Commercial
 * @copyright Benjamin Geiger 2020
 * @files     forms/PriceCalc.php
 * @version   1.00.00
 * @date      202007010000
 */

 namespace felixxxxx\CrmEasy\Resources\contao\classes;

/**
 * Class PriceCalc
 *

 *
 * @author Benjamin Geiger
 */
class PriceCalc {


    public function addZahl($zahl)
    {
        $zahl = $zahl + '5';

        return $zahl;
    }

    
    /**
     * Generiere Staffelpreis-Werte
     * @param object $objResult
     * @param int $fieldId
     * @return bool
     */
    public function generateGPPrice(object $objResult, int $fieldId) : bool
    {
        $fieldName = 'licenseGP'.$fieldId;
            
        /**
         * QuantityMin / QuantityMax
         */
        $quantityMin = $fieldName.'MinValue'; $quantityMax = $fieldName.'MaxValue';
        $strQuantityMin = 'quantity'.$fieldId.'Min'; $strQuantityMax = 'quantity'.$fieldId.'Max';
        if($objResult->$quantityMin > 0 && $objResult->$quantityMax > $objResult->$quantityMin)
        {    
            $this->$strQuantityMin = $objResult->$quantityMin;
            $this->$strQuantityMax = $objResult->$quantityMax;
        }elseif($objResult->$quantityMax > 0) {
            $this->$strQuantityMin = 0;
            $this->$strQuantityMax = $objResult->$quantityMax;
        }else{
            $this->$strQuantityMin = false;
            $this->$strQuantityMax = false;
        }
        
        $type = $fieldName.'Type'; $strType = 'quantity'.$fieldId.'Type';
        $this->$strType = $objResult->$type;
        
        /**
         * Unit / Price
         */
        $price = $fieldName.'Price'; $strPrice = 'price'.$fieldId;
        $unit = $fieldName.'Unit'; $strUnit = 'unit'.$fieldId;
        $this->$strPrice = $objResult->$price;
        $this->$strUnit = $objResult->$unit;
        
        return true;
    }
    

}

class_alias(PriceCalc::class, 'PriceCalc');