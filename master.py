import paho.mqtt.client as mqtt 
import subprocess,re
import serial,usb
import time,datetime,os
import numpy as np
import json
import struct
import sys
import psutil


def killproc(script):
 process = subprocess.Popen("ps aux | grep "+script,shell=True,stdout=subprocess.PIPE)
 stdout_list = process.communicate()[0].decode()
 aa =(stdout_list.split('\n'))
 for i in aa:
  try: 
   pid = int(re.split("\s+", i)[1])
   process = psutil.Process(int(pid))
   process.kill()
  except:
   pass



#Establish a connection to MQTT
def establishconnection():
      ### mqtt ###
      broker_address="localhost" 
      print("creating new instance")
      client = mqtt.Client("P2") #create new instance
      client.on_message=on_message #attach function to callback
      print("connecting to broker")
      client.connect(broker_address) #connect to broker
      print("Subscribing to topic","controllabbot")
      client.subscribe("controllabbot")
      print("ok its subscribed")
      # Continue the network loop
      client.loop_forever()
      return client



## mqtt message handler ##
def on_message(client, userdata, message):
    print("message received")
    cmd = str(message.payload.decode("utf-8"))
    if cmd == "turnon":
      print("turning on ... ")
      #subprocess.Popen(["sudo","python3", "/home/pi/labbot/microflsubscriber.py"]).pid
      subprocess.Popen(["sudo","python3", "/var/www/html/labautobox/microflsubscriber.py"]).pid
      time.sleep(0.5)
      #subprocess.Popen(["sudo","python3", "/home/pi/labbot/subscriber.py"]).pid
      subprocess.Popen(["sudo","python3", "/var/www/html/labautobox/subscriber.py"]).pid
      time.sleep(0.5)
      #subprocess.Popen(["sudo","python3", "/home/pi/labbot/positiondisplay.py"]).pid
      subprocess.Popen(["sudo","python3", "/var/www/html/labautobox/positiondisplay.py"]).pid
      time.sleep(0.5)
      #subprocess.Popen(["sudo","python3", "/home/pi/labbot/leveldisplay.py"]).pid
      subprocess.Popen(["sudo","python3", "/var/www/html/labautobox/leveldisplay.py"]).pid
      time.sleep(0.5)
      #subprocess.Popen(["sudo","python3", "/home/pi/labbot/temperaturedisplay.py"]).pid
      subprocess.Popen(["sudo","python3", "/var/www/html/labautobox/temperaturedisplay.py"]).pid
    if re.match("^turnoff.*", cmd):
      killproc('subscriber.py')
      print("turning off ... ")
      time.sleep(0.5)
      killproc('positiondisplay.py')
      time.sleep(0.5)
      killproc('leveldisplay.py')
      time.sleep(0.5)
      killproc('temperaturedisplay.py')
    if cmd == "restart":
      killproc('subscriber.py')
      time.sleep(2)
      subprocess.Popen(["sudo","python3", "/home/pi/labautobox/subscriber.py"]).pid
    if cmd == "kill positiondisplay":
      killproc('positiondisplay.py')
    if cmd == "run positiondisplay":
      subprocess.Popen(["sudo","python3", "/home/pi/labautobox/positiondisplay.py"]).pid
    if cmd == "kill leveldisplay":
      killproc('leveldisplay.py')
    if cmd == "run leveldisplay":
      subprocess.Popen(["sudo","python3", "/home/pi/labautobox/leveldisplay.py"]).pid
    if cmd == "kill temperaturedisplay":
      killproc('temperaturedisplay.py')
    if cmd == "run temperaturedisplay":
      subprocess.Popen(["sudo","python3", "/home/pi/labautobox/temperaturedisplay.py"]).pid




client = establishconnection()



