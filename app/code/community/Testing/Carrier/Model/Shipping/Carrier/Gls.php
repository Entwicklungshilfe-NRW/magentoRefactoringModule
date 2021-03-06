<?php
/**
 * Testing_Carrier extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Gls
 * @package    Testing_Carrier
 * @copyright  Copyright (c) 2012 webvisum GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   webvisum GmbH
 * @package    Testing_Carrier
 */
class Testing_Carrier_Model_Shipping_Carrier_Gls extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'gls';
    
    public function isActive()
    {
        return true;
    }
    
    public function isTrackingAvailable()
    {
        return true;
    }
    
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        return Mage::getModel('shipping/rate_result');
    }
    
    public function getAllowedMethods()
    {
        return array($this->_code => 'gls');
    }
}