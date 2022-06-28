HRAM0 - A Heap Random Access Machine (HRAM) Model for Exploring Memory Safety
about

about
-----
# About The Project.
HRAM0 is an abstract machine, a type of random-access machine (RAM), developed to investigate memory safety in programming languages like C/C++. As well as research, the hope is that it might serve as a helpful educational tool especially in regard to simplified abstract machines. HRAM0 is designed with the following aims:

1. To strike a balance between the competing objectives of minamlity of instruction set and ease of programmability.
2. Mirror a single-threaded userland C program with non-interactive input.
3. Be easy to understand and program.
4. Incorporate ideas from RISC design, have "working memory" (in the form of registers) separate from main memory.
5. Be sufficiently simple to facilitate automated reasoning / proof checking.
6. Provide a complexity-theoretic model where it is straightforward to measure complexity as a function of input size.

The project is quite simply a work in-progress. We have endeavored to meet the above criteria in the current design, as presented in the [specification](?page=spec). The simplicity of HRAM0 makes it possibly well-suited to proof checking and automated reasoning. Many of the features (or limitations) of HRAM0 were chosen with this in mind. For example, indirect branching is not supported so one can only branch to constant addresses, thereby simplifying control-flow analysis. The instruction set equivalent of "syntactic sugar" is discernible from the inclusion of the CAL and RET instructions.

# Developed So Far
1. An assembler to transform programs written in HRAM0 assembly into "machine code".
2. Python implementation of HRAM0 interpreter that is written to precisely match the mathematial description in the specification with no focus on optimization.

# Planned Developments

1. Reference implementation in Haskell.
2. Adaptation of above to Coq.
3. Proofs in Coq of certain properties (ongoing research).
4. Performance-focused implementation, perhaps in C/C++.