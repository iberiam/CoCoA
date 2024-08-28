import sys
from lexer import *
from tokens import *
from translator import *
from ds import *
from storageWorker import *
from vulnDetector import *
from cripto import *
import yaml
import json
import time
import pickle
from lib.ore_wrapper import getInitiatedParams, OreVal
from decryptor import decrypt_lineno
from preprocessor import Preprocessor
#perf counter to measure time more accurately
from time import perf_counter

#Kd_key = Random.new().read(AES.block_size)
Kd_key = "1234567891234567" #Deterministic master key
Kr_key = "9876543290293456" #Random master key

flag = False #Flag to run encryption or not 
ore_params = None #ore depends on the flag -o
xss_sens_flag = True
preprocess_flag = False

if __name__ == '__main__':
    #get flag from command line arguments
    preprocessor = None
    for arg in sys.argv[1:-1]:
        if arg == "-e" or arg == "--encrypt":
            flag = True
        elif arg == "-o" or arg == "--ore":
            flag = True
            ore_params = [getInitiatedParams() for _ in range(4)]
        elif arg == "-s" or arg == "--sqli":
            xss_sens_flag = False
        elif arg == "-p" or arg == "--preprocess":
            preprocessor = Preprocessor()
        else:
            print("Unrecognized argument: " + arg)

    if xss_sens_flag:
        input = "INPUT"
        sens = "XSS_SENS"
        sans = "XSS_SANS"
    else:
        input = "INPUT"
        sens = "SQLi_SENS"
        sans = "SQLi_SANS"
        
    
    config = yaml.safe_load(open("config.yaml"))
    # source ==> lextoken stream
    file = open(sys.argv[-1], 'r')
    filename = sys.argv[-1].split(".")[-2]
    input_data = file.read()

    # --- Preprocessor ---
    start_time = time.perf_counter()
    if preprocessor:
        input_data = preprocessor.preprocess_php(input_data)
        end_time = time.perf_counter()
        print("---Preprocessor %s seconds ---" % (end_time - start_time))
    #print(input_data)
    # --- Lexer ---
    start_time = time.perf_counter()
    lexer.input(input_data)
    lextokens = []
    while True:
        tok = lexer.token()
        if not tok:
            break      # No more input
        lextokens.append(tok)
        #print(tok)
    end_time = time.perf_counter()
    print("---Lexer %s seconds ---" % (end_time - start_time))

    # --- Translator ---
    start_time = time.perf_counter()
    # lextoken stream ==> intermediate language
    intermediate = translator.translate(lextokens)
    #print(*intermediate, sep='\n')
    end_time = time.perf_counter()
    print("---Translator %s seconds ---" % (end_time - start_time))

    # --- Encryptor ---
    # Create encypted index
    start_time = time.perf_counter()
    # intermediate ==> data structure
    data = DataStructure()
    wrk = Worker(data, intermediate, Kd_key,Kr_key,ore_params) if flag else Worker(data,intermediate) 
    wrk.store(0)
    # print(data.data)
    end_time = time.perf_counter()
    print("---Encryptor %s seconds ---" % (end_time - start_time))
    
    # --- Vulnerability Detection ---
    start_time = time.perf_counter()
    vd = VulnerabilityDetector(data, Kd_key)
    common_sans = '_SANS'
    print(input,sens)
    if (flag):
        rnd_key = encrypt(Kr_key, sens)
        results = vd.detection(encrypt(Kd_key,input),encrypt(Kd_key,sens), encrypt(Kd_key,sans), encrypt(Kd_key, common_sans), rnd_key)
    else:
        results = vd.detection(input, sens, sans, common_sans)
    end_time = time.perf_counter()
    print("---VD %s seconds ---" % (end_time - start_time))
    for i in results:
        print(i)
    if ore_params != None:
        results = decrypt_lineno(results, ore_params[0],100)
    
    #convert the lines to the original line numbers
    if preprocessor:
        updated_results = []
        for i in results:
            updated_i = []
            for j in i:
                j = list(j)
                j[1] = preprocessor.get_original_line(j[1])
                updated_i.append(tuple(j))
            updated_results.append(updated_i)
        results = updated_results

    
    if results:
        print("Vulnerabilitys' path:")
    for i in results:
        # print the path from input to sensitive link without duplicates
        print("* ",'->'.join(map(str,list(dict.fromkeys([x[1] for x in i]))[::-1])))
    for i in range(len(results)):
        for j in range(len(results[i])):
            results[i][j] = tuple(str(x) for x in results[i][j])

    with open("output.txt", "w") as f:
        f.write(json.dumps(results))
        f.close()
    
    #remove BASE_DEPTH from dictionary
    data.data.pop("BASE_DEPTH", None)
    
    #convert everything to bytes
    with open("index.txt", "wb") as f:
        pickle.dump(data.data, f)

 #wc -l

