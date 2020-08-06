import subprocess,re
import sys
import psutil


def killproc(script):
 process = subprocess.Popen("ps aux | grep "+script,shell=True,stdout=subprocess.PIPE)
 stdout_list = process.communicate()[0].decode()
 aa =(stdout_list.split('\n'))
 for i in aa:
  try: 
   pid = int(re.split("\s+", i)[1])
   print(pid)
   process = psutil.Process(int(pid))
   process.kill()
  except:
   pass





killproc('positiondisplay.py')

