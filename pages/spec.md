Specification
spec

spec
-----
### Instruction Set
We use the term "register" to refer to units of "working memory". The set of registers is a small finite set of storage locations, labelled r1, r2, r3... etc, and which are completely distinct from locations in *memory*. The latter is split into a code segment (where instructions are stored) and a data segment which consists of an unbounded number of locations of unbounded size, addressed starting from 0. 

The instruction set is as follows:
<table style="width:80%">

<colgroup>
       <col span="1" style="width: 10%;">
       <col span="1" style="width: 30%;">
       <col span="1" style="width: 5%;">
       <col span="1" style="width: 55%;">
    </colgroup>
<tr class="row">
<th>Operation</th>
<th>Mnemonic</th>
<th>Opcode</th>
<th>Description</th>
</tr>

<tr class="row">
<td>Halt</td>
<td>HLT</td>
<td>0</td>
<td>Stop</td>
</tr>

<tr class="row">
<td>Put</td>
<td>PUT &lt;constant&gt;, &lt;register&gt</td>
<td>1</td>
<td>Put constant value in a register</td>
</tr>

<tr class="row">
<td>Add</td>
<td>ADD &lt;register&gt, &lt;register&gt, &lt;register&gt</td>
<td>2</td>
<td>Add first two registers and store the result in the third register</td>
</tr>

<tr class="row">
<td>Subtract</td>
<td>SUB &lt;register&gt, &lt;register&gt, &lt;register&gt</td>
<td>3</td>
<td>Subtract first register from second register and store the result in the third register</td>
</tr>

<tr class="row">
<td>Load</td>
<td>LOD &lt;register&gt, &lt;register&gt</td>
<td>4</td>
<td>Load word from memory (data segment) at address given by first register into second register</td>
</tr>

<tr class="row">
<td>Store</td>
<td>STO &lt;register&gt, &lt;register&gt</td>
<td>5</td>
<td>Store word from first register to memory (data segment) address given by second register</td>
</tr>

<tr class="row">
<td>Branch if negative</td>
<td>BRN &lt;register&gt, &lt;constant&gt</td>
<td>6</td>
<td>If contents of first register is negative, jump to non-negative constant address (code segment) given as the second operand; ELSE next instruction</td>
</tr>

<tr class="row">
<td>Call subroutine</td>
<td>CAL &lt;constant&gt</td>
<td>7</td>
<td>Jump to non-negative constant address (code segment) and record address of next instruction which can be returned to with RET (see next)</td>
</tr>

<tr class="row">
<td>Return from subroutine</td>
<td>RET</td>
<td>8</td>
<td>Return to the instruction following most recent CAL. If no such CAL, then halt.</td>
</tr>

<tr class="row">
<td>Allocate (malloc)</td>
<td>MAL &lt;register&gt, &lt;register&gt</td>
<td>9</td>
<td>Allocate block of memory of size (in words) given by first register and store the address in the second register</td>
</tr>

<tr class="row">
<td>Deallocate (free)</td>
<td>FRE &lt;register&gt</td>
<td>10</td>
<td>Free block of memory that starts at the address given by the first register</td>
</tr>
</table>

Indirect branching is not supported so a constant address must be specified as the target of branches (BRN) and function calls (CAL). The target address is an address in the code segment which contains instructions and which is read-only. To load/store data from the data segment, the desired address is first put in a register and this register is passed to the load/store instruction. For example, the code below loads the value at address 2 in the data segment into the register r1, using r0 to hold the address.
```asm
put 2, r0
lod r0, r1
```
The standard encoding of instructions we use favors simplicity over compression, using a distinct integer to encode the opcode and every operand. Therefore, the first instruction in the code above would be encoded as 1, 2, 0 and the second as 4, 0, 1, each occupuing 3 locations in memory. We use the term "word" to refer to such storage locations, albeit no "word" size is specified, meaning their capacity is in theory unbounded and leaving it up to implementations to impose a concrete size.

The formal specification document for the HRAM0 model is available [here](https://github.com/hram0/hram0/blob/main/spec.pdf) which includes the formal semantics.

Provisionally, the extension of the instruction set to include the instructions MUL (integer multiplication) and DIV (integer division) is dubbed *HRAM0x*. This extension requires some non-trivial changes such as an additional halting state triggered on division by zero that is distinct from the ERROR state reserved for memory safety violations.
