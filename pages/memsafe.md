Memory Safety
memsafe

memsafe
-----
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>

The goal of the HRAM0 project is to contribute to research on memory safety in languages like C/C++ that do not have memory management built-in. In particular, we are interested in instrumentation approaches to achieve memory safety. HRAM0 is a model that captures the essential features of programs that allocate/delallocate memory in a similar manner to C (malloc/free) and triggers a special ERROR state if and only if a spatial or temporal safety violalion occurs, and concludes in a HALT state otherwise (assuming the program terminates). Proving safety of programs in theorem provers like Coq is a key goal of this project as is producing efficient transformations that transform a program (which may be unsafe) into one that is guranteed to be safe (and terminate gracefully in the event of violations) via an instrumentation approach. Minimizing the overhead of the instrumented program is a fundamental goal of our project. Below we unambiguously delineate the problem and its range of solutions in a formal manner using the HRAM0 model. The content of these pages is work-in-progress and will be extended and modified over time.

## Theoretical Aspects
These pages contain the results of our research thus far. A prerequisite for reading the following is the formal specification of HRAM0 (see [specification](?page=spec)). 

- [Characterization of Memory Safety](?page=char)


<u>Next (planned and unfinished)</u>:
- Universal Screeners
- Probabilistic Correctness
- Selective Screeners: Complexity and Optimizations
- Optimizations via Formal Proof Techniques
- Application to LLVM Sanitizers