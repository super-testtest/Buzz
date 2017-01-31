<?php
/**
 * USA ePay Magento Plugin.
 * v1.1.7 - December 19th, 2014
 *
 * Copyright (c) 2010 USAePay
 * All rights reserved.
 *
 * The code in this file based on the USA ePay PHP Library.
 *     v1.6.5 - Nov 11, 2009
 *     Copyright (c) 2001-2009 USAePay
 *     http://help.usaepay.com/developer/phplibrary
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *     - Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     - Redistributions in binary form must reproduce the above
 *       copyright notice, this list of conditions and the following
 *       disclaimer in the documentation and/or other materials
 *       provided with the distribution.
 *     - Neither the name of the USAePay nor the names of its
 *       contributors may be used to endorse or promote products
 *       derived from this software without specific prior written
 *       permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category    Mage
 * @package     Mage_Usaepay_Model
 * @copyright   Copyright (c) 2010 USAePay  (www.usaepay.com)
 * @license     http://opensource.org/licenses/bsd-license.php  BSD License
 */


// USA ePay PHP Library.
//	v1.6.5 - Nov 11, 2008
//
// 	Copyright (c) 2001-2008 USAePay
//	For assistance please contact devsupport@usaepay.com
//

define("USAEPAY_VERSION", "1.1.7");


/**
 * USAePay Transaction Class
 *
 */
class Mage_Usaepay_Model_TranApi {

	// Required for all transactions
	public $key;			// Source key
	public $pin;			// Source pin (optional)
	public $amount;		// the entire amount that will be charged to the customers card
							// (including tax, shipping, etc)
	public $invoice;		// invoice number.  must be unique.  limited to 10 digits.  use orderid if you need longer.

	// Required for Commercial Card support
	public $ponum;			// Purchase Order Number
	public $tax;			// Tax
	public $nontaxable;	// Order is non taxable
	public $proccessingurl;
	public $proccessingnum;
	// Amount details (optional)
	public $tip; 			// Tip
	public $shipping;		// Shipping charge
	public $discount; 	// Discount amount (ie gift certificate or coupon code)
	public $subtotal; 	// if subtotal is set, then
							// subtotal + tip + shipping - discount + tax must equal amount
							// or the transaction will be declined.  If subtotal is left blank
							// then it will be ignored
	public $currency;		// Currency of $amount

	// Required Fields for Card Not Present transacitons (Ecommerce)
	public $card;			// card number, no dashes, no spaces
	public $exp;			// expiration date 4 digits no /
	public $cardholder; 	// name of card holder
	public $street;		// street address
	public $zip;			// zip code

	// Fields for Card Present (POS)
	public $magstripe;  	// mag stripe data.  can be either Track 1, Track2  or  Both  (Required if card,exp,cardholder,street and zip aren't filled in)
	public $cardpresent;   // Must be set to true if processing a card present transaction  (Default is false)
	public $termtype;  	// The type of terminal being used:  Optons are  POS - cash register, StandAlone - self service terminal,  Unattended - ie gas pump, Unkown  (Default:  Unknown)
	public $magsupport;  	// Support for mag stripe reader:   yes, no, contactless, unknown  (default is unknown unless magstripe has been sent)
	public $contactless;  	// Magstripe was read with contactless reader:  yes, no  (default is no)
	public $dukpt;			// DUK/PT for PIN Debit
	public $signature;     // Signature Capture data

	// fields required for check transactions
	public $account;		// bank account number
	public $routing;		// bank routing number
	public $ssn;			// social security number
	public $dlnum;			// drivers license number (required if not using ssn)
	public $dlstate;		// drivers license issuing state
	public $checknum;		// Check Number
	public $accounttype;       // Checking or Savings
	public $checkformat;	// Override default check record format
	public $checkimage_front;    // Check front
	public $checkimage_back;		// Check back


	// Fields required for Secure Vault Payments (Direct Pay)
	public $svpbank;		// ID of cardholders bank
	public $svpreturnurl;	// URL that the bank should return the user to when tran is completed
	public $svpcancelurl; 	// URL that the bank should return the user if they cancel



	// Option parameters
	public $origauthcode;	// required if running postauth transaction.
	public $command;		// type of command to run; Possible values are:
						// sale, credit, void, preauth, postauth, check and checkcredit.
						// Default is sale.
	public $orderid;		// Unique order identifier.  This field can be used to reference
						// the order for which this transaction corresponds to. This field
						// can contain up to 64 characters and should be used instead of
						// UMinvoice when orderids longer that 10 digits are needed.
	public $custid;   // Alpha-numeric id that uniquely identifies the customer.
	public $description;	// description of charge
	public $cvv2;			// cvv2 code
	public $custemail;		// customers email address
	public $custreceipt;	// send customer a receipt
	public $custreceipt_template;	// select receipt template
	public $ignoreduplicate; // prevent the system from detecting and folding duplicates
	public $ip;			// ip address of remote host
	public $testmode;		// test transaction but don't process it
	public $usesandbox;    // use sandbox server instead of production
	public $timeout;       // transaction timeout.  defaults to 45 seconds
	public $gatewayurl;   	// url for the gateway
	public $proxyurl;		// proxy server to use (if required by network)
	public $ignoresslcerterrors;  // Bypasses ssl certificate errors.  It is highly recommended that you do not use this option.  Fix your openssl installation instead!
	public $cabundle;      // manually specify location of root ca bundle (useful of root ca is not in default location)
	public $transport;     // manually select transport to use (curl or stream), by default the library will auto select based on what is available

	// Card Authorization - Verified By Visa and Mastercard SecureCode
	public $cardauth;    	// enable card authentication
	public $pares; 		//

	// Third Party Card Authorization
	public $xid;
	public $cavv;
	public $eci;

	// Recurring Billings
	public $recurring;		//  Save transaction as a recurring transaction:  yes/no
	public $schedule;		//  How often to run transaction: daily, weekly, biweekly, monthly, bimonthly, quarterly, annually.  Default is monthly.
	public $numleft; 		//  The number of times to run. Either a number or * for unlimited.  Default is unlimited.
	public $start;			//  When to start the schedule.  Default is tomorrow.  Must be in YYYYMMDD  format.
	public $end;			//  When to stop running transactions. Default is to run forever.  If both end and numleft are specified, transaction will stop when the ealiest condition is met.
	public $billamount;	//  Optional recurring billing amount.  If not specified, the amount field will be used for future recurring billing payments
	public $billtax;
	public $billsourcekey;

	// Billing Fields
	public $billfname;
	public $billlname;
	public $billcompany;
	public $billstreet;
	public $billstreet2;
	public $billcity;
	public $billstate;
	public $billzip;
	public $billcountry;
	public $billphone;
	public $email;
	public $fax;
	public $website;

	// Shipping Fields
	public $delivery;		// type of delivery method ('ship','pickup','download')
	public $shipfname;
	public $shiplname;
	public $shipcompany;
	public $shipstreet;
	public $shipstreet2;
	public $shipcity;
	public $shipstate;
	public $shipzip;
	public $shipcountry;
	public $shipphone;

	// Custom Fields
	public $custom1;
	public $custom2;
	public $custom3;
	public $custom4;
	public $custom5;
	public $custom6;
	public $custom7;
	public $custom8;
	public $custom9;
	public $custom10;
	public $custom11;
	public $custom12;
	public $custom13;
	public $custom14;
	public $custom15;
	public $custom16;
	public $custom17;
	public $custom18;
	public $custom19;
	public $custom20;


	// Line items  (see addLine)
	public $lineitems;


	public $comments; // Additional transaction details or comments (free form text field supports up to 65,000 chars)

	public $software; // Allows developers to identify their application to the gateway (for troubleshooting purposes)


	// response fields
	public $rawresult;		// raw result from gateway
	public $result;		// full result:  Approved, Declined, Error
	public $resultcode; 	// abreviated result code: A D E
	public $authcode;		// authorization code
	public $refnum;		// reference number
	public $batch;		// batch number
	public $avs_result;		// avs result
	public $avs_result_code;		// avs result
	public $avs;  					// obsolete avs result
	public $cvv2_result;		// cvv2 result
	public $cvv2_result_code;		// cvv2 result
	public $vpas_result_code;      // vpas result
	public $isduplicate;      // system identified transaction as a duplicate
	public $convertedamount;  // transaction amount after server has converted it to merchants currency
	public $convertedamountcurrency;  // merchants currency
	public $conversionrate;  // the conversion rate that was used
	public $custnum;  //  gateway assigned customer ref number for recurring billing

	// Cardinal Response Fields
	public $acsurl;	// card auth url
	public $pareq;		// card auth request
	public $cctransid; // cardinal transid


	// Errors Response Feilds
	public $error; 		// error message if result is an error
	public $errorcode; 	// numerical error code
	public $blank;			// blank response
	public $transporterror; 	// transport error

	// Extended Fraud Profiler Fields
	public $session; // session id
	// responses fields
	public $profilerScore;
	public $profilerResponse;
	public $profilerReason;

	function __construct()
	{
		// Set default values.
		$this->command="sale";
		$this->result="Error";
		$this->resultcode="E";
		$this->error="Transaction not processed yet.";
		$this->timeout=45;
		$this->cardpresent=false;
		$this->lineitems = array();
		if(isset($_SERVER['REMOTE_ADDR'])) $this->ip=$_SERVER['REMOTE_ADDR'];
		$this->software="USAePay PHP API v" . USAEPAY_VERSION;
	}

	protected function _log($message, $level = null)
	{
		Mage::log($message, $level, 'usaepay.log');
	}

	protected function _urlExists($url=NULL)
	{
		if($url == NULL) return false;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 45);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($httpcode>=200 && $httpcode<300){
			return true;
		} else {
			return false;
		}
	}

	protected function _getproccessingurl() {
		$urlArr = array('https://www.usaepay.com', 'https://www-01.usaepay.com', 'https://www-02.usaepay.com', 'https://www-03.usaepay.com');
		foreach ($urlArr as $v) {
			$test = $this->_urlExists($v.'/ping');
			if($test){
				$temp = $v;
				break;
			}
		}
		return $temp;
	}

	protected function _getGatewayBaseUrl()
	{
		if($this->usesandbox){
			$url =	'https://sandbox.usaepay.com';
		}
		else {
			$url = $this->_getproccessingurl();
		}
		//$this->_log("urlExists\nURL: $url\n------------------------\nRequestURL:\n".$url."\n------------------------\n");
		return $url;
	}

	/**
	 * Add a line item to the transaction
	 *
	 * @param string $sku
	 * @param string $name
	 * @param string $description
	 * @param double $cost
	 * @param string $taxable
	 * @param int $qty
	 */
	function addLine($sku, $name, $description, $cost, $qty, $taxAmount)
	{
		$this->lineitems[] = array(
				'sku' => $sku,
				'name' => $name,
				'description' => $description,
				'cost' => $cost,
				'taxable' => ($taxAmount > 0) ? 'Y' : 'N',
				'qty' => $qty
			);
	}

	function clearLines()
	{
		$this->lineitems=array();
	}


	/**
	 * Verify that all required data has been set
	 *
	 * @return string
	 */
	function CheckData()
	{
		if(!$this->key) return "Source Key is required";
		if(in_array(strtolower($this->command), array("quickcredit","quicksale","cc:capture", "cc:refund", "refund", "check:refund","capture", "creditvoid")))
		{
			if(!$this->refnum) return "Reference Number is required";
		}else if(in_array(strtolower($this->command), array("svp")))
		{
			if(!$this->svpbank) return "Bank ID is required";
			if(!$this->svpreturnurl) return "Return URL is required";
			if(!$this->svpcancelurl) return "Cancel URL is required";
		}  else {
			if(in_array(strtolower($this->command), array("check:sale","check:credit", "check", "checkcredit","reverseach") )) {
					if(!$this->account) return "Account Number is required";
					if(!$this->routing) return "Routing Number is required";
			} else {
				if(!$this->magstripe) {
					if(!$this->card) return "Credit Card Number is required ({$this->command})";
					if(!$this->exp) return "Expiration Date is required";
				}
			}
			$this->amount = preg_replace('/[^\d.]+/', '', $this->amount);
			if(!$this->amount) return "Amount is required";
			if(!$this->invoice && !$this->orderid) return "Invoice number or Order ID is required";
			if(!$this->magstripe) {
				//if(!$this->cardholder) return "Cardholder Name is required";
				//if(!$this->street) return "Street Address is required";
				//if(!$this->zip) return "Zipcode is required";
			}
		}
		return 0;
	}


	/**
	 * Send transaction to the USAePay Gateway and parse response
	 *
	 * @return boolean
	 */
	function Process()
	{
		if($this->command=='quicksale') return $this->ProcessQuickSale();
		if($this->command=='quickcredit') return $this->ProcessQuickCredit();

		// check that we have the needed data
		$tmp=$this->CheckData();
		if($tmp)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error=$tmp;
			$this->errorcode=10129;
			return false;
		}

		// format the data
		$data =
			array("UMkey" => $this->key,
			"UMcommand" => $this->command,
			"UMauthCode" => $this->origauthcode,
			"UMcard" => $this->card,
			"UMexpir" => $this->exp,
			"UMbillamount" => $this->billamount,
			"UMamount" => $this->amount,
			"UMinvoice" => $this->invoice,
			"UMorderid" => $this->orderid,
			"UMponum" => $this->ponum,
			"UMtax" => $this->tax,
			"UMnontaxable" => ($this->nontaxable?'Y':''),
			"UMtip" => $this->tip,
			"UMshipping" => $this->shipping,
			"UMdiscount" => $this->discount,
			"UMsubtotal" => $this->subtotal,
			"UMcurrency" => $this->currency,
			"UMname" => $this->cardholder,
			"UMstreet" => $this->street,
			"UMzip" => $this->zip,
			"UMdescription" => $this->description,
			"UMcomments" => $this->comments,
			"UMcvv2" => $this->cvv2,
			"UMip" => $this->ip,
			"UMtestmode" => $this->testmode,
			"UMcustemail" => $this->custemail,
			"UMcustreceipt" => ($this->custreceipt?'Yes':'No'),
			"UMcustreceiptname" => $this->custreceipt_template,
			"UMrouting" => $this->routing,
			"UMaccount" => $this->account,
			"UMssn" => $this->ssn,
			"UMdlstate" => $this->dlstate,
			"UMdlnum" => $this->dlnum,
			"UMchecknum" => $this->checknum,
			"UMaccounttype" => $this->accounttype,
			"UMcheckformat" => $this->checkformat,
			"UMcheckimagefront" => base64_encode($this->checkimage_front),
			"UMcheckimageback" => base64_encode($this->checkimage_back),
			"UMcheckimageencoding" => 'base64',
			"UMrecurring" => $this->recurring,
			"UMbillamount" => $this->billamount,
			"UMbilltax" => $this->billtax,
			"UMschedule" => $this->schedule,
			"UMnumleft" => $this->numleft,
			"UMstart" => $this->start,
			"UMexpire" => $this->end,
			"UMbillsourcekey" => ($this->billsourcekey?"yes":""),
			"UMbillfname" => $this->billfname,
			"UMbilllname" => $this->billlname,
			"UMbillcompany" => $this->billcompany,
			"UMbillstreet" => $this->billstreet,
			"UMbillstreet2" => $this->billstreet2,
			"UMbillcity" => $this->billcity,
			"UMbillstate" => $this->billstate,
			"UMbillzip" => $this->billzip,
			"UMbillcountry" => $this->billcountry,
			"UMbillphone" => $this->billphone,
			"UMemail" => $this->email,
			"UMfax" => $this->fax,
			"UMwebsite" => $this->website,
			"UMshipfname" => $this->shipfname,
			"UMshiplname" => $this->shiplname,
			"UMshipcompany" => $this->shipcompany,
			"UMshipstreet" => $this->shipstreet,
			"UMshipstreet2" => $this->shipstreet2,
			"UMshipcity" => $this->shipcity,
			"UMshipstate" => $this->shipstate,
			"UMshipzip" => $this->shipzip,
			"UMshipcountry" => $this->shipcountry,
			"UMshipphone" => $this->shipphone,
			"UMcardauth" => $this->cardauth,
			"UMpares" => $this->pares,
			"UMxid" => $this->xid,
			"UMcavv" => $this->cavv,
			"UMeci" => $this->eci,
			"UMcustid" => $this->custid,
			"UMcardpresent" => ($this->cardpresent?"1":"0"),
			"UMmagstripe" => $this->magstripe,
			"UMdukpt" => $this->dukpt,
			"UMtermtype" => $this->termtype,
			"UMmagsupport" => $this->magsupport,
			"UMcontactless" => $this->contactless,
			"UMsignature" => $this->signature,
			"UMsoftware" => $this->software,
			"UMignoreDuplicate" => $this->ignoreduplicate,
			"UMrefNum" => $this->refnum,
			"UMsession" => $this->session
			);

		// tack on custom fields
		for($i=1; $i<=20; $i++)
		{
			if($this->{"custom$i"}) $data["UMcustom$i"] = $this->{"custom$i"};
		}

		// tack on line level detail
		$c=1;
		if(!is_array($this->lineitems)) $this->lineitems=array();
		foreach($this->lineitems as $lineitem)
		{
			$data["UMline{$c}sku"] = $lineitem['sku'];
			$data["UMline{$c}name"] = $lineitem['name'];
			$data["UMline{$c}description"] = $lineitem['description'];
			$data["UMline{$c}cost"] = $lineitem['cost'];
			$data["UMline{$c}taxable"] = $lineitem['taxable'];
			$data["UMline{$c}qty"] = $lineitem['qty'];
			$c++;
		}

		// Create hash if pin has been set.
		if(trim($this->pin))
		{
			// generate random seed value
			$seed = microtime(true) . rand();

			// assemble prehash data
			$prehash = $this->command . ":" . trim($this->pin) . ":" . $this->amount . ":" . $this->invoice . ":" . $seed;

			// if sha1 is available,  create sha1 hash,  else use md5
			if(function_exists('sha1')) $hash = 's/' . $seed . '/' . sha1($prehash) . '/n';
			else $hash = 'm/' . $seed . '/' . md5($prehash) . '/n';

			// populate hash value
			$data['UMhash'] = $hash;
		}

		$url = $this->_getGatewayBaseUrl().'/gate';

		//$this->_log("TranApi::Process\nURL: $url\n------------------------\nRequest:\n".print_r($data, true)."\n------------------------\n");

		// Post data to Gateway
		$result=$this->httpPost($url, $data);
		//$this->_log("_getGatewayBaseUrl\nResponse:\n".$this->proccessingurl."\n------------------------\n");
		 //$this->_log("TranApi::Process\nResponse:\n$result\n------------------------\n");

		if($result===false) return false;

		// result is in urlencoded format, parse into an array
		parse_str($result,$tmp);

		// check to make sure we received the correct fields
		if(!isset($tmp["UMversion"]) || !isset($tmp["UMstatus"]))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error parsing data from card processing gateway.";
			$this->errorcode=10132;
			return false;
		}

		// Store results
		$this->result=(isset($tmp["UMstatus"])?$tmp["UMstatus"]:"Error");
		$this->resultcode=(isset($tmp["UMresult"])?$tmp["UMresult"]:"E");
		$this->authcode=(isset($tmp["UMauthCode"])?$tmp["UMauthCode"]:"");
		$this->refnum=(isset($tmp["UMrefNum"])?$tmp["UMrefNum"]:"");
		$this->batch=(isset($tmp["UMbatch"])?$tmp["UMbatch"]:"");
		$this->avs_result=(isset($tmp["UMavsResult"])?$tmp["UMavsResult"]:"");
		$this->avs_result_code=(isset($tmp["UMavsResultCode"])?$tmp["UMavsResultCode"]:"");
		$this->cvv2_result=(isset($tmp["UMcvv2Result"])?$tmp["UMcvv2Result"]:"");
		$this->cvv2_result_code=(isset($tmp["UMcvv2ResultCode"])?$tmp["UMcvv2ResultCode"]:"");
		$this->vpas_result_code=(isset($tmp["UMvpasResultCode"])?$tmp["UMvpasResultCode"]:"");
		$this->convertedamount=(isset($tmp["UMconvertedAmount"])?$tmp["UMconvertedAmount"]:"");
		$this->convertedamountcurrency=(isset($tmp["UMconvertedAmountCurrency"])?$tmp["UMconvertedAmountCurrency"]:"");
		$this->conversionrate=(isset($tmp["UMconversionRate"])?$tmp["UMconversionRate"]:"");
		$this->error=(isset($tmp["UMerror"])?$tmp["UMerror"]:"");
		$this->errorcode=(isset($tmp["UMerrorcode"])?$tmp["UMerrorcode"]:"10132");
		$this->custnum=(isset($tmp["UMcustnum"])?$tmp["UMcustnum"]:"");

		// extended fraud profiler results
		$this->profilerScore = isset($tmp['UMprofilerScore']) ? $tmp['UMprofilerScore'] : '';
		$this->profilerResponse = isset($tmp['UMprofilerResponse']) ? $tmp['UMprofilerResponse'] : '';
		$this->profilerReason = isset($tmp['UMprofilerReason']) ? $tmp['UMprofilerReason'] : '';

		// Obsolete variable (for backward compatibility) At some point they will no longer be set.
		$this->avs=(isset($tmp["UMavsResult"])?$tmp["UMavsResult"]:"");
		$this->cvv2=(isset($tmp["UMcvv2Result"])?$tmp["UMcvv2Result"]:"");


		if(isset($tmp["UMcctransid"])) $this->cctransid=$tmp["UMcctransid"];
		if(isset($tmp["UMacsurl"])) $this->acsurl=$tmp["UMacsurl"];
		if(isset($tmp["UMpayload"])) $this->pareq=$tmp["UMpayload"];

		if($this->resultcode == "A") return true;
		return false;

	}

	function ProcessQuickSale()
	{

		// check that we have the needed data
		$tmp=$this->CheckData();
		if($tmp)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error=$tmp;
			$this->errorcode=10129;
			return false;
		}

				// Create hash if pin has been set.
		if(!trim($this->pin))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error='Source key must have pin assigned to run transaction';
			$this->errorcode=999;
			return false;
		}

		// generate random seed value
		$seed = microtime(true) . rand();

		// assemble prehash data
		$prehash = $this->key . $seed . trim($this->pin);

		// hash the data
		$hash = sha1($prehash);


		$data = '<?xml version="1.0" encoding="UTF-8"?>' .
				'<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:usaepay" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' .
				'<SOAP-ENV:Body>' .
				'<ns1:runQuickSale>' .
				'<Token xsi:type="ns1:ueSecurityToken">' .
				'<ClientIP xsi:type="xsd:string">' . $_SERVER['REMOTE_ADDR'] . '</ClientIP>' .
				'<PinHash xsi:type="ns1:ueHash">' .
				'<HashValue xsi:type="xsd:string">' . $hash . '</HashValue>' .
				'<Seed xsi:type="xsd:string">' . $seed . '</Seed>' .
				'<Type xsi:type="xsd:string">sha1</Type>' .
				'</PinHash>' .
				'<SourceKey xsi:type="xsd:string">' . $this->key . '</SourceKey>' .
				'</Token>' .
				'<RefNum xsi:type="xsd:integer">' . preg_replace('/[^0-9]/','',$this->refnum) . '</RefNum>' .
				'<Details xsi:type="ns1:TransactionDetail">' .
				'<Amount xsi:type="xsd:double">' . $this->xmlentities($this->amount) . '</Amount>' .
				'<Description xsi:type="xsd:string">' . $this->xmlentities($this->description) . '</Description>'.
				'<Discount xsi:type="xsd:double">' . $this->xmlentities($this->discount) . '</Discount>' .
				'<Invoice xsi:type="xsd:string">' . $this->xmlentities($this->invoice) . '</Invoice>' .
				'<NonTax xsi:type="xsd:boolean">' . ($this->nontaxable?'true':'false') . '</NonTax>' .
				'<OrderID xsi:type="xsd:string">' . $this->xmlentities($this->orderid) . '</OrderID>' .
				'<PONum xsi:type="xsd:string">' . $this->xmlentities($this->ponum) . '</PONum>' .
				'<Shipping xsi:type="xsd:double">' . $this->xmlentities($this->shipping) . '</Shipping>' .
				'<Subtotal xsi:type="xsd:double">' . $this->xmlentities($this->subtotal) . '</Subtotal>' .
				'<Tax xsi:type="xsd:double">' . $this->xmlentities($this->tax) . '</Tax>' .
				'<Tip xsi:type="xsd:double">' . $this->xmlentities($this->tip) . '</Tip>' .
				'</Details>' .
				'<AuthOnly xsi:type="xsd:boolean">false</AuthOnly>' .
				'</ns1:runQuickSale>' .
				'</SOAP-ENV:Body>' .
				'</SOAP-ENV:Envelope>';



		$url = $this->_getGatewayBaseUrl().'/soap/gate/15E7FB61';

		//$this->_log("TranApi::ProcessQuickSale\nURL: $url\n------------------------\nRequest:\n$data\n------------------------\n");

		// Post data to Gateway
		$result=$this->httpPost($url, array('xml'=>$data));

		//$this->_log("TranApi::ProcessQuickSale\nResponse:\n$result\n------------------------\n");

		if($result===false) return false;


		if(preg_match('~<AuthCode[^>]*>(.*)</AuthCode>~', $result, $m)) $this->authcode=$m[1];
		if(preg_match('~<AvsResult[^>]*>(.*)</AvsResult>~', $result, $m)) $this->avs_result=$m[1];
		if(preg_match('~<AvsResultCode[^>]*>(.*)</AvsResultCode>~', $result, $m)) $this->avs_result_code=$m[1];
		if(preg_match('~<BatchRefNum[^>]*>(.*)</BatchRefNum>~', $result, $m)) $this->batch=$m[1];
		if(preg_match('~<CardCodeResult[^>]*>(.*)</CardCodeResult>~', $result, $m)) $this->cvv2_result=$m[1];
		if(preg_match('~<CardCodeResultCode[^>]*>(.*)</CardCodeResultCode>~', $result, $m)) $this->cvv2_result_code=$m[1];
		//if(preg_match('~<CardLevelResult[^>]*>(.*)</CardLevelResult>~', $result, $m)) $this->cardlevel_result=$m[1];
		//if(preg_match('~<CardLevelResultCode[^>]*>(.*)</CardLevelResultCode>~', $result, $m)) $this->cardlevel_result_code=$m[1];
		if(preg_match('~<ConversionRate[^>]*>(.*)</ConversionRate>~', $result, $m)) $this->conversionrate=$m[1];
		if(preg_match('~<ConvertedAmount[^>]*>(.*)</ConvertedAmount>~', $result, $m)) $this->convertedamount=$m[1];
		if(preg_match('~<ConvertedAmountCurrency[^>]*>(.*)</ConvertedAmountCurrency>~', $result, $m)) $this->convertedamountcurrency=$m[1];
		if(preg_match('~<Error[^>]*>(.*)</Error>~', $result, $m)) $this->error=$m[1];
		if(preg_match('~<ErrorCode[^>]*>(.*)</ErrorCode>~', $result, $m)) $this->errorcode=$m[1];
		//if(preg_match('~<isDuplicate[^>]*>(.*)</isDuplicate>~', $result, $m)) $this->isduplicate=$m[1];
		if(preg_match('~<RefNum[^>]*>(.*)</RefNum>~', $result, $m)) $this->refnum=$m[1];
		if(preg_match('~<Result[^>]*>(.*)</Result>~', $result, $m)) $this->result=$m[1];
		if(preg_match('~<ResultCode[^>]*>(.*)</ResultCode>~', $result, $m)) $this->resultcode=$m[1];
		if(preg_match('~<VpasResultCode[^>]*>(.*)</VpasResultCode>~', $result, $m)) $this->vpas_result_code=$m[1];


		// Store results
		if($this->resultcode == "A") return true;
		return false;
	}




	function ProcessQuickCredit()
	{

		// check that we have the needed data
		$tmp=$this->CheckData();
		if($tmp)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error=$tmp;
			$this->errorcode=10129;
			return false;
		}

				// Create hash if pin has been set.
		if(!trim($this->pin))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error='Source key must have pin assigned to run transaction';
			$this->errorcode=999;
			return false;
		}

		// generate random seed value
		$seed = microtime(true) . rand();

		// assemble prehash data
		$prehash = $this->key . $seed . trim($this->pin);

		// hash the data
		$hash = sha1($prehash);


		$data = '<?xml version="1.0" encoding="UTF-8"?>' .
				'<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:usaepay" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' .
				'<SOAP-ENV:Body>' .
				'<ns1:runQuickCredit>' .
				'<Token xsi:type="ns1:ueSecurityToken">' .
				'<ClientIP xsi:type="xsd:string">' . $_SERVER['REMOTE_ADDR'] . '</ClientIP>' .
				'<PinHash xsi:type="ns1:ueHash">' .
				'<HashValue xsi:type="xsd:string">' . $hash . '</HashValue>' .
				'<Seed xsi:type="xsd:string">' . $seed . '</Seed>' .
				'<Type xsi:type="xsd:string">sha1</Type>' .
				'</PinHash>' .
				'<SourceKey xsi:type="xsd:string">' . $this->key . '</SourceKey>' .
				'</Token>' .
				'<RefNum xsi:type="xsd:integer">' . preg_replace('/[^0-9]/','',$this->refnum) . '</RefNum>' .
				'<Details xsi:type="ns1:TransactionDetail">' .
				'<Amount xsi:type="xsd:double">' . $this->xmlentities($this->amount) . '</Amount>' .
				'<Description xsi:type="xsd:string">' . $this->xmlentities($this->description) . '</Description>'.
				'<Discount xsi:type="xsd:double">' . $this->xmlentities($this->discount) . '</Discount>' .
				'<Invoice xsi:type="xsd:string">' . $this->xmlentities($this->invoice) . '</Invoice>' .
				'<NonTax xsi:type="xsd:boolean">' . ($this->nontaxable?'true':'false') . '</NonTax>' .
				'<OrderID xsi:type="xsd:string">' . $this->xmlentities($this->orderid) . '</OrderID>' .
				'<PONum xsi:type="xsd:string">' . $this->xmlentities($this->ponum) . '</PONum>' .
				'<Shipping xsi:type="xsd:double">' . $this->xmlentities($this->shipping) . '</Shipping>' .
				'<Subtotal xsi:type="xsd:double">' . $this->xmlentities($this->subtotal) . '</Subtotal>' .
				'<Tax xsi:type="xsd:double">' . $this->xmlentities($this->tax) . '</Tax>' .
				'<Tip xsi:type="xsd:double">' . $this->xmlentities($this->tip) . '</Tip>' .
				'</Details>' .
				'<AuthOnly xsi:type="xsd:boolean">false</AuthOnly>' .
				'</ns1:runQuickCredit>' .
				'</SOAP-ENV:Body>' .
				'</SOAP-ENV:Envelope>';


		$url = $this->_getGatewayBaseUrl().'/soap/gate/15E7FB61';

		//$this->_log("TranApi::ProcessQuickCredit\nURL: $url\n------------------------\nRequest:\n$data\n------------------------\n");

		// Post data to Gateway
		$result=$this->httpPost($url, array('xml'=>$data));

		// $this->_log("TranApi::ProcessQuickCredit\nResponse:\n$result\n------------------------\n");

		if($result===false) return false;


		if(preg_match('~<AuthCode[^>]*>(.*)</AuthCode>~', $result, $m)) $this->authcode=$m[1];
		if(preg_match('~<AvsResult[^>]*>(.*)</AvsResult>~', $result, $m)) $this->avs_result=$m[1];
		if(preg_match('~<AvsResultCode[^>]*>(.*)</AvsResultCode>~', $result, $m)) $this->avs_result_code=$m[1];
		if(preg_match('~<BatchRefNum[^>]*>(.*)</BatchRefNum>~', $result, $m)) $this->batch=$m[1];
		if(preg_match('~<CardCodeResult[^>]*>(.*)</CardCodeResult>~', $result, $m)) $this->cvv2_result=$m[1];
		if(preg_match('~<CardCodeResultCode[^>]*>(.*)</CardCodeResultCode>~', $result, $m)) $this->cvv2_result_code=$m[1];
		//if(preg_match('~<CardLevelResult[^>]*>(.*)</CardLevelResult>~', $result, $m)) $this->cardlevel_result=$m[1];
		//if(preg_match('~<CardLevelResultCode[^>]*>(.*)</CardLevelResultCode>~', $result, $m)) $this->cardlevel_result_code=$m[1];
		if(preg_match('~<ConversionRate[^>]*>(.*)</ConversionRate>~', $result, $m)) $this->conversionrate=$m[1];
		if(preg_match('~<ConvertedAmount[^>]*>(.*)</ConvertedAmount>~', $result, $m)) $this->convertedamount=$m[1];
		if(preg_match('~<ConvertedAmountCurrency[^>]*>(.*)</ConvertedAmountCurrency>~', $result, $m)) $this->convertedamountcurrency=$m[1];
		if(preg_match('~<Error[^>]*>(.*)</Error>~', $result, $m)) $this->error=$m[1];
		if(preg_match('~<ErrorCode[^>]*>(.*)</ErrorCode>~', $result, $m)) $this->errorcode=$m[1];
		//if(preg_match('~<isDuplicate[^>]*>(.*)</isDuplicate>~', $result, $m)) $this->isduplicate=$m[1];
		if(preg_match('~<RefNum[^>]*>(.*)</RefNum>~', $result, $m)) $this->refnum=$m[1];
		if(preg_match('~<Result[^>]*>(.*)</Result>~', $result, $m)) $this->result=$m[1];
		if(preg_match('~<ResultCode[^>]*>(.*)</ResultCode>~', $result, $m)) $this->resultcode=$m[1];
		if(preg_match('~<VpasResultCode[^>]*>(.*)</VpasResultCode>~', $result, $m)) $this->vpas_result_code=$m[1];


		// Store results
		if($this->resultcode == "A") return true;
		return false;
	}




	function buildQuery($data)
	{
		//ueLogDebug("TranApi::buildQuery");
		if(function_exists('http_build_query') && ini_get('arg_separator.output')=='&') return http_build_query($data);

		$tmp=array();
		foreach($data as $key=>$val) $tmp[] = rawurlencode($key) . '=' . rawurlencode($val);

		return implode('&', $tmp);

	}

	function httpPost($url, $data)
	{
		// if transport was not specified,  auto select transport
		if(!$this->transport)
		{
			if(function_exists("curl_version")) {
				$this->transport='curl';
			} else if(function_exists('stream_get_wrappers'))  {
				if(in_array('https',stream_get_wrappers())){
					$this->transport='stream';
				}
			}
		}

		// Use selected transport to post request to the gateway
		switch($this->transport)
		{
			case 'curl': return $this->httpPostCurl($url, $data);
			case 'stream': return $this->httpPostPHP($url, $data);
		}



		// No HTTPs libraries found,  return error
		$this->result="Error";
		$this->resultcode="E";
		$this->error="Libary Error: SSL HTTPS support not found";
		$this->errorcode=10130;
		return false;
	}

	function httpPostCurl($url, $data)
	{

		//init the connection
		$ch = curl_init($url);
		if(!is_resource($ch))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Libary Error: Unable to initialize CURL ($ch)";
			$this->errorcode=10131;
			return false;
		}

		// set some options for the connection
		curl_setopt($ch,CURLOPT_HEADER, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, ($this->timeout>0?$this->timeout:45));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

		// Bypass ssl errors - A VERY BAD IDEA
		if($this->ignoresslcerterrors)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}

		// apply custom ca bundle location
		if($this->cabundle)
		{
			curl_setopt($ch, CURLOPT_CAINFO, $this->cabundle);
		}

		// set proxy
		if($this->proxyurl)
		{
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, $this->proxyurl);
		}

		$soapcall=false;
		if(is_array($data))
		{
			if(array_key_exists('xml',$data)) $soapcall=true;
		}



		if($soapcall)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-type: text/xml;charset=UTF-8",
				"SoapAction: urn:ueSoapServerAction"
			));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data['xml']);

		} else {
			// rawurlencode data
			$data = $this->buildQuery($data);

			// attach the data
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		}

		// run the transfer
		$result=curl_exec ($ch);

		//get the result and parse it for the response line.
		if(!strlen($result))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error reading from card processing gateway.";
			$this->errorcode=10132;
			$this->blank=1;
			$this->transporterror=$this->curlerror=curl_error($ch);
			// $this->_log('curl error: '.$this->curlerror, Zend_Log::ERR);
			curl_close ($ch);
			return false;
		}

		curl_close ($ch);
		$this->rawresult=$result;

		if($soapcall) {
			return $result;
		}

		if(!$result) {
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Blank response from card processing gateway.";
			$this->errorcode=10132;
			return false;
		}

		// result will be on the last line of the return
		$tmp=explode("\n",$result);
		$result=$tmp[count($tmp)-1];

		return $result;
	}

	function httpPostPHP($url, $data)
	{


		// rawurlencode data
		$data = $this->buildQuery($data);

		// set stream http options
		$options = array(
			'http'=> array(
				'method'=>'POST',
	            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
	                . "Content-Length: " . strlen($data) . "\r\n",
	            'content' => $data,
	            'timeout' => ($this->timeout>0?$this->timeout:45),
	            'user_agent' => 'uePHPLibary v' . USAEPAY_VERSION . ($this->software?'/' . $this->software:'')
	        ),
	        'ssl' => array(
	            'verify_peer' => ($this->ignoresslcerterrors?false:true),
	            'allow_self_signed' => ($this->ignoresslcerterrors?true:false)
			)
		);

		if($this->cabundle) $options['ssl']['cafile'] = $this->cabundle;

		if(trim($this->proxyurl)) $options['http']['proxy'] = $this->proxyurl;


		// create stream context
		$context = stream_context_create($options);

		// post data to gateway
		$fd = fopen($url, 'r', null, $context);
		if(!$fd)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Unable to open connection to gateway.";
			$this->errorcode=10132;
			$this->blank=1;
			if(function_exists('error_get_last')) {
				$err=error_get_last();
				$this->transporterror=$err['message'];
			} else if(isset($GLOBALS['php_errormsg'])) {
				$this->transporterror=$GLOBALS['php_errormsg'];
			}
			//curl_close ($ch);
			return false;
		}

		// pull result
		$result = stream_get_contents($fd);

		// check for a blank response
		if(!strlen($result))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error reading from card processing gateway.";
			$this->errorcode=10132;
			$this->blank=1;
			fclose($fd);
			return false;
		}

		fclose($fd);
		return $result;

	}

	function xmlentities($string)
	{
		// $string = preg_replace('/[^a-zA-Z0-9 _\-\.\'\r\n]/e', '_uePrivateXMLEntities("$0")', $string);
		$string = preg_replace_callback('/[^a-zA-Z0-9 _\-\.\'\r\n]/', array('self', '_xmlEntitesReplaceCallback'), $string);
		return $string;
	}

	static protected function _xmlEntitesReplaceCallback($matches)
	{
		return self::_uePrivateXMLEntities($matches[0]);
	}

	static protected function _uePrivateXMLEntities($char)
	{
		$chars = array(
			128 => '&#8364;',
			130 => '&#8218;',
			131 => '&#402;',
			132 => '&#8222;',
			133 => '&#8230;',
			134 => '&#8224;',
			135 => '&#8225;',
			136 => '&#710;',
			137 => '&#8240;',
			138 => '&#352;',
			139 => '&#8249;',
			140 => '&#338;',
			142 => '&#381;',
			145 => '&#8216;',
			146 => '&#8217;',
			147 => '&#8220;',
			148 => '&#8221;',
			149 => '&#8226;',
			150 => '&#8211;',
			151 => '&#8212;',
			152 => '&#732;',
			153 => '&#8482;',
			154 => '&#353;',
			155 => '&#8250;',
			156 => '&#339;',
			158 => '&#382;',
			159 => '&#376;'
		);
		$num = ord($char);
		return (($num > 127 && $num < 160) ? $chars[$num] : "&#".$num.";" );
	}
}

function _uePrivateXMLEntities($num)
{	$chars = array(
    128 => '&#8364;',
    130 => '&#8218;',
    131 => '&#402;',
    132 => '&#8222;',
    133 => '&#8230;',
    134 => '&#8224;',
    135 => '&#8225;',
    136 => '&#710;',
    137 => '&#8240;',
    138 => '&#352;',
    139 => '&#8249;',
    140 => '&#338;',
    142 => '&#381;',
    145 => '&#8216;',
    146 => '&#8217;',
    147 => '&#8220;',
    148 => '&#8221;',
    149 => '&#8226;',
    150 => '&#8211;',
    151 => '&#8212;',
    152 => '&#732;',
    153 => '&#8482;',
    154 => '&#353;',
    155 => '&#8250;',
    156 => '&#339;',
    158 => '&#382;',
    159 => '&#376;');
    $num = ord($num);
    return (($num > 127 && $num < 160) ? $chars[$num] : "&#".$num.";" );
}
