<? $jsonmicrofl = json_decode(file_get_contents('microfluidics.json'), true);?>
<? if(isset($_POST['pcvon'])){
 $_SESSION['labbot3d']['pcvon'] = 1;
 $cmd = 'mosquitto_pub -t "labbot" -m "pcvon"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['pcvoff'])){
 $_SESSION['labbot3d']['pcvon'] = 0;
 $cmd = 'mosquitto_pub -t "labbot" -m "pcvoff"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['manpcv'])){
 $_SESSION['labbot3d']['manpcv'] = 0;
 $cmd = 'mosquitto_pub -t "labbot" -m "manpcv"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['feedbackpcv'])){
 $_SESSION['labbot3d']['manpcv'] = 1;
 $jsonmicrofl['sensorvalue'] = $_POST['setlevel'];
 $cmd = 'mosquitto_pub -t "labbot" -m "setlevelval '.$_POST['setlevel'].'"';
 exec($cmd);
 sleep(1);
 $cmd = 'mosquitto_pub -t "labbot" -m "feedbackpcv"';
 exec($cmd);

 file_put_contents('microfluidics.json', json_encode($jsonmicrofl));
 $jsonmicrofl = json_decode(file_get_contents('microfluidics.json'), true);
 echo "<meta http-equiv='refresh' content='0'>";
}?>
<? if(isset($_POST['washon'])){
 $_SESSION['labbot3d']['washon'] = 1;
 $cmd = 'mosquitto_pub -t "labbot" -m "washon"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>
<? if(isset($_POST['washoff'])){
 $_SESSION['labbot3d']['washon'] = 0;
 $cmd = 'mosquitto_pub -t "labbot" -m "washoff"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>
<? if(isset($_POST['wasteon'])){
 $_SESSION['labbot3d']['wasteon'] = 1;
 $cmd = 'mosquitto_pub -t "labbot" -m "wasteon"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>
<? if(isset($_POST['wasteoff'])){
 $_SESSION['labbot3d']['wasteon'] = 0;
 $cmd = 'mosquitto_pub -t "labbot" -m "wasteoff"';
 exec($cmd);
 echo "<meta http-equiv='refresh' content='0'>";
} ?>


<? if(isset($_POST['govalvepos'])){
 $jsonmicrofl['valve'][$_POST['selvalve']]['output'] = $_POST['output'];  
 $jsonmicrofl['valve'][$_POST['selvalve']]['input'] = $_POST['input'];  
 $jsonmicrofl['valve'][$_POST['selvalve']]['bypass'] = $_POST['bypass'];  
 $jsonmicrofl['valve'][$_POST['selvalve']]['close'] = $_POST['close'];  
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
</div>
<div class="row">&nbsp;<br>&nbsp;<br>&nbsp;</div>
<div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-4">
<button type="submit" name=govalvepos value="govalvepos"  class="btn btn-primary btn-sm">Go to position</button><br>
</div>
<div class="col-sm-4">
<!--<button type="submit" name=savevalvepos value="savevalvepos"  class="btn btn-danger btn-sm">Save position</button><br>-->
</div>
</form>
</div>

<form action=<?=$_SERVER['PHP_SELF']?> method=post>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-10"><hr></div>
<div class="row">
<div class="col-sm-1">&nbsp;&nbsp;&nbsp;</div>
<div class="col-sm-3">&nbsp;&nbsp;<b>Wash/Waste</b></div>
<div class="col-sm-5">
<? if(!isset($_SESSION['labbot3d']['washon'])){ $_SESSION['labbot3d']['washon'] = 0; } ?>
<? if(!isset($_SESSION['labbot3d']['wasteon'])){ $_SESSION['labbot3d']['dryon'] = 0; } ?>
<? if(!isset($_SESSION['labbot3d']['editwashdry'])){ $_SESSION['labbot3d']['editwashdry'] = 0; } ?>
<table><tr><td align=center>
<? if($_SESSION['labbot3d']['washon'] == 0) { ?>
<button type="submit" name=washon value="washon"  class="btn btn-warning btn-sm">Wash on</button>
<? } else { ?>
<button type="submit" name=washoff value="washoff"  class="btn btn-danger btn-sm">Wash off</button>
<? } ?>
</td><td align=center>&nbsp;&nbsp;&nbsp;</td>
</td><td align=center>
<? if($_SESSION['labbot3d']['wasteon'] == 0) { ?>
<button type="submit" name=wasteon value="wasteon"  class="btn btn-success btn-sm">Waste on</button>
<? } else { ?>
<button type="submit" name=wasteoff value="wasteoff"  class="btn btn-danger btn-sm">Waste off</button>
<? } ?>
</td></tr></table>
</form>
</div>
</div>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-10"><hr></div></div>

<div class="row">
<div class="col-sm-1"></div>
<div class="col-sm-3">
&nbsp;&nbsp;<b>Pressure</b><br><br>
<? if(!isset($_SESSION['labbot3d']['pcvon'])){ $_SESSION['pcvon'] = 0;} ?>
<? if($_SESSION['labbot3d']['pcvon'] == 0) { ?>
<button type="submit" name=pcvon value="pcvon"  class="btn btn-success btn-sm">PCV on</button>
<? } else { ?>
<button type="submit" name=pcvoff value="pcvoff"  class="btn btn-danger btn-sm">PCV off</button>
<? } ?>


</div>
<div class="col-sm-4">
<form action=<?=$_SERVER['PHP_SELF']?> method=post><font size=2>
<? if(!isset($_SESSION['labbot3d']['manpcv'])){$_SESSION['labbot3d']['manpcv'] = 0;}?>
<? if($_SESSION['labbot3d']['manpcv'] == 0) { ?>
<table><tr>
<td><font size=1><b>Sensor</b></font> </td>
</tr>
<tr>
<td><input type=text name=setlevel value="<?=$jsonmicrofl['sensorvalue']?>" size=3  style="text-align:right;font-size:10px;"> &nbsp;&nbsp;</td>
</tr>
</table>
<button type="submit" name=feedbackpcv value="feedbackpcv"  class="btn btn-warning btn-sm">Feedback on</button><br>
<? } else { ?>
<button type="submit" name=manpcv value="manpcv"  class="btn btn-danger btn-sm">Feedback off</button>
<? } ?>
  <? $mqttset = array("divmsg"=>"tempmessages","topic"=>"temp","client"=>"client3")?>
  <? include('mqtt.sub.js.inc.php'); ?> 
</div>
<div class="col-sm-1">
<table><tr><td><font size=1><b>Sensor</b></font></td></tr><tr><td><b><font size=1><div style="font-weight:bold" id="<?=$mqttset['divmsg']?>"> C </b></div></font></td></tr>
<tr><td><font size=1><b>Feedback<br><?=$jsonmicrofl['sensorvalue']?></b></font></td></tr>
</table>

</form>
</div>
<? include('heatblock.inc.php');?>



