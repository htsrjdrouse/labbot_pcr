<? session_start(); ?>
<? if(isset($_POST['select'])){
 $dir = scandir("uploads/");
 array_shift($dir);
 array_shift($dir);
 $_SESSION['objectsactive'] = $dir[$_POST['objectlist']];
 $_SESSION['labbotjson'] = json_decode(file_get_contents('uploads/'.$_SESSION['objectsactive']), true);
 header("Location: index.php");
} ?>
<? if(isset($_POST['display'])){
 $ff = 'uploads/'.$_SESSION['objectsactive'];
 $myfile = fopen($ff, "r") or die("Unable to open file!");
 echo fread($myfile,filesize($ff));
 fclose($myfile);
} ?>
<? if(isset($_POST['delete'])){
 $dir = scandir("uploads/");
 array_shift($dir);
 array_shift($dir);
 unlink("uploads/".$dir[$_POST['objectlist']]);
 header("Location: objects.json.php");
} ?>

