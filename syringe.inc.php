
<form action=<?=$_SERVER['PHP_SELF']?> method=post>
 <div class="col-sm-3"><b>Syringe pump</b> <br><a href=backlashrating.php target=new>backlash</a>
 <br>
 <button type="submit" name=syringesubmitstep class="btn-xs btn-primary">Move syringe</button>
 </div> 
 <div class="col-sm-4"> 
 <?if(!isset($_SESSION['syringespeed'])){ $_SESSION['syringespeed'] = 1000;}?>
 <?if(!isset($_SESSION['syringeacceleration'])){ $_SESSION['syringeacceleration'] = 500;}?>
 <?if(!isset($_SESSION['microliter'])){ $_SESSION['microliter'] = 10;}?>
 <?if(!isset($_SESSION['syringetime'])){ $_SESSION['syringetime'] = 1;}?>
 <?$jsonimg = json_decode(file_get_contents('nx.imgdataset.json'), true); ?>
<b>Microliter</b> <input type=text name=microliter value="<?=$_SESSION['microliter']?>" size=4><br>
  <input type=checkbox name=homesyringe><b>&nbsp;Home</b>
  <br>

 </div>
 <div class="col-sm-4"> 
 <b>Speed</b> <input type=text name=syringespeed value="<?=$_SESSION['syringespeed']?>" size=5><br>
 <b>Acceleration</b> <input type=text name=syringeacceleration value="<?=$_SESSION['syringeacceleration']?>" size=5><br>
</td>
 </div>
 <div class="col-sm-1"> 
 </div>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-10"><hr></div></div>
</form>
