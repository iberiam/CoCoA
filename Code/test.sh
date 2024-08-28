#!/bin/bash

#script to log all php files in which a vulnerability was found

# Set the path to the base directory containing PHP files
base_dir="../Tests/"

# dirs to check
php_dirs=("WebApps")

log_folder="logs"
#associate a flag to a log file
flags=("-p" "-p -e" "-p -o")
files=("log.txt" "log_e.txt" "log_e_o.txt")
#file log.txt is flags 0, log_e.txt is flags 1, log_e_o.txt is flags 2
for i in {0..2}; do
    flag="${flags[$i]}"
    echo "Flag: $flag"
    file="${files[$i]}"
    # get all php files
    for dir in "${php_dirs[@]}"; do
        php_files_dir="$base_dir$dir"
        
        find "$php_files_dir" -type f -name "*.php" | while read -r php_file; do
            python3 main.py "$flag" "$php_file" > /dev/null 2>&1
            echo "Checking $php_file"
            output=$(cat "output.txt")
            empty="[]" # no path found
            if ! test "$output" = "$empty" ; then
                cat "output.txt"
                echo ""
                # found a paht register what file it was
                echo "$php_file" >> "$base_dir$log_folder/$file"
            fi
        done
    done
done

