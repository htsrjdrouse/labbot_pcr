<? $jsonmicrofl = json_decode(file_get_contents('microfluidics.json'), true);?>

<? if(isset($_POST['syringesubmitstep'])){
 $_SESSION["microliter"]= $_POST['microliter'];
 $_SESSION["syringespeed"]= $_POST['syringespeed'];
 $_SESSION["syringeacceleration"]= $_POST['syringeacceleration'];
 if (isset($_POST['homesyringe'])){
  $msg =  "homing syringe";
  $_SESSION['microliter'] = 0;
  $pcmd = "sg28e0";
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);
 } else {
  $pcmd = "sg1e".$_POST['microliter']."s".$_POST['syringespeed']."a".$_POST['syringeacceleration'];
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);
 }
}
?> 

<? if(isset($_POST['gotowash'])){
 foreach($_SESSION['labbotjson']['types'][0] as $tt) { 
  if ($tt['name'] == 'wash station'){
   $coord = $tt;
  }
 } 
  $pcmd = "G1Z".($coord['ztrav']."F".$_SESSION['labbotprogram']['feedrate']);
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  echo $cmd.'<br>';
  exec($cmd);
  $row = 1;
  if(!(isset($_SESSION['labbotprogram']['feedrate']))){ $_SESSION['labbotprogram']['feedrate']  = 3000; }
   $pcmd = "G1X".($coord['posx']+$coord['marginx']+(($coord['shimx']/$coord['wellrow'])*($row-1)))."Y".($coord['posy']+($coord['wellrowsp']*($row-1))+$coord['marginy'] + ($coord['shimy']/$row)*($row-1))."F".$_SESSION['labbotprogram']['feedrate'];
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);
  sleep(2);
  $pcmd ="G1Z".($coord['Z'])."F".$_SESSION['labbotprogram']['feedrate'];
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);
  echo "<meta http-equiv='refresh' content='0'>";
}?>

<? if(isset($_POST['gotowaste'])){
 foreach($_SESSION['labbotjson']['types'][0] as $tt) { 
  if ($tt['name'] == 'waste station'){
   $coord = $tt;
  }
 } 
  $pcmd = "G1Z".($coord['ztrav']."F".$_SESSION['labbotprogram']['feedrate']);
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);
  $row = 1;
  if(!(isset($_SESSION['labbotprogram']['feedrate']))){ $_SESSION['labbotprogram']['feedrate']  = 3000; }
   $pcmd = "G1X".($coord['posx']+$coord['marginx']+(($coord['shimx']/$coord['wellrow'])*($row-1)))."Y".($coord['posy']+($coord['wellrowsp']*($row-1))+$coord['marginy'] + ($coord['shimy']/$row)*($row-1))."F".$_SESSION['labbotprogram']['feedrate'];
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);
  sleep(2);
  $pcmd ="G1Z".($coord['Z'])."F".$_SESSION['labbotprogram']['feedrate'];
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);
}?>


<? if(isset($_POST['gotodry'])){
 foreach($_SESSION['labbotjson']['types'][0] as $tt) { 
  if ($tt['name'] == 'drypad'){
   $coord = $tt;
  }
 } 
  if(!(isset($_SESSION['labbotprogram']['feedrate']))){ $_SESSION['labbotprogram']['feedrate']  = 3000; }
  if(!(isset($_SESSION['dryrefnum']))){ $_SESSION['dryrefnum']  = 0; }

  $pcmd = "touch_".($coord[0]['drypositions'][$_SESSION['dryrefnum']]['x'])."_".($coord[0]['drypositions'][$_SESSION['dryrefnum']]['y'])."_".($coord['z'])."_".($coord['ztrav'])."_".$_SESSION['labbotprogram']['feedrate']."_".($_SESSION['drypadtime']);
  $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
  exec($cmd);

  
}?>

<? if(isset($_POST['pcvon'])){
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "manpcv"';
 exec($cmd);
 sleep(1);
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "pcvon"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['pcvoff'])){
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "pcvoff"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['manpcv'])){
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "heatoff"';
 exec($cmd);
 sleep(0.5);
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "manpcv"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['feedbackpcv'])){
 $jsonmicrofl['sensorvalue'] = $_POST['setlevel'];
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "heatval '.$_POST['heatval'].'"';
 exec($cmd);
 sleep(0.5);
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "heaton"';
 exec($cmd);
 sleep(0.5);
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "setlevelval '.$_POST['setlevel'].'"';
 exec($cmd);
 sleep(1);
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "feedbackpcv"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['washon'])){
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "washon"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>
<? if(isset($_POST['washoff'])){
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "washoff"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>
<? if(isset($_POST['wasteon'])){
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "wasteon"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>
<? if(isset($_POST['wasteoff'])){
 $cmd = 'mosquitto_pub -t "labbotmicrofl" -m "wasteoff"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>


<? if(isset($_POST['govalvepos'])){
 $jsonmicrofl['valvepos'] = $_POST['valvepos'];  
 $opt = array('output','input','bypass','close');
 foreach($opt as $oo){
   if($_POST[$oo] == "checked"){
     $_SESSION['labbot3d']['valvepos'] = "checked";
   }
 }
 $tiplist = array();
 $tiplistst = "";
 for($i=0;$i<8;$i++){ 
  if(isset($_POST['valve'.$i])){
   array_push($tiplist, 1); 
   $tiplistst = $tiplistst."1.";
  } else { 
   $tiplistst = $tiplistst."0.";
   array_push($tiplist, 0); 
  }
 }
 $tiplistst = preg_replace("/.$/", "", $tiplistst);
 //echo "tiplist ".$tiplistst."<br>";
 $jsonmicrofl['tiplist'] = $tiplist;
 $_SESSION['labbot3d']['editvalvepos'] = 0; 
 $_SESSION['labbot3d']['valvepos'] = $_POST['valvepos'];
 file_put_contents('microfluidics.json', json_encode($jsonmicrofl));
 $jsonmicrofl = json_decode(file_get_contents('microfluidics.json'), true);
 $pcmd = 'valve-'.$tiplistst.'-'.$_POST['valvepos'];
 $cmd = 'mosquitto_pub -t "labbot" -m "'.$pcmd.'"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}
?>



<div class="row">
<form action=<?=$_SERVER['PHP_SELF']?> method=post>

<div class="col-sm-3">
<b>Select valves</b>
</div>
<div class="col-sm-8">
<table><tr>
<? for($i=0;$i<8;$i++){ ?>
<td align=center><?=($i+1)?><br><input type=checkbox class="form-check-input" name="valve<?=($i)?>" <? if ($jsonmicrofl['tiplist'][$i] == 1){ echo "checked"; }?>>&nbsp;</td>
<? } ?>
</tr></table>
</div>
</div>
<div class="row">&nbsp;</div>

<div class="row">
<div class="col-sm-3">
<b>Valve position</b>
</div>
<div class="col-sm-9">
<? if(!isset($_SESSION['labbot3d']['valvepos'])){$_SESSION['labbot3d']['valvepos'] = "input"; } ?>
<? if(!isset($_SESSION['labbot3d']['editvalvepos'])){$_SESSION['labbot3d']['editvalvepos'] = 0; } ?>
<table border=0><tr><td align=center>
<b><font size=1>Input</font></b><br>
<input type=radio name=valvepos value=input id=input <? if($_SESSION['labbot3d']['valvepos'] == "input"){?> checked <? } ?>> 
</td><td>&nbsp;&nbsp;</td><td align=center>
<b><font size=1>Bypass</font></b><br>
<input type=radio name=valvepos value=bypass id=bypass <? if($_SESSION['labbot3d']['valvepos'] == "bypass"){?> checked <? } ?>>
</td><td>&nbsp;&nbsp;</td><td align=center>
<b><font size=1>Output</font></b><br>
<input type=radio name=valvepos value=output id=output <? if($_SESSION['labbot3d']['valvepos'] == "output"){?> checked <? } ?>>
</td><td>&nbsp;&nbsp;</td><td align=center>
<b><font size=1>Close</font></b><br>
<input type=radio name=valvepos value=close id=close <? if($_SESSION['labbot3d']['valvepos'] == "close"){?> checked <? } ?>>
</td></tr></table>
<br>
<button type="submit" name=govalvepos value="govalvepos"  class="btn btn-primary btn-xs">Go to position</button>
</form>
</div>
</div>

<div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-4">
</div>
<div class="col-sm-4">
<!--<button type="submit" name=savevalvepos value="savevalvepos"  class="btn btn-danger btn-xs">Save position</button><br>-->
</div>
</div>


<form action=<?=$_SERVER['PHP_SELF']?> method=post>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-10"><hr></div></div>
<div class="row">
<!--<div class="col-sm-1">&nbsp;&nbsp;&nbsp;</div>
<div class="col-sm-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Wash<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Waste</b></div>
<div class="col-sm-3">&nbsp;&nbsp;<b>Wash<br>&nbsp;&nbsp;Waste</b></div>
-->

<div class="col-sm-2"><b>Wash<br>Waste</b></div>
<div class="col-sm-6">
<? if(!isset($_SESSION['labbot3d']['washon'])){ $_SESSION['labbot3d']['washon'] = 0; } ?>
<? if(!isset($_SESSION['labbot3d']['wasteon'])){ $_SESSION['labbot3d']['dryon'] = 0; } ?>
<? if(!isset($_SESSION['labbot3d']['editwashdry'])){ $_SESSION['labbot3d']['editwashdry'] = 0; } ?>
<table><tr><td align=center>
<? if($jsonmicrofl['washon'] == 0) { ?>
<button type="submit" name=washon value="washon"  class="btn btn-warning btn-xs">Wash on</button>
<? } else { ?>
<button type="submit" name=washoff value="washoff"  class="btn btn-danger btn-xs">Wash off</button>
<? } ?>
</td><td align=center>
<? if($jsonmicrofl['wasteon'] == 0) { ?>
<button type="submit" name=wasteon value="wasteon"  class="btn btn-success btn-xs">Waste on</button>
<? } else { ?>
<button type="submit" name=wasteoff value="wasteoff"  class="btn btn-danger btn-xs">Waste off</button>
<? } ?>
</td>
</td><td align=center>
 <?if(!isset($_SESSION['drypadtime'])){ $_SESSION['drypadtime'] = 0.1;}?>
 <font size=1><b>Dry time</b><br><input type=text name=drypadtime value="<?=$_SESSION['drypadtime']?>" size=1>
</td>
</tr>
<tr>
<td>
<br><button type="submit" name=gotowash value="gotowash"  class="btn btn-primary btn-xs">Go to wash</button><br>
</td>
<!--<td align=center>&nbsp;&nbsp;&nbsp;</td>-->
<td align=center>
<br><button type="submit" name=gotowaste value="gotowaste"  class="btn btn-primary btn-xs">Go to waste</button><br>
</td>
<td><br><button type="submit" name=gotodry value="gotodry"  class="btn btn-primary btn-xs">Go to dry</button>
</td></tr>
</table>
</form>
</div>
</div>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-10"><hr></div></div>

<div class="row">
<? include('syringe.inc.php'); ?>
</div>

<div class="row">
<form action=<?=$_SERVER['PHP_SELF']?> method=post>
<!--<div class="col-sm-1"></div> -->
<div class="col-sm-3">
<b>Pressure</b><br><br>
<? if($jsonmicrofl['pcvon'] == 0) { ?>
<button type="submit" name=pcvon value="pcvon"  class="btn btn-success btn-xs">PCV on</button>
<? } else { ?>
<button type="submit" name=pcvoff value="pcvoff"  class="btn btn-danger btn-xs">PCV off</button>
<? } ?>
</form>
</div>

<div class="col-sm-4">
<form action=<?=$_SERVER['PHP_SELF']?> method=post><font size=2>
<? if($jsonmicrofl['manpcv'] == 0) { ?>
<table>
<tr>
<td><font size=1><b>Sensor</b></font> </td>
</tr>
<tr>
<td><input type=text name=setlevel value="<?=$jsonmicrofl['sensorvalue']?>" size=3  style="text-align:right;font-size:10px;"> &nbsp;&nbsp;</td>
</tr>
<tr>
<td><font size=1><b>Heat</b></font> </td>
</tr>
<tr>
<td><input type=text name=heatval value="<?=$jsonmicrofl['heatval']?>" size=3  style="text-align:right;font-size:10px;"> &nbsp;&nbsp;</td>
</tr>
</table>

<button type="submit" name=feedbackpcv value="feedbackpcv"  class="btn btn-warning btn-xs">Feedback on</button><br>
<? } else { ?>
<button type="submit" name=manpcv value="manpcv"  class="btn btn-danger btn-xs">Feedback off</button>
<? } ?>
  <? $mqttset = array("divmsg"=>"tempmessages","topic"=>"temp","client"=>"client3")?>
  <? include('mqtt.sub.js.inc.php'); ?> 
</div>
<div class="col-sm-1">
<table>
<tr><td><font size=1><b>Sensor</b></font></td></tr>
<tr><td><b><font size=1><div style="font-weight:bold" id="<?=$mqttset['divmsg']?>"> C </b></div></font></td></tr>
<tr><td><font size=1><b>Feedback<br><?=$jsonmicrofl['setlevelval']?></b></font></td></tr>
</table>
</form>
</div>
<? include('heatblock.inc.php');?>



