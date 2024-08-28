from lib.ore_wrapper import OreVal
from tokens import *


class MyValue(object):
    def __init__(self, lineno, flowinfo, token, order, type, scope = "GLOBAL"):
        self.lineno = lineno
        self.flowinfo = flowinfo
        self.token = token
        self.order = order
        self.type = type
        self.scope = scope
        
            

    def get_line(self):
        return self.lineno

    def get_flow(self):
        return self.flowinfo

    def get_token(self):
        return self.token

    def get_order(self):
        return self.order

    def get_type(self):
        return self.type
    
    def get_scope(self):    
        return self.scope

    def __repr__(self):
        return f'MyValue({self.lineno}, {self.flowinfo}, {self.order}, {self.type},{self.token},{self.scope})'

    def _serialize(self):
        #have to indicate if we are using ore or not
        return ("ore;;" if isinstance(self.flowinfo, OreVal) else "") + str(self.lineno) + ";;" + str(self.flowinfo) + ";;" + self.token.type + ";;" + str(self.token.lineno) + ";;" + str(self.order) + ";;" + str(self.type) + ";;" + self.scope

    def to_bytes(self):
        return bytes(self.lineno)+bytes(self.flowinfo)+bytes(self.token)+bytes(self.order)+bytes(self.type)+bytes(self.scope)
    @classmethod
    def _deserialize(self, text):
        a = text.split(";;")
        return MyValue(int(a[0]), int(a[1]), MyToken(a[2], int(a[3])), int(a[4]), int(a[5]), a[6])


class MyEncryptedValue(MyValue): #same as super class but with a random key and a deterministic key
    def __init__(self, detkey, rndkey : bytes,lineno, flowinfo, order,type,scope="GLOBAL",ore_params = None):
        #stored with a token where the type is actually the deterministic key
        #that way i have to do less changes in vulnDetector
        self.detkey = detkey 
        self.rndkey = rndkey
        self.lineno = lineno
        self.flowinfo = flowinfo
        self.order = order
        self.type = type
        self.scope = scope
        self.ore_params = ore_params
        # Order Revealing Encryption
        if ore_params:
            self.ore_params = ore_params
            self.detkey = MyToken(detkey.type, OreVal(detkey.lineno, ore_params[0][0], ore_params[0][1]))
            self.lineno = OreVal(lineno, ore_params[0][0], ore_params[0][1])
            self.flowinfo = OreVal(flowinfo, ore_params[1][0], ore_params[1][1])
            self.order = OreVal(order, ore_params[2][0], ore_params[2][1])
            self.type = OreVal(type, ore_params[3][0], ore_params[3][1])

    def _serialize(self):
        if self.ore_params:
            return "ore;;"+str(self.detkey.type)+";;"+str(self.detkey.lineno) + ";;" + str(self.rndkey) + ";;" + str(self.lineno) + ";;" + str(self.flowinfo) + ";;" + str(self.order) + ";;" + str(self.type)+ ";;" + str(self.scope)
        else:
            return str(self.detkey.type)+";;"+str(self.detkey.lineno) + ";;" + str(self.rndkey) + ";;" + str(self.lineno) + ";;" + str(self.flowinfo) + ";;" + str(self.order) + ";;" + str(self.type)+ ";;" + str(self.scope)
    

    @classmethod
    def _deserialize(self, text):
        a = text.split(";;")
        if a[0] == "ore":
            return MyEncryptedValue(MyToken(a[1],OreVal(a[2])), a[3], OreVal(a[4]), OreVal(a[5]), OreVal(a[6]), OreVal(a[7]), a[8])
        else:
            return MyEncryptedValue(MyToken(a[0],int(a[1])), a[2], int(a[3]), int(a[4]), int(a[5]), int(a[6]), a[7])
        
    def __repr__(self):
        return f"MyEncryptedValue({self.detkey}, {self.rndkey}, {self.lineno}, {self.flowinfo}, {self.order}, {self.type}, {self.scope})"
    
    def get_det_key(self):
        return self.detkey
    
    def get_rnd_key(self):
        return self.rndkey

    
class DataStructure(object):
    def __init__(self):
        self.data = {}

    def put(self, key, value):
        if key in self.data:
            self.data[key].append(value)
        else:
            self.data[key] = [value]

    def get(self, key):
        if not key in self.data:
            return None
        else:
            return self.data[key]
