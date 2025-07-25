Projekt: Symfony Kanban Board
Ziel: Aufbau einer Symfony-Anwendung für ein Kanban-Board inklusive Docker-Unterstützung mit MariaDB.
Verwendete Technologien
Symfony
PHP 8.2 + Apache
MariaDB
Docker & Docker Compose


Struktur
Dockerfile: PHP-Apache-Image mit Composer und Symfony-Abhängigkeiten


docker-compose.yaml: Startet Symfony-App + MariaDB-Datenbank


.htaccess: Aktiviert URL-Rewriting für Symfony Routing


vhost.conf: Konfiguration für Apache-VirtualHost auf /public


Entitäten & Beziehungen
Übersicht
User erstellt Boards, Cards und Kommentare
Board enthält mehrere Columns
Column (z.B. „To Do“, „In Progress“, „Done“) ordnet Cards
Card ist das Kanban-Element (Aufgabe/Ticket)
Label (z.B. „Bug“, „Feature“) kann mehrfach an Cards hängen
Comment zu jeder Card
Card_Assignment verknüpft Cards und Users (Mehrfach‑Zuweisung möglich)

User
username (string)


email (string)


password (string, gehashed)


createdAt (datetime_immutable)


Board
name (string)


description (text)


createdAt (datetime_immutable)


owner (User, ManyToOne)


Lane
title (string)


position (int, für Sortierung)


board (Board, ManyToOne)


Card
title (string), description (text), position (int), createdAt (datetime)


createdBy (User), lane (Lane)


Comment
content (text), createdAt (datetime)


card (Card), user (User)


Label
name (string), color (hex code)


board (Board)


CardLabel
card (Card), label (Label)


Primärschlüssel: Kombination aus beiden


CardAssignment
card (Card), user (User)


assignedAt (datetime)


Primärschlüssel: Kombination aus beiden


