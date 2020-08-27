<? if (!isset($_SESSION['labbotprogramjson'])){
   $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
} ?>

<? function macro($cmdlist,$labbotprogramjson){ 
 foreach($labbotprogramjson['macrocontents'] as $mm){
   array_push($cmdlist,$mm);
 }
 return $cmdlist; 
}?>

<? function eject($cmdlist,$labbotprogramjson){ 
    foreach($_SESSION['labbotjson']['types'][0] as $tt){
     if($tt['name'] =="pipette removal"){
      $ejector = $tt;
      }
 }
    //echo "G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+5)."Z0F5000<br>";
  if ($ejector[0]['pipettetype'] == "P20") { 
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+12.5)."Z0F5000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+12.5)."Z".$ejector['Z']."F5000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z".$ejector['Z']."F1000");
 array_push($cmdlist,"G1X".($ejector['posx']-1.75)."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z']-11)."F100");
 array_push($cmdlist,"G1X".($ejector['posx']-1.75)."Y".($ejector['posy']+$ejector['marginy']+11)."Z".($ejector['Z']-11)."F1000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+11)."Z".($ejector['Z']-11)."F1000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z'])."F1000");
 array_push($cmdlist,"G1X".($ejector['posx']+1.75)."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z'])."F1000");
 array_push($cmdlist,"G1X".($ejector['posx']+1.75)."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z']-11)."F100");
 array_push($cmdlist,"G1X".($ejector['posx']+1.75)."Y".($ejector['posy']+$ejector['marginy']+12.5)."Z".($ejector['Z']-11)."F100");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+12.5)."Z0F5000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+12.5)."Z".$ejector['Z']."F5000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z".$ejector['Z']."F1000");
 array_push($cmdlist,"G1X".($ejector['posx']-1.75)."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z']-11)."F100");
 array_push($cmdlist,"G1X".($ejector['posx']-1.75)."Y".($ejector['posy']+$ejector['marginy']+11)."Z".($ejector['Z']-11)."F1000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+11)."Z".($ejector['Z']-11)."F1000");
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z'])."F1000");
 array_push($cmdlist,"G1X".($ejector['posx']+1.75)."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z'])."F1000");
 array_push($cmdlist,"G1X".($ejector['posx']+1.75)."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['Z']-11)."F100");
 array_push($cmdlist,"G1X".($ejector['posx']+1.75)."Y".($ejector['posy']+$ejector['marginy']+12.5)."Z".($ejector['Z']-11)."F100");
 array_push($cmdlist,"G28Z0");
  }
 return $cmdlist; 
} ?>


<? function turnonac($cmdlist){ ?>
<? array_push($cmdlist,"M118 turnonac"); ?>
<? return $cmdlist; ?>
<? } ?>

<? function loadpipettes($cmdlist,$labbotprogramjson){ 
 if (preg_match("/^pipette tip.*$/", $labbotprogramjson['object'])){
  foreach($_SESSION['labbotprogramjson'] as $tt) { 
   if (isset($tt['object'])){
    if ($tt['mesg'] == $labbotprogramjson['mesg']){
     $obj = $tt['object'];
    }
   }
  } 
  foreach($_SESSION['labbotjson']['types'][0] as $tt) { 
   if ($tt['name'] == $obj){
    $coord = $tt;
   }
  }
 if(!isset($labbotprogramjson['column'])){$labbotprogramjson['column']=1;}
 if(!isset($labbotprogramjson['row'])){$labbotprogramjson['row']=1;}
  array_push($cmdlist, "G1Z".($coord['ztrav']."F".$labbotprogramjson['feedrate']));
  //array_push($cmdlist, "G1X".($coord['posx']+$coord['marginx'])."Y".($coord['posy']+$coord['wellrowsp']*($labbotprogramjson['row']-1)+$coord['marginy'])."F".$labbotprogramjson['feedrate']);
  array_push($cmdlist, "G1X".($coord['posx']+$coord['marginx']+(($coord['shimx']/$coord['wellrow'])*($labbotprogramjson['row']-1)))."Y".($coord['posy']+($coord['wellrowsp']*($labbotprogramjson['row']-1))+$coord['marginy'] + ($coord['shimy']/$labbotprogramjson['row'])*($labbotprogramjson['row']-1))."F".$labbotprogramjson['feedrate']);
  array_push($cmdlist, "G1Z".($coord['Z']-$labbotprogramjson['zheight'])."F".$labbotprogramjson['feedrate']);
  array_push($cmdlist, "G1Z".($coord['Z']-$labbotprogramjson['zheight']-10)."F500");
  array_push($cmdlist, "G28Z0");
 }
  return $cmdlist; 
}
?>

<? function motion($cmdlist,$labbotprogramjson){ ?>
<? foreach($_SESSION['labbotprogramjson'] as $tt) { 
  if (isset($tt['object'])){
   if ($tt['mesg'] == $labbotprogramjson['mesg']){
    $obj = $tt['object'];
   }
  }
} ?>

<? foreach($_SESSION['labbotjson']['types'][0] as $tt) { 
  if ($tt['name'] == $obj){
     $coord = $tt;
  }
  } ?>
<? 
 if(!isset($labbotprogramjson['column'])){$labbotprogramjson['column']=1;}
 if(!isset($labbotprogramjson['row'])){$labbotprogramjson['row']=1;}
  //echo "G1 X".$coord['posx']." Y".($coord['posy']+$coord['wellrowsp']*$labbotprogramjson['row'])." F".$labbotprogramjson['feedrate']."<br>"; 
  array_push($cmdlist, "G1Z".($coord['ztrav']."F".$labbotprogramjson['feedrate']));
  if (preg_match("/^drypad.*$/", $labbotprogramjson['object'])){ 
   array_push($cmdlist, "G1X".($coord[0]['drypositions'][$_SESSION['dryrefnum']]['x'])."Y".($coord[0]['drypositions'][$_SESSION['dryrefnum']]['y'])."F".$labbotprogramjson['feedrate']);
   $_SESSION['dryrefnum'] = $_SESSION['dryrefnum'] + 1;
   if($_SESSION['dryrefnum'] == count($coord[0]['drypositions'])-1){$_SESSION['dryrefnum'] = 0;}
  } else {
   array_push($cmdlist, "G1X".($coord['posx']+$coord['marginx']+(($coord['shimx']/$coord['wellrow'])*($labbotprogramjson['row']-1)))."Y".($coord['posy']+($coord['wellrowsp']*($labbotprogramjson['row']-1))+$coord['marginy'] + ($coord['shimy']/$labbotprogramjson['row'])*($labbotprogramjson['row']-1))."F".$labbotprogramjson['feedrate']);
  }
  //echo "G1 Z".($coord['ztrav']-$labbotprogramjson['zheight'])." F".$labbotprogramjson['feedrate']."<br>";
  array_push($cmdlist,"G1Z".($coord['Z'] - $labbotprogramjson['zheight'])."F".$labbotprogramjson['feedrate']);
  return $cmdlist; 
 } ?>


<? function valve($cmdlist,$labbotprogramjson){
  //valve-1.1.1.1.1.1.1.1-input
  //$line = 'valve'-<?=preg_replace("/valve/", "", $labbotprogramjson['valvepos']); 
	$line = 'valve-'.$labbotprogramjson['valvelist'].'-'.preg_replace("/valve/", "", $labbotprogramjson['valvepos']);
  $jsonmicrofl = json_decode(file_get_contents('microfluidics.json'), true);
  $jsonmicrofl['tiplist'] = $labbotprogramjson['valvelist']; 
  $jsonmicrofl['valvepos'] = $labbotprogramjson['position']; 
  file_put_contents('microfluidics.json', json_encode($jsonmicrofl));

  array_push($cmdlist, $line);
  return $cmdlist; 
 } ?>

<? function pipettewash($cmdlist,$labbotprogramjson){ 
 foreach($_SESSION['labbotjson']['types'][0] as $tt) { 
  if ($tt['name'] == 'wash station'){
   $coord = $tt;
  }
 } 
  $cmdlist = motion($cmdlist,$labbotprogramjson);
  for($i=0;$i<$labbotprogramjson['pipettewashcycles'];$i++){
  array_push($cmdlist,"G1Z".($coord['ztrav']-$labbotprogramjson['zheight'])."F".$labbotprogramjson['feedrate']);
  //set all valves to input 
  array_push($cmdlist,"//set all valves to input");
  array_push($cmdlist,"valve-1_1_1_1_1_1_1_1-valveinput");
  //wasteon
  array_push($cmdlist,"wasteon");
  //washon
  array_push($cmdlist,"washon");
  //aspirate syinge 
  array_push($cmdlist,"sg1e".$labbotprogramjson['pipettewashvol']."s1000a500_".$labbotprogramjson['pipettewashtime']);
  //move select valves to output
  //"pipettelist"=>$pipettelist,
  array_push($cmdlist,"valve-".$labbotprogramjson['pipettelist']."-valveoutput");
  //dispense home for dispensing
  array_push($cmdlist,"sg28e0_".$labbotprogramjson['pipettewashtime']);
  if ($i == ($labbotprogramjson['pipettewashcycles'] - 1)){
   //washon
   array_push($cmdlist,"washoff");
   //wasteon
   array_push($cmdlist,"wasteoff");
   array_push($cmdlist,"//set all valves to bypass");
   array_push($cmdlist,"valve-1_1_1_1_1_1_1_1-valvebypass");
   if ($labbotprogramjson['dryafterwash'] == "on"){
    $labbotprogramjson['object'] = "drypad";
    foreach($_SESSION['labbotjson']['types'][0] as $tt) { 
     if ($tt['name'] == 'drypad'){
      $coord = $tt;
      }
     }  
     array_push($cmdlist,"G1Z".$coord['ztrav']);
     //$cmdlist = motion($cmdlist,$labbotprogramjson);
     array_push($cmdlist, "G1X".($coord[0]['drypositions'][$_SESSION['dryrefnum']]['x'])."Y".($coord[0]['drypositions'][$_SESSION['dryrefnum']]['y'])."F".$labbotprogramjson['feedrate']);
     $_SESSION['dryrefnum'] = $_SESSION['dryrefnum'] + 1;
     if($_SESSION['dryrefnum'] == count($coord[0]['drypositions'])-1){$_SESSION['dryrefnum'] = 0;}
     array_push($cmdlist,"G1Z".($coord['Z'] - $labbotprogramjson['zheight'])."F".$labbotprogramjson['feedrate']."_".$labbotprogramjson['drypadtime']);
     array_push($cmdlist,"G1Z".$coord['ztrav']);
    }
   }
  }
 return $cmdlist;
 }
?>

<? function syringe($cmdlist,$labbotprogramjson){ 
 if ($labbotprogramjson['mesg'] == "homing syringe"){
	 $line = "sg28e0_".$labbotprogramjson['syringetime'];
 } else {
	 $line = "sg1e".$labbotprogramjson['microliter']."s".$labbotprogramjson['syringespeed']."a".$labbotprogramjson['syringeacceleration']."_".$labbotprogramjson['syringetime'];
 }
 //echo $line."<br>";
 array_push($cmdlist,$line);
 return $cmdlist;
} ?>

<? function camera($cmdlist,$labbotprogramjson){ 
 $line = "snap ".$labbotprogramjson['location']."_".$labbotprogramjson['fname']." ".$labbotprogramjson['campredelay']." ".$labbotprogramjson['campostdelay'];
 $line = $line." ".$labbotprogramjson['camfocus']." ".$labbotprogramjson['camexposure'];
 array_push($cmdlist,$line);
 return $cmdlist;
} ?>


<? function heatblock($cmdlist,$labbotprogramjson){ 
  $line = "M104 T0 S".$labbotprogramjson['heatblock1temp'];
  array_push($cmdlist,$line);
  $line = "M104 T1 S".$labbotprogramjson['heatblock2temp'];
  array_push($cmdlist,$line);
  return $cmdlist;
} ?>

<? //wash, dry, pcv ?>
<? function gpio($cmdlist,$labbotprogramjson){
  $jsonmicrofl = json_decode(file_get_contents('microfluidics.json'), true);
  if ($labbotprogramjson['plug'] == "1"){
   $line  = "wash";
  } else if ($labbotprogramjson['plug'] == "2"){
   $line  = "waste";
  } else if ($labbotprogramjson['plug'] == "3"){
   $line  = "pcv";
  } else if ($labbotprogramjson['plug'] == "4"){
   $line  = "blueled";
  }
  /*
 if ($labbotprogramjson['closedloop'] == "off"){
  $line = $line." S".$labbotprogramjson['magnitude'];
 } else{
  $line = $line." P".$labbotprogramjson['plug']." T".$labbotprogramjson['temperature']." S".$labbotprogramjson['magnitude'];
 }
   */
  $line = $line.$labbotprogramjson['onoff'];
  if ($line == "washon"){ $_SESSION['labbot3d']['washon'] = 1; $jsonmicrofl['wash']['on'] = 1;}
  if ($line == "washoff"){ $_SESSION['labbot3d']['washon'] = 0; $jsonmicrofl['wash']['on'] = 0;}
  if ($line == "wasteon"){ $_SESSION['labbot3d']['dryon'] = 1; $jsonmicrofl['waste']['on'] = 1;}
  if ($line == "wasteoff"){ $_SESSION['labbot3d']['dryon'] = 0; $jsonmicrofl['waste']['on'] = 0;}
  if ($line == "pcvon"){ $_SESSION['labbot3d']['pcvon'] = 1; $jsonmicrofl['pcv']['on'] = 1;}
  if ($line == "pcvoff"){ $_SESSION['labbot3d']['pcvon'] = 0; $jsonmicrofl['pcv']['on'] = 0;}
  file_put_contents('microfluidics.json', json_encode($jsonmicrofl));

 //echo $line."<br>";
 array_push($cmdlist,$line); 
 return($cmdlist);
} ?>
