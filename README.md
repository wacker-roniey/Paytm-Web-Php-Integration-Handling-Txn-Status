# Paytm Web/Php Integration with mysql db Handling Txn success and failure Case
 @Dated : Year-2020 
 @Credited : Paytm Devlopers Team 
 
 ```@Original : https://github.com/Paytm-Payments/Paytm_Web_Sample_Kit_PHP ```

# How to install the sample kit on a web server:
 1. Copy PaytmKit folder in document root of your server (like /var/www/html)
 2. Open config_paytm.php file from the PaytmKit/lib folder and update the below constant values
    - PAYTM_MERCHANT_KEY – Provided by Paytm
    - PAYTM_MERCHANT_MID - Provided by Paytm
    - PAYTM_MERCHANT_WEBSITE - Provided by Paytm
 3. PaytmKit folder is having following files:
    - TxnTest.php – Testing transaction through Paytm gateway.
    - pgRedirect.php – This file has the logic of checksum generation and passing all required parameters to Paytm PG. 
    - pgResponse.php – This file has the logic for processing PG response after the transaction        processing.
    - TxnStatus.php – Testing Status Query API

# For Offline(Wallet Api) Checksum Utility below are the methods:
  1. getChecksumFromString : For generating the checksum
  2. verifychecksum_eFromStr : For verifing the checksum
  
# To generate refund checksum in PHP :
  1. Create an array with key value pair of following paytm parameters 
     (MID, ORDERID, TXNTYPE, REFUNDAMOUNT, TXNID, REFID)
  2. To generate checksum, call the following method. This function returns the checksum as a string.
     getRefundChecksumFromArray($arrayList, $key, $sort=1)

# lib folder db.php page added  :
  1. Here you can find db page for database connection.

# Sql folder :
  1. contains mysql file.

# Technique/Algo : 
TABLE => `paytm` 

 `id` | `custid` | `gen-id` | `stts` |  `data`  |
 
  1. cust_id and random order_id inserted into db on  pgRedirected.php page
  2. and auto redirects to paytm api via pgRedirect.php page
  3. on response page [pgResponse.php], we've got order_id on response data as well as we can get cust_id,id from db by mysqli fetch
  4. if response txn status success then updates stts=ok on db against cust_id/id 
  else, remove order_id [updates custid='' on db against cust_id/id] and redirected to pgRedirect.php with cust_id/id
  5. On redirected pgRedirected.php we just update new random generated order_id against $_GET cust_id/id on db and finished.
