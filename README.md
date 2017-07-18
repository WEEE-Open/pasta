# P.A.S.T.A.
**P**recompilatore  
**A**bnorme della  
**S**IR (Scheda Identificazione Rischi occupazionali)  
**T**otalmente  
**A**utomatico  

**WORK IN PROGRESS NON INSTALLARE ANCORA MANCANO TUTTI I FILE LA ROBA NON FUNZIONA**

Il solito script PHP da riga di comando che prende il modulo della SIR non compilato (T-MOD-SIR.pdf),
un csv con i dati dei lavoratori, e produce `n` file pdf compilati con i dati del csv, col potere di LaTeX.

Si può adattare in maniera relativamente facile per compilare altri moduli, modificando `template.tex`.

## Requisiti

PHP (qualsiasi versione vagamente recente) e `pdflatex`.

## Installazione

1. Clonare il repo
2. Rinominare `template-example.tex` in `template.tex` e modificarlo secondo le proprie esigenze
(dipartimento, responsabili, date di inizio/fine contratto, locali, etc...), non modificare le
stringhe tra parentesi quadre in quanto vengono sostituite con il contenuto del csv dallo script
3. Rinominare `data-example.csv` in `data.csv` e inserire i dati dei lavoratori
4. Eseguire `pasta.php`: se tutto va bene verrà generato un pdf per ogni riga del csv.
