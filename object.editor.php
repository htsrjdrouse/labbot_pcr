<? session_start(); ?>
<?
if (isset($_GET['tarid'])){
 if ($_GET['tarid'] == 1){
  $_SESSION['labbotjson']['edittargets'] = 1;
 } else {
  $_SESSION['labbotjson']['edittargets'] = 0;
 }
 header("Location: index.php");
}
if (isset($_POST['selecttarget'])){
 $_SESSION['labbotjson']['targettrack'] = $_POST['targetlist'];
 $_SESSION['labbotjson']['targetactive'] = $_SESSION['labbotjson']['types'][0][$_POST['targetlist']]['name'];
 $_SESSION['labbotjson']['types']['active'] = $_SESSION['labbotjson']['targetactive'];
 //echo $_SESSION['labbotjson']['targettrack'].'<br>';
 //echo $_SESSION['labbotjson']['targetactive'].'<br>';
 header("Location: index.php");
}

if (isset($_POST['resettarget'])){
 $_SESSION['labbotjson'] = json_decode(file_get_contents('labbot.objects.json'), true);
 header("Location: index.php");
}

if (isset($_POST['saveobjects'])){
 //$_SESSION['labbotjson'] = json_decode(file_get_contents('labbot.objects.json'), true);
 file_put_contents('nx.imgdataset.json', json_encode($_SESSION['labbotjson']));
 header("Location: index.php");
}




if (isset($_POST['deletetarget'])){
 unset($_SESSION['labbotjson']['types'][0][$_POST['targetlist']]);
 foreach($_SESSION['labbotjson']['types'][0] as $key => &$val){ 
  $_SESSION['labbotjson']['targettrack'] = $key;
 }
 $_SESSION['labbotjson']['targetactive'] = $_SESSION['labbotjson']['types'][0][$key]['name'];
 header("Location: index.php");
}
if (isset($_POST['exportjson'])){
 //var_dump($_SESSION['labbotjson']);
 header('Content-Type: application/json');
 echo json_encode($_SESSION['labbotjson']);
}

if (isset($_POST['addtarget'])){
 $tname = $_POST['tarname'];
 $kk = 0;
 foreach($_SESSION['labbotjson']['types'][0] as $key => &$val){ 
  if($tname == $val['name']){$kk = 1;}
 } 
 if ($kk == 0){
  if ($_POST['active'] == "Active"){echo "its on<br>";$on = 'on';} else {$on = 'off';}
  $newd = array(
   'name' => $_POST['tarname'],
   'catalog' => $_POST['tarcatalog'],
   'status' => $on,
   'posx' => $_POST['posx'],
   'posy' => $_POST['posy'],
   'X' => $_POST['X'],
   'Y' => $_POST['Y'],
   'Z' => $_POST['Z'],
   'marginx' => $_POST['marginx'],
   'marginy' => $_POST['marginy'],
   'wellrow' => $_POST['wellrow'],
   'wellcolumn' => $_POST['wellcolumn'],
   'wellrowsp' => $_POST['wellrowsp'],
   'wellcolumnsp' => $_POST['wellcolumnsp'],
   'shape' => $_POST['shape'],
   'shapex' => $_POST['shapex'],
   'shapey' => $_POST['shapey'],
   'color' => $_POST['color'],
   'ztrav' => $_POST['ztrav']
  );
 }
 array_push($_SESSION['labbotjson']['types'][0], $newd);
 foreach($_SESSION['labbotjson']['types'][0] as $key => &$val){ 
  $_SESSION['labbotjson']['targettrack'] = $key;
 }
 $_SESSION['labbotjson']['targetactive'] = $_SESSION['labbotjson']['types'][0][$key]['name'];
 header("Location: index.php");
}

if (isset($_POST['savetarget'])){ 
 //echo "target list <br>";
 //echo $_POST['targetlist'];
 //echo "<br>";
 //echo 'active '.$_POST['active'];
 //echo "<br>";
 //$_SESSION['labbotjson']['targettrack'] = $_POST['targetlist'];
 //$_SESSION['labbotjson']['targetactive'] = $_SESSION['labbotjson']['types'][0][$_POST['targetlist']]['name'];
  if ($_POST['active'] == "Active"){echo "its on<br>";$on = 'on';} else {$on = 'off';}
  echo 'on '.$on.'<br>';
  $newd = array(
   'name' => $_POST['tarname'],
   'catalog' => $_POST['tarcatalog'],
   'status' => $on,
   'posx' => $_POST['posx'],
   'posy' => $_POST['posy'],
   'X' => $_POST['X'],
   'Y' => $_POST['Y'],
   'Z' => $_POST['Z'],
   'marginx' => $_POST['marginx'],
   'marginy' => $_POST['marginy'],
   'wellrow' => $_POST['wellrow'],
   'wellcolumn' => $_POST['wellcolumn'],
   'wellrowsp' => $_POST['wellrowsp'],
   'wellcolumnsp' => $_POST['wellcolumnsp'],
   'shape' => $_POST['shape'],
   'shapex' => $_POST['shapex'],
   'shapey' => $_POST['shapey'],
   'color' => $_POST['color'],
   'ztrav' => $_POST['ztrav']
  );
 $nomnom = array();
 foreach($_SESSION['labbotjson']['types'][0] as $key => &$val){ 
  if ($key == $_POST['targetlist']){
	
   array_push($nomnom, $newd);
  } else {
   array_push($nomnom, $val);
  }
 }

 //var_dump($_SESSION['labbotjson']['types'][0]);
 //echo "<br>-------------<br>";
 $_SESSION['labbotjson']['types'][0] = $nomnom;
 //var_dump($_SESSION['labbotjson']['types'][0]);
 header("Location: index.php");
}
?>
