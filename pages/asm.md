Assembly Language
assembly

assembly, asm
-----
The assembler we have developed is written in Python. You can find it [here](https://github.com/hram0/hram0/tree/main/assembler).

Below is an implementation of selection sort in HRAM0 assembly language.
```asm
BEGIN CODE

###############################################################################
# This program does some setup (initializes r2 := -1 for example) and calls
# the selsort function (below) to sort the input array, then halts.
###############################################################################
main:
	put -1, r2 # setup r2 (this is one of our common conventions)
	put &x, r4 # copy address of input to r4 in order to sort input
	call selsort
	hlt

###############################################################################
# Selection Sort (function dirties r5-r13 (i.e.not saved/restored from stack))
#        
# arguments: r4 - address of array of integers to sort of length n (reg n)
# sorts array pointed to by r4 in-place
###############################################################################
selsort:
        put 0, r5 # r5 is outer index
        add r2, n, r12 # r12 = n - 1
        sub r12, r5, r9
        brn r9, outerloop
        brn r2, end_outerloop

outerloop:
        add r5, r4, r6
        lod r6, r7 # r7 is min value
        put 0, r8
        add r8, r7, r13
        add r8, r5, r8
        sub r2, r5, r9 # r9 = r5 + 1 = inner index

        sub n, r9, r10
        brn r10, innerloop
        brn r2, end_innerloop

innerloop:
        add r9, r4, r10
        lod r10, r10
        sub r7, r10, r11
        brn r11, newmin
        brn r2, nextiter
newmin:
        put 0, r11
        add r11, r9, r8
        add r11, r10, r7
nextiter:
        sub r2, r9, r9
        sub n, r9, r10
        brn r10, innerloop

end_innerloop:
        add r8, r4, r10
        sto r13, r10
        sto r7, r6

        sub r2, r5, r5
        sub r12, r5, r9
        brn r9, outerloop

end_outerloop:
        ret
END CODE
```
The assembler takes an assembly file (.asm) written in HRAM0 assembly language whose syntax is demonstrated by the examples in the '../programs' directory. A brief overview of the format is as follows.

A "section" is a program structure delimited with a line containing `BEGIN <type>` where `<type>` is the section type, and a line containing `END <type>`. For example:

```asm
BEGIN INCLUDES
    include "harness.asm"
END INCLUDES
```

The above is an includes section. You can have as many lines containing include statements as you need. Each include statement consists of the keyword `include` followed by a string in quotes with the name of the assembly file to include. Another example of a section is as follows.

```asm
BEGIN CONSTANTS

MIN_SHADE,      1,        0
MAX_SHADE,      1,        255
RED,            3,        255, 0, 0
GREEN,          3,        0, 255, 0
BLUE,           3,        0, 0, 255

END CONSTANTS
```

The above is a constants section. It defines five constants. The first is named `MIN_SHADE` and occupies 1 integer and whose value is 0. The third is named `RED` and is an array of 3 integers containing the values 255, 0, 0.

Below is a data section which defines two variables, a single integer and an array. It has identical syntax to a constants section.

```asm
BEGIN DATA

speed, 1,       0
color, 3,       255, 255, 255

END DATA
```

In the assembly specification, the /value/ of say the second element of the array `color` (i.e. with index 1) as it is defined in the data section is specified by writing `color[1]`. If one needs to specify the address in the data segment of where color[1] resides, one writes `&color[1]`.

A macro section has a name and an arity (number of arguments). When a macro appears in the main code block, the arguments it takes are supplied with the intention that the assembler expands the occurence of the macro to its associated block of code. Argument number 2 (say) where indexing starts at 0 is referenced with `args[2]`. Furthermore, comments involve adding '#' and the rest of the line following the '#' character is taken as a comment, and ignored by the assembler. Here is an example of a macro that takes a register and three constant values as arguments.

```asm
# This macro takes a register and three constant values, stores each of them at
# the address given by the supplied register (the first argument) and its
# adjacent locations (the register in args[0] along with r3 are modified)
# r2 is assumed to be -1 (this is a convention we unvaryingly follow)
BEGIN MACRO set_color 4
      put args[1], r3 # put second argument (first of the constants) in r3
      sto r3, args[0] # store this value at address in register given by first arg
      sub r2, args[0], args[0] # add 1 to register in the first argument
      put args[2], r3
      sto r3, args[0]      
      sub r2, args[0], args[0] # add 1 to register in the first argument
      put args[3], r3      
      sto r3, args[0]
END MACRO
```

All assembly files are required to have a main code block. This is defined in a code section, like the one that follows:

```asm
BEGIN CODE

start:
        put -1, r2
        put &color, r4
        set_color r4, RED[0], RED[1], RED[2]
        hlt

END CODE
```


The above code puts the value -1 in r2 and puts the address of the variable 'color' (that we defined earlier in the data section) in r4. Next it uses the set_color macro with the arguments r4 and the three components of the 3-element constant array 'RED' (that we defined in the constants section). The occurence above of 'start:' is an example of a label which maps to an address in the code segment. The label 'start' above maps to 0 for example.


# Running the assembler
The bash script assemble.sh sets the PYTHONPATH enviornment variable so that the hram module can be found, then it runs python3 assemble.py. To run the assembler on the ../programs/selsort.asm file, execute the following:

./assemble.sh -o ../programs/bin/ ../programs/src/selsort.asm

The file ../programs/bin/selsort.prg should be generated. You can execute this program with the evaluator. See the ../evaluator directory.
