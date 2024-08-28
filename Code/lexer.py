import ply.lex as lex
import yaml

config = yaml.safe_load(open("config.yaml"))

# Reserved keywords
keywords = {
    # Types
    'Int': 'INT',
    'Float': 'FLOAT',
    'String': 'STRING',
    'Boolean': 'BOOLEAN',
    # Conditional statements
    'if': 'IF',
    'else': 'ELSE',
    'elseif': 'ELSEIF',
    'switch': 'SWITCH',
    'case': 'CASE',
    'break': 'BREAK',
    'default': 'DEFAULT',
    # error
    'try': 'TRY',
    'catch': 'CATCH',
    # Loop
    'while': 'WHILE',
    'for': 'FOR',
    # 'do': 'DO',
    'foreach': 'FOREACH',
    'continue': 'CONTINUE',
    # Misc
    'return': 'RETURN',
    'php': 'PHP',
    'function': 'FUNC',
    # Classes
    'class': 'CLASS',
    'public': 'PUBLIC',
    'private': 'PRIVATE',

}


tokens = list(keywords.values()) + [

    'POINTER',
    # Operators
    'PLUS', 'MINUS', 'TIMES', 'DIVIDE', 'MOD',
    'OR', 'AND', 'NOT',
    'LT', 'GT', 'GE', 'LE',
    'EQ', 'NEQ',
    'QM', 'CONCAT',

    # Assignment
    'EQUALS',

    # Delimiters
    'LPAREN', 'RPAREN',
    'LBRACE', 'RBRACE',
    'LBRACKET', 'RBRACKET',
    'SEMI', 'COLON', 'COMMA',

    # Values
    'VAR', 'FUNC_CALL', 'FLOAT_LITERAL', 'INT_LITERAL', 'BOOLEAN_LITERAL', 'STRING_LITERAL',

    # Input Functions
    'INPUT'

] + [i['name']+'_SENS' for i in config['VULNS']] + [i['name']+'_SANS' for i in config['VULNS']] + ['_SANS']

t_POINTER = r'->'
# Operators
t_PLUS = r'\+'
t_MINUS = r'-'
t_TIMES = r'\*'
t_DIVIDE = r'/'
t_MOD = r'%'
t_OR = r'\|\|'
t_AND = r'&&'
t_NOT = r'!'
t_LT = r'<'
t_GT = r'>'
t_GE = r'>='
t_LE = r'<='
t_EQ = r'=='
t_NEQ = r'!='
t_QM = r'\?'
t_CONCAT = r'\.'

# Assignment
t_EQUALS = r'='

# Delimiters
t_LPAREN = r'\('
t_RPAREN = r'\)'
t_LBRACE = r'\{'
t_RBRACE = r'\}'
t_LBRACKET = r'\['
t_RBRACKET = r'\]'
t_SEMI = r';'
t_COLON = r':'
t_COMMA = r'\,'


def t_FLOAT_LITERAL(t):
    r'(\d*)?[.]\d+'
    try:
        t.value = float(t.value)
    except ValueError:
        print("Float value too large %d", t.value)
        t.value = 0.0
    return t


def t_INT_LITERAL(t):
    r'\d((_|\d)*\d)?'
    try:
        t.value = int(t.value)
    except ValueError:
        print("Integer value too large %d", t.value)
        t.value = 0
    return t


def t_STRING_LITERAL(t):
    r'(\'(((\\)+(\')?)|([^\']))*\')|("(((\\)+(")?)|([^"]))*")'
    try:
        t.value = str(t.value)[1:-1]  # removing the "" or ''
    except ValueError:
        print("Not a string %s", t.value)
        t.value = ""
    t.lexer.lineno += t.value.count("\n")
    return t


def t_BOOLEAN_LITERAL(t):
    r'true|false|True|False|TRUE|FALSE'
    try:
        if t.value == "true":
            t.value = True
        else:
            t.value = False
    except ValueError:
        print("Not a boolean %b", t.value)
        t.value = ""
    return t


def t_VAR(t):
    r'\${0,1}[a-zA-Z_][a-zA-Z0-9_]*'
    t.type = keywords.get(t.value, 'VAR')    # Check for reserved words
    if t.value in config['INPUT']:
        t.type = 'INPUT'
        return t

    if t.value in config['SANS']:
        t.type = '_SANS'
        return t

    for vuln in config['VULNS']:
        if t.value in vuln['sensitive_sinks']:
            t.type = vuln['name']+'_SENS'
            return t
        elif t.value in vuln['sanitization_functions']:
            # if vuln sans is defined for all vulns then give just SANS 
            t.type = vuln['name']+'_SANS'
            return t
    if t.value == 'NULL' or t.value == 'null':
        t.type = 'STRING_LITERAL'
        return t
    elif t.value[0] != '$' and t.type == 'VAR':
        t.type = 'FUNC_CALL'
        return t
    return t


def t_COMMENT(t):
    #comments like # or // or /* */ or <!-- -->
    r'(\#.*)|(\/\*[\s\S]*\*\/)|(\/\/.*)'
    t.lexer.lineno += t.value.count("\n")
    pass  # ignore this token


# Ignored characters
t_ignore = " \t"


def t_newline(t):
    r'\n+'
    t.lexer.lineno += t.value.count("\n")


# Error handling rule
def t_error(t):
    print("Illegal character '%s'" % t.value[0])
    t.lexer.skip(1)


def find_column(input, token):
    line_start = input.rfind('\n', 0, token.lexpos) + 1
    return (token.lexpos - line_start) + 1


# Build the lexer
lexer = lex.lex()
