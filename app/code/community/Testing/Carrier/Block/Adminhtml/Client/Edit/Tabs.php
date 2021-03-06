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
class Testing_Carrier_Block_Adminhtml_Client_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('client_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('glsbox')->__('Tab'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('glsbox')->__('Daten'),
            'title'     => Mage::helper('glsbox')->__('Daten'),
            'content'   => $this->getLayout()->createBlock('glsbox/adminhtml_client_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}