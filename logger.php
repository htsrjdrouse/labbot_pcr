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


</head>
<body>
<ul>
<h2>Logger</h2><br>
  <? $mqttset = array("divmsg"=>"logger","topic"=>"labbot3d_track","client"=>"client4")?>
  <? include('mqtt.log.js.inc.php'); ?> 
<ul>
<b><font size=1><div style="font-weight:bold" id="logger"></b></div><font>
</ul>
</ul>

</body>
</html>
