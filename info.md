# DigiLib - Gestionale libreria
Web App scritta in **PHP** che utilizza un DB in MariaDB.

L'app permette dal lato utente di:
- Visuallizare i libri (attraverso il catalogo) della biblioteca.
- Suggerire nuovi libri **account richiesto*.

L'app permette dal lato del Admin/Gestore di:
- Aggiungere, modificare e eliminare libri dal DB
    - A ogni libro si possono aggiungere dei tag tramite appositi menù
- Rimuovere, approvare o rifiutare i suggerimenti


# Informazioni tecniche

## Configurazione
### DB
- `servername` = "localhost"
- `dbname`  = "library"

- `username` Username di accesso al DB
- `password` Password per l'accesso del DB

- `maximagesize` Peso in Byte massimo di un immagine

### Paths
- `bookimgs` Percorso in cui verranno salvate le immagini dei libri
                
### Support
- `email` Email usata per il supporto (Es. Recupero password)

### Dashboard
- `showUnreviewedSuggestion` Se impostato su **false** non mostra i suggerimenti che sono stati rifiutati/accettati nel Dashboard


## Limitazioni
> La tabella Tags è limitata a 100 tags (non modificabile dai config) nel codice PHP

## Know Issues
> Non è presente un sistema integrato di ratelimit

## Tabelle DB
### Books
| id (int)       | img (str)                | name (str)       | author (str)     | publish_date (date)   | state (int)                                | position (str)                       | tag_id (int)   |
|----------------|--------------------------|------------------|------------------|-----------------------|--------------------------------------------|--------------------------------------|----------------|
| Identificativo | Path o Url dell'immagine | Titolo del libro | Autore del libro | Data di pubblicazione | **0** In attesa <br> **1** In prestito <br> **X** Sconosciuto | Scaffale in cui è collocato il libro | Identificativo |

### Suggestions
| id (int)       | title (str)              | notes (str)             | date (date)                        | creator (int)  | State                                                 |
|----------------|--------------------------|-------------------------|------------------------------------|----------------|-------------------------------------------------------|
| Identificativo | Nome del libro suggerito | Informazioni aggiuntive | Data di creazione del suggerimento | Identificativo | **0** In attesa <br> **1** Accettato <br> **2** Rifiutato  <br> **X** Sconosciuto |

## Tags
| id (int)       | name (str)   | color (str[6])           | background (str[6])         |
|----------------|--------------|--------------------------|-----------------------------|
| Identificativo | Nome del tag | Hex del colore del testo | Hex del colore dello sfondo |

## Users
| id (int)       | email (str) | password (str)     | isAdmin (bool) | color (str[6])             |
|----------------|-------------|--------------------|----------------|----------------------------|
| Identificativo | Email       | Password (cifrata) | **0** User <br> **1** Admin | Hex del colore del profilo |