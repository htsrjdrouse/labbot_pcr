import serial
import subprocess,re
import serial,usb
import time,datetime,os
import numpy as np
import json




def whatstheports():
 output = str(subprocess.check_output("python3 -m serial.tools.list_ports -v", shell=True))
 oo = re.split('/dev', output)
 ports = {}
 for i in oo:
    if re.match('^.*Duet.*', i):
        port = re.match('^.*tty(.*) .*', i)
        ports['duet'] = re.split(' ', port.group(1))[0]
    if re.match('^.*Arduino Micro.*', i):
        port = re.match('^.*tty(.*) .*', i)
        #ports['microfluidics'] = re.split(' ',port.group(1))[0]
        pport = re.split(' ', port.group(1))[0]
        print(pport)
        ser = openport(pport)
        ser.write(b"info\n")
        a = ser.readlines()
        b = str((a[0].decode()))
        print(b)
        if re.match("multisteppe.*",b):
            ports['syringe'] = pport
        if re.match("wash_dry_pcv_electrocaloric_kill_stepper_valv.*", b):
            ports['microfluidics'] = pport
        ser.close()
 return ports










def openport(prt):
  try:
   ser = serial.Serial("/dev/tty"+prt, 115200, timeout=0.2)
   time.sleep(2)
  except:
   print("its not connecting")
  return ser

ports = whatstheports()
print(ports)
dser = openport(ports['duet'])
sser = openport(ports['syringe'])
time.sleep(3)
#dser.write(b'M106 P0 I1 F25000 \n')

#dser.write(b'M106 P0 I0 F25000 \n')
#dser.write(b'M106 P0 I1 F25000 \n')
dser.write(b'M563 P1 D1 H2\n')
dser.write(b'M305 P2 R4700 T100000 B4388\n')
dser.write(b'M307 H1 A240 C640 D5.5 V12\n')
dser.write(b'M307 H2 A240 C640 D5.5 V12\n')
