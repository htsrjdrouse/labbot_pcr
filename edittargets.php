<hr>
<form action=object.editor.php method=post>
<h4>Edit/Add Objects</h4>
 <? $indetype = $types[0][$_SESSION['labbotjson']['targettrack']]; ?>
<input type=hidden name=targetlist value=<?=$_SESSION['labbotjson']['targettrack']?> >
<br>
<button type="submit" name=addtarget class="btn btn-warning">Add target</button>
 &nbsp; &nbsp; &nbsp; 
<!--<button type="submit" name=generatetargetgroup class="btn btn-success">Generate Target Group</button>-->
&nbsp; &nbsp; &nbsp;
<button type="submit" name=savetarget class="btn btn-danger">Save Target Settings</button>
&nbsp; &nbsp; &nbsp;
<br><br>
<table cellpadding=10><tR>

<td>
<div class="col-sm-3"><b>Name</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-6"><input type=text name=tarname value="<?=$indetype['name']?>" size=20></div>
<div class="col-sm-3">
<br>
<? if($indetype['status'] == "on"){?>
<b>Active</b> <input type=radio name=active value="Active" checked> <b>Deactive</b><input type=radio name=active value="Deactive">
<? } else { ?>
<b>Active</b> <input type=radio name=active value="Active"> <b>Deactive</b><input type=radio name=active value="Deactive" checked>
<? } ?>
</div>
</td>
</tr>


<? if($indetype['name'] == "pipette removal"){ ?>
<tr>
<td>
<div class="col-sm-3"><b>Pipette tip</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-9">
<? if(!isset($_SESSION['pipettetype'])) {  $_SESSION['pipettetype'] = "P20"; } ?>
<? $pipettetype = array("P20", "P200"); ?>
<? foreach($pipettetype as $ppp){ ?>
	<b><?=$ppp?></b> <input type=radio name=pipettetype value="<?=$ppp?>" <? if($ppp == $_SESSION['pipettetype']){ echo "checked"; } ?>> &nbsp;&nbsp;
<? } ?>

</div>
</td>
</tr>
<tr>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr>
<? } ?>

<tr>
<td>
<div class="col-sm-3"><b>Catalog</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-9"><input type=text name=tarcatalog value="<?=$indetype['catalog']?>" size=20></div>
</td>
</tr>
<tr>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr>





<tr>
<td>
<table cellpadding=10><tr>
<td><b>&nbsp;&nbsp;Size X</b> &nbsp; &nbsp;<input type=text name=X value="<?=$indetype['X']?>" size=7>&nbsp; &nbsp; &nbsp;</td>
<td><b>Y</b> &nbsp; &nbsp;<input type=text name=Y value="<?=$indetype['Y']?>" size=7>&nbsp; &nbsp; &nbsp;</td>
<td><b>Z</b>  &nbsp; &nbsp;<input type=text name=Z value="<?=$indetype['Z']?>" size=7>&nbsp; &nbsp; &nbsp;</td>
</td>
</tr></table>
<br>
</tr><tr>
<td>
<div class="col-sm-3"><b>Position X</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=posx value="<?=$indetype['posx']?>" size=3><br>
&nbsp; &nbsp; &nbsp; <?=$indetype['posx']+$indetype['marginx']?>
</div>
<div class="col-sm-3"><b>Y</b> &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp;
<?=$indetype['posy']+$indetype['marginy']?>
</div>
<div class="col-sm-3"><input type=text name=posy value="<?=$indetype['posy']?>" size=3></div>
</td>
</tr><tr>
<td>
<div class="col-sm-3"><b>Well row</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=wellrow value=<?=$indetype['wellrow']?> size=3></div>
<div class="col-sm-3"><b>Column</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=wellcolumn value=<?=$indetype['wellcolumn']?> size=3></div>
</tD>
</tr><tr>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr><tr>
<td>
<div class="col-sm-3"><b>Well row spacing</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=wellrowsp value=<?=$indetype['wellrowsp']?> size=3></div>
<div class="col-sm-3"><b>Column spacing</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=wellcolumnsp value=<?=$indetype['wellcolumnsp']?> size=3></div>
</tD>
</tr><tr>
<td>
<br>
<div class="col-sm-3"><b>Shim X</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=shimx value="<?=$indetype['shimx']?>" size=3></div>
<div class="col-sm-3"><b>&nbsp;&nbsp;&nbsp;&nbsp;Y</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=shimy value="<?=$indetype['shimy']?>" size=3></div>

</td>
</tr><tr>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr><tr>
<td>


<div class="col-sm-3"><b>Margin X</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=marginx value=<?=$indetype['marginx']?> size=3></div>
<div class="col-sm-3"><b>&nbsp;&nbsp;&nbsp;&nbsp;Y</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=marginy value=<?=$indetype['marginy']?> size=3></div>
</td>
</tr><tr>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr><tr>
<td>
<div class="col-sm-6"><b>Shape</b> &nbsp; &nbsp; &nbsp;
<? if ($indetype['shape'] == "ellipse"){?>
 <b>Ellipse</b> <input type=radio name=shape value="ellipse" checked> 
 <b>Square</b> <input type=radio name=shape value="square"> 
<? } else { ?>
 <b>Ellipse</b> <input type=radio name=shape value="ellipse"> 
 <b>Square</b> <input type=radio name=shape value="square" checked> 
<? } ?>
</div>
<div class="col-sm-3"><b>X diam </b><br><input type=text name=shapex value=<?=$indetype['shapex']?> size=3></div>
<div class="col-sm-3"><b>Y diam </b><bR><input type=text name=shapey value=<?=$indetype['shapey']?> size=3></div>

</tD>
<? if(preg_match("/^drypad.*$/",$indetype['name'])){?>
</tr><tr>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr><tr>
<td>
<br>


<div class="col-sm-6"><b>Record drypad positions</b> &nbsp; &nbsp;
<? if ($indetype[0]['recorddrypositions'] == "on"){$check = "checked"; } else { $check = ""; } ?>
 <input type=checkbox name=recorddrypositions <?=$check?>>
</div>
<div class="col-sm-6"><b>Spacing</b> &nbsp;&nbsp;<input type=text name=dryspacing value="<?=$indetype[0]['dryspacing']?>" size=1><br>
<? if(!isset($_SESSION['dryrefnum'])){$indetype[0]['dryrefnum'] = 0; $_SESSION['dryrefnum'] = 0; } ?>
<b>Ref number</b> &nbsp;&nbsp;<input type=text name=dryrefnum value="<?=$indetype[0]['dryrefnum']?>" size=1></div>
<? } ?>

</tD>
</tr><tr>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr><tr>
<td>

<div class="col-sm-6"><b>Z travel height to surface</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=ztrav value=<?=$indetype['ztrav']?> size=4></div>
</tD>
</tR>
</tr><tr><td><br></td></tr>
</tr><tr>

<td>
<div class="col-sm-6"><b>Color</b> &nbsp; &nbsp; &nbsp;</div>
<div class="col-sm-3"><input type=text name=color value=<?=$indetype['color']?> size=10></div>
</tD>
</tR>
</table>
 </form>










