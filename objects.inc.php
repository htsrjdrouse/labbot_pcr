<div class="row">
<div class="col-sm-8">
<h2>Objects</h2>
</div>
</div>
<div class="row">
<form action=object.editor.php method=post>
<div class="col-sm-8">
<h4>Select Objects</h4>
 <? if (!isset($_SESSION['labbotjson']['targetactive'])){ $_SESSION['labbotjson']['targetactive'] = $types['active'];}?>
 <select class="form-control form-control-sm" name="targetlist" size=<?=$size?>>
<? foreach($types[0] as $key => &$val){ ?>
 <? if ($val['name'] == $_SESSION['labbotjson']['targetactive']) { ?> 
 <? $_SESSION['labbotjson']['targettrack'] = $key; ?>
   <option value=<?=$key?> selected><?=$val['name']?></option>
 <? } else { ?>
 <option value=<?=$key?>><?=$val['name']?></option>
<? }?>
<? }?>
 </select> 
</div>
<div class="col-sm-4">
 <button type="submit" name=selecttarget class="btn btn-primary">Select</button>
 &nbsp;
 &nbsp;
 &nbsp;
 <button type="submit" name=deletetarget class="btn btn-danger">Delete</button>
 <br><br>
 <button type="submit" name=resettarget class="btn btn-warning">Reset</button>
 &nbsp;
 &nbsp;
 &nbsp;
 <button type="submit" name=exportjson class="btn btn-success">Export JSON</button>
</form>
<br>
</div>
</div>
 <!--</form>-->





