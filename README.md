# CoCoA

CoCoA is a tool capable of protecting code through encryption in a compact data structure while also  being able to perform static analysis over that encrypted code. 

This repository contains the source code for the CoCoA tool and also the source code of the web applications used in its testing.

# Installation

Download the .zip of the "Code" folder and extract it in your desired location.

# Execution


Before trying to execute the tool make sure all the dependencies listed below are installed in your system. Then run the setup script:

> setup.sh
> 
> cd Code
> 
> source venv/bin/activate

To execute CoCoA, open a terminal inside the Code/ folder and execute the main.py file with the file you wish to verify as parameter. Example:

> python3 main.py ../Tests/Cases/Case1.php

Use the flags:

    * To run encrypted use -e or --encrypt
    
    * To run encrypted with ORE additionally use -o or --ore

Example:

> python3 main.py -e ../Tests/Cases/Case1.php

or 

> python3 main.py -e -o ../Tests/Cases/Case1.php

Also checkout the test_CoCoA.py and test_CoCoA_performance.py scripts in the Code/Scripts folder.

Real webapp tests:

> python3 scripts/gather_CoCoA_Output.py

> python3 scripts/test_CoCoA_performance.py

Synthethic dataset tests:

> python3 scripts/test_CoCoA.py



### Dependencies
- Python3
- Pycryptodome
- Ply
- matplotlib
- Non python related:
  - gcc
  - make


#### Acknowledgements

A big thanks to the fastore repository for a easy to use and relatively fast implementation of order revealing encryption:
- https://github.com/kevinlewi/fastore


 
