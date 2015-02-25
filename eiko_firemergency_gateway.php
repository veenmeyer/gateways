
<?php

/**
 * @version     1.0.0
 * @package     eiko_gateway Schnittstelle FirEmergency -> Einsatzkomponente V3.x
 * @copyright   Copyright (C) 2014 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */

$username 			= 'DEIN_USERNAME';   // Bitte hier den Usernamen eintragen, der auch in den FirEmergency-Parametern steht
$api 				= '0000000000000';  // Secret-Key muss identisch dem aus den FirEmergency-Parametern sein.
$alarmierungsart_id = '1';   		 	// Die ID-Nr. einer Alarmierungsart aus der Einsatzkomponente V3.x
$einsatzart 		= "Einsatz";     	// Name einer Einsatzart aus der Einsatzkomponente V3.x


require('configuration.php');
$config = new JConfig;

// Datenbank 
$host		=$config->host;
$dbuser		=$config->user;     
$dbpass		=$config->password; 
$db_name 	=$config->db;
$dbprefix 	=$config->dbprefix;


mysql_connect($host, $dbuser,$dbpass) or die ("Keine Verbindung moeglich");

date_default_timezone_set('Europe/Berlin'); 
//date_default_timezone_set('UTC');
setlocale(LC_ALL, 'de_DE.utf8');

// Variabeln definieren
$bug 		= '0';
$user 		= '';
$apikey 	= '';
$zeit 		= '';
$ort 		= '';
$msg 		= '';
$ort 		= '';
$lon  		= '1';
$lat 		= '1';
$debug 		= '1';
$allbug 	= '100';
$bugtext 	= 'Fehler bei der Übertragung: </br>';



// Übergabe-Parameter aus Url auslesen
$user = mysql_real_escape_string(stripslashes($_GET['user']));
$apikey = mysql_real_escape_string(stripslashes($_GET['apikey']));
$timestamp = mysql_real_escape_string(stripslashes($_GET['zeit'])); 
$zeit = date("Y-m-d H:i:s",$timestamp); 	//Formatiert den Timestamp um in Y-m-d H:i:s zum speichern in die DB
$updatetime = date( "Y-m-d H:i:s"); 
$ort =  mysql_real_escape_string(stripslashes($_GET['ort']));
$kat = mysql_real_escape_string(stripslashes($_GET['kat']));
$msg = mysql_real_escape_string(stripslashes($_GET['msg']));
$lon  = mysql_real_escape_string(stripslashes($_GET['lon']));
$lat = mysql_real_escape_string(stripslashes($_GET['lat']));
$debug = mysql_real_escape_string(stripslashes($_GET['debug']));


//if ($debug =='1') :
//echo 'user    : '.$user.'<br/>';
//echo 'apikey  : '.$apikey.'<br/>';
//echo 'zeit    : '.$zeit.'<br/>';
//echo 'ort     : '.$ort.'<br/>';
//echo 'msg     : '.$msg.'<br/>';
//echo 'lon     : '.$lon.'<br/>';
//echo 'lat     : '.$lat.'<br/>';
//echo 'debug   : '.$debug.'<br/>';
//endif;


if ($lat == '')   // GMap-Koordinaten vorhanden ?
{$lat = "1";}
else     
{   }

if ($lon == '')   // GMap-Koordinaten vorhanden ?
{$lon = "1";}
else     
{   }


if ($msg == '')   // Kurzbericht vorhanden ?
{$bug ='60';$allbug = '0';}
else     
{   }


//if ($ort == '')   // Einsatzort vorhanden ?
//{$bug ='55';$allbug = '0';}
//else     
//{   }

if ($timestamp == '')   // Timestamp vorhanden ?
{$bug ='70';$allbug = '0';}
else     
{   }

//
//
//$now = time();
//$diff = ($now-$timestamp); 
//if ($diff > '604800')   // ist der Einsatz ist älter als 7 Tage ?    7 Tage sind 604800 sec.
//{$bug ='22';$allbug = '0';$bugtext .='- Einsatz ist älter wie 7 Tage </br>';}
//else     
//{  }
//
//if ($diff < '0')   // ist der Einsatz in der Zukunft ? 
//{$bug ='21';$allbug = '0';$bugtext .='- Einsatzzeit liegt in der Zukunft </br>';}
//else     
//{  }

if ($api == $apikey)   // Apikey richtig ?
{}
else     
{ $bug ='0';$allbug = '0'; $bugtext .='- API-Key ist falsch </br>';  } 


if ($username == $user)   // Username richtig ?
{}
else     
{ $bug ='13';$allbug = '0'; $bugtext .='- Username ist falsch </br>'; }


if ($debug == '1' and $allbug == '100')   
{$bug ='99';}
else     
{   
}

if ($debug == '0' and $allbug == '100') :
$bug ='100';$bugtext ='Einsatzmeldung war erfolgreich</br>'; 

// Datenbank verbinden
$dbconnect=mysql_connect($host,$dbuser,$dbpass);
 // Werte in Datenbank eintragen
$query = "INSERT INTO `".$db_name."`.`".$dbprefix."eiko_einsatzberichte` (`id`, `asset_id`, `ordering`, `data1`, `image`, `address`, `date1`, `date2`, `date3`, `summary`, `boss`, `boss2`, `people`, `department`, `desc`, `alerting`, `gmap_report_latitude`, `gmap_report_longitude`, `counter`, `gmap`, `presse_label`, `presse`, `presse2_label`, `presse2`, `presse3_label`, `presse3`, `updatedate`, `einsatzticker`, `notrufticker`, `tickerkat`, `auswahlorga`, `vehicles`, `status`, `state`, `created_by`) VALUES ('', '0', '0', '".$einsatzart."', '', '".$ort."', '".$zeit."', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000', '".$msg."', '', '', '', '0', '', '".$alarmierungsart_id."', '".$lat."', '".$lon."', '', '1', 'Presselink', '', 'Presselink', '', 'Presselink', '', '".$updatetime."', '', '', '', '', '', '', '1', '0');";
 
 

//if ($debug =='1') :
//echo '<br/>Insert-Query:<br/>'.$query.'<br/><br/>Debug-Modus aktiviert. Es erfolgte kein DB-Eintrag !!<br/>';
//mysql_close($dbconnect);
//
//else:
mysql_query($query) or die (mysql_error());  
mysql_close($dbconnect);
//endif;
endif;



echo $bug;  // Response Code ausgeben


//      GET-Variabeln (*=Pflichtvariabeln) :
//      $user    = *Benutzername (einzugeben in den Optionen Ihres Programmes)
//      $apikey  = *Sicherheitsschlüssel (einzugeben in den Optionen Ihres Programmes)
//      $zeit    = *Zeit der Alarmierung (im Format Timestamp)
//      $msg     = *Alarmierungstext
//      $ort     =  Einsatzort (natürlich müssen vorher Hausnummern entfernt werden. 
//      $lon     =  Koordinate Longitude 
//      $lat     =  Koordinate Latitude 
//      $debug   =  Wert 1 oder 0 für Testmodus
//      
//    
//      http://www.meine-feuerwehr.de/joomla-hauptverzeichniss/eiko_gateway.php?user=benutzername&apikey=3535253432&zeit=1406833169&msg=F_Zimmerbrand_Y%20klein&ort=Musterstadt,Musterstr&lon=7.34332324&lat=53.324234&debug=0
//      
//      Response-Code :
//      
//      100 = Einsatzmeldung erfolgreich
//       99 = Einsatzmeldung im Debug-Modus erfolgreich, kein DB-Eintrag
//       70 = Einsatzzeit fehlt
//       60 = Einsatzmeldung fehlt
//       55 = Einsatzort fehlt
//       13 = Username falsch
//        0 = Api-Key falsch



?>
