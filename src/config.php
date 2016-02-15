<?php

date_default_timezone_set('Europe/London' );

function GetSetting($Key)
{
  $Settings = LoadSettingFile();

  if (isset($Settings[$Key])){
    return $Settings[$Key];
  } else
  {
    return false;
  }


}

function SaveSetting($KeyName, $Value)
{
      $Settings = LoadSettingFile();


      $Settings[$KeyName] = $Value;
      
      file_put_contents('iihSettings.json.php', json_encode($Settings));
      return $Settings;


        
}

function LoadSettingFile(){
  $SettingFileName = 'iihSettings.json.php';
  if (file_exists($SettingFileName)){

  $SettingArray = json_decode( file_get_contents($SettingFileName), true);
  }
  else {
    $SettingArray = array();
  }
  return $SettingArray;
}
