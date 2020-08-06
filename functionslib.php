<?


function publish_message($msg, $topic, $server, $port, $keepalive) {
        $json = json_decode(file_get_contents('/home/pi/config.json'), true);
	$client = new Mosquitto\Client();
	$client->onConnect('connect');
        //$client->setCredentials('ampmicrofl', 'labbot3d');
        $client->setCredentials($json['username'], $json['password']);
	$client->onDisconnect('disconnect');
	$client->onPublish('publish');
	$client->connect($server, $port, $keepalive);
	try {
		$client->loop();
		$mid = $client->publish($topic, $msg);
		$client->loop();
		}catch(Mosquitto\Exception $e){
				echo 'Exception';          
				return;
			}
    $client->disconnect();
    unset($client);					    
}

// Call back functions required for publish function
function connect($r) {
		if($r == 0) echo "{$r}-CONX-OK|";
		if($r == 1) echo "{$r}-Connection refused (unacceptable protocol version)|";
		if($r == 2) echo "{$r}-Connection refused (identifier rejected)|";
		if($r == 3) echo "{$r}-Connection refused (broker unavailable )|";        
}


 
function publish() {
        global $client;
        echo "Message published:";
}
 
function disconnect() {
        echo "Disconnected|";
}




//Relay and linear actuator on marlin8pi 
function socketclient($ip,$cmd,$json){
        //Stop button - here I need to check the stop button thing ...
        // where is the socket server?
        $host=$ip;
        $port = 8888;
        //echo $cmd;

        // open a client connection
        $fp = fsockopen ($host, $port, $errno, $errstr);
        if (!$fp)
        {
                $result = "Error: could not open socket connection";
                echo $result.'<br>';
        }
        else
        {
        if (preg_match('/^liquidlevel/', $cmd)){
          fputs ($fp, $cmd);
          $result = fread ($fp, 1024);
          $json['pressure']['read'] = $result;
        }
        else if (preg_match('/^M114/', $cmd)){
          fputs ($fp, $cmd);
          //$result = fread ($fp, 1024);
          //$json['position'] = $result;
        }
        else {
         $message = $cmd;
         fputs ($fp, $message);
        }
        fclose ($fp);
        }
        return $json;
}

function openjson($jsonfile){
  $imgdataset = $jsonfile;
  $json = json_decode(file_get_contents($imgdataset), true);
  return $json;
}

function closejson($json,$jsonfile){
  //var_dump($json);
  //echo "closejson";
  $imgdataset = $jsonfile;
  file_put_contents($imgdataset, json_encode($json));
}
?>

