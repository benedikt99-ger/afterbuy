<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Model;

/**
 * @eshopExtension
 *
 * This is an example for a module extension (chain extend) of
 * the shop user model.
 * NOTE: class must not be final.
 *
 * @mixin \OxidEsales\Eshop\Application\Model\Order
 */
class Order extends Order_parent
{
    
 /**
   * @extend finalizeOrder
   * 
   * @param oxBasket $oBasket
   * @param object $oUser
   * @param boolean $blRecalculatingOrder
   * @return boolean 
   */
  public function finalizeOrder( oxBasket $oBasket, $oUser, $blRecalculatingOrder = false )
  {
    $return = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

	// Nur nach Afterbuy übertragen, wenn noch nicht eingetragen in DB    
	if ($this->oxorder__oxtrackcode=="") $afterbuy__SendOrder2Afterbuy=false;
	// Nur nach Afterbuy übertragen, wenn E-Mail, Strasse,PLZ,Ort vorhanden!
	if ($this->oxorder__oxbillemail=="") $afterbuy__SendOrder2Afterbuy=true;
	if ($this->oxorder__oxbillstreet=="") $afterbuy__SendOrder2Afterbuy=true;
	if ($this->oxorder__oxbillzip=="") $afterbuy__SendOrder2Afterbuy=true;
	if ($this->oxorder__oxbillcity=="") $afterbuy__SendOrder2Afterbuy=true;
	
	// Nur nach Afterbuy übertragen, wenn finalizeOrder const ORDER_STATE_OK = 1 ??
	
	// $return loggen
	// OXPAYMENTTYPE
	$filename = $this->getConfig()->getConfigParam( 'sShopDir' ) . '/log/afterbuy_log.txt'; 	// write $response in File
	$content = date("Ymd H:i:s").";".$HTTP_SERVER_VARS['REMOTE_ADDR'].";return ".$return." ordernr ".$this->oxorder__oxordernr." billemail ".$this->oxorder__oxbillemail."\n";
	$file = fopen ($filename, "a");	
	if ($file) {		
		fwrite($file, $content);
	}
	fclose ($file);	
	
	if ($return==1) {
		if ($afterbuy__SendOrder2Afterbuy == false) {
			$this->sendOrder2Afterbuy();
		} else {
			$filename = $this->getConfig()->getConfigParam( 'sShopDir' ) . '/log/afterbuy_curl.txt';
			$content = date("Ymd H:i").";NICHT übertragen ".$this->getId()."\n";
			$file = fopen ($filename, "a");	
			if ($file) fwrite($file, $content);
			fclose ($file);
		}
	}
    return $return;
  }	

  public function sendOrder2Afterbuy()
  {
		//-----------------------------------------------------------------
		//  Afterbuy Mail
		$pID = $this->oxorder__oxid;
		$array_inseln = array(18565,25845,25846,25847,25849,25859,25863,25869,25929,25930,25931,25932,25933,25938,25939,25940,25941,25942,25946,25947,25948,25949,25952,25953,25954,25955,25961,25962,25963,25964,25965,25966,25967,25968,25969,25970,25980,25985,25986,25988,25989,25990,25992,25993,25994,25996,25997,25998,25999,26465,26474,26486,26548,26571,26579,26757,27498,83256);
		$Host = "https://api.afterbuy.de/afterbuy/ShopInterfaceUTF8.aspx";
		// $Host = "https://api.afterbuy.de/afterbuy/ShopInterface.aspx";		
		$my_array['Action'] = 'new';

		// $my_array['Partnerid'] = "2162";
		// $my_array['PartnerPass'] = "19gumb65";
		// $my_array['UserID'] = "matratze-marquardt";
		
		$my_array['PartnerToken'] = "8a7791f6-91e0-40ff-ad5e-fe760876a753";
		$my_array['AccountToken'] = "e47dfec8-e2c6-4dc3-834f-550e2c91d6a6";

		// Kunde  
		$my_array['Kbenutzername'] = $this->oxorder__oxbillfname.$this->oxorder__oxbilllname;
		$my_array['VID'] = $this->getId();
		$my_array['Kundenerkennung'] = 1;
		if ($this->oxorder__oxbillsal=="MR") 	$my_array['Kanrede'] = "Herr";
		if ($this->oxorder__oxbillsal=="MRS") 	$my_array['Kanrede'] = "Frau";	
		$my_array['KFirma'] = $this->oxorder__oxbillcompany;
		$my_array['KVorname'] = $this->oxorder__oxbillfname;
		$my_array['KNachname'] =  $this->oxorder__oxbilllname;  	
		$my_array['KStrasse'] = $this->oxorder__oxbillstreet." ".$this->oxorder__oxbillstreetnr;
		$my_array['KStrasse2'] = '';
		$my_array['KPLZ'] = $this->oxorder__oxbillzip;
		$my_array['KOrt'] = $this->oxorder__oxbillcity;
		$my_array['KTelefon'] =  $this->oxorder__oxbillfon;
		$comment='';
		if (strlen($this->oxorder__oxremark->value)>1 && $this->oxorder__oxremark->value!="Hier können Sie uns noch etwas mitteilen.") $comment = "Kommentar:".$this->oxorder__oxremark->value;
		if (strlen($this->oxorder__oxbilladdinfo->value)>1) $comment .= "Rechnungsadresse kommentar:".$this->oxorder__oxbilladdinfo->value;
		if (strlen($oUser->oxuser__oxmobfon->value)>1) {	// oxuser->OXMOBFON in Fax eintragen, wenn vorhanden
			$my_array['Kfax'] = $oUser->oxuser__oxmobfon->value;
		} else {
			$my_array['Kfax'] = $this->oxorder__oxbillfax;
		}
		$my_array['Kemail'] = $this->oxorder__oxbillemail;
		
         $oCountry = oxNew(\OxidEsales\Eshop\Application\Model\Country::class);
         $oCountry->loadInLang(\OxidEsales\Eshop\Core\Registry::getLang()->getObjectTplLanguage(),$this->oxorder__oxbillcountryid);
         $my_array['KLand'] = new \OxidEsales\Eshop\Core\Field($oCountry->oxcountry__oxisoalpha2->value);		// oxcountry__oxtitle
		// oxCountry oxisoalpha2 oxisoalpha3
		// $oCountry->oxcountry__oxisoalpha2->value
		if ($my_array['KLand']=="DE"){
			$my_array['Versandart'] = "Paketdienst";
			$my_array['NoVersandCalc'] = "1";
		} else {	
			$my_array['Versandart'] = "ausland";
// 	Gibt an, ob kein Versuch unternommen werden soll, die Versandkosten seitens Afterbuy zu ermitteln, sowie das Gewicht, die Versandgruppe und den CrossKatalog.
// 0 = Versandkosten-Berechnung seitens Afterbuy
// 1= keineVversandkosten-Berechnung seitens Afterbuy - übergebene Versandkosten bleiben erhalten			
			$my_array['NoVersandCalc'] = "1";
		}
		$my_array['Versandkosten'] = str_replace(".",",",$this->oxorder__oxdelcost->value);
		$my_array['ZFunktionsID'] = '1';
		$my_array['Zahlart'] = 'Vorkasse / Überweisung';
		//	Gibt an, wie ein vorhandenes Produkt in Afterbuy erkannt werden soll.
		//	0 = Afterbuy-ProduktID   1 = Afterbuy-Artikelnummer  2 = Afterbuy-externe Artikelnummer 13 = Hersteller EAN		
		$my_array['Artikelerkennung'] = 1;

// NoFeedback
// 0= Feedbackdatum setzen und KEINE automatische Erstkontaktmail versenden
// 1= KEIN Feedbackdatum setzen, aber automatische Erstkontaktmail versenden (Achtung: Kunde müsste Feedback durchlaufen wenn die Erstkontakt nicht angepasst wird!)
// 2= Feedbackdatum setzen und automatische Erstkontaktmail versenden (Achtung: Erstkontaktmail muss mit Variablen angepasst werden!)  		
		
		$my_array['NoFeedback'] = '2';
		
		
		// OXPAYMENTTYPE
		if ($this->oxorder__oxpaymenttype->value=="psamazonpayment") {
			$my_array['ZFunktionsID'] = '99';
			$my_array['SetPay'] = '1';
			$my_array['Zahlart'] = 'AmazonPayment';
			$my_array['NoFeedback'] = '0';		
		}		
		
		if ($this->oxorder__oxpaymenttype->value=="oscpaypal") {
			$my_array['ZFunktionsID'] = '5';
			$my_array['SetPay'] = '1';
			$my_array['Zahlart'] = 'PayPal';
			$my_array['NoFeedback'] = '0';		
		}
		if ($this->oxorder__oxpaymenttype->value=="oscpaypal_sepa") {
			$my_array['ZFunktionsID'] = '5';
			$my_array['SetPay'] = '1';
			$my_array['Zahlart'] = 'PayPal';
			$my_array['NoFeedback'] = '0';		
		}
		if ($this->oxorder__oxpaymenttype->value=="oscpaypal_acdc") {
			$my_array['ZFunktionsID'] = '5';
			$my_array['SetPay'] = '1';
			$my_array['Zahlart'] = 'PayPal';
			$my_array['NoFeedback'] = '0';		
		}		
		if ($this->oxorder__oxpaymenttype->value=="oscpaypal_express") {
			$my_array['ZFunktionsID'] = '5';
			$my_array['SetPay'] = '1';
			$my_array['Zahlart'] = 'PayPal';
			$my_array['NoFeedback'] = '0';		
		}			
		if ($this->oxorder__oxpaymenttype->value=="38850994e29a805403b1af506a0ac4a3") {
			$my_array['ZFunktionsID'] = '12';
			$my_array['Zahlart'] = 'Sofortüberweisung';
			$my_array['NoFeedback'] = '2';
		}
		if ($this->oxorder__oxpaymenttype->value=="oxidcashondel") {
			$my_array['Zahlart'] = 'Nachnahme';
			$my_array['ZahlartenAufschlag'] = '7,5'; // Hier komma anstatt Punkt!
			$my_array['ZFunktionsID'] = '4';
			$my_array['NoFeedback'] = '0';
		}
		if ($this->oxorder__oxpaymenttype->value=="paypinstallments") {
			$my_array['Zahlart'] = 'Ratenzahlung Powered by PayPal';
			$my_array['ZahlartenAufschlag'] = '0';
			$my_array['ZFunktionsID'] = '99';
			$my_array['SetPay'] = '1';
			$my_array['NoFeedback'] = '0';
		}		
		if ( $this->oxorder__oxdellname->value>"" && $this->oxorder__oxdelstreet->value>"" && $this->oxorder__oxdelcity->value>"" && $this->oxorder__oxdelzip->value>"" ) {
			// set delivery address
			$my_array['Lieferanschrift'] = 1;
			$my_array['KLFirma'] = $this->oxorder__oxdelcompany;
			$my_array['KLVorname'] = $this->oxorder__oxdelfname;
			$my_array['KLNachname'] = $this->oxorder__oxdellname;
			$my_array['KLStrasse'] = $this->oxorder__oxdelstreet." ".$this->oxorder__oxdelstreetnr;
			// $this->oxorder__oxdeladdinfo   = clone $oDelAdress->oxaddress__oxaddinfo;
			$my_array['KLOrt'] = $this->oxorder__oxdelcity->value;
			$my_array['KLPLZ'] = $this->oxorder__oxdelzip->value;
			$my_array['KLStrasse2'] = '';
			$oCountry = oxNew(\OxidEsales\Eshop\Application\Model\Country::class);
			$oCountry->loadInLang(\OxidEsales\Eshop\Core\Registry::getLang()->getObjectTplLanguage(),$this->oxorder__oxdelcountryid);
			$my_array['KLLand'] = new \OxidEsales\Eshop\Core\Field($oCountry->oxcountry__oxisoalpha2->value);		// oxcountry__oxtitle			
			// $my_array['KLLand'] = 'D';	
			if (strlen($this->oxorder__oxdeladdinfo->value)>1) $comment .= "Lieferadresse kommentar:".$this->oxorder__oxdeladdinfo->value;
		} else {
			$my_array['Lieferanschrift'] = 0;
		}
	
		// Warenkorb
		$oOrderArticles = $this->getOrderArticles();
		$my_array['PosAnz'] = count( $oOrderArticles );   // Anzahl der Positionen
		$cntArticle = 0;  // Anzahl der Artikel
		foreach ( $oOrderArticles as $oOrderArticle ) {
			$j++;
			$my_array['Artikelnr_'.$j] = $oOrderArticle->oxorderarticles__oxartnum->value;
			$my_array['AlternArtikelNr1_'.$j] = $oOrderArticle->oxorderarticles__oxartnum->value;
			$my_array['AlternArtikelNr2_'.$j] = 0;
			/*
			Längenkürzung : keine, Breitenkürzung : Keine, Fußhöhenkürzung : Keine || 70x200 cm
			Längenkürzung : cm genaue Längenkürzung +60,00 €, Breitenkürzung : cm genaue Breitenkürzung +90,00 €, Fußhöhenkürzung : cm genaue Fußhöhenkürzung +30,00 € || 90x200 cm

Längenkürzung : cm genaue Längenkürzung +60,00 €, Breitenkürzung : cm genaue Breitenkürzung +90,00 €, Fußhöhenkürzung : cm genaue Fußhöhenkürzung +30,00 € || 90x200 cm
a:1:{s:7:"details";s:17:"Bitte 34x70x189cm";}
			
			*/
			$oxtitle = $oOrderArticle->oxorderarticles__oxtitle->value;
			$oxtitle = str_ireplace("in Wunschhöhe 7 bis 35cm","",$oxtitle); // Wunschmaß
			$oxselvariant = $oOrderArticle->oxorderarticles__oxselvariant->value;
			// $oxselvariant = str_ireplace("cm genaue Längenkürzung","",$oxselvariant);
			// $oxselvariant = str_ireplace("cm genaue Breitenkürzung","",$oxselvariant);
			// $oxselvariant = str_ireplace("cm genaue Fußhöhenkürzung","",$oxselvariant);

			// oxselvariant:	200 | 70 | Wunschmaß 7-35cm Rahmen 4cm Gesamt 11-39cm
			// OXPERSPARAM:		a:1:{s:7:"details";s:38:"Wunschmaß 20cm Rahmen 4cm Gesamt 24cm";}
			$oxpersparam = unserialize($oOrderArticle->oxorderarticles__oxpersparam->value);
			$soxpersparam = implode(",",$oxpersparam);
			$pos = stripos($oxpersparam,"Wunsch");
			if ($pos !== false) {
				$soxpersparam = str_replace("details,","",$soxpersparam);
				$oxtitle .= " ".$soxpersparam;
				$comment .= " ".$soxpersparam;
			}
			$oxselvariant = str_ireplace("Wunschmaß 7-35cm Rahmen 4cm Gesamt 11-39cm","",$oxselvariant);
			
			
			$my_array['Artikelname_'.$j] = $oxtitle." ".$oxselvariant; // Variante		
			$my_array['ArtikelEpreis_'.$j] = str_replace(".",",",round($oOrderArticle->oxorderarticles__oxbprice->value,2)); 
			$my_array['ArtikelMwSt_'.$j] = str_replace(".",",",round($oOrderArticle->oxorderarticles__oxvat->value,2));
			
			$aPersParam = isset( $oOrderArticle->oxorderarticles__oxpersparam ) ? $oOrderArticle->getPersParams() : null;
			
			if (is_array($aPersParam)) $my_array['ArtikelLink_'.$j] = implode($aPersParam);
			else $my_array['ArtikelLink_'.$j] = '';
		
			$my_array['ArtikelMenge_'.$j] =  $oOrderArticle->oxorderarticles__oxamount->value;
			$cntArticle += $oOrderArticle->oxorderarticles__oxamount->value;
			// mpn suchen !
			// $oProduct = oxNew('oxarticle');
            // $oProduct->load($oOrderArticle->oxorderarticles__oxartid->value);
			
			// oxweight
			$my_array['ArtikelStammID_'.$j] = $oOrderArticle->oxorderarticles__oxartnum->value;
			if ($oOrderArticle->oxorderarticles__oxweight->value>0) $my_array['ArtikelGewicht_'.$j] = str_replace(".",",",$oOrderArticle->oxorderarticles__oxweight->value);
			else $my_array['ArtikelGewicht_'.$j] = '1';
			$my_array['Attribute_'.$j] = 0;
		}
		if (strlen($comment)>1) {
			$my_array['kommentarDa'] = 1; // 0 = Kunde hat keinen Kommentar hinterlassen 1 oder -1 = Kunde hat einen Kommentar hinterlassen 
			$my_array['Kommentar'] = $comment;	
		} else {
			$my_array['kommentarDa'] = 0; // 0 = Kunde hat keinen Kommentar hinterlassen 1 oder -1 = Kunde hat einen Kommentar hinterlassen 
	}
		// Gutschein  OXVOUCHERDISCOUNT
		if ($this->oxorder__oxvoucherdiscount->value > 0) {
			$j++;
			$my_array['Kommentar'] .= ' Gutschein &uuml;ber Summe: '.$this->oxorder__oxvoucherdiscount->value;
			$my_array['PosAnz'] = $my_array['PosAnz']+1;
			$my_array['Artikelnr_'.$j] = '9999999999';
			$my_array['AlternArtikelNr1_'.$j] = 0;
			$my_array['AlternArtikelNr2_'.$j] = 0;
			$my_array['Artikelname_'.$j] = "Gutschein";
			$my_array['ArtikelEpreis_'.$j] = str_replace(".",",",-$this->oxorder__oxvoucherdiscount->value); 
			$my_array['ArtikelMwSt_'.$j] = 19;
			$my_array['ArtikelLink_'.$j] = 'privat';
			$my_array['ArtikelMenge_'.$j] =  1;
			$my_array['ArtikelStammID_'.$j] = 0;
			$my_array['ArtikelGewicht_'.$j] = '0';
			$my_array['Attribute_'.$j] = 0;		
		}
		// ---------------------------------------------------------------------------
		// Nicht mehr nötig, da Inselzuschlag im Shop als zahlungsart hinterlegt
		// Korrekten Inselzuschlag  berechnen
		/*
		$inselzuschlag  = 4.90+$cntArticle*10;
		if (in_array($my_array['KPLZ'], $array_inseln) && $my_array['Lieferanschrift']==0) {
			$oDb = oxDb::getDb();
			$sQ = "UPDATE oxorder SET OXDELCOST = '$inselzuschlag' WHERE OXID =  '$pID'";
			$oDb->execute( $sQ );	
	
			$my_array['Versandkosten'] =  str_replace(".",",",$inselzuschlag);
		}
		if ((in_array($my_array['KLPLZ'], $array_inseln)) && ($my_array['Lieferanschrift']==1)) {
			$oDb = oxDb::getDb();
			$sQ = "UPDATE oxorder SET OXDELCOST = '$inselzuschlag' WHERE OXID =  '$pID'";
			$oDb->execute( $sQ );	
		
			$my_array['Versandkosten'] =  str_replace(".",",",$inselzuschlag);
		}
		*/


		//loop through the array and make a string 
		foreach ($my_array as $key1 => $value1) { 
		   $value1 = urlencode(stripslashes($value1)); 
		   $ContentLength .= "&$key1=$value1"; 
		} 
		// $ContentLength = "?".substr($ContentLength,1);  Nicht so!
		$filename = $this->getConfig()->getConfigParam( 'sShopDir' ) . '/log/afterbuy_curl.txt'; 	// write $response in File
		$content = date("Ymd H:i:s").";".$HTTP_SERVER_VARS['REMOTE_ADDR'].";".$ContentLength."\n";
		$file = fopen ($filename, "a");	
		if ($file) {		
			fwrite($file, $content);
		}
		fclose ($file);
		

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Host); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDSIZE, strlen($ContentLength));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $ContentLength);
		$response = curl_exec($ch); 
		// $response.="cURL error number:" .curl_errno($ch);
		if (curl_error($ch)) $response.=" cURL error:" . curl_error($ch); 
		curl_close ($ch);  
		$dom = new DOMDocument('1.0');
		$dom->loadXML($response);
		if (!$dom) {
		  $response = 'Fehler beim Parsen des Dokuments';
		} else {
			$root = simplexml_import_dom($dom);
			$node = $root->data[0]->UID;
			$uid = str_replace("{","",$node);
			$uid = str_replace("}","",$uid);
			$KundenNr = $root->data[0]->KundenNr;
			$AID = $root->data[0]->AID;
	
			 $this->oxorder__oxtrackcode = $uid;
			 $this->oxorder__oxbillnr = $KundenNr;
			 $oDb = oxDb::getDb();
			 $sQ = "UPDATE oxorder SET OXTRACKCODE = '$uid', OXBILLNR='$AID' WHERE OXID =  '$pID'";
			 $oDb->execute( $sQ );	
		}		
		$filename = $this->getConfig()->getConfigParam( 'sShopDir' ) . '/log/afterbuy_response.txt';	
		$content = date("Ymd H:i:s").";".$HTTP_SERVER_VARS['REMOTE_ADDR'].";".$response."\n";
		$file = fopen ($filename, "a");	
		if ($file) {		
			fwrite($file, $content);
		}
		fclose ($file);		
		
	}
}
