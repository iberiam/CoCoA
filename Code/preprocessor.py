import re
import sys
#Extract php snippets only from the code and convert explicit casts to functions
# so that they can be recognized by cocoa as sanitization functions
php_tag_pattern = re.compile(r'((?:<\?php)|(?:<\?PHP)|(?:<\?=))(.*?)($|(?:\?>))')
php_end_tag_pattern = re.compile(r'()(.*?)(?:$|(\?>))') #empty group so that it can be used in the same way as php_tag_pattern
class Preprocessor:
    def __init__(self):
        self.offset = []


    def preprocess_php(self, input_data):
        #replace all non php code with blanks
        in_php = False
        lines = input_data.split("\n")
        if not lines[0].startswith("<?php"):
            result = "<?php\n"
            current_line = 1
            self.offset.append(current_line)
        else:
            result = "<?php"
            current_line = 0
        for line in lines:
            current_line += 1
            output = line
            #find all matches
            matches = php_tag_pattern.findall(line)
            if in_php:
                #there was a tag still open
                potential_end = php_end_tag_pattern.search(line)
                if potential_end:
                    #append at the start
                    matches = [potential_end.groups()] + matches
            new_line = ""
            for match in matches:
                code = match[1]
                add_new_line = False
                if match[0] == "<?=":
                    code = "echo "+code + ";"
                    add_new_line = True
                if re.compile(r'\s*\$\w+\s*').match(code):
                    code = preprocess_casts(code)
                new_line += code
                if add_new_line:
                    current_line += 1
                    new_line += "\n"
                    self.offset.append(current_line)
                if match[2] == "?>":
                    in_php = False
                else:
                    in_php = True
            if len(matches) == 0 and in_php:
                code = line 
                if re.compile(r'\s*\$\w+\s*').match(code):
                    code = preprocess_casts(code)
                new_line += code
            output = new_line
            result += output + "\n"
        return result + "?>"

    def get_original_line(self, line):
        result = line
        for offseted_line in self.offset:
            if line >= offseted_line:
                result -= 1
        return result
var_pattern = r'(?:(?:\$\w+)|(?:[\",\']\w+[\",\'])|(?:\w+\(.+\)))'

#turn this (int) 5; into intval(5);
def convert_explicit_cast_to_function(php_code): 
    #int, float, string, bool, array, object
    pattern = r'(\((?:int|float|string|bool)\))\s*(.*)\s*;'

    if not re.compile(pattern).search(php_code):
        return php_code
    cast_pattern = re.compile(r'\(\s*(int|float|string|bool)\s*\)\s*([^\s;,\)]+)')

    def replace_cast(match):
        cast_type = match.group(1)
        expression = match.group(2)
        return f'{cast_type}val({expression})'

    # Replace all occurrences of the explicit cast pattern
    php_code = cast_pattern.sub(replace_cast, php_code)
    return php_code
    
#turn $a += 0; into $a = $a +0; 
def convert_op_assign(php_code):
    whole_pattern = r'(\$\w+)(\s*[\+\-\*\/\%]\s*)(\=).*(\d+(?:\.\d+)?).*;'
    match = re.compile(whole_pattern).search(php_code)
    new_code = php_code
    if match:
        op = match.group(2)
        var = match.group(1)
        new_code = new_code.replace(op, "",1)
        new_code = new_code.replace(match.group(3), " = "+var+op+" ",1)
    return new_code


#turn $a = $a + 0; into $a = intval($a) +0;
#or $a = $a + 0.0 + $b + '5'; into $a = floatval($a) +0.0 + floatval($b+'5');
def convert_sum_cast_to_function(php_code):
    whole_pattern = r'\$\w+[\+\-\*\/\%]?\s*\=\s*(?:'+var_pattern+'.*\d+(?:\.\d+)?\s*.*\s*)|(?:\d+(?:\.\d+)?.*'+var_pattern+'\s*);'
    match = re.compile(whole_pattern).search(php_code)
    new_code = php_code
    if match:
        #get full match 
        #find int or float
        digit = re.compile(r'(\d+(\.\d+)?)').search(php_code).group(1)
        assign =  re.compile(r'(?:[\+\-\*\/\%]\s*)?\=\s*.*\s*;').search(php_code).group(0)
        new_assign = assign

        assign_left = re.compile(r'(?:[\+\-\*\/\%]\s*)?(\=\s*)(\s*.*\s*)'+digit).search(php_code)
        if assign_left:
            assign_left = assign_left.group(2)
            vars = re.compile(var_pattern).findall(assign_left)
            cast_type = "int" if "." not in digit else "float"
            for var in vars:
                new_assign_left = assign_left.replace(var, cast_type+"val("+var+")")
                #turn the right part of the assignment into a cast
                new_assign = new_assign.replace(assign_left, new_assign_left)            
        assign_right = re.compile(r"("+digit+r"\s*[+\-\*\/\%]\s*)(.+)(;)").search(php_code)
        if assign_right:
            assign_right = assign_right.group(2)
            new_assign_right = cast_type+"val("+assign_right+")"
            new_assign = new_assign.replace(assign_right, new_assign_right)
        new_code = new_code.replace(assign, new_assign,1)
    return new_code

def preprocess_casts(php_code):
    php_code = convert_explicit_cast_to_function(php_code)
    php_code = convert_op_assign(php_code)
    php_code = convert_sum_cast_to_function(php_code)
    return php_code

#main
if __name__ == "__main__":
    test_Case = "$a = $a + 0.0 + $b + func($c) +'5';"
    print(preprocess_casts(test_Case))
    test_Case = "$a += 0 ;"
    print(preprocess_casts(test_Case))
    test_Case = "$a = $b + (int) 5 +1;"
    print(preprocess_casts(test_Case))
    test_Case = "$a = 0 + $b + (int) 5 +1;"
    print(preprocess_casts(test_Case))
    test_Case = "funcion((int) $_GET['a'] + 1);"
    print(preprocess_casts(test_Case))
    test_Case = "$picquery = mysql_query(\"SELECT * FROM picdata WHERE ID = \".(int)$_GET['pic_id'], $conx);"
    print(preprocess_casts(test_Case))
   
    


