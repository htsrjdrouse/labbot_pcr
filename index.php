<? session_start(); ?>
<? //header("Refresh:0");?>
<!DOCTYPE html>
<html lang="en">
<head>
<? include('functionslib.php');?>
<? error_reporting(E_ALL & ~E_NOTICE);?>
<title>HTS LabBot</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/bootstrap.min.css">
  <script src="jquery.min.js"></script>
  <script src="bootstrap.min.js"></script>
  <script src="/jquery.min.js" type="text/javascript"></script>
  <script src="/mqttws31.js" type="text/javascript"></script>

<?php
    if ($_GET["reloaded"] != 1) {
     echo "<meta http-equiv='refresh' content='0'>";
        echo '
            <script>
                $(function () {
                    window.location.href = "index.php?reloaded=1";
                });
            </script>
       ';
    }
?>

</head>

<? if (!(isset($_SESSION['labbotjson']))){ 
 $dir = scandir("uploads/");
 array_shift($dir);
 array_shift($dir);
 $_SESSION['objectsactive'] = $dir[0];
 $_SESSION['labbotjson'] = json_decode(file_get_contents('uploads/'.$_SESSION['objectsactive']), true);
} ?>

 
<? $types = ($_SESSION['labbotjson']['types']);?>
<? $groups = ($_SESSION['labbotjson']['groups']);?>
<?
 $size = count($types)+1;
 if ($size>10){
  $size = 10;
 }
 if (!isset($_SESSION['labbotjson']['tracktargp'])){
  $optflag = 0;
 } else {
  $optflag = $_SESSION['labbotjson']['tracktargp'];
}
?>


<?
 if(isset($_POST['connectsub'])){
  $cmd = 'mosquitto_pub -t "controllabbot" -m "turnon"';
  exec($cmd);
 }
 if(isset($_POST['disconnectsub'])){
  exec("ps aux | grep -i 'subscriber.py' | grep -v grep", $pids);
  $pid = (preg_split("/ /",$pids[0])[5]);
  $cmd = 'mosquitto_pub -t "controllabbot" -m "turnoff '.$pid.'"';
  exec($cmd);
  echo "<meta http-equiv='refresh' content='0'>";
 }

?>

<body>


<div class="row">
 <div class="col-md-3"><br><br>
 <ul>
<?
exec("ps aux | grep -i 'subscriber.py' | grep -v grep", $pids);
if(empty($pids)) { ?>
 <form action=<?=$_SERVER['PHP_SELF']?> method=post>
 <button type="submit" name=connectsub class="btn btn-success">Connect</button>
 </form>
 <br>
 <br>
 <br>
 This is the LabBot Controller.<br>
 Please connect to start moving your LabBot
 <br>
 <br>
 There are 3 selectable views: 
 <ol>
 <li>Create/Edit Objects</li>
 <li>Build Macros</li>
 <li>Edit/Run Macros</li>
 </ol>

<?
} else { ?>
 <form action=<?=$_SERVER['PHP_SELF']?> method=post>
 <button type="submit" name=disconnectsub class="btn btn-danger">Disconnect</button><br><br>
</form>
<? include('pronterface.panel.inc.php'); ?>
<hr>
<? include('microfluidics.inc.php'); ?>
<? } ?>


</ul>
</div> <!-- end col-md-3-->
<div class="col-md-4">
<h1>LabBot Interface</h1>
 <? if(!($_SESSION['labbot']['view'])){$_SESSION['labbot']['view']='objects';} ?>
 <? if(isset($_POST['objects'])){$_SESSION['labbot']['view'] = 'objects'; }?>
 <? if(isset($_POST['buildmacro'])){$_SESSION['labbot']['view'] = 'buildmacro'; } ?>
 <? if(isset($_POST['editmacro'])){$_SESSION['labbot']['view'] = 'editmacro'; } ?>
 <form action=<?=$_SERVER['PHP_SELF']?> method=post>
  <table><tr>
 <td><button type="submit" name=objects class="btn btn-primary">Objects</button>&nbsp;&nbsp;</td>
 <td><button type="submit" name=buildmacro class="btn btn-success">Build Macro</button>&nbsp;&nbsp;</td>
 <td><button type="submit" name=editmacro class="btn btn-warning">Edit/Run Macro</button>&nbsp;&nbsp;</td>
 <td><a class="btn btn-danger" href="logger.php" role="button" name=logger target="new">Logger</button></td>
 </tr></table>
 </form>
<hr>

<? if ($_SESSION['labbot']['view'] == 'objects') {include('objects.inc.php'); include('edittargets.php'); }?>
<? if ($_SESSION['labbot']['view'] == 'buildmacro') {include('programmer.php'); }?>
<? if ($_SESSION['labbot']['view'] == 'editmacro') {include('edit.macro.inc.php'); }?>
<? if ($_SESSION['labbot']['view'] == 'logger') {include('logger.inc.php'); }?>
<? //include('edittargets.php'); ?>
 </div>
<div class="col-md-4"><br>
<? include('target.layout.inc.php');?>
<br>
<? if(!empty($pids)) { 
 include('imaging.inc.php');
} ?>


</div>

</div> <!-- row -->
</body>



