from tokens import MyToken
from cripto import AESCipher, encrypt  # , encrypt_aes
import re
from ds import MyValue, MyEncryptedValue
import secrets
from lib.ore_wrapper import OreVal


def _isVar(typestr):
    pattern = re.compile(r'^(FUNC[0-9]+_)?VAR[0-9]+')
    return pattern.match(typestr)


def _isOP(typestr):
    pattern = re.compile(r'OP[0-9]+')
    return pattern.match(typestr)


def _isSans(tok):
    sans = re.compile(r'[a-zA-Z]*\_SANS')
    if sans.search(tok):
        return True
    return False


def _isSens(tok):
    sens = re.compile(r'[a-zA-Z]*\_SENS')
    if sens.search(tok):
        return True
    return False

def _isFunc(tok):
    pattern = re.compile(r'^FUNC[0-9]+$')
    return pattern.match(tok)
class Worker(object):
    def __init__(self, ds, tokenstream, kd_key=None, kr_key=None, ore_params=None):
        self.ds = ds
        self.tokenstream = tokenstream
        #print("\nTokenstream: ", tokenstream)
        self.counter = {}
        
        self.next = 0
        self.order = [0]
        self.type = 0
        self.ore_params = ore_params
        self.kd_key = kd_key
        self.kr_key = kr_key
        if self.ore_params:
            self.ds.put("BASE_DEPTH", OreVal(0, self.ore_params[1][0], self.ore_params[1][1]))
        else:
            self.ds.put("BASE_DEPTH", 0)
        self.inFunction = [] #stack of function we are in
        self.funcCalls = {} #args and calls 

    def store(self, depth):
        if self.next >= len(self.tokenstream):
            #if there are functions defined elsewhere we create entries of the assign to all the calls
            for func in self.funcCalls:
                for assign in self.funcCalls[func]["assigns"]:
                    for call in self.funcCalls[func]["calls"]:
                        for call_i in call:
                            self.create_entry(assign, call_i, call_i.lineno, depth, self.order[depth], self.type)
            return
        else:
            curr = self.tokenstream[self.next]
            if _isVar(curr.type) and self.next+1 < len(self.tokenstream) and self.tokenstream[self.next+1].type == "OP0":
                key = curr
                self.next += 2
                curr = self.tokenstream[self.next]
                while curr.type != "END_ASSIGN" :
                    if _isFunc(curr.type):
                        self.handle_func_call(curr, depth, key)
                    if _isSens(curr.type) or _isSans(curr.type):
                        self.next += 1
                        dummie = self.tokenstream[self.next]
                        while dummie.type != "END_CALL":
                            if not _isOP(dummie.type) and dummie.type != "NEXT_ARG":
                                self.create_entry(curr, dummie, curr.lineno, depth, self.order[depth], self.type)
                            self.next += 1
                            dummie = self.tokenstream[self.next]
                    if not _isOP(curr.type) and curr.type != "END_ASSIGN" and curr.type != "END_CALL":
                        self.create_entry(key,curr, key.lineno, depth, self.order[depth], self.type)
                    
                    self.next += 1
                    curr = self.tokenstream[self.next]
                
            elif _isSens(curr.type) or _isSans(curr.type):
                key = curr
                self.next += 1
                curr = self.tokenstream[self.next]
                while curr.type != "END_CALL":
                    if not _isOP(curr.type) and curr.type != "NEXT_ARG":
                        self.create_entry(key,curr, key.lineno, depth, self.order[depth], self.type)
                    self.next += 1
                    curr = self.tokenstream[self.next]
            elif curr.type == "BEGINFUNCDEF": #handle function definition
                self.next += 1
                curr = self.tokenstream[self.next]
                func = curr
                funcname = func.type
                self.next += 1
                curr = self.tokenstream[self.next]
                while curr.type != "ENDFUNCDEF":
                    if funcname not in self.funcCalls: 
                        self.funcCalls[funcname] = {
                            "args": [],
                            "calls": [],
                            "assigns": [] #keep track of vars that are assigned to the return value of the function
                        }
                    self.funcCalls[funcname]["args"].append(curr)
                    self.next += 1
                    curr = self.tokenstream[self.next]
                self.inFunction.append(func)
                #check if there were already calls to this function 
                if funcname in self.funcCalls:
                    for arg in self.funcCalls[funcname]["args"]:
                        if len(self.funcCalls[funcname]["calls"]) > 0:
                            call = self.funcCalls[funcname]["calls"].pop(0)
                            for call_i in call:
                                self.create_entry(arg, call_i, call_i.lineno, depth, self.order[depth], self.type)
                    self.funcCalls[funcname]["assigns"] = [] #reset assigns 
            elif _isFunc(curr.type): #function calls without caring for return value 
                self.handle_func_call(curr, depth)
            elif curr.type == "RETURN" and len(self.inFunction) > 0:
                self.next += 1
                curr = self.tokenstream[self.next]
                while curr.type != "ENDFUNCBLOCK":
                    if _isFunc(curr.type):
                        self.handle_func_call(curr, depth, key)
                    if _isSens(curr.type) or _isSans(curr.type):
                        self.next += 1
                        dummie = self.tokenstream[self.next]
                        while dummie.type != "END_CALL":
                            self.next += 1
                            dummie = self.tokenstream[self.next]
                    if not _isOP(curr.type) and curr.type != "END_ASSIGN" and curr.type != "END_CALL":
                        self.create_entry(self.inFunction[-1],curr, self.inFunction[-1].lineno, depth, self.order[depth], self.type)
                    self.next += 1
                    curr = self.tokenstream[self.next]
                self.inFunction.pop()
            elif curr.type == "ENDFUNCBLOCK" and len(self.inFunction) > 0:
                self.inFunction.pop()
            elif curr.type == "ELSEIF" or curr.type == "CASE":
                next = self.tokenstream[self.next+1]
                while next.type != "END_COND" and next.type != "ENDCASE":
                    self.next += 1
                    next = self.tokenstream[self.next]
                self.next += 1
                aux = self.type
                self.type += 1  # TODO
                self.store(depth)
                self.type = aux
            elif curr.type == "IF":
                next = self.tokenstream[self.next+1]
                while next.type != "END_COND":
                    self.next += 1
                    next = self.tokenstream[self.next]
                self.next += 1
                if len(self.order) == depth+1:
                    self.order.append(0)
                else:
                    self.order[depth+1] = self.order[depth+1]+1
                aux = self.type
                self.type = 1  # TODO
                self.store(depth+1)
                self.type = aux
            elif curr.type == "ELSE":
                self.next += 1
                aux = self.type
                self.type = -1  # TODO
                self.store(depth)
                self.type = aux
            elif curr.type == "ENDIF" or curr.type == "ENDELSE" or curr.type == "ENDELSEIF" or curr.type == "ENDCASE":
                return
        self.next += 1
        self.store(depth)

    def handle_func_call(self, curr, depth, assign=None):
        funcname = curr.type

        if funcname not in self.funcCalls:
            self.funcCalls[funcname] = {
                        "args": [],
                        "calls": [],
                        "assigns": []  
                    }
        #keep track of vars that are assigned to the return value of the function
        #only if the function is defined outside the scope of the analysed code
        if assign and not self.funcCalls[funcname]["args"]: 
            self.funcCalls[funcname]["assigns"].append(assign)
        self.next += 1 
        curr = self.tokenstream[self.next]
        current_arg = 0
        while curr.type != "END_CALL":
            if curr.type == "NEXT_ARG":
                current_arg += 1
                self.next += 1
                curr = self.tokenstream[self.next]

            if len(self.funcCalls[funcname]["args"]) > current_arg:
                self.create_entry(self.funcCalls[funcname]["args"][current_arg], curr, curr.lineno, depth, self.order[depth], self.type)
            else:
                if len(self.funcCalls[funcname]["calls"]) <= current_arg:
                    self.funcCalls[funcname]["calls"].append([])
                self.funcCalls[funcname]["calls"][current_arg].append(curr)
            if _isFunc(curr.type):
                self.handle_func_call(curr, depth)
                curr = self.tokenstream[self.next]
                continue
            self.next += 1 
            curr = self.tokenstream[self.next]

    #(DET(D_Var2, 2) , RND(R_Var2, {D_Var1, R_Var1 , 4, 0,0,0})
    def create_entry(self, key_ind, val_ind, lineno, depth, order, type, scope="GLOBAL"):
        if len(self.inFunction) > 0:
            scope = self.inFunction[-1].type
        #print(key_ind, "-->",val_ind)
        if self.kd_key and self.kr_key:
            val_lineno = val_ind.lineno

            key_ind = key_ind.type
            val_ind = val_ind.type 
            self.counter[key_ind] = self.counter.get(key_ind, 0) + 1
            
            #cryptographic keys
            key_detkey = encrypt(self.kd_key, key_ind)
            key_rndkey = encrypt(self.kr_key,key_ind)
            val_detkey = encrypt(self.kd_key, val_ind)
            
            #hide scope (function token)
            scope = encrypt(self.kd_key, scope) 
            
            val_rndkey = encrypt(self.kr_key,val_ind)            

            val_ind = MyEncryptedValue(MyToken(val_detkey,val_lineno), val_rndkey, lineno, depth, order, type, scope, self.ore_params)
            val_ind = AESCipher(key_rndkey).encrypt(val_ind._serialize())
            key_ind = encrypt(key_detkey, str(self.counter[key_ind]))
        else:
            val_ind = MyValue(
            key_ind.lineno, depth, MyToken(val_ind.type,val_ind.lineno), self.order[depth], self.type, scope)
            key_ind = key_ind.type
        
        self.ds.put(key_ind, val_ind)
