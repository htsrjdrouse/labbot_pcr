import json,re,os,time

a = 1

while a > 0:
  cmd = 'mosquitto_pub -t "labbotmicrofl" -m "readlevel"'
  os.system(cmd)
  time.sleep(2)

