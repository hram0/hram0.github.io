Characterization of Memory Safety
memsafe

memsafe
-----
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
Here we attempt to provide  a rigorous characterization of memory safety using HRAM0.

!definition{def:program}{A program `$P$` is a pair `$(\mathbf{c}, \mathbf{d})$` where `$\mathbf{c} \in \mathbb{Z}^+$` is a non-empty finite sequence of integers that represents the program code and `$\mathbf{d} \in \mathbb{Z}^*$` is a finite sequence of integers that represents the statically-allocated data.
}
A concrete example is the following representation of the program `$P_{\mathsf{init}}$` described in the specification, where the label ``main`` is mapped to the address 6 (in fact, the BRN instruction in this case simply jumps to the next instruction). Hence, we write `$P_{\mathsf{init}} = (\mathbf{c} := [1, -1, 2, 6, 2, 6], \mathbf{d} := [0])$`.

The execution of a program `$P = (\mathbf{c}, \mathbf{d})$` on input `$\mathbf{x} \in \mathbb{Z}^*$` involves loading `$\mathbf{c}$` into the code segment, whose size we denote by `$c = |\mathbf{c}|$`, and loading `$\mathbf{d} \parallel \mathbf{x}$` into the data segment, whose size we denote by `$d = |\mathbf{d}| + n$` where `$n = |\mathbf{x}|$` and starting the machine with this state. We conveniently write `$\mathsf{exec}(P, \mathbf{x})$` to signify such execution, which is defined as follows.
    - `$\mathsf{exec}_f(P, \mathbf{x}) = \begin{cases}
    \infty & \text{ if execution does not terminate} \\
    \bot & \text{ if execution terminates in the ERROR state} \\
    f(M) & \text{ if execution terminates in the HALT state} \\
    \end{cases}$`
   
    where `$M \subset \mathcal{P}(\mathbb{Z} \times \mathbb{Z})$` is the memory relation reflecting the contents of the data segment when the program halts and `$f : \mathcal{P}(\mathbb{Z} \times \mathbb{Z}) \to \Gamma$` is a *filter* function, expressed here polymorphically since `$\Gamma$` stands for some set, that maps memory contents to elements of `$\Gamma$`. 

The input is not required to be non-empty. Naturally, `$\mathsf{exec}_f(\cdot, \cdot)$` is not computable in the general case as a result of the halting problem. When the subscript `$f$` is omitted, then `$f$` is taken to be the identity function; that is, `$\Gamma$` is instantiated with the set `$\mathcal{P}(\mathbb{Z} \times \mathbb{Z})$`. For example, a filter `$g : \mathcal{P}(\mathbb{Z} \times \mathbb{Z}) \to \mathbb{Z}$` that sums the first `$k$` ``words`` of memory assuming such are defined would be defined as: `$g(M) = \sum_{i=0}^{k - 1} M(i)$`.

In the following definitions, we write an instruction's three-letter mnemonic in place of its numerical opcode value, but it is the latter that should be interpreted. We now define the following arity function `$\alpha : \mathbb{Z} \to \mathbb{N}$`:
    - `$\alpha(v) = \begin{cases}
    0 & \text{ if } v \leq 0 \text{ or } v = \mathsf{RET} \text{ or } v > 10 \\
    1 & \text{ if } v = \mathsf{FRE} \text{ or } v = \mathsf{CAL} \\
    3 & \text{ if } v = \mathsf{ADD} \text{ or } v = \mathsf{SUB} \\
    2 & \text{ otherwise } \\
    \end{cases}$`

We now define a predicate `$\mathsf{vi} : \mathcal{P}(\mathbb{Z}) \times \mathbb{Z}^+ \to \{0, 1\}$` that determines whether an integer sequence validly encodes an instruction. Note that this notion of validity is purely syntactic and relates to the well-formedness of an instruction encoding.
    - `$\mathsf{vi}(V, \mathbf{c}) = \begin{cases}
    1 & \text{ if } c_0 = \mathsf{FRE} \; \land \; |\mathbf{c}| \geq 2 \; \land \; 0 \leq c_1 < \rho  \\
    1 & \text{ if } c_0 = \mathsf{PUT} \; \land \; |\mathbf{c}| \geq 3 \; \land \; 0 \leq c_2 < \rho  \\
    1 & \text{ if } c_0 \in \{\mathsf{ADD}, \mathsf{SUB}\} \; \land \; |\mathbf{c}| \geq 4 \; \land \; 0 \leq c_1, c_2 \leq \rho + 1 \; \land \; 0 \leq c_3 < \rho  \\
    1 & \text{ if } c_0 \in \{\mathsf{LOD}, \mathsf{MAL}\} \; \land \; |\mathbf{c}| \geq 3 \; \land \; 0 \leq c_1 \leq \rho + 1 \; \land \; 0 \leq c_2 < \rho \\
    1 & \text{ if } c_0 = \mathsf{STO} \; \land \; |\mathbf{c}| \geq 3 \; \land \; 0 \leq c_1, c_2 \leq \rho + 1 \\
    1 & \text{ if } c_0 = \mathsf{CAL} \; \land \; |\mathbf{c}| \geq 2 \; \land \; c_1 \in V \\
    1 & \text{ if } c_o = \mathsf{BRN} \; \land \; |\mathbf{c}| \geq 3 \; \land \; 0 \leq c_1 \leq \rho + 1 \; \land \; c_2 \in V \\
    0 & \text{ otherwise } \\
    \end{cases}$`

Our definition of a predicate that establishes the validity of a program requires us to first define two important prerequisites, namely, a recursive predicate `$\mathsf{vprec} : \mathcal{P}(\mathbb{Z}) \times \mathbb{Z}^+ \to \{0, 1\}$`, and a recursive function `$\mathsf{aggr} : \mathbb{Z}^+ \times \mathbb{Z} \times \mathcal{P}(\mathbb{Z}) \to \mathcal{P}(\mathbb{Z})$`, which we define in turn.
    - `$\mathsf{vprec}(V, \mathbf{c}) = \mathsf{vi}(V, \mathbf{c}) \; \land \; (|\mathbf{c}| = \alpha(c_0) + 1 \lor \mathsf{vprec}(V, \mathbf{c}[\alpha(c_0) + 1;\ldots]))$`
    
    where the notation `$\mathbf{c}[i;...]$` denotes the subsequence `$c_i, c_{i  + 1}, \ldots, c_{|\mathbf{c}| - 1}$`.
    
    - `$\mathsf{aggr}(\mathbf{c}, \mathsf{offset}, V) = $`
    `$$\begin{cases}
    V \cup \{\mathsf{offset}\} & \text{ if } \mathsf{offset} = |\mathbf{c}| \\
    \mathsf{aggr}(\mathbf{c}, \mathsf{offset} + 1 + \alpha(c_{\mathsf{offset}}), V \cup \{\mathsf{offset}\}) & \text{ if } 0 \leq \mathsf{offset} < |\mathbf{c}| \; \land \; 0 \leq c_{\mathsf{offset}} \leq 10 \\
    \emptyset & \text{ otherwise } \\
    \end{cases}$$`

Now we can define a predicate `$\mathsf{vp} : \mathbb{Z}^+ \to \{0, 1\}$` that determines the validity of a program.
    - `$\mathsf{vp}(\mathbf{c}) = \mathsf{vprec}(V, \mathbf{c})$`
    where `$V = \mathsf{aggr}(\mathbf{c}, 0, \emptyset)$`.

It is easy to see that the above predicates are computable.
!definition{def:admissible}{
The *admissible* program space `$\mathbb{P}$` is the subset of `$\hat{\mathbb{P}} := \mathbb{Z}^+ \times \mathbb{Z}^*$` whose code sequence is valid. Formally, `$\mathbb{P}$` is defined as
`$$\mathbb{P} := \{(\mathbf{c}, \mathbf{d}) : \mathbf{c} \in \mathbb{Z}^+, \mathbf{d} \in \mathbb{Z}^*, \mathsf{vp}(\mathbf{c}) = 1\} \subset \hat{\mathbb{P}}.$$`}

Throughout this page, all programs `$P \in \hat{\mathbb{P}}$` are assumed to be valid i.e. `$P \in \mathbb{P}$`.
!definition{def:equiv}{
Two programs `$P_1, P_2 \in \mathbb{P}$` are said to be equivalent, written `$P_1 \equiv_f P_2$`, if for all inputs `$\mathbf{x} \in \mathbb{Z}^\ast$`, it holds that `$\mathsf{exec}_f(P_1, \mathbf{x}) = \mathsf{exec}_f(P_2, \mathbf{x})$`. The identity filter is assumed when the subscript `$f$` is omitted.
}

## Program Transformations
!definition{def:trans}{
A program transformation is a function `$T : \mathbb{P} \to \mathbb{P}$` that maps a valid program `$P \in \mathbb{P}$`  to another valid program `$P' \in \mathbb{P}$`. The transformation is said to be \*functionality-preserving* iff `$\forall P \in \mathbb{P} \;\; P \equiv T(P)$`.
}


Now we define a partial order relation `$\preceq$` on the admissible program space in the form of a binary predicate `$\mathbb{P} \times \mathbb{P} \to \{0, 1\}$`:
`$$P_1 \preceq_f P_2 = \bigwedge_{\mathbf{x} \in \mathbb{Z}^\ast} P_1 \preceq_{f, \mathbf{x}} P_2$$`
where
`$$
P_1 \preceq_{f,\mathbf{x}} P_2 := \begin{cases}
1 & \text{ if } \mathsf{exec}_f(P_1, \mathbf{x}) = \mathsf{exec}_f(P_2, \mathbf{x}) \\
1 & \text{ if } \mathsf{exec}(P_1, \mathbf{x}) \neq \bot \; \land \; \mathsf{exec}(P_2, \mathbf{x}) \in \{\infty, \bot\} \\
0 & \text{ otherwise} \\
\end{cases}.
$$` If the subscript `$f$` is omitted, it is taken to be the identity function.
Informally, the fact that `$P_1 \preceq P_2$` implies that `$P_1$` is universally (i.e. over *all* inputs) "at least as safe as" `$P_2$`.
!definition{def:nonweak}{
A transformation `$T$` is said to be *non-weakening* with respect to `$f$` if for all `$P \in \mathbb{P}$` it holds that `$T(P)\preceq_f P$`.
}
The identity transformation is trivially non-weakening. 

!definition{def:instrum}{
An *instrumentor* is a transformation `$T$` with two essential properties:
    1. `$T$` is non-weakening.
    2. `$\forall (\mathbf{c}, \mathbf{d}) \in \mathbb{P}\;\;\; |\mathbf{c'}| \geq |\mathbf{c}|$` where `$(\mathbf{c'}, \mathbf{d'}) \gets T(\mathbf{c}, \mathbf{d})$`.
    3. `$\exists (\mathbf{c}, \mathbf{d}) \in \mathbb{P}\;\;\; |\mathbf{c'}| > |\mathbf{c}|$` where `$(\mathbf{c'}, \mathbf{d'}) \gets T(\mathbf{c}, \mathbf{d})$`.

}

Informally, the above properties tell us that an instrumentor must never decrease a program's size and must, at least some of the time, increase a program's size while preserving functionality for all safely terminating programs whereas nothing can be said about unsafe termination. The third condition is necessary to prevent trivial examples of an instrumentor such as the identity transformation, whereas trivial examples such as the transformation that appends a zero (HLT) followed by any arbitrary sequence of valid instructions qualifies as an instrumentor. An instrumentor is a transformation that adds code "without breaking things".


!definition{def:strenghtening}{
A transformation `$T$` is said to be *strengthening* if for all `$P \in \mathbb{P}$` the following two properties are satisfied.
     - `$\mathbf{S.1}$`: `$\mathsf{exec}(P, \mathbf{x}) = \bot \implies \mathsf{exec}(T(P), \mathbf{x}) \neq \bot \;\; \forall \mathbf{x} \in \mathbb{Z}^*$`.
     - `$\mathbf{S.2}$`: `$T(P) \preceq P$` (non-weakening)
}
On first reading, it may appear that there is some redundancy in the above definition. This is in fact not the case and the following simple lemma elucidates the necessity of both properties S.1 and S.2.
!lemma{lemma:strength}{1. `$S.1 \not\implies S.2$`
2. `$S.2 \not\implies S.1$`
}
!proof{Suppose `$S.1$` holds. Now also suppose `$\mathsf{exec}(P, \mathbf{x}) \neq \bot$` for some program `$P$` and input `$\mathbf{x}$`. Now also suppose `$M = \mathsf{exec}(P, \mathbf{x}) \in \mathcal{P}(\mathbb{Z} \times \mathbb{Z})$`. It may also be the case that `$M' = \mathsf{exec}(T(P), \mathbf{x}) \in \mathcal{P}(\mathbb{Z} \times \mathbb{Z})$`. Further it may be such that `$M \neq M'$`, and therefore `$T(P) \not\preceq P$`. 

Next we turn to the second statement. Assume `$S.2$` holds. Let `$\mathbf{x} \in \mathbb{Z}^*$` and `$P \in \mathbb{P}$` such that `$\mathsf{exec}(P, \mathbf{x}) = \mathsf{exec}(T(P), \mathbf{x}) = \bot$`. Consequently, this violates `$S.1$`.}

### Screeners
It is the class of strengthening instrumentors that are the primary focus of this work.
!definition{def:screener}{A *screener* is a transformation `$T : \mathbb{P} \to \mathbb{P}$` that is
    - an instrumentor
    - strengthening}

One of the primary goals of this project is to analyze different types of screener with an aim of maximizing efficiency. We aim to post an update to this page soon.

####Universal Screeners
The notion of a transformation resembles the notion of "sanitizer" in the LLVM project. The well-known LLVM sanitizer known as *address sanitizer* (ASan) has some similarities to the *type* of screener we explore in the next post. In particular, the type of screener we explore in the post to follow checks *every* memory access (i.e. load or store) for safety. We call such a screener (i.e. one that checks *every* memory access) a *universal screener*. The asymptotic complexity in terms of number of memory accesses of a transformed program `$P' = T(P)$` is linearly dependent on `$m_P(n)$` where `$T$` is our screener transformation described later and `$P \in \mathbb{P}$` is a valid program where `$m_P(n)$` is the (average) number of memory accesses made by program P on inputs of length n. The complexity also depends on another important factor: the data structure used to keep track of valid allocated blocks. Such "bookkeeping" is indispensible and plays an important role in any realization of a screener.

*To be continued...*