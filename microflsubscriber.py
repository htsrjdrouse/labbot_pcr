import paho.mqtt.client as mqtt 
import subprocess,re
import serial,usb
import time,datetime,os
import numpy as np
import json
import operator
from adafruit_servokit import ServoKit


#microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setlevelval":60,"heatval":100}
def writemicrofluidicsjson(microflstatus):
   nx = open('microfluidics.json')
   nxdata = json.load(nx)
   nx.close()
   mjson = open('microfluidics.json','w')
   mm = {}
   mm['manpcv'] = microflstatus['manpcv']
   mm['heaton'] = microflstatus['heaton']
   mm['washon'] = microflstatus['washon']
   mm['wasteon'] = microflstatus['wasteon']
   mm['pcvon'] = microflstatus['pcvon']
   mm['setlevelval'] = microflstatus['setlevelval']
   mm['heatval'] = microflstatus['heatval']
   mm['valvepos']=nxdata['valvepos']
   mm['tiplist']=nxdata['tiplist']
   #print(mm)
   datar = json.dumps(mm, sort_keys=True)
   mjson.write(datar)
   mjson.close()

def upublisher(mesg):
  aa = {}
  ts = time.gmtime()
  tts = time.strftime("%Y-%m-%d %H:%M:%S", ts)
  aa['mesg'] = mesg + ' -- ' +tts
  #cmd = "mosquitto_pub  -t 'labbot3d_1_control_track' -u smoothie -P labbot3d -d -m '"+aa['mesg']+"'" 
  cmd = "mosquitto_pub  -t 'labbot3d_track' -m '"+aa['mesg']+"'" 
  os.system(cmd) 
  '''
  if re.match('^G1.*X.*Y.*', mesg):
     ppos = coordparser(cmd)
     yy = 'X'+str(ppos['X'])+'_Y'+str(ppos['Y'])
     cmd = "mosquitto_pub  -t 'logger' -m '"+yy+"'" 
     os.system(cmd) 
  '''



def leveldisplay(aser,cmd):
  aser.write(cmd.encode()+"\n".encode())
  temp = aser.readlines()
  temp = (temp[0].decode().rstrip()) 
  cmd = 'mosquitto_pub -t "temp" -m "'+temp+'"'
  os.system(cmd)


#Establish a connection to MQTT
def establishconnection():
      ### mqtt ###
      broker_address="localhost" 
      print("creating new instance")
      client = mqtt.Client("P3") #create new instance
      client.on_message=on_message #attach function to callback
      print("connecting to broker")
      client.connect(broker_address) #connect to broker
      print("Subscribing to topic","labbotmicrofl")
      client.subscribe("labbotmicrofl")
      print("ok its subscribed")
      # Continue the network loop
      client.loop_forever()
      return client





def whatstheports():
 output = str(subprocess.check_output("python3 -m serial.tools.list_ports -v", shell=True))
 oo = re.split('/dev', output)
 ports = {}
 for i in oo:
    if re.match('^.*Arduino Micro.*', i):
        port = re.match('^.*tty(.*) .*', i)
        #ports['microfluidics'] = re.split(' ',port.group(1))[0]
        pport = re.split(' ', port.group(1))[0]
        #print(pport)
        try:
         ser = openport(pport)
         ser.write(b"info\n")
         a = ser.readlines()
         b = str((a[0].decode()))
         #print(b)
         if re.match("multisteppe.*",b):
            ports['syringe'] = pport
         if re.match("wash_dry_pcv_electrocaloric_kill_stepper_valv.*", b):
            ports['microfluidics'] = pport
         ser.close()
        except:
         pass
 return ports


def openport(prt):
  try:
   ser = serial.Serial("/dev/tty"+prt, 115200, timeout=0.2)
   time.sleep(2)
  except:
   print("its not connecting")
  return ser


## mqtt message handler ##
def on_message(client, userdata, message):
    cmd = str(message.payload.decode("utf-8"))
    if re.match("^setval.*", cmd):
      microflstatus['heatval'] = int(re.sub("^setval ", "", cmd))
      aser.write(cmd.encode())
    #if re.match("^heater.*", cmd):
    #  aser.write(cmd.encode())
    if cmd == "washon":
      microflstatus['washon'] = 1
      aser.write(cmd.encode()+"\n".encode())
    if cmd == "washoff":
      microflstatus['washon'] = 0
      #print(cmd)
      aser.write(cmd.encode()+"\n".encode())
    if cmd == "wasteon":
      #print(cmd)
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['wasteon'] = 1
      aser.write("dryon".encode()+"\n".encode())
    if cmd == "wasteoff":
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['wasteon'] = 0
      aser.write("dryoff".encode()+"\n".encode())
    if cmd == "pcvoff":
      #print(cmd)
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['pcvon'] = 0
      aser.write(cmd.encode()+"\n".encode())
    if cmd == "pcvon":
      #print(cmd)
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['pcvon'] = 1
      aser.write(cmd.encode()+"\n".encode())
    if cmd == "manpcv":
      aser.write("setlevelval 0".encode()+"\n".encode())
      time.sleep(0.5)
      aser.write("manpcv".encode()+"\n".encode())
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['manpcv'] = 1
    if cmd == "feedbackpcv":
      #print(cmd)
      time.sleep(0.5)
      aser.write(cmd.encode()+"\n".encode())
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['manpcv'] = 0
    if cmd == "heaton":
      #print(cmd)
      aser.write(cmd.encode()+"\n".encode())
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['heaton'] = 1
    if cmd == "heatoff":
      #print(cmd)
      aser.write(cmd.encode()+"\n".encode())
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setpcvval":60,"heatval":100}
      microflstatus['heaton'] = 0
    if cmd == "readlevel":
      leveldisplay(aser,cmd)
    if re.match("setlevelval.*", cmd):
      print(cmd)
      #microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setlevelval":60,"heatval":100}
      microflstatus['setlevelval'] = int(re.sub("^setlevelval ", "", cmd))
      microflstatus['heaton'] = 0
      aser.write(cmd.encode()+"\n".encode())
    if re.match("heatval.*", cmd):
      microflstatus['heatval'] = int(re.sub("^heatval ", "", cmd))
      print(cmd)
      aser.write(cmd.encode()+"\n".encode())


ports = whatstheports()
aser = openport(ports['microfluidics'])

aser.write("manpcv".encode()+"\n".encode())
time.sleep(0.2)
aser.write("heatoff".encode()+"\n".encode())
time.sleep(0.2)
aser.write("washoff".encode()+"\n".encode())
time.sleep(0.2)
aser.write("wasteoff".encode()+"\n".encode())
time.sleep(0.2)
aser.write("dryoff".encode()+"\n".encode())
time.sleep(0.2)
aser.write("pcvoff".encode()+"\n".encode())
time.sleep(0.2)
microflstatus = {}
#microflstatus = {"manpcv":1,"heaton":0,"washon":0,"wasteon":0,"pcvon":0:"setlevelval":60,"heatval":100}
microflstatus["manpcv"] = 1 
microflstatus["heaton"]= 0
microflstatus["washon"] = 0
microflstatus["wasteon"] = 0
microflstatus["pcvon"] = 0
microflstatus["setlevelval"] = 60
microflstatus["heatval"] = 100
writemicrofluidicsjson(microflstatus)


client = establishconnection()


