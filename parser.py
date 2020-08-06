import re

def xyzgetposition():
 pos = "X:0.000 Y:0.000 Z:0.000"
 aa = (re.split(" ", pos))
 gg = []
 pos = {}
 for i in aa:
  aaa = re.split(":", i)
  pos[aaa[0]] = aaa[1]
 return pos


pos = xyzgetposition()
print(pos)

