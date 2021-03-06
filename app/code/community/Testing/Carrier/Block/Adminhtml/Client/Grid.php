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
class Testing_Carrier_Block_Adminhtml_Client_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('ClientGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('glsbox/client')->getCollection());
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('glsbox')->__('ID'),
            'align'     => 'right',
            'index'     => 'id',
            'type'      => 'number',
        ));
		
        $this->addColumn('display_name', array(
            'header'    => Mage::helper('glsbox')->__('Versender Name'),
            'align'     => 'left',
            'index'     => 'display_name',
        ));		

        $this->addColumn('kundennummer', array(
            'header'    => Mage::helper('glsbox')->__('Kundennummer'),
            'align'     => 'left',
            'index'     => 'kundennummer',
        ));		

        $this->addColumn('customerid', array(
            'header'    => Mage::helper('glsbox')->__('Customer Id'),
            'align'     => 'left',
            'index'     => 'customerid',
        ));		
		
        $this->addColumn('contactid', array(
            'header'    => Mage::helper('glsbox')->__('Contact Id'),
            'align'     => 'left',
            'index'     => 'contactid',
        ));

        $this->addColumn('depotnummer', array(
            'header'    => Mage::helper('glsbox')->__('DepotNummer'),
            'align'     => 'left',
            'index'     => 'depotnummer',
        ));

        $this->addColumn('depotcode', array(
            'header'    => Mage::helper('glsbox')->__('DepotCode'),
            'align'     => 'left',
            'index'     => 'depotcode',
        ));			
		
        $this->addColumn('status', array(
            'header'    => Mage::helper('glsbox')->__('Status'),
            'index'     => 'status',
			'type'      => 'options',			
			'options'   => array(
				1 => 'Aktiv',
				0 => 'Inaktiv',
				),
        ));

        $this->addColumn('notes', array(
            'header'    => Mage::helper('glsbox')->__('Notizen'),
            'align'     => 'left',
            'index'     => 'notes',
        ));		

        Mage::dispatchEvent('glsbox_adminhtml_grid_prepare_columns', array('block'=>$this));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}