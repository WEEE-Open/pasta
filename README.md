# P.A.S.T.A.
**P**recompilatore  
**A**bnorme della  
**S**IR (Scheda Identificazione Rischi occupazionali)  
**T**otalmente  
**A**utomatico  


Il solito script PHP da riga di comando che prende il modulo della SIR non
compilato (T-MOD-SIR.pdf) del Politecnico di Torino, un csv con i dati dei
lavoratori, e produce `n` file pdf compilati con i dati del csv, attraverso il
potere di LaTeX e del posizionamento assoluto dei paragrafi.

Si può adattare in maniera relativamente facile per compilare altri moduli,
modificando `template.tex`.

## Requisiti

PHP 5.1 o successive (in teoria, provato solo con PHP 7) e `pdflatex`.

## Installazione

1. Clonare il repo
2. Scaricare `T-MOD-SIR.pdf` dal sito del Politecnico (si trova nell'intranet,
solo per utenti abilitati)
3. Rinominare `template-example.tex` in `template.tex` e modificarlo secondo le
proprie esigenze (dipartimento, responsabili, date di inizio/fine contratto,
locali, etc...), non modificare le stringhe tra parentesi quadre in quanto
vengono sostituite con il contenuto del csv dallo script
4. Rinominare `data-example.csv` in `data.csv` e inserire i dati dei lavoratori
5. Eseguire `pasta.php`: se tutto va bene verrà generato un pdf per ogni riga
del csv, oltre ai file tex e un po' di file temporanei.

Se nel csv sono presenti le colonne NAME, SURNAME e ID (case sensitive) verranno
usate per generare il nome del file, altrimenti verranno usati dei numeri
progressivi (che corrispondono alla riga nel csv, peraltro).

Il template è stato costruito sulla base della versione 9 (18/05/2017) della
SIR, altre versioni potrebbero richiedere aggiustamenti.

