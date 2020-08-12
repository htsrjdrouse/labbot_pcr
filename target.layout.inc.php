
<? if(isset($_POST['adjustbedsize'])){
  $_SESSION['bedsize']['bedsizex'] = $_POST['bedsizex'];
  $_SESSION['bedsize']['bedsizey'] = $_POST['bedsizey'];
  file_put_contents('labbot.bedsize.json', json_encode($_SESSION['bedsize']));
} ?>
<?
 $_SESSION['bedsize'] = json_decode(file_get_contents('labbot.bedsize.json'), true);
?>

<form action=<?=$_SERVER['PHP_SELF']?> method=post>
<b>Adjust bed size 
X<input type=text name=bedsizex value="<?=$_SESSION['bedsize']['bedsizex']?>" size=5 style="text-align:right;font-size:10px;">
Y<input type=text name=bedsizey value="<?=$_SESSION['bedsize']['bedsizey']?>" size=5 style="text-align:right;font-size:10px;">
<button type="submit" name=adjustbedsize value="Adjust bed size" class="btn btn-success btn-sm">Adjust bedsize</button><br>
</b>
</form>


<?
  $bedsizex=($_SESSION['bedsize']['bedsizex']);
  $bedsizey=($_SESSION['bedsize']['bedsizey']);
  $shimx = 40;
  $shimy = 30;
?>


<?
  /*
  foreach($_SESSION['labbotjson']['types'][0] as $key => &$val){ 
   if ($val['status'] == "on") {
   echo $val['name'];
   echo "<br>";
   print_r($val);
   echo "<br>";
   }
  }
   */
?>
<br>

  <?// $mqttset = array("divmsg"=>"procmessages","topic"=>"proctemp","client"=>"client5","x"=>"x","y"=>"y")?>
  <?// include('mqtt.proc.js.inc.php'); ?> 

<script src="/processing.min.js"></script>
<script type="text/processing" data-processing-target="mycanvas">

// Global variables
float radius = 50.0;
//int X, Y;
//int nX, nY;
int delay = 16;


PImage bg;
PFont f;
int a;
boolean overRightButton = false;
boolean reGrid = false;
boolean setSearcharea = false;
boolean overLeftButton = false;
boolean bover = false;
boolean locked = false;
float bdifx = 0.0;
float bdify = 0.0;
int px,py,rowdiam,columndiam;
int rectX, rectY;      // Position of square button
int circleX, circleY;  // Position of circle button
int rectSize = 60;     // Diameter of rect
int circleSize = 60;   // Diameter of circle
int nX, nY;
int X, Y;
color rectColor, circleColor, baseColor;
color rectHighlight, circleHighlight;
color currentColor;
boolean rectOver = false;
boolean clrectOver = false;
boolean circleOver = false;
PFont font;
String spots;
String st;
ArrayList spotlist;
int flag = 0;
color currentcolor;
spotlistch = new ArrayList();
String[] sa1;
String[] xpos;
String[] ypos;
String[] shapex;
String[] shapey;
String[] shape;
String[] wellrowsp;
String[] wellcolumnsp;


void setup()
{
  sal = { }; 
  ypos = { }; 
  xpos = { }; 
  shape = { }; 
  shapex = { }; 
  shapey = { }; 
  wellrowsp = { }; 
  wellcolumnsp = { }; 
  frameRate( 15 );
  size(<?=($bedsizex+80)?>,<?=($bedsizey+60)?>);
  /*
  rectColor = color(0);
  //noLoop();
  rectHighlight = color(101);
  circleColor = color(255);
  circleHighlight = color(204);
  baseColor = color(102);
  currentColor = baseColor;
  circleX = 0;
  circleY = 0;

  X = width / 2;
  Y = height / 2;
  nX = X;
  nY = Y;
  int[] circleXry = {20,150};
  int[] circleYry = {20,100};
  //rectX = width/2-rectSize-10;
  //rectY = height/2-rectSize/2;
  ellipseMode(CENTER);
  */
  color c1 = color(102, 255, 255);
  background(100);

  fill(c1);
  noStroke();
  //rect(0,0,360,360);
  rect(0,0,<?=($bedsizex+80)?>,<?=($bedsizey+60)?>);
  //size(<?=($bedsizex+40)?>,<?=$bedsizey?>);
  fill(255,255,255);
  //stroke(0,0,0);
  rect(<?=$shimx?>,<?=$shimy?>,<?=$bedsizex?>,<?=$bedsizey?>);
  noStroke();
  fill(0,0,0);
  font = loadFont("FFScala.ttf");
  textFont(font);
  textSize(10);
  text("<?=0?>, <?=0?>",<?=(40-10)?>,<?=($bedsizey+30+10)?>);
  text("<?=($bedsizex)?>, <?=$bedsizey?>",<?=$bedsizex+14?>,<?=30-5?>);

 <?foreach($_SESSION['labbotjson']['types'][0] as $key => &$val){ 
  if ($val['status'] == "on") { ?>
   fill(<?=$val['color']?>);
   text("<?=$val['name']?>",<?=($val['posx']+$shimx)?>,<?=($bedsizey+($shimy)-$val['Y']-$val['posy']+$margin['y'])?>);
   noStroke();
   rect(<?=($val['posx']+$shimx)?>,<?=($bedsizey+($shimy)-$val['Y']-$val['posy']+$margin['y'])?>,<?=$val['X']?>,<?=$val['Y']?>);
   stroke(0,0,0);
   <? if($val['shape'] == "ellipse"){ ?>
   <? for($x=0;$x<$val['wellcolumn'];$x++){ ?>
   <?for($y=0;$y<$val['wellrow'];$y++){ ?>
   ellipse(<?=($val['posx']+$shimx+$val['marginx']+($x*$val['wellcolumnsp']+(($val['shimx']/$val['wellrow'])*$y)+$val['shapex']/2))?>,<?=($bedsizey+($shimy)-$val['posy']-($val['marginy']*1)) - ($y*$val['wellrowsp']) - (($val['shimy']/$val['wellrow'])*$y)?>,<?=$val['shapex']?>,<?=$val['shapey']?>);
   <? } ?>
   <? }?>
   <? } ?>

   <? if($val['shape'] == "square"){ ?>
   <? for($x=0;$x<$val['wellcolumn'];$x++){ ?>
   <?for($y=0;$y<$val['wellrow'];$y++){ ?>
   rect(<?=($val['posx']+$shimx+$val['marginx']+($x*$val['wellcolumnsp']+(($val['shimx']/$val['wellrow'])*$y)))?>,<?=($bedsizey+($shimy)-$val['posy']-($val['marginy']*1)) - ($y*$val['wellrowsp']) - (($val['shimy']/$val['wellrow'])*$y)?>,<?=$val['shapex']?>,<?=$val['shapey']?>);
   <? } ?>
   <? }?>
   <? } ?>





  <? 
   }
  } ?>

  fill(0,0,0);


  /*
  //buttons
  textSize(14);
  rectX = 0;
  rectY = 80;
  color baseColor = color(102);
  currentcolor = baseColor;
  */

}

void draw(){  
 //text("Hello Web!",20,20);
 //println("Hello ErrorLog!");
 update(mouseX, mouseY);
 //update(mouseX, mouseY);
 //<div id="=$mqttset['x']</div>
}


void mouseMoved(){
  nX = mouseX;
  nY = mouseY;  
  //println(nX+","+nY);
  textFont(font);
  fill(230, 230, 250);
  noStroke();
  rect(80,0,60,30);
  fill(0,0,0);
  text((mouseX-<?=$shimx?>)+" "+(<?=($bedsizey+$shimy)?>-mouseY),90,20);
}


</script>
<canvas id="mycanvas"></canvas>
