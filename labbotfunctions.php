
<? function eject($cmdlist,$labbotprogramjson){ 
    foreach($_SESSION['labbotjson']['types'][0] as $tt){
     if($tt['name'] =="pipette removal"){
      $ejector = $tt;
      }
 }
    //echo "G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+5)."Z0F5000<br>";
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+5)."Z0F5000");
 //echo "G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+5)."Z".$ejector['ztrav']."F3000<br>";
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+5)."Z".$ejector['ztrav']."F3000");
 //echo "G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+2)."Z".$ejector['ztrav']."F3000<br>";
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+2)."Z".$ejector['ztrav']."F3000");
 //echo "G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+2)."Z".($ejector['ztrav']-1)."F500<br>";
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy']+2)."Z".($ejector['ztrav']-1)."F500");
 //echo "G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['ztrav']-3)."F500<br>";  
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z".($ejector['ztrav']-3)."F500");
 //echo "G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z0F5000<br>";
 array_push($cmdlist,"G1X".$ejector['posx']."Y".($ejector['posy']+$ejector['marginy'])."Z0F5000");
 return $cmdlist; 
} ?>

<? function turnonac($cmdlist){ ?>
<br>
<? array_push($cmdlist,"M118 turnonac"); ?>
<? return $cmdlist; ?>
<? } ?>
<? if (!isset($_SESSION['labbotprogramjson'])){
   $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
} ?>

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
  //echo "G1 X".$coord['posx']." Y".($coord['posy']+$coord['wellrowsp']*$labbotprogramjson['row'])." F".$labbotprogramjson['feedrate']."<br>"; 
  array_push($cmdlist, "G1X".($coord['posx']+$coord['marginx'])."Y".($coord['posy']+$coord['wellrowsp']*$labbotprogramjson['row']+$coord['marginy'])."F".$labbotprogramjson['feedrate']);
  //echo "G1 Z".($coord['ztrav']-$labbotprogramjson['zheight'])." F".$labbotprogramjson['feedrate']."<br>";
  array_push($cmdlist,"G1Z".($coord['ztrav']-$labbotprogramjson['zheight'])."F".$labbotprogramjson['feedrate']);
  return $cmdlist; 
 } ?>
<? function valve($cmdlist,$labbotprogramjson){
  //valve-1.1.1.1.1.1.1.1-input
  //$line = 'valve'-<?=preg_replace("/valve/", "", $labbotprogramjson['valvepos']); 
  $line = 'valve-'.$labbotprogramjson['valvelist'].'-'.preg_replace("/valve/", "", $labbotprogramjson['valvepos']);
  array_push($cmdlist, $line);
  return $cmdlist; 
 } ?>

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
  if ($labbotprogramjson['plug'] == "1"){
   $line  = "wash";
  } else if ($labbotprogramjson['plug'] == "2"){
   $line  = "dry";
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
 //echo $line."<br>";
 array_push($cmdlist,$line); 
 return($cmdlist);
} ?>
