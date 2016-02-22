<?php
include('config.php');

 
  if (!file_exists('data.txt')){
    echo '<h2>Data.txt does not exist</h2><P>Please copy data from iih pdf to create data.txt file </p>';
    exit;
  }

  //Load and Split data file into an array of lines.
  $Data = explode("\n", file_get_contents('data.txt'));


    echo "Loading Data.txt file  \n<BR>";
    echo "Deleting Previous Items file... \n<BR>";
		//remove cached data file.
		unlink('iihitems.data.txt');



    $Statements = array();

    $FirstLine = true;
    $AutoItemID = 0;
    foreach ($Data as $line)
    {

      //Store details of load
      if ($FirstLine){

        SaveSetting('LastDataUpdate', array('Title' => $line, 'timestamp' => date('Ymd H:i')));
        $FirstLine = false;
      }

         $AutoItemID++;

        //Match the full line.
        //This looks for a IIH code in the line.
        if  (preg_match('/([\\d]{5}?[A,B\\d])(.*)/i', $line, $regs)) {

              $Desc = $regs[2];

           
              $ItemArray[] = array('Code' => $regs[1], 'Description' => $Desc, 'Section' => $Header, 'AutoItemID' => $AutoItemID);
             
              echo "\n<BR>LOAD LINE: $line";

        }
        else  //If not code is found just store line as header, often goes through multiple ignored lines before loading items again. 
              //Most recent header is used.
        {
           
            echo "\n<BR>IGNORE LINE: " . $line . '<BR>';
           
            $Header = str_replace("'",' ' ,$line);
        }

    }

    SaveIIHItems($ItemArray);

    echo "FINISHED.";
// print_R($Statements);

//exit;







/*This function takes the items extracted from the data.txt file and writes them out as javascript.
This Javascript is then saved as a html scrap which is loaded into the main application.*/
function SaveIIHItems($Items){

  $iihDataTextFile = 'iihitems.data.txt';

  if (file_exists($iihDataTextFile)){
    $html = file_get_contents($iihDataTextFile);
  } else {
      $html = '';
      $r = 0;
      foreach ($Items as $item){
              $r++;
            // $html .= "//$r \n";

//Create Javascript Objects. Avoid Unnecessary white space.
$html .= 'd =  {}; d.id = ' . $item['AutoItemID'] . ';
d.Code = "' . $item['Code'] . '";
d.Description = "' . addslashes(trim($item['Description'])) . '";
d.Quantity = 0;
d.Section = "' . addslashes(trim($item['Section'])) . '";
data.push(d);';

            $html .=  "\n";
        }

        file_put_contents($iihDataTextFile, $html);

      }
    return $html;
  
}

