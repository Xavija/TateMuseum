# TateMuseum

Gestione di un database di Tate Museum tramite sito web.
- Bulma Framework
- mysql

Progetto per il corso di Basi di Dati e Laboratorio, Unife aa2018-2019.

## Specifiche 
Il progetto consiste nella realizzazione di un sito web che permetta di visualizzare gli artisti e le relative opere presenti nel database dei musei Tate (Tate Modern, Tate Britain, Tate Liverpool e Tate St. Ives).

I dati relativi ad artisti ed opere da importare nel database sono contenuti in un archivio (project_data.zip) che può essere scaricato dalla pagina del corso ().

La fonte dei dati è un repository su GitHub (https://github.com/tategallery/collection).
                        
Basandosi sulla struttura dei dati a disposizione le tabelle saranno verosimilmente due: artista e opera. La difficoltà dal punto di vista del database sarà quella di identificare eventuali chiavi esterne e attributi duplicati e gestirli di conseguenza. Prestate attenzione a come gestite i dati, alcuni record potrebbero avere dei campi non valorizzati o non sempre formattati come vi aspettate.
                        
Il sito web deve poter offrire le seguenti funzionalità di minima:
- ricerca di un artista inserendo uno o più parametri (anche parziali) - nel caso in cui nessun parametro venga specificato deve essere presentata la lista completa degli artisti;
- visualizzazione di tutte le opere di un determinato artista, eventualmente suddivise per tipologia e presentando un report generale, anche grafico, su anni, tipologie, etc...;
- ricerca delle opere inserendo uno o più parametri (anche parziali), in forma libera o eventualmente guidata;
- calcolo di statistiche relative ad artisti e opere, ad esempio:
  - numero di opere realizzate in un determinato anno;
  - numero di opere per artista;
  - etc.

## ToDo

- ~~Integrazione `connessione.php` e `index.html` (unica pagina)~~
- Query:
  - prevenzione SQL injection
  - report(s)
  - ~~visualizzazione risultati~~
- ~~Visualizzazione in tabella~~
  - ~~thumbnail e url~~
- ~~Integrare valori checkbox in php~~
- Completare l'integrazione dei menù drop down
- ~~Headers effettivi nella pagina~~
- Calcolo di statistiche relativi ad artisti e opere



- Michele
  - ~~tabella con scrollbar~~
  - ~~fixare tabella scrollbar (bordo esterno con pochi elementi)~~
  - ~~preparazione pagina per thumbnail e url~~
  - Abbellire le pagine in depth
- Phil
  - report
  - ~~dropdown~~
