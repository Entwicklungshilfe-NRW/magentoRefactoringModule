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
class Testing_Carrier_Model_Label_Gls_Business extends Testing_Carrier_Model_Label_Abstract
{
	public function __construct() {
		parent::__construct();
		$this->insertBusinessDefaults();
	}

	protected function insertBusinessDefaults(){
		//defaults fuellen, von Business, allgemeine defaults wurden bereits durch den konstruktor von parent gefüllt
	}
}