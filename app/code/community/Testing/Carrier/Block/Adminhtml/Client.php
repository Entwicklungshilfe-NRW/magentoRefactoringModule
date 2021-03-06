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
class Testing_Carrier_Block_Adminhtml_Client extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'glsbox';
        $this->_controller = 'adminhtml_client';
        $this->_headerText = Mage::helper('glsbox')->__('Gls Mandanten Übersicht');
        $this->_addButtonLabel = Mage::helper('glsbox')->__('Neuen Mandanten Hinzufügen');
        parent::__construct();
    }
}