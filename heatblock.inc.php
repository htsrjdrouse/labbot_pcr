<div class="row"><div class="col-sm-1"></div><div class="col-sm-10"><hr></div></div>
<div class="col-sm-3">
 <? $bmqttset = array("divmsg"=>"bltempmessages","topic"=>"blocktemp","client"=>"client4")?>
<? include('mqtt.blsub.js.inc.php'); ?> 
<b>Heatblocks</b><br><br>
</div>
<div class="col-sm-7">
<div style="font-weight:bold" id="<?=$bmqttset['divmsg']?>">  </b></div>
</div>
</div>
 

