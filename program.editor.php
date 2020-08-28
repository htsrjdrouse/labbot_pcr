<? session_start(); ?>
<? include('functionslib.php'); ?>
<? include('labbotfunctions.php'); ?>
<?
if (isset($_POST['custommacro'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 $pprog = json_decode(file_get_contents('labbot.macros.json'), true);
 //print_r($pprog['macros'][$_POST['macrolist']]['content']);

 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"macro",
  "macrocontents"=> $pprog['macros'][$_POST['macrolist']]['content'],
  "mesg"=>$pprog['macros'][$_POST['macrolist']]['fname']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}

if (isset($_POST['deletecustommacro'])){ 
 $macros = json_decode(file_get_contents('labbot.macros.json'), true);
 echo $_POST['macrolist'].'<br>';
 $newmacro = array();
 foreach($macros['macros'] as $akey => &$val){ 
  if($akey != $_POST['macrolist']){
  echo $akey.'<br>';
  array_push($newmacro,array("fname"=>$val['fname'], "content"=>$val['content']));
  }
 }
 $macros['macros'] = $newmacro;
 var_dump($newmacro);
 file_put_contents('labbot.macros.json', json_encode($macros));
 header("Location: index.php");
 }

if (isset($_POST['storemacro'])){ 
 $vvr = preg_split("/\n/", $_POST['macrofiledata']);
 $_SESSION['cmdlist'] = $vvr;
 $prog = array("program"=>$vvr);
 file_put_contents('labbot.programtorun.json', json_encode($prog));
 sleep(1);
 $macros = json_decode(file_get_contents('labbot.macros.json'), true);
 $newmacro = array();
 if ((strlen($_POST['custommacroname'])) > 0){
 foreach($macros['macros'] as $mm){
  if ($mm['fname'] != $_POST['custommacroname']){
   array_push($newmacro,array("fname"=>$mm['fname'], "content"=>$mm['content']));
  }
 }
 $vvr = preg_split("/\n/", $_POST['macrofiledata']);
 array_push($newmacro,array("fname"=>$_POST['custommacroname'], "content"=>$vvr));
 $macros['macros'] = $newmacro;
 file_put_contents('labbot.macros.json', json_encode($macros));
 header("Location: index.php");
 }  
	
}



if (isset($_POST['pipettewashsubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }

 $pipettelist = "";
 for($i=1;$i<9;$i++){
   if($i==8){
	   if(isset($_POST['pipette'.$i])){$pipettelist = $pipettelist."1";} else { $pipettelist = $pipettelist."0";}
   } else {
	   if(isset($_POST['pipette'.$i])){$pipettelist = $pipettelist."1_";} else { $pipettelist = $pipettelist."0_";}
   }
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"pipettewash",
  "pipettewashvol"=>$_POST['pipettewashvol'],
  "pipettewashtime"=>$_POST['pipettewashtime'],
  "pipettewashcycles"=>$_POST['pipettewashcycles'],
  "feedrate"=>$_POST['feedrate'],
  "object"=>'wash station',
  "zheight"=>$_POST['zheight'],
  "row"=>$_POST['row'],
  "pipettelist"=>$pipettelist,
  "dryafterwash"=>$_POST['dryafterwash'],
  "drypadtime"=>$_POST['drypadtime'],
  "mesg"=>"wash pipettes object ".$_POST['targetlist']." ".$_POST['pipettewashvol']." microl cycles ".$_POST['pipettwashcycles']." dry ".$_POST['dryafterwash']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}

if (isset($_POST['ejectpipettes'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"eject",
  "object"=>"pipette removal",
  "mesg"=>"pipettes eject"
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");

}
if (isset($_POST['loadpipettes'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"loadpipettes",
  "object"=>$_POST['targetlist'],
  "row"=>$_POST['row'],
  "zheight"=>$_POST['zheight'],
  "feedrate"=>$_POST['feedrate'],
  "mesg"=>"load pipettes row ".$_POST['row']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}

if (isset($_POST['motionsubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"motion",
  "task"=>$_POST['task'],
  "object"=>$_POST['targetlist'],
  "feedrate"=>$_POST['feedrate'],
  "row"=>$_POST['row'],
  "zheight"=>$_POST['zheight'],
  "mesg"=>"motion ".$_POST['targetlist']." zh ".$_POST['zheight']." row ".$_POST['row']." F".$_POST['feedrate']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}

if (isset($_POST['valvesubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 $valvelist = "";
 for($i=1;$i<9;$i++){
   if($i==8){
	   if(isset($_POST['valve'.$i])){$valvelist = $valvelist."1";} else { $valvelist = $valvelist."0";}
   } else {
	   if(isset($_POST['valve'.$i])){$valvelist = $valvelist."1_";} else { $valvelist = $valvelist."0_";}
   }
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"valve",
  "valvelist"=>$valvelist,
  "valvepos"=>$_POST['valvepos'],
  "mesg" => 'valve-'.$valvelist.'-'.$_POST['valvepos']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}
if (isset($_POST['syringesubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 $_SESSION["microliter"]= $_POST['microliter'];
 $_SESSION["syringespeed"]= $_POST['syringespeed'];
 $_SESSION["syringeacceleration"]= $_POST['syringeacceleration'];
 if (isset($_POST['homesyringe'])){
	 $msg =  "homing syringe";
	 $_SESSION['microliter'] = 0;
 } else {
	 $msg =  "syringe ".$_POST['microliter']." speed ".$_POST['syringespeed']. " acc ".$_POST['syringeacceleration'];
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"syringe",
  "esteps"=>$_POST['esteps'],
  "microliter"=>$_POST['microliter'],
  "syringespeed"=>$_POST['syringespeed'],
  "syringeacceleration"=>$_POST['syringeacceleration'],
  "syringetime"=>$_POST['syringetime'],
  "homesyringe"=>$_POST['homesyringe'],
  "mesg" => $msg
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}
if (isset($_POST['gpiosubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 if (isset($_POST['plug'])){ $plug = $_POST['plug']; } else { $plug = 0; }
 
 if ($plug == 1) { $gt = "wash"; }
 if ($plug == 2) { $gt = "waste"; }
 if ($plug == 3) { $gt = "pcv"; }
 if ($plug == 4) { $gt = "blueled"; }
 if ($closedloop == "on") {
	 $mesg= $gt." ".$plug." ".$_POST['pump']." ".$_POST['temperature'];
 } else {
	 $mesg= $gt." ".$plug." ".$_POST['pump'];
 }
 if ($plug > 0) {
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"gpio",
  "plug"=>$plug,
  "onoff"=>$_POST['pump'],
  "magnitude"=>$_POST['magnitude'],
  "temperature"=>$_POST['temperature'],
  "thermistor"=>$_POST['thermistor'],
  "mesg" => $mesg
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 }
 header("Location: index.php");
}

if (isset($_POST['heatblocksubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"heatblock",
  "heatblock1temp"=>$_POST['heatblock1temp'],
  "heatblock2temp"=>$_POST['heatblock2temp'],
  "mesg" => "heatblockstemp block1 ".$_POST['heatblock1temp']." block2 ".$_POST['heatblock2temp']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 $_SESSION['labbot3d']['heatblock1temp'] = $_POST['heatblock1temp'];
 $_SESSION['labbot3d']['heatblock2temp'] = $_POST['heatblock2temp'];
 header("Location: index.php");
}
if (isset($_POST['camsubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"camera",
  "location"=>$_SESSION['labbot3d']['imgdir'],
  "fname"=>$_POST['fname'],
  "campredelay"=>$_POST['campredelay'],
  "campostdelay" => $_POST['campostdelay'],
  "camexposure" => $_POST['camexposure'],
  "camfocus" => $_POST['camfocus'],
  "mesg" => "camsnap ".$_SESSION['labbot3d']['imgdir']."_".$_POST['fname']." ".$_POST['campredelay']." ".$_POST['campostdelay']. " ".$_POST['camfocus']." ".$_POST['camexposure']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}
if (isset($_POST['telnetsubmitstep'])){ 
 unset($_SESSION['labbotprogramjson']);
 $_SESSION['labbotprogramjson'] = json_decode(file_get_contents('labbot.programs.json'), true);
 if(!isset($_SESSION['labbotprogramjson'])){
  $_SESSION['labbotprogramjson'] = array();
 }
 array_push($_SESSION['labbotprogramjson'], array(
  "tasktype"=>"telnetcommand",
  "task"=>$_POST['selectcommand'],
  "mesg" => "M115 ".$_POST['selectcommand']
 ));
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}
if (isset($_POST['deletemacro'])){ 
 foreach($_POST['macro'] as $mm){
  unset($_SESSION['labbotprogramjson'][$mm]);
 }
 closejson($_SESSION['labbotprogramjson'],'labbot.programs.json');
 header("Location: index.php");
}


if (isset($_POST['displaymacro'])){ 
 $labbotprogramjson = json_decode(file_get_contents('labbot.programs.json'), true);
 $cmdlist = array();
 foreach($_POST['macro'] as $mm){
  echo "//".$labbotprogramjson[$mm]['mesg']."<br>";
  if($labbotprogramjson[$mm]['tasktype'] == "telnetcommand"){
     $cmdlist = turnonac($cmdlist);
     displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "pipettewash"){
    $cmdlist = pipettewash($cmdlist, $labbotprogramjson[$mm]);
    displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "loadpipettes"){
    $cmdlist = loadpipettes($cmdlist, $labbotprogramjson[$mm]);
    displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "motion"){
    $cmdlist = motion($cmdlist, $labbotprogramjson[$mm]);
     displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "macro"){
    $cmdlist = macro($cmdlist, $labbotprogramjson[$mm]);
    displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "valve"){
    $cmdlist = valve($cmdlist, $labbotprogramjson[$mm]);
     displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "syringe"){
    $cmdlist = syringe($cmdlist, $labbotprogramjson[$mm]);
     displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "gpio"){
    $cmdlist = gpio($cmdlist, $labbotprogramjson[$mm]);
     displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "eject"){
    $cmdlist = eject($cmdlist, $labbotprogramjson[$mm]);
     displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "camera"){
    $cmdlist = camera($cmdlist, $labbotprogramjson[$mm]);
     displaymacro($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "heatblock"){
    $cmdlist = heatblock($cmdlist, $labbotprogramjson[$mm]);
     displaymacro($cmdlist);
  }

 }
}

if (isset($_POST['editmacro'])){ 
 $labbotprogramjson = json_decode(file_get_contents('labbot.programs.json'), true);
 $cmdlist = array();
 foreach($_POST['macro'] as $mm){
  array_push($cmdlist,"//".$labbotprogramjson[$mm]['mesg']);
  if($labbotprogramjson[$mm]['tasktype'] == "telnetcommand"){
     $cmdlist = turnonac($cmdlist);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "pipettewash"){
    $cmdlist = pipettewash($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "macro"){
    $cmdlist = macro($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "loadpipettes"){
    $cmdlist = loadpipettes($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "motion"){
    $cmdlist = motion($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "valve"){
    $cmdlist = valve($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "syringe"){
    $cmdlist = syringe($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "gpio"){
    $cmdlist = gpio($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "eject"){
    $cmdlist = eject($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "camera"){
    $cmdlist = camera($cmdlist, $labbotprogramjson[$mm]);
  }
  if($labbotprogramjson[$mm]['tasktype'] == "heatblock"){
    $cmdlist = heatblock($cmdlist, $labbotprogramjson[$mm]);
  }
 }
 if ($_POST['restart']){
   array_push($cmdlist,'restart');
 }
 $cc = array("cmdlist"=>$cmdlist);
 $_SESSION['cmdlist'] = $cmdlist;
 $_SESSION['labbot']['editprogram'] = 1;
 $_SESSION['labbot']['view'] = 'editmacro';
 file_put_contents('labbot.programtorun.json', json_encode($cmdlist));
 header("Location: index.php");
}


if (isset($_POST['savemacro'])){ 
 $vvr = preg_split("/\n/", $_POST['macrofiledata']);
 $_SESSION['cmdlist'] = $vvr;
 $prog = array("program"=>$vvr);
 file_put_contents('labbot.programtorun.json', json_encode($prog));
 header("Location: index.php");
}

if (isset($_POST['runmacro'])){ 
 $vvr = preg_split("/\n/", $_POST['macrofiledata']);
 $_SESSION['cmdlist'] = $vvr;
 $prog = array("program"=>$vvr);
 file_put_contents('labbot.programtorun.json', json_encode($prog));
 sleep(1);
 exec('mosquitto_pub -t "labbot" -m "runmacro"');
 //$_SESSION['labbot']['view'] = 'logger';
 header("Location: index.php");
}

function displaymacro($cmd){
 foreach($cmd as $cc){
  echo $cc.'<br>';
 }

}

?>


