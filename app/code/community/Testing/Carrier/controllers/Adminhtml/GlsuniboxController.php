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
class Testing_Carrier_Adminhtml_GlsuniboxController extends Mage_Adminhtml_Controller_Action 
{
    public function printlabelAction()
    {
        $labelId = $this->getRequest()->getParam('glslabel_id');
		(string)$paketnummer = $this->getRequest()->getParam('paketnummer');
        if (isset($labelId)) {
				$tagdata = $this->_initGlsLabel($labelId);
				if ($tagdata == false) {$this->_redirect('*/*/history');}
                if (!isset($pdf)){
                    $pdf = Mage::getModel('glsbox/pdf_label')->createLabel($tagdata);
                }
			$this->_saveToDiskIfPossible($pdf,$paketnummer);
            $this->_prepareDownloadResponse(str_replace(' ','',Mage::getStoreConfig('glsbox/labels/file_name_prefix').$paketnummer), $pdf->render(), 'application/pdf');
        } else {
            $this->_redirect('*/*/history');
        }
    }

    public function deletelabelAction()
    {
        $labelId = $this->getRequest()->getParam('glslabel_id');
		$response_msg = $this->_initGlsLabelDelete($labelId);
		
		$this->loadLayout();
        if (is_String($response_msg)) {
					$response = array(
						'error'     => true,
						'message'   => $response_msg,
					);            
        } else {
            $response = $this->getLayout()->getBlock('Testing_Carrier')->toHtml();
        }
		$this->getResponse()->setBody($response);
	}

    protected function _prepareDownloadResponse($fileName, $content, $contentType = 'application/octet-stream', $contentLength = null)
    {
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true)
            ->setHeader('Content-Length', is_null($contentLength) ? strlen($content) : $contentLength)
            ->setHeader('Content-Disposition', 'attachment; filename='. $fileName)
            ->setHeader('Last-Modified', date('r'));
        if (!is_null($content)) {
            $this->getResponse()->setBody($content);
        }
        return $this;
    }
	
	protected function _saveToDiskIfPossible($pdf,$name)
	{
		if(Mage::helper('glsbox')->getSaveToDiskEnabled()){
			$path = Mage::helper('glsbox')->getFileDestination();

			if ( Mage::getStoreConfig('glsbox/labels/file_name_prefix', Mage::app()->getStore()->getId()) != "" ) 
				{ $prefix = Mage::getStoreConfig('glsbox/labels/file_name_prefix', Mage::app()->getStore()->getId()); }
			else
				{ $prefix = ''; }
			$name = str_replace(' ', '', $prefix . $name . '.pdf');
			
			if(!is_dir(str_replace('//','/',$path))){
				mkdir(str_replace('//','/',$path), 0777, true);
				// .htaccess erstellen
$htaccess = 'Order deny,allow
Deny from all';
				$handle = fopen($path.'.htaccess', 'w');
				fwrite($handle, $htaccess);
				fclose($handle); 
				
				$pdf->save($path.$name);
			} else {
				$pdf->save($path.$name);
			}
		}
	}
	
	public function submitshipmentAction()
	{
        try {
            $service = $this->getRequest()->getPost('glsservice');
			(int)$weight = str_replace (',', '.', $this->getRequest()->getPost('weight'));
			$shipfromID = $this->getRequest()->getPost('shipfrom');
			$shipmentID = $this->getRequest()->getParam('shipment_id');
			$notiz = $this->getRequest()->getPost('notiz');
			$extra['frankatur'] = $this->getRequest()->getPost('frankatur');
			$extra['expressart'] = $this->getRequest()->getPost('expressart');
			$extra['alternativzustellung'] = $this->getRequest()->getPost('alternativzustellung');
			$extra['notiz'] = $notiz;
			
            if (empty($service)) {
                Mage::throwException($this->__('Sie muessen einen Service auswaehlen.'));
            }
            if (empty($weight) || $weight <= 0) {
                Mage::throwException($this->__('Das Paketgewicht muss größer als 0 kg sein.'));
            }
            if (empty($shipfromID)) {
                Mage::throwException($this->__('Sie müssen eine Versandadresse auswählen.'));
            }			
            if (empty($shipmentID)) {
                Mage::throwException($this->__('Keine Shipment Id gegeben.'));
            }	
			$shipment = $this->_initShipment($shipmentID);
			$shipfrom = $this->_initShipfrom($shipfromID);
			
            if ($shipment && $shipfrom) {

                $this->loadLayout();
				$response_gls = $this->_initGlsService($service,$shipment,$weight,$shipfrom,$notiz,$extra);
				if (is_String($response_gls)) {
					$response = array(
						'error'     => true,
						'message'   => $response_gls,
					);
				} else {
					$response = $this->getLayout()->getBlock('Testing_Carrier')->toHtml();
					$this->_insertTracking($shipment,$response_gls,$notiz);
				}
				
				} else {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Konnte Shipment oder Gls Mandanten nicht initialisieren.'),
                );
            }
        } catch (Mage_Core_Exception $e) {
            $response = array(
                'error'     => true,
                'message'   => $e->getMessage(),
            );
        } catch (Exception $e) {
            $response = array(
                'error'     => true,
                'message'   => $this->__('Ein unerwarteter Fehler ist aufgetreten.'),
            );
        }
        if (is_array($response)) {
            $response = Mage::helper('core')->jsonEncode($response);
        }
        $this->getResponse()->setBody($response);
	
	}
	
	protected function _initGlsService($service,$shipment,$weight,$shipfrom,$notiz,$extra){
		$submit = Mage::getModel('glsbox/unibox_parser');
		$gls = $submit->create($service,$shipment,$weight,$shipfrom,$notiz,$extra);
		return $gls;
	}

	protected function _initGlsLabel($id){
		$model = Mage::getModel('glsbox/unibox_parser');
		$data = $model->preparePrint($id);
		return $data;
	}
		
	protected function _initGlsLabelDelete($id){
		$model = Mage::getModel('glsbox/unibox_parser');
		$data = $model->prepareDelete($id);
		return $data;
	}

	protected function _insertTracking($shipment,$gls_return,$notiz){
		if (Mage::helper('glsbox')->getAutoInsertTracking() == true) {  
			$paketnummer = $gls_return->getItemsByColumnValue('tag', '8916'); $paketnummer = $paketnummer[0]->getValue();
			if ($notiz == "") { $notiz = 'GLS'; }
			$arrTracking = array(
				'carrier_code' => 'gls',
				'title' => $notiz,
				'number' => $paketnummer,
			);			
			$track = Mage::getModel('sales/order_shipment_track')->addData($arrTracking);
			$shipment->addTrack($track)->save();
		}
	}

    private function _initShipment($id)
    {
        $shipment = false;
        $shipmentId = $id;
        if ($shipmentId) {
            $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
        }
        return $shipment;
    }
	
    private function _initShipfrom($id)
    {
		$client = false;
        if ($id) {
			$client = Mage::getModel('glsbox/client')->getCollection()->addFieldToFilter('status', '1')->addFieldToFilter('id', $id)->getFirstItem();
        }
        return $client;
    }
}