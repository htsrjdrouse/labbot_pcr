   <script type="text/javascript">
   var <?=$bmqttset['client']?> = new Messaging.Client("<?=$_SERVER['SERVER_ADDR']?>", 8080, "my<?=$bmqttset['client']?>id_" + parseInt(Math.random() * 100, 10));
    <?=$bmqttset['client']?>.onConnectionLost = function (responseObject) {
     alert("connection lost: " + responseObject.errorMessage);
   };
    //Gets called whenever you receive a message for your subscriptions
   <?=$bmqttset['client']?>.onMessageArrived = function (message) {
     //Do something with the push message you received
     //$('#messages').append('<span>Topic: ' + message.destinationName + '  | ' + message.payloadString + '</span><br/>');
	    $('#<?=$bmqttset['divmsg']?>').text(' ' + message.payloadString);
    };
    //Connect Options
    var options = {
      timeout: 3,
      //Gets Called if the connection has sucessfully been established
      onSuccess: function () {
      //alert("Connected");
	//<?=$mqttset['client']?>.subscribe('testtopic', {qos: 2}); 
	      <?=$bmqttset['client']?>.subscribe('<?=$bmqttset['topic']?>', {qos: 2}); 
      },
      //Gets Called if the connection could not be established
      onFailure: function (message) {
         //alert("Connection failed: " + message.errorMessage);
      }
     };
     //Creates a new Messaging.Message Object and sends it to the HiveMQ MQTT Broker
     //var publish = function (payload, topic, qos) {
     //Send your message (also possible to serialize it as JSON or protobuf or just use a string, no limitations)
     // var message = new Messaging.Message(payload);
     // message.destinationName = topic;
     // message.qos = qos;
     // <?=$mqttset['client']?>.send(message);
     //}
     <?=$bmqttset['client']?>.connect(options);
</script>

