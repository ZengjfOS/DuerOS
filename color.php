<!DOCTYPE html>
<html>
  <head>
    <title>Start Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
  </head>
  <body id="body_color">

    <!-- 
        Eclipse Paho JavaScript Client 
            https://www.eclipse.org/paho/clients/js/
    -->
 
    <script>
        // Create a client instance
        var client = new Paho.MQTT.Client("zengjf.mqtt.iot.gz.baidubce.com", 8884, "DeviceId-egkn9o");
        
        // set callback handlers
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;
        
        // connect the client
        client.connect({onSuccess:onConnect, onFailure:onConnectError, userName:"zengjf/sz_monitor_room", password:"QE0BHFvFnIkBRIaJtPYzo3m/63Esv5fzzMr9tYVOsHo=", useSSL:true});
        
        
        // called when the client connects
        function onConnect() {
          // Once a connection has been made, make a subscription and send a message.
          console.log("onConnect");
          client.subscribe("test-iot-service");
        }

        // called when the client connects
        function onConnect() {
          // Once a connection has been made, make a subscription and send a message.
          console.log("onConnect");
          client.subscribe("test-iot-service");
        }

        // called when the client connects
        function onConnectError() {
          // Once a connection has been made, make a subscription and send a message.
          console.log("onConnectError");
        }
        
        // called when the client loses its connection
        function onConnectionLost(responseObject) {
          if (responseObject.errorCode !== 0) {
            console.log("onConnectionLost:"+responseObject.errorMessage);
          }
        }
        
        // called when a message arrives
        function onMessageArrived(message) {
          console.log("onMessageArrived:"+message.payloadString);
          json_data = JSON.parse(message.payloadString);
          document.getElementById("body_color").style.background = json_data["color"];
        }
 
        console.log("MQTT Client Set Over, Wait Data Tranfer.")
    </script>
  </body>
</html>
