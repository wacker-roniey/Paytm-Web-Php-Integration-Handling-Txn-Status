<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
require_once("./lib/db.php");
$obj=new Database();

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
	}

	if (isset($_POST) && count($_POST) > 0 )
	{ 



		foreach($_POST as $paramName => $paramValue) {
			
				//response ORDERID data
				if ($paramName == "ORDERID") {
				   $ordis=$paramValue;
				}
				//response Txn Stts data
				if ($paramName == "RESPMSG") {
					$txnStts=$paramValue;
									}
			}

			// Get cust_id,id via response ORDERID data
			$fet=mysqli_query($obj->connect(),"SELECT id,custid FROM `paytm` WHERE `gen-id` = '$ordis' ")or die (mysqli_error($obj->connect()));
			while ($row=mysqli_fetch_array($fet)) {

						$GetId=$row['id'];
						$cID=$row['custid'];
					}		
				
				//based on Txn Stts
					if ($txnStts=="Txn Success") {
						//update stts
						mysqli_query($obj->connect(),"UPDATE `paytm` SET `stts`='OK' WHERE `id` = '$GetId' ")or die (mysqli_error($obj->connect()));
					}else{
						//remove order_id (gen-id)
						mysqli_query($obj->connect(),"UPDATE `paytm` SET `gen-id`='',`stts`='' WHERE `id` = '$GetId' ")or die (mysqli_error($obj->connect()));
						//------------
						//REDIRECT TO pgResponse.php with cust_id
						echo "<script>window.location='pgRedirect.php?cID=$cID';</script>";
					}
				
	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>