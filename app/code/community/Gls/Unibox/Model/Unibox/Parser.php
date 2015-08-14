<?php
/**
 * Gls_Unibox extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Gls
 * @package    Gls_Unibox
 * @copyright  Copyright (c) 2012 webvisum GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   webvisum GmbH
 * @package    Gls_Unibox
 */
class Gls_Unibox_Model_Unibox_Parser {

	public function create($service,$shipment,$weight,$shipfrom,$notiz,$extra) {
		$sendString = $this->createGlsSubmitTag($service,$shipment,$weight,$shipfrom,$extra);
		$returnedtag = $this->sendViaSocket($sendString);
		if($returnedtag === false){ 
			return false; 
		} else 	{ 
			$tags = $this->parseIncomingTag($returnedtag);
			if(is_Array($tags)){
				if ($service == "business") {
					$glsService = Mage::getModel('glsbox/label_gls_business');
				}
				elseif ($service == "express") { 
					$glsService = Mage::getModel('glsbox/label_gls_express');
				}
		
				if($glsService != null) {
					//check if wrong $service is submitted
					/*	No Error => Save Info in Database.	*/
					$glsCustomerId = $shipfrom->getCustomerid();
					$glsContactId = $shipfrom->getContactid();
					$glsDepotCode = $shipfrom->getDepotcode();
					$glsKundennummer = $shipfrom->getKundennummer();
					$glsDepotnummer = $shipfrom->getDepotnummer();	
					$paketnummer = $this->nextAvailableLabelNumber($service,$shipfrom);
						
					$glsSave = Mage::getModel('glsbox/shipment');
					$glsSave->setService($service)
							->setShipmentId($shipment->getId())
							->setGlsMessage($returnedtag)
							->setWeight($weight)
							->setKundennummer($glsKundennummer)
							->setCustomerid($glsCustomerId)
							->setContactid($glsContactId)
							->setDepotcode($glsDepotCode)
							->setDepotnummer($glsDepotnummer)
							->setNotes($notiz)
							->setPaketnummer($paketnummer)
							->save();
					$glsService->importValues($tags);
					return $glsService->getData();			
				} else {
					return 'Bitte wählen Sie einen gültigen Versandservice von Gls';
				}
			} else { 
				return $tags;
				// Return, because it includes Errorcode TODO: Errorhandling and Message for different errortypes.
			}
		}
	}

	public function preparePrint($id) {
		$returnedtag = Mage::getModel('glsbox/shipment')->getCollection()->addFieldToFilter('id', $id)->getFirstItem()->getGlsMessage();
		if($returnedtag === false || $returnedtag == "") { 
			return false;
		} else 	{ 
			$tags = $this->parseIncomingTag($returnedtag);
			if(is_Array($tags)) {
				$service = Mage::getModel('glsbox/shipment')->getCollection()->addFieldToFilter('id', $id)->getFirstItem()->getService();
				if ($service == "business" || $service == "cash") {
					$glsService = Mage::getModel('glsbox/label_gls_business'); 
				}
				elseif ($service == "express") {
					$glsService = Mage::getModel('glsbox/label_gls_express'); 
				}
				if($glsService != null) { 
					$glsService->importValues($tags);
					return $glsService->getData();				
				} else { 
					return false;
				}
			} else { 
				return false;
			}		
		}
	}	
	
	public function prepareDelete($id) {
		$item = Mage::getModel('glsbox/shipment')->getCollection()->addFieldToFilter('id', $id)->getFirstItem();
		if($item === false) { 
			return "Konnte Gls Shipment Kollektion nicht holen."; 
		} else 	{ 
			$paketnummer = Mage::helper('glsbox')->getTagValue($item->getGlsMessage(),'400');
			$sendString ='\\\\\\\\\\GLS\\\\\\\\\\T000:'.$paketnummer.'|/////GLS/////';
			$returnedtag = $this->sendViaSocket($sendString);
			if($returnedtag === false) { 
					return "Socketkommunikation fehlgeschlagen"; 
			} else 	{ 
				$tags = $this->parseIncomingTag($returnedtag);
				if(is_Array($tags)){
					$item->setStorniert(1)->save();
					return true;
				} else {
					return $tags;
				}		
			}			
		}
	}	
	
	private function parseIncomingTag($returnedtag) {
		//$returnedtag = '\\\\\\\\\\GLS\\\\\\\\\\T010:|T050:Versandsoftwarename|T051:V 1.5.2|T8700:DE 550|T330:20354|T090:NOPRINT|T400:552502000716|T545:26.01.2009|T8904:001|T8905:001|T800:Absender|T805:12|T810:GLS IT Services GmbH|T811:Project Management|T820:GLS Germany Str. 1-7|T821:DE|T822:36286|T823:Neuenstein / Aua|T851:KD Nr.:|T852:10166|T853:ID No.:|T854:800018406|T859:Company|T860:GLS Germany GmbH & Co.OHG|T861:Depot 20|T863:Pinkertsweg 49|T864:Hamburg|T921:Machine Parts|T8914:27600ABCDE |T8915:2760000000|T080:4.67|T520:21012009|T510:ba|T500:DE 550|T560:DE03|T8797:IBOXCUS|T540:26.01.2009|T541:11:20|T100:DE|CTRNUM:276|CTRA2:DE|T202:|T210:|ARTNO:Standard|T530:16.20|ALTZIP:20354|FLOCCODE:DE 201|OWNER:5|TOURNO:1211|T320:1211|TOURTYPE:21102|SORT1:0|T310:0|T331:20354|T890:2001|ROUTENO:1006634|ROUTE1:R33|T110:R33|FLOCNO:629|T101: 201|T105:DE|T300:27620105|NDI:|T8970:A|T8971:A|T8975:12|T207:|T206:10001|T8980:AA|T8974:|T8916:552502000716|T8950:Tour|T8951:ZipCode|T8952:Your GLS Track ID|T8953:Product|T8954:Service Code|T8955:Delivery Address|T8956:Contact|T8958:Contact|T8957:Customer ID|T8959:Phone|T8960:Note|T8961:Parcel|T8962:Weight|T8963:Notification on damage which is not recognisable from outside had to be submitted to GLS|T8964:on the same Day of Delivery in writing. This Transport is based on GLS terms and conditions|T8913:ZFFX4HDZ|T8972:ZFFX4HDZ|T8902:ADE 550DE 20100000000002760000000ZFFX4HDZAA 0R33121120354 01620000000000000012 |T8903:A¬GLS Germany GmbH & Co.OHG¬Pinkertsweg 49¬Hamburg¬¬¬800018406¬|PRINTINFO:|PRINT1:|RESULT:E000:552502000716|T565:112059|PRINT0:xxGLSintermecpf4i.int01|/////GLS/////';
		//$returnedtag = iconv("ISO-8859-1" ,"UTF-8//TRANSLIT", $returnedtag);
		
		if( stripos($returnedtag ,'\\\\\\\\\\GLS\\\\\\\\\\' ) !== false && stripos($returnedtag ,'/////GLS/////' ) !== false ){
			$returnedtag = str_ireplace ( array('\\\\\\\\\\GLS\\\\\\\\\\','/////GLS/////') ,'', $returnedtag); 
		} else {
			return 'Fehler: Kein gültiger GLS Stream';
		}
		//Sonderzeichen der Datamatrix2 umwandeln in + für die Speicherung in der Datenbank
		$returnedtag = str_replace("¬", "+",$returnedtag);
		$returnedtag = explode('|',$returnedtag);
		$glsTags = array();
		foreach ($returnedtag as $item) {
			if (stripos($item,'T') === 0) {
				$tmp = explode(':',$item,2); $tmp[0] = str_ireplace('T','',$tmp[0]);
				if($tmp[1] != ''){
					$glsTags[$tmp[0]] = $tmp[1] ; 
				}
			}elseif (stripos($item,'RESULT') === 0 && stripos($item,'E000') === false ) { 
				return 'Fehler - Rückgabewert der Unibox : '.$item;
			}
			$tmp = null;
		}
		return $glsTags;
	}
	
	private function sendViaSocket($broadcast_string){
		//$broadcast_string = '\\\\\\\\\\GLS\\\\\\\\\\T090:NOPRINT,NOSAVE|T050:Versandsoftwarename|T051:V 1.5.2|T100:DE|T8700: DE 550|T330:20354|T400:552502000723|T530:16,2|T545:26.01.2009|T8904:001|T8905:001|T800:Absender|T805:12|T810:GLS IT Services GmbH|T811:Project Management|T820:GLS-Germany-Str. 1-7|T821:DE|T822:36286|T823:Neuenstein / Aua|T851:KD-Nr.:|T852:10166|T853:ID-No.:|T854:800018406|T859:Company|T860:GLS Germany GmbH & Co.OHG|T861:Depot 20|T863:Pinkertsweg 49|T864:Hamburg|T921:Machine Parts|T8914:27600ABCDE| T8915:2760000000|/////GLS/////';
		/* Port for socket. */
		$service_port = (int)Mage::getStoreConfig('glsbox/account/glsport'); //Test : 3030;
		/* IP for Socket. */
		$address = Mage::getStoreConfig('glsbox/account/glsip'); //Test : "217.7.25.136";
		/* Create TCP/IP Socket. */
		
		if(!filter_var($address, FILTER_VALIDATE_IP) || $service_port <= 0) { 
			return false;
		}
		
		if( ($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0 ){
			#error hier...
			#die("socket_create() failed, reason: ".socket_strerror($this->master));
		}
		#dann kann das hier weg
		if ($socket === false) {
			return false; //Fehler : socket_strerror(socket_last_error());
		}

		socket_set_option( $socket, SOL_SOCKET, SO_BROADCAST, 1);
		socket_set_option( $socket, SOL_SOCKET, SO_SNDTIMEO, array( "sec"=>1, "usec"=>0 ));
		socket_set_option( $socket, SOL_SOCKET, SO_RCVTIMEO, array( "sec"=>6, "usec"=>0 ));

		$result = socket_connect($socket, $address, $service_port);
		if ($result === false) {
			return false; //Fehler : socket_strerror(socket_last_error($socket));
		}

		$in = $broadcast_string;
		$out = '';

		$written = socket_send($socket, $in, strlen($in),MSG_DONTROUTE);
		//socket_shutdown($socket, 1);

		$buf = '';

		while ($out = socket_read($socket, 2048*4)) {
			$buf .= $out;
		}
		socket_close($socket);
		return $buf;
	}
	
	private function createGlsSubmitTag($service,$shipment,$weight,$shipfrom,$extra){
		$neuePaketnummer = $this->generateNewGlsLabelNumber($service,$shipfrom);
		$shipmentIncId = $shipment->getIncrementId();
		
		$glsCustomerId = $shipfrom->getCustomerid();
		$glsContactId = $shipfrom->getContactid();
		$glsDepotCode = $shipfrom->getDepotcode();
		$kundennummer = $shipfrom->getKundennummer(); 
		
		$versenderName1 = Mage::getStoreConfig('glsbox/versender/name1');
		$versenderName2 = Mage::getStoreConfig('glsbox/versender/name2');
		$versenderStrasse = Mage::getStoreConfig('glsbox/versender/strasse');
		$versenderLandeskennzeichen = Mage::getStoreConfig('glsbox/versender/landkennzeichen');
		$versenderPlz = Mage::getStoreConfig('glsbox/versender/plz');
		$versenderOrt = Mage::getStoreConfig('glsbox/versender/ort');
		
		//var_dump($shipment->getOrder()->getShippingAddress());die();
		$customerName = $shipment->getOrder()->getShippingAddress()->getFirstname().' '.$shipment->getOrder()->getShippingAddress()->getLastname();
		$customerStrasse = $shipment->getOrder()->getShippingAddress()->getStreetFull();
		$customerOrt = $shipment->getOrder()->getShippingAddress()->getCity();
		$customerPlz = $shipment->getOrder()->getShippingAddress()->getPostcode();
		$customerCountryId = $shipment->getOrder()->getShippingAddress()->getCountryId();
		$paketNotiz = $extra['notiz'];
		
		$starttag = '\\\\\\\\\\GLS\\\\\\\\\\';
		$tags=array(
			//'T090' => 'NOPRINT,NOSAVE',																			//Parameter f. Sonderfunktionen
			'T090' => 'NOPRINT',																					//Parameter f. Sonderfunktionen
			'T050' => 'GlsUniboxMagentoModule',																		//Softwarename
			'T051' => 'V '.Mage::getConfig()->getModuleConfig("Gls_Unibox")->version.'.'.Mage::getVersion(),		//Softwareversion (Modulversion&Magentoversion bsp: 0.0.2.1.7.2)
			'T100' => $customerCountryId,
			'T8700' => $glsDepotCode,
		
			'T400' => $neuePaketnummer,																				//generierte Paketnummer
			'T530' => str_replace('.',',',$weight),																	//Gewicht
			'T545' => date('d.m.Y'),																				//Versanddatum
			'T8904' => '001',																						//Paket Nr.
			'T8905' => '001',																						//Paket maxNr.
			'T800' => 'Absender',
			'T805' => $kundennummer,
			'T810' => $versenderName1,
			'T811' => $versenderName2,
			'T820' => $versenderStrasse,
			'T821' => $versenderLandeskennzeichen,																	
			'T822' => $versenderPlz,
			'T823' => $versenderOrt,
			//'T851' => 'KD-Nr.:',
			//'T852' => 10166
			'T853' => 'Bestellnummer.:',
			'T854' => $shipmentIncId,
			'T860' => $customerName,
			//'T861' => Shippingaddress_name 2 falls gewünscht übergeben
			'T863' => $customerStrasse,
			'T864' => $customerOrt,
			'T330' => $customerPlz,
			//'T921' => Machine Parts
			'T8914' => $glsContactId,
			'T8915' => $glsCustomerId,
			'T920' => $paketNotiz,
		);
		$endtag = '/////GLS/////';	
		
		//Extrawurst für Schweiz
		//Frankatur
		if ($extra['frankatur'] != "0" ) {
			$tags['T210'] = $extra['frankatur'];
		}
		
		if ($service == "express" ) { 
			$tags['T200'] = $extra['expressart']; 
			$tags['T204'] = 'T';
			$tags['T750'] = 'EXPRESS-SERVICE';
			if ($extra['alternativzustellung'] == 'J') {
				$tags['T753'] = 'Alternativzustellung erlaubt: Ja';$tags['T762'] = 'J';
			}
			if ($extra['alternativzustellung'] == 'N') {
				$tags['T753'] = 'Alternativzustellung erlaubt: Nein';$tags['T762'] = 'N';
			}
		}
		
		$finalstring = '';
		foreach($tags as $t => $value){
			$finalstring .= $t.':'.$value.'|';
		}
	
		$finalstring = $starttag.$finalstring.$endtag;
	
		return $finalstring;
	}

	private function generateNewGlsLabelNumber($service, $client)
	{
		(string)$versandDepotnummer = $client->getDepotnummer();
		
		//$laufendePaketnummer = $this->nextAvailableLabelDevNumber();
		$laufendePaketnummer = $this->nextAvailableLabelNumber($service,$client);
		$laufendePaketnummer = $this->addCheckDigit($laufendePaketnummer);

		return $laufendePaketnummer;
	}

	private function nextAvailableLabelNumber($service,$client)
	{
		//Hole für customerId und service art letzte Nummer
		$_packetNumberStart = ($service == "express") ? (int)$client->getNumcircleExpressStart() : (int)$client->getNumcircleStandardStart();
		$_packetNumberEnd = ($service == "express") ? (int)$client->getNumcircleExpressEnd() : (int)$client->getNumcircleStandardEnd();
		$_packetNumber = (int)$_packetNumberStart+1;

		//Prüfe, ob Nummer+1 < numcircle service end
		//Wenn JA
		if($_packetNumber <= $_packetNumberEnd){
			//speicher Nummer+1 für customerId und service
			if($service == "express"){
				$client->setNumcircleExpressStart($_packetNumber);
			}
			if($service == "business"){
				$client->setNumcircleStandardStart($_packetNumber);
			}
			$client->save();
			//Wenn Nummer+1 == numcircle_service_end
			if($_packetNumber == $_packetNumberEnd){
				//Meldung ERFOLG, neuen Nummernkreis anfordern service client
				$this->_getSession()->addSuccess(Mage::helper('gls/unibox')->__('The shipment was processed, but your numcicle is expired by now!'));
			}
			//return Nummer+1
			return $_packetNumber;
		//Wenn NEIN ->
		//Wenn Nummer+1 > numcircle_service_end
		}elseif($_packetNumber > $_packetNumberEnd){
			//Meldung Fehler, neuen Nummernkreis für service client
			$this->_getSession()->addError(Mage::helper('gls/unibox')->__('Your numcicle is expired!'));	
		}
	}
	
	Public function addCheckDigit($number)
	{
		$_digitArray = str_split($number);
		$_digitArray = array_reverse($_digitArray);
		$sum = 0;
		foreach($_digitArray as $key => $value){
			if($key%2 == 0){
				$sum += 3*$value;
			}else{
				$sum += $value;
			}
		}
		$diff = (int)(ceil($sum/10)*10)-($sum+1);
		if($diff == 10){
			return 0;
		}
		return $number.$diff;
	}
	
	/* 
	private function nextAvailableLabelDevNumber()
	{
		(int)$current = Mage::getModel('glsbox/shipment')->getCollection()->getLastItem()->getPaketnummer(); //Wenn keine Einträge existieren, so wird aus false 0 werden
		$current++;
		$next = str_pad($current, 7 ,'0', STR_PAD_LEFT);
		return $next;
	}*/
}