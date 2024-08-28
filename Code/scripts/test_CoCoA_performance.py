#Script to test the performance of the CoCoA tool
#And all the prints are not commented out
#folders to test must be on the master_dir variable (.../WebApps/ by default)
import csv
import os
import subprocess
import sys
from multiprocessing import Pool
import re

run_count = 5 #number of times to run each file
master_dir = "../Tests/WebApps/"
info = [("performance.csv", ["-p"]), ("performance_e.csv", ["-e", "-p"]), ("performance_o.csv", ["-o", "-p"])]


def test_file(file_info,flags, timeout=5):
    # Unpack file_info
    file_to_test, files_dir = file_info
    result = file_to_test
    if os.path.isfile(file_to_test):
        # Get the output of the main.py with flags -o -d
        try:
            p = subprocess.run(["python3", "main.py"] + flags+ [file_to_test], capture_output=True, timeout=timeout)
        except subprocess.TimeoutExpired:
            result = "Timeout"
            return file_to_test, result, None
        if p.returncode != 0:
            result = "Error"
        else:
            result = p.stdout.decode('utf-8').rstrip('\n')

        return file_to_test, result
    return file_to_test, result, None

def extract_performace_values(output):
    search = re.compile(r"---Preprocessor (.+) seconds ---")
    preprocessor_time = float(search.search(output).group(1)) *1000 if search.search(output) else None

    search = re.compile(r"---Lexer (.+) seconds ---")
    lexer_time = float(search.search(output).group(1))*1000 if search.search(output) else None

    search = re.compile(r"---Translator (.+) seconds ---")
    translator_time = float(search.search(output).group(1))*1000 if search.search(output) else None

    search = re.compile(r"---Encryptor (.+) seconds ---")
    encryptor_time = float(search.search(output).group(1))*1000 if search.search(output) else None

    search = re.compile(r"---VD (.+) seconds ---")
    vd_time = float(search.search(output).group(1))*1000 if search.search(output) else None
    
    #disk usage by the encrypted index read the dump file size filesize.txt
    filesize = os.path.getsize("index.txt")
    
    return preprocessor_time,lexer_time, translator_time, encryptor_time, vd_time, filesize
if __name__ == "__main__":
    if "-s" in sys.argv or "--sqli" in sys.argv:
        new_info = []
        for output, flags in info:
            new_info.append((output.replace(".csv","_sqli.csv") , flags + ["-s"]))
        info = new_info
    php_files = []
    for path, subdires, files in os.walk(master_dir):
        #if "zipec" not in path:
        #    continue
        for file in files:
            if file.endswith(".php") or file.endswith(".phps"):
                php_files.append(os.path.join(path, file))
    php_files = [(file, master_dir) for file in php_files]
    
    for output, flags in info:    
        #clear caches
        rows = []
        rows = [["WebApp","Preprocessor Time (ms)", "Lexer Time (ms)", "Translator Time (ms)", "Encryptor Time (ms)", "VD Time (ms)", "Encrypted Index Size (bytes)", "Success Files Count", "Disk Space", "LOC"]]
        print("Testing files in: ", master_dir + " with flags: " + " ".join(flags))
        count = 0

        results = []
        for file in php_files:
            
            count += 1
            avgs = []
            for i in range(run_count):
                result = test_file(file, flags)
                if result[1] == "Error" or result[1] == "Timeout":
                    #print("Error in file: " + file[0])
                    #delete that file from the directory
                    #os.remove(file[0])
                    #sys.exit(1)
                    continue
                result = extract_performace_values(result[1])

                avgs.append(result)
            if len(avgs) < run_count:
                print(f"Error in file: {file[0]}", end="\r")
                continue
            avgs = [sum(x)/run_count for x in zip(*avgs)]
            size_of_file = os.path.getsize(file[0])
            loc_of_file = 0
            with open(file[0], 'r') as f:
                loc_of_file = len(f.readlines())
            result = [file[0]] + avgs + [1,size_of_file, loc_of_file]
            #remove cientific notation and use comma as decimal separator
            rows.append(result)
            print(f"Tested file {count}/{len(php_files)}: {file[0]}", end="\r")
            #clean stdout
            sys.stdout.flush()
            sys.stdout.write("\033[K")
        #group results by WebApp
        grouped = {}
        for result in rows[1:]:
            app = result[0].split("/")[3]
            if app in grouped:
                #sum the values
                result = result 
                for i in range(1, len(result)):
                    grouped[app][i] = grouped[app][i] + result[i]
            else:
                grouped[app] = result
        # #create a new csv with the output
        #dont use cientific notation
        for key in grouped:
            grouped[key] = [f"{x:.10f}".replace(".",",") if x is not None else "" for x in grouped[key][1:]]
        rows = rows[0:1]
        for key in sorted(grouped.keys(), key=lambda x: x.lower()):
            rows.append([key] + grouped[key])
        if not os.path.exists(master_dir+"/.performance"):
            os.makedirs(master_dir+"/.performance")
        with open(master_dir+"/.performance/"+output, 'w', newline='') as file:
            writer = csv.writer(file, delimiter='\t')
            writer.writerows(rows)
        print("Total files found: " + str(count))
   
