<?php

//file_put_contents('mydata.txt',print_r($_POST,true));
if (isset($_POST['debug'])){
    print_R($_POST);
}

/*if (!isset($_POST['items']) ){
  echo '{"Msg": "Done"}';
  exit;
}*/

//print_r($_POST['AccountInfo']);
extract($_POST['AccountInfo']);

date_default_timezone_set('Europe/London');
include('config.php');

switch ($_POST['action'] ){

    case 'GenerateMail':

      if (!isset($_POST['items'])){
        $Msg = 'No Items Selected';
      } else {

        //print_R($_POST);

        $Msg = "
Dear IIH,
AccountID: $AccountID
CustomerID: $CustomerID
Here is  my Order:
";
foreach ($_POST['items'] as $Item)
{
    $Msg .= "$Item[Quantity] x $Item[Code] : $Item[Description] \n";

}

$Msg .= "

Yours

$CustomerSignature

        ";

        $Data =  $_POST['items'];
        SaveSetting('IIH_CURRENTORDER_'. $CustomerID,$Data);
      }

        echo $Msg;
       //	DBConnect();

        break;
    case 'SaveAccount':
	//DBConnect();
        $Data =  $_POST['AccountInfo'];
  	SaveSetting('IIH_ACCOUNTDETAILS_' . $_POST['AccountInfo']['CompanyTag'],$Data);
        break;
    case 'SaveOrder' :
	//DBConnect();
        $Data =  $_POST['items'];
  	SaveSetting('IIH_CURRENTORDER_'. $CustomerID,$Data);
        break;   
    case 'NewOrder' :
	   // DBConnect();
      if (isset($_POST['items'])){
        $Data =  $_POST['items'];
        SaveSetting('IIH_ORDER_' . $CustomerID .'_' . date('Ymd-H:i'),$Data);
      }
        print_R($Data);
      echo 'reset curernt order';
        //Reset Current Order
  	    SaveSetting('IIH_CURRENTORDER_'. $CustomerID,'[{}]');
        break;     	
    	break; 
}





