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
 * @category   Webvisum
 * @package    Testing_Carrier
 */
class Testing_Carrier_Block_Adminhtml_Sales_Order_Shipment_View_Form extends Mage_Adminhtml_Block_Sales_Order_Shipment_View_Form
{
	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}

    public function getLabelUrl($id) {
        return $this->getUrl('*/glsunibox/printlabel', array(
            'glslabel_id' => $id
        ));
    }
}