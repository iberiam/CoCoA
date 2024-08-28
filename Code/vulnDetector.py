from copy import copy
import re
from unittest import result
from cripto import *  # decrypt_aes
from ds import *


class VulnerabilityDetector(object):

    def __init__(self, data, aeskey):
        self.ds = data
        self.output = []
        self.path = []
        self.visited = []
        self.alg = AESCipher(aeskey)
        #check if any entry has more than one value
        # for k, v in self.ds.data.items():
        #     if len(v) > 1:
        #         print("Warning: Multiple values for key " + k)

    #depth first search sse
    def sse_search(self,end, start, cur_rndkey, line=None, flow=0, order=0, type=0, scope=None):
        if line:
            self.path.append((start, line, flow, order, type, scope))

        counter = 1
        key_ind = encrypt(start,str(counter))
        if key_ind not in self.ds.data or start == end:
            self.output.append(copy(self.path))
            return
        counter = 1
        while True:
            
            key_ind = encrypt(start,str(counter))
            if key_ind not in self.ds.data:
                break
            val = self.ds.data[key_ind][0]
            val_ind = AESCipher(cur_rndkey).decrypt(val)
            val_ind = MyEncryptedValue._deserialize(val_ind)

            currLine = val_ind.get_line()
            currFlow = val_ind.get_flow()
            currOrder = val_ind.get_order()
            currType = val_ind.get_type()
            currScope = val_ind.get_scope()
            currToken = val_ind.get_det_key()
            currRndKey = val_ind.get_rnd_key()

            if currToken not in self.visited:
                self.visited.append(currToken)
                if not line:
                    self.path = [None]
                    self.path[0] = (start, currLine, currFlow,
                                    currOrder, currType, currScope)
                self.sse_search(end, currToken.type,currRndKey,
                            currToken.lineno, currFlow, currOrder, currType, currScope)
                self.path.pop()
                self.visited.remove(currToken)
            counter += 1
        return


        


    def search(self, end, start, line=None, flow=0, order=0, type=0, scope=None):
        if line:
            self.path.append((start, line, flow, order, type, scope))

        if start == end or start not in self.ds.data:
            self.output.append(copy(self.path))
            return

        val = self.ds.data[start]
        for v in val:
            currToken = v.get_token()
            currLine = v.get_line()
            currFlow = v.get_flow()
            currOrder = v.get_order()
            currType = v.get_type()
            currScope = v.get_scope()
            if v.get_token() not in self.visited:
                self.visited.append(currToken)
                if not line:
                    self.path = [None]
                    self.path[0] = (start, currLine, currFlow,
                                    currOrder, currType, currScope)
                self.search(end, currToken.type,
                            currToken.lineno, currFlow, currOrder, currType, currScope)
                self.path.pop()
                self.visited.remove(currToken)

    def detection(self,start, end, sans, common_sans, init_rnd_key = None):
        if init_rnd_key:
            self.sse_search( start,end, init_rnd_key)
        else:
            self.search(start, end)
        # operations over output
        #for x in self.output:
            #print(x)
        final = {}
        group_by_vulns = {}
        myresult = []

        self.output = [x for x in self.output if x]
        for i in self.output:
            if ore_tuple(i[0]) in group_by_vulns:
                group_by_vulns[ore_tuple(i[0])].append(i)
            else:
                group_by_vulns[ore_tuple(i[0])] = [i] 

        for k, v in group_by_vulns.items():
            # remove substitutions after vuln
            # also remove invalid paths
            to_remove = set()
            lowest = {} # according to scope
            for i, path in enumerate(v):
                lowest[path[0][5]] = path[0][1]
                for j in path[1:]:
                    #print(j)
                    if j[5] in lowest and j[1] > lowest[j[5]]:
                        try:
                            to_remove.add(i)
                        except:
                            pass
                    else:
                        lowest[j[5]] = j[1]
            new_v = []
            for i in range(len(v)):
                if i not in to_remove:
                    new_v.append(v[i])
            v = new_v
            group_by_vulns[k] = v
            # depth checker
            # for i in range(1, max(len(x) for x in v)):
            #     for j in range(0, len(v)):z
            #         continue
            # find closest path to vulnerability
            # group by control flows
            # one group with every control flow
            groups =  {}
            flows = []
            for i in v:
                control_flows = []
                for j in i:
                    control_flows.append(ore_tuple((j[2], j[3], j[4])))
                flows.append((control_flows,i))
            flows = sorted(flows, key=lambda x: len(x[0]), reverse=True)
            for control_flows, i in flows:
                tup = tuple(control_flows)
                if tup in groups:
                    groups[tup].append(i)
                else:
                    #check if a subset of the control flow is already in the dict
                    subset_found = False
                    for k in groups.keys():
                        if len(k) > len(tup) and k[:len(tup)] == tup:
                            groups[k].append(i)
                            subset_found = True
                            break
                    if not subset_found:
                        groups[tup] = [i]
            #Find the closest path for each of the sets
            for k,path_set in groups.items():
                best_match = None
                discarted = set()
                for i in range(1, max(len(x) for x in path_set)):
                    closest = None, None
                    for j in range(0, len(path_set)):
                        if j in discarted: continue
                        if i < len(path_set[j]):
                            if not closest[0]:
                                closest = path_set[j][i], j
                                best_match = path_set[j]
                            elif path_set[j][i][1] > closest[0][1] or (path_set[j][i][1] == closest[0][1] and path_set[j][i][0] == start):
                                discarted.add(closest[1])
                                closest = path_set[j][i], j
                                best_match = path_set[j]
                            
                #print(closest)
                #print(best_match)
                # print("_______________________")
                if best_match[0] in final:
                    final[best_match[0]].append(best_match)
                else:
                    final[best_match[0]] = [best_match]
        for path in final.values():
            #######################################
            for v in path:
                myresult.append(v)
            accused = -1
            base_depth = self.ds.get("BASE_DEPTH")[0]
            for i in path:
                boolskip = True
                for j in i:
                    if j[2] > base_depth: 
                        for loles in i[2:]:
                            # if there is any atribution
                            if loles[2] == i[0][2] and loles[3] == i[0][3] and loles[4] == i[0][4]:
                                boolskip = False
                        if boolskip:
                            atual = group_by_vulns[ore_tuple(i[0])]
                            # check if it's all outside control flow
                            for verify in atual:
                                allzero = True
                                for token in verify:
                                    if token[2] != base_depth:
                                        allzero = False
                                        break
                                if allzero:
                                    myresult.append(verify)
                            for verify in atual:
                                if verify != i:
                                    for token in verify:
                                        #if line nº is above
                                        if token[1] <= j[1]:
                                            if token[3] != j[3]:
                                                myresult.append(verify)
                                            elif token[3] == j[3] and token[4] != j[4]:
                                                myresult.append(verify)
                                            elif token[3] == j[3] and token[4] == j[4] and token[2] != j[2]:
                                                myresult.append(verify)
                        break
        #analyse paths
        #remove those that do not end in input
        myresult = [x for x in myresult if x[-1][0] == start]
        # # other check and control flow
        remall = []
        aux = []
        i = 0
        while i < len(myresult):
            for j in myresult[i]:
                if j[0] == sans or j[0] == common_sans:
                    vuln = myresult[i][0]
                    if j[2] == vuln[2] and j[3] == vuln[3] and j[4] == vuln[4]:
                        remall.append(myresult[i][0])
                    else:
                        aux.append(myresult[i])
            i += 1

        myresult = [x for x in myresult if x not in aux]
        myresult = [x for x in myresult if x[0] not in remall]
        myresult = [x for x in myresult if x != []]
        finalresult = []
        for x in myresult:
            if x not in finalresult:
                finalresult.append(x)

        return finalresult


#hashable tuple that may contain probailistic ORE values
#Allow probabilistic ORE values to have the same hash
class ore_tuple:
    vals = []
    rep_key = []
    def __init__(self, tup):
        self.tup = tup
        self.hash_rep = list(tup)
        for i in range(len(tup)):
            if len(ore_tuple.vals) <= i:
                ore_tuple.vals.append({})
                ore_tuple.rep_key.append(0)
            if isinstance(tup[i], OreVal):
                rep = None
                for k, v in ore_tuple.vals[i].items():
                    if v == tup[i]:
                        rep = k

                        break
                if rep is None:
                    rep = ore_tuple.rep_key[i]
                    ore_tuple.vals[i][rep] = tup[i]
                    ore_tuple.rep_key[i] += 1
                self.hash_rep[i] = rep
        self.hash_rep = tuple(self.hash_rep)
    def __eq__(self, other):
        for i in range(len(self.tup)):
            if self[i] != other[i]:
                return False
        return True

    def __hash__(self):
        return hash(self.hash_rep)
    
    #index acess   
    def __getitem__(self, key):
        return self.tup[key]
