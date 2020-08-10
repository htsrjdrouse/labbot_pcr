
<?
 $jsonimg = json_decode(file_get_contents('nx.imgdataset.json'), true);
 if (isset($_POST['checkbutn'])){
   echo "check";

 }
 if (isset($_POST['sendgcodecmd'])){
   $sendgcode = $_POST['sendgcode']; 
   $jsonimg['smoothielastcommand'] = $sendgcode;
   $cmd = 'mosquitto_pub -t "labbot" -m "'.$sendgcode.'"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
 }
 if (isset($_POST['getgcodepos'])){
   publish_message("M114", 'labbot', 'localhost', 1883, 5);    
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
   $cmd = 'mosquitto_pub -t "labbot" -m "M114"';
   exec($cmd);
 }

 if (isset($_POST['sendxyfeed'])){
   $xyfeed = $_POST['xyfeed']; 
   $jsonimg['speed']['xyjogfeed'] = $xyfeed;
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
 }
 if (isset($_POST['sendzfeed'])){
   $zfeed = $_POST['zfeed']; 
   $jsonimg['speed']['zjogfeed'] = $zfeed;
 }
 if (isset($_POST['homelinact'])){
   $jsonimg['linact']['position'] = 0;
   publish_message('linact--home', 'labbot', 'localhost', 1883, 5);    
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
 }

 if (isset($_POST['linactsettings'])){
   //"linact":{"steprate":500,"steps":2000}
   $jsonimg['linact']['steps'] = $_POST['linactsteps'];
   $jsonimg['linact']['steprate'] = $_POST['linactspeed'];
   $linactsteps = $_POST['linactsteps'];
   $linactspeed = $_POST['linactspeed'];
   publish_message('linact--stepsandrate '.$linactsteps.'_'.$linactspeed, 'labbot', 'localhost', 1883, 5);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
 }
 if (isset($_POST['linactmvup'])){
   //$jsonimg['linact']['position'] = $jsonimg['linact']['position'] - $jsonimg['linact']['steps'];
   publish_message('linact--up', 'labbot', 'localhost', 1883, 5);
 }
 if (isset($_POST['linactmvdown'])){
   //$jsonimg['linact']['position'] = $jsonimg['linact']['position'] + $jsonimg['linact']['steps'];
   publish_message('linact--down', 'labbot', 'localhost', 1883, 5);
 }
 if (isset($_GET['id'])){
  $idtag = $_GET['id'];
  $position = 'G1 X'.$jsonimg['currcoord']['X'].' Y'.$jsonimg['currcoord']['Y'].' Z'.$jsonimg['currcoord']['Z']; 
  if (preg_match('/^moveypos.*/', $idtag)){
   preg_match('/^moveypos(.*)$/', $idtag, $ar);
   $jsonimg['currcoord']['Y'] = $jsonimg['currcoord']['Y'] + $ar[1];
   $gcodecmd = 'G1 X'.$jsonimg['currcoord']['X'].' Y'.$jsonimg['currcoord']['Y'].' Z'.$jsonimg['currcoord']['Z'].' F'.$jsonimg['speed']['xyjogfeed'];
   $cmd = 'mosquitto_pub -t "labbot" -m "'.$gcodecmd.'"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
  }
  if (preg_match('/^moveyneg.*/', $_GET['id'])){
   preg_match('/^moveyneg(.*)$/', $_GET['id'], $ar);
   $jsonimg['currcoord']['Y'] = $jsonimg['currcoord']['Y'] - $ar[1];
   $gcodecmd = 'G1 X'.$jsonimg['currcoord']['X'].' Y'.$jsonimg['currcoord']['Y'].' Z'.$jsonimg['currcoord']['Z'].' F'.$jsonimg['speed']['xyjogfeed'];
   $cmd = 'mosquitto_pub -t "labbot" -m "'.$gcodecmd.'"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
  }
  if (preg_match('/^movexpos.*/', $_GET['id'])){
   preg_match('/^movexpos(.*)$/', $_GET['id'], $ar);
   $jsonimg['currcoord']['X'] = $jsonimg['currcoord']['X'] + $ar[1];
   $gcodecmd = 'G1 X'.$jsonimg['currcoord']['X'].' Y'.$jsonimg['currcoord']['Y'].' Z'.$jsonimg['currcoord']['Z'].' F'.$jsonimg['speed']['xyjogfeed'];
   $cmd = 'mosquitto_pub -t "labbot" -m "'.$gcodecmd.'"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
  }
  if (preg_match('/^movexneg.*/', $_GET['id'])){
   preg_match('/^movexneg(.*)$/', $_GET['id'], $ar);
   $jsonimg['currcoord']['X'] = $jsonimg['currcoord']['X'] - $ar[1];
   $gcodecmd = 'G1 X'.$jsonimg['currcoord']['X'].' Y'.$jsonimg['currcoord']['Y'].' Z'.$jsonimg['currcoord']['Z'].' F'.$jsonimg['speed']['xyjogfeed'];
   $cmd = 'mosquitto_pub -t "labbot" -m "'.$gcodecmd.'"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
  }
  if (preg_match('/^movezpos.*/', $_GET['id'])){
   preg_match('/^movezpos(.*)$/', $_GET['id'], $ar);
   $jsonimg['currcoord']['Z'] = $jsonimg['currcoord']['Z'] + $ar[1];
   $gcodecmd = 'G1 X'.$jsonimg['currcoord']['X'].' Y'.$jsonimg['currcoord']['Y'].' Z'.$jsonimg['currcoord']['Z'].' F'.$jsonimg['speed']['xyjogfeed'];
   $cmd = 'mosquitto_pub -t "labbot" -m "'.$gcodecmd.'"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
  }
  if (preg_match('/^movezneg.*/', $_GET['id'])){
   preg_match('/^movezneg(.*)$/', $_GET['id'], $ar);
   $jsonimg['currcoord']['Z'] = $jsonimg['currcoord']['Z'] - $ar[1];
   $gcodecmd = 'G1 X'.$jsonimg['currcoord']['X'].' Y'.$jsonimg['currcoord']['Y'].' Z'.$jsonimg['currcoord']['Z'].' F'.$jsonimg['speed']['xyjogfeed'];
   $cmd = 'mosquitto_pub -t "labbot" -m "'.$gcodecmd.'"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
   echo "<meta http-equiv='refresh' content='0'>";
  }

  if (preg_match('/^homez/', $_GET['id'])){
   $jsonimg['currcoord']['Z'] = 0; 
   $cmd = 'mosquitto_pub -t "labbot" -m "G28 Z0"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
   echo "<meta http-equiv='refresh' content='0'>";
  } 
  if (preg_match('/^homex/', $_GET['id'])){
   $jsonimg['currcoord']['X'] = 0; 
   $cmd = 'mosquitto_pub -t "labbot" -m "G28 X0"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
   echo "<meta http-equiv='refresh' content='0'>";
  } 

  if (preg_match('/^homexyz/', $_GET['id'])){
   $jsonimg['currcoord']['X'] = 0; 
   $cmd = 'mosquitto_pub -t "labbot" -m "G28 Z0"';
   exec($cmd);
   sleep(0.5);
   $cmd = 'mosquitto_pub -t "labbot" -m "G28 X0"';
   exec($cmd);
   sleep(0.5);
   $cmd = 'mosquitto_pub -t "labbot" -m "G28 Y0"';
   exec($cmd);
   sleep(0.5);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
   echo "<meta http-equiv='refresh' content='0'>";
  } 


  if (preg_match('/^homey/', $_GET['id'])){
   $jsonimg['currcoord']['Y'] = 0; 
   $cmd = 'mosquitto_pub -t "labbot" -m "G28 Y0"';
   exec($cmd);
   file_put_contents('nx.imgdataset.json', json_encode($jsonimg));
   echo "<meta http-equiv='refresh' content='0'>";
  } 

 }
   header('Location: index.php');
?>
