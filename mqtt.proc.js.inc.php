   <script type="text/javascript">
   var client = new Messaging.Client("<?=$_SERVER['SERVER_ADDR']?>", 8080, "myclientid_" + parseInt(Math.random() * 100, 10));
    client.onConnectionLost = function (responseObject) {
     alert("connection lost: " + responseObject.errorMessage);
   };
    //Gets called whenever you receive a message for your subscriptions
   client.onMessageArrived = function (message) {
     //Do something with the push message you received
	   //$('#messages').append('<span>Topic: ' + message.destinationName + '  | ' + message.payloadString + '</span><br/>');
     var pos = message.payloadString.split("_")
     var x = pos[0]
     var y = pos[1]

     $('#<?=$mqttset['divmsg']?>').text(' ' + message.payloadString);
    };
     $('#<?=$mqttset['x']?>').text(x);
     $('#<?=$mqttset['y']?>').text(y);
    //Connect Options
    var options = {
      timeout: 3,
      //Gets Called if the connection has sucessfully been established
      onSuccess: function () {
      //alert("Connected");
	//client.subscribe('testtopic', {qos: 2}); 
	      client.subscribe('<?=$mqttset['topic']?>', {qos: 2}); 
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
     // client.send(message);
     //}
     client.connect(options);
</script>

