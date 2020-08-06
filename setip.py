import serial
import json
import subprocess,re

def whatstheports():
 output = str(subprocess.check_output("python3 -m serial.tools.list_ports -v", shell=True))
 oo = re.split('/dev', output)
 ports = {}
 for i in oo:
    if re.match('^.*Duet.*', i):
        port = re.match('^.*tty(.*) .*', i)
        ports['duet'] = re.split(' ', port.group(1))[0]
    if re.match('^.*Serial.*', i):
        port = re.match('^.*tty(.*) .*', i)
        ports['microfluidics'] = re.split(' ',port.group(1))[0]
 return ports


def openport(prt):
  try:
   ser = serial.Serial("/dev/tty"+prt, 115200, timeout=1)
   time.sleep(2)
  except:
   pass
   #print("its not connecting")
  return ser

ports = whatstheports()
print(ports)
dser = openport(ports['duet'])
dser.write(b'M552 S-1\n')
'''
print(dser.readlines())
dser.write(b'M552\n')
print(dser.readlines())
dser.write(b'M552 S0\n')
print(dser.readlines())
dser.write(b'M552\n')
print(dser.readlines())
dser.write(b'M588 S"*"\n')
print(dser.readlines())
dser.write(b'M587 S"picloud" P"raspberry"\n')
print(dser.readlines())
dser.write(b'M552 S1\n')
print(dser.readlines())
dser.write(b'M552\n')
print(dser.readlines())
dser.write(b'M587\n')
print(dser.readlines())
dser.write(b'M587\n')
print(dser.readlines())
'''
