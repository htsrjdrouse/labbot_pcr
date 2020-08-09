<? session_start(); ?>




<? if(isset($_POST['upload'])){
$target_dir = "uploads/";
$target_file =  $target_dir . basename($_FILES["fileToUpload"]["name"]);

$uploadOk = 1;
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

if($check == false) {
  $uploadOk = 1;
} else {
  echo "File is an image.";
  $uploadOk = 1;
}

if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

  if ($uploadOk == 1){
   if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $msg = 'target file '.$target_file.' uploaded<br>';
  } else {
      $msg = "Sorry, there was an error uploading your file.<br>";
  }
  }
}
?>


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

<div class="row">
 <div class="col-md-1"></div>
 <div class="col-md-4"><br><br>
  <h3>LabBot object layout file manager</h3>
  </div>
 <div class="col-md-6"><br><br>
<h4>Upload object list file</h4>
 </div>
</div>
<div class="row">
 <div class="col-md-1"></div>
 <div class="col-md-4"><br> 
 </div>
 <div class="col-md-1"><br><br>
<form action="objects.json.php" method="post" enctype="multipart/form-data">
 <style>


.fileContainer {
    overflow: hidden;
    position: relative;
}

.fileContainer [type=file] {
    cursor: inherit;
    display: block;
    font-size: 999px;
    filter: alpha(opacity=0);
    min-height: 100%;
    min-width: 100%;
    opacity: 0;
    position: absolute;
    right: 0;
    text-align: right;
    top: 0;
}
 </style>

<script>

$( '.fileContainer [type=file]' ).on( 'click', function updateFileName( event ){
    var $input = $( this );

    setTimeout( function delayResolution(){
        $input.parent().text( $input.val().replace(/([^\\]*\\)*/,'') )
    }, 0 )
} );

</script>
<label class="fileContainer">
    <button type="submit" name=submit name="fileToUpload" id="fileToUpload" class="btn btn-success">Browse ...</button>
   <input type="file" name="fileToUpload" id="fileToUpload">
</label>
</div>


 <div class="col-md-1"><br><br>
  <button type="submit" name=upload value="Upload file" class="btn btn-primary">Upload file</button>
</form>
</div>
<div class="col-md-3"><?=$msg?></div>
</div>

<div class="row">
 <div class="col-md-1"><br><br></div>
 <div class="col-md-2"><h4>Select object list file:</h4><br>
 <?$dir = scandir("uploads/"); ?>
 <? $size = count($dir)-2;
  if (count($dir) > 10){ $size=10; }
 ?>
<? array_shift($dir)?>
<? array_shift($dir)?>
<form action=objects.json.form.php method=post>

 <select class="form-control form-control-sm" name="objectlist" size=<?=$size?>>
  <? foreach($dir as $key => &$val){ ?>
  <? if ($val == $_SESSION['objectsactive']) { ?> 
   <option value=<?=$key?> selected><?=$val?></option>
  <? } else { ?>
   <option value=<?=$key?>><?=$val?></option>
  <? } ?>
  <? } ?>
 </select>
</div>
 <div class="col-md-3"></dir>
  <button type="submit" name="select" class="btn-sm btn-primary">Select file</button><br><br>
  <button type="submit" name="display" class="btn-sm btn-success">Display</button><br><br>
  <button type="submit" name="delete" class="btn-sm btn-danger">Delete file</button><br><br>
</form>
</div>

</body>
</html>
