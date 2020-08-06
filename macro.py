#runeachmacrocmd(i,dser,aser)
def runeachmacrocmd(cmd,dser,aser):
  sim = 0
  if len(cmd)>0:
   if re.match("^G1",cmd):
    gss = re.split("_", cmd)
    gcodecmd = gss[0]
    tme = gss[1]
    if float(tme) < 0.2:
      tme = str(0.2)
    if sim == 0:
     #sersmoothie.readlines()
     dser.write(gcodecmd+"\r\n")
     time.sleep(float(tme))
     gg =dser.readlines()
     upublisher(cmd)
    else:
     print(gcodecmd)
     print(tme)
   if re.match("^G28",cmd):
    gss = re.split("_", cmd)
    gcodecmd = gss[0]
    tme = gss[1]
    print("sim is"+str(sim))
    if sim == 0:
     #sersmoothie.readlines()
     dser.write(gcodecmd+"\r\n")
     time.sleep(float(tme))
     gg =dser.readlines()
     upublisher(cmd)
    else:
     print(gcodecmd)
     print(tme)
   if re.match("^M114",cmd):
    upublisher(cmd)
    if sim == 0:
     gg = getposition(dser)
     upublisher(gg)
     time.sleep(0.2)
    else:
     print(cmd)
     print("1")
   if re.match("^[wash|waste|pcv].*", cmd):
     microfluidic(cmd,aser)
   if re.match("^//",cmd):
     upublisher(cmd)


def putmacrolinestogether(reformatmacro):
 macrorunready = []
 for i in reformatmacro:
  if isinstance(i, list):
   for j in i:
    macrorunready.append(j)
  else:
   macrorunready.append(i)
 return macrorunready



def tmecalc(gcodebatch):	
        mesg = readnxjson()
        coordlog = readschedularjson()
        try: 
         X = coordlog['X'][len(coordlog['X'])-1]
        except:
         X = mesg['currcoord']['X']
        try:
         Y = coordlog['Y'][len(coordlog['Y'])-1]
        except:
         Y = mesg['currcoord']['Y']
        try:
         Z = coordlog['Z'][len(coordlog['Z'])-1]
        except:
         Z = mesg['currcoord']['Z']
        try:
         E = coordlog['E'][len(coordlog['E'])-1]
        except:
         E = mesg['currcoord']['E']
        tmln = []
        b = []
        tim = 0
        poscmds = []
        ct = 0
        for i in gcodebatch:
            i = re.sub("\n|\r", "", i)
            dt = {}
	    #G1 F1800.000 E1.00000
	    #here I need to have a conditional if to separate non gcodes from gcodes
            if re.match('^G1', i):
             if re.match("^.*_", i):
              cc = re.split("_", i)
              ci = cc[0]
              tt = cc[1]
             else:
              ci = i
              tt = 0
             i = ci
             if re.match('^.*F.*', i):
              df = re.match('^.*F(.*)$', i)
              abf = re.sub('[ |X|x|Y|y|Z|z|E|e].*', '', df.group(1))
              pf = float(abf)
              if pf > 0:
               F = pf
             if re.match('^.*[Z|X|Y|E]', i):
                dt['F'] = F
                ct = ct + 1
                dt['ct'] = ct
                pe = 0
                px = 0
                py = 0
                pz = 0
                if re.match('^.*E', i):
                  d = re.match('^.*E(.*)', i)
                  abe = re.sub('[ |X|x|Y|y|Z|z|F|f].*', '', d.group(1))
                  pe = float(abe)
                  dt['diffe'] = abs(E-pe)
                  E = pe
                  dt['E'] = pe
                if re.match('^.*X', i):
                  dx = re.match('^.*X(.*)', i)
                  abx = re.sub('[ |E|e|Y|y|Z|z|F|f].*', '', dx.group(1))
                  px = float(abx)
                  dt['diffx'] = abs(X-px)
                  X = px
                  dt['X'] = px
                if re.match('^.*Y', i):
                  dy = re.match('^.*Y(.*)', i)
                  aby = re.sub('[ |E|e|X|x|Z|z|F|f].*', '', dy.group(1))
                  py = float(aby)
                  dt['diffy'] = abs(Y-py)
                  Y = py
                  dt['Y'] = py
                if re.match('^.*Z', i):
                  dz = re.match('^.*Z(.*)', i)
                  abz = re.sub('[ |E|e|X|x|Y|y|F|f].*', '', dz.group(1))
                  pz = float(abz)
                  dt['diffz'] = abs(Z-pz)
                  Z = pz
                  dt['Z'] = pz
                dt['cmd'] = i
                comp = {}
                try: 
                  comp['diffx'] = dt['diffx']
                except:
                  pass
                try: 
                  comp['diffy'] = dt['diffy']
                except:
                  pass
                try: 
                  comp['diffz'] = dt['diffz']
                except:
                  pass
                try: 
                  comp['diffe'] = dt['diffe']
                except:
                  pass
                sorted_comp = sorted(comp.items(), key=operator.itemgetter(1))
                dt['maxdiff'] = sorted_comp[int(len(comp)-1)][1]
                if dt['F'] > 0:
                  dt['time'] = (dt['maxdiff'] / dt['F']) * 60
                else: 
                  dt['time'] = 0
                if tt > 0:
                 dt['time'] = float(tt)
                tmln.append(i+"_"+str(dt['time']))
                tim = tim + dt['time']
                poscmds.append(dt)
            else:
                tmln.append(i)
        delaytme = int(tim)+1
        return tmln



def gcodesplitter(gcr):
 coordlog = readschedularjson()
 gtba = []
 ba = []
 bba = []
 tba = []
 fl = 0
 for i in gcr:
  if re.match('^G', i):
   try:
    cc = re.split('_', i)
    ci = cc[0]
    ti = cc[1]
   except:
    ti = 0
   coord = jogcoordparser(ci)
   if 'X' in coord:
    coordlog['X'].append(coord['X']) 
   if 'Y' in coord:
    coordlog['Y'].append(coord['Y']) 
   if 'Z' in coord:
    coordlog['Z'].append(coord['Z']) 
   if 'E' in coord:
    coordlog['E'].append(coord['E']) 
   writeschedularjson(coordlog)
   gtba.append(i)
   fl = 1
  else:
   fl = 0
  if fl == 1:
   bba.append(i)
  if fl == 0:
   if len(bba)>0:
    tmln = tmecalc(bba)	
    bba = []
    tba.append(tmln)
   tba.append(i)
  if i == gcr[len(gcr)-1]:
   if len(bba)>0 and re.match('^G', i):
    tmln = tmecalc(bba)	
    tba.append(tmln)
 reformatmacro = tba
 return reformatmacro

def readtaskjobjson():
  pcv = open('labbot.programtorun.json')
  pcvdata = json.load(pcv)
  pcv.close()
  return pcvdata


def writeschedularjson(dat):
  pcvdatar = json.dumps(dat)
  pcv = open('schedular.json','w')
  pcv.write(pcvdatar)
  pcv.close()

def runmacro(dser,aser):
   coordlog = {}
   coordlog['X'] =[]
   coordlog['Y'] =[]
   coordlog['Z'] =[]
   coordlog['E'] =[]
   #resets teh schedular
   writeschedularjson(coordlog)
   taskjob = readtaskjobjson()
   #reformatmacro = gcodesplitter(taskjob['data'][str(taskjob['track'])]) 
   reformatmacro = gcodesplitter(taskjob['program']) 
   macroready = putmacrolinestogether(reformatmacro)
   for i in macroready:
    runeachmacrocmd(i,dser,aser)


def readschedularjson():
  pcv = open('schedular.json')
  pcvdata = json.load(pcv)
  pcv.close()
  return pcvdata









