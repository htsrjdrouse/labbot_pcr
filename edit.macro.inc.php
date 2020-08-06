<h2>Edit and Run Macro</h2>
<form action=program.editor.php method=post>
<? if(!isset($_SESSION['cmdlist'])){ 
 $pprog = json_decode(file_get_contents('labbot.programtorun.json'), true);
 $prog = $pprog['program'];
 } else {  $prog = $_SESSION['cmdlist']; }?>
<table cellpadding=10><tr><td>
<textarea name="macrofiledata" rows="14" cols="40">
<?
 if(isset($prog)){
  foreach($prog as $gg){
   $gg = preg_replace("/^\s/", "", $gg);
   $gg = preg_replace("/\r|\n/", "", $gg);
   $gg= preg_replace("/^\s+/","",$gg);
   if (strlen($gg) > 1){
    echo preg_replace("/'/","",$gg).'&#013;&#010';
   }
  }
 }
 ?>
</textarea>
</td><td valign=top>
 &nbsp;&nbsp;<button type="submit" name=savemacro class="btn-xs btn-primary">Save Macro</button><br><br>
 &nbsp;&nbsp;<button type="submit" name=runmacro class="btn-xs btn-success">Run Macro</button><br><br>
</td></tr></table>
</form>






