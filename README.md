# Upfront Builder

**Deutsch** | [**English**](README-en.md)

[![Version](https://img.shields.io/badge/Version-1.0.6-2271b1?style=flat-square)](readme.txt)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777bb4?style=flat-square&logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-bis%207.1.0-21759b?style=flat-square&logo=wordpress&logoColor=white)
![ClassicPress](https://img.shields.io/badge/ClassicPress-2.7.1-03768e?style=flat-square)
[![Lizenz](https://img.shields.io/badge/Lizenz-GPL--2.0--or--later-2ea44f?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)

**Visuelle Theme-Entwicklung für ClassicPress.**

Upfront Builder erweitert das Upfront-Framework um eine vollständige Arbeitsumgebung zum Erstellen, Bearbeiten und Exportieren eigener Themes. Layouts, globale Designregeln und responsive Ansichten werden direkt auf der Website gestaltet. Das Ergebnis ist kein in der Datenbank eingeschlossenes Seitendesign, sondern ein eigenständiges Upfront-Theme, das weitergegeben und auf anderen Installationen verwendet werden kann.

> Upfront Builder arbeitet mit Upfront-basierten Themes. Er ist kein universeller Page Builder für beliebige ClassicPress-Themes.

## Was der Builder kann

- neue Upfront-Themes über einen geführten Einstieg anlegen
- bestehende Upfront-Themes visuell weiterentwickeln
- Seiten-, Beitrags-, Archiv-, Such- und 404-Layouts verwalten
- Inhalte und Designelemente per Drag-and-drop positionieren
- responsive Layouts mit Breakpoints und Rasterführung gestalten
- globale Farben, Typografie, Regionen und Elementstile pflegen
- Bilder, Galerien, Slider, Videos, Menüs, Formulare, Widgets und eigenen Code einsetzen
- Theme-Metadaten, Vorschaubild, Lizenz und Textdomain bearbeiten
- Layouts, Styles und optional verwendete Bilder in das Theme schreiben
- fertige Themes als installierbares Paket herunterladen
- Designgrundlagen über den integrierten CodePen-Styleguide bearbeiten

## So funktioniert es

### 1. Upfront vorbereiten

Der Builder benötigt das Upfront-Framework und ein aktives Upfront-basiertes Theme. Fehlt das Framework, lädt der integrierte Kickstart automatisch das Theme-Paket der [aktuellsten stabilen GitHub-Release](https://github.com/Power-Source/upfront/releases), prüft dessen SHA-256-Integrität, installiert es und aktiviert es. Ist Upfront bereits installiert, aber nicht aktiv, übernimmt der Kickstart nur die Aktivierung. Außerdem müssen sprechende Permalinks aktiviert sein.

### 2. Theme erstellen oder auswählen

Im ClassicPress-Backend öffnest Du **Upfront > Upfront Builder**. Dort kannst Du:

- einen Namen für ein neues Theme vergeben und direkt mit dem Aufbau beginnen,
- ein vorhandenes Upfront-Theme im Builder öffnen,
- Theme-Informationen und Vorschaubild bearbeiten oder
- ein fertiges Theme herunterladen.

Beim Anlegen erzeugt der Builder die benötigte Theme-Struktur und stellt grundlegende Layoutvorlagen bereit.

### 3. Visuell gestalten

Der Builder startet im Frontend-Kontext der Website. Dort bearbeitest Du Layouts und Regionen direkt in ihrer späteren Darstellung. Elemente lassen sich hinzufügen, verschieben, skalieren und konfigurieren; responsive Varianten werden über das Breakpoint-System abgestimmt.

Änderungen im Builder-Modus werden in den Dateien des ausgewählten Themes gespeichert. Damit eignet sich der Workflow sowohl für schnelle Prototypen als auch für auslieferbare Kunden- und Basisthemes.

### 4. Speichern und exportieren

Über **Theme speichern** schreibt der Builder Layouts und Designinformationen in das Theme. In der Theme-Verwaltung kannst Du anschließend Metadaten ergänzen, Bilder in den Export aufnehmen und das vollständige Theme-Paket herunterladen. Das exportierte Theme kann danach wie ein anderes ClassicPress-Theme installiert werden, sofern das Upfront-Framework auf dem Zielsystem vorhanden ist.

## Typischer Workflow

1. Upfront Builder installieren und aktivieren.
2. Sprechende Permalinks einschalten.
3. Falls nötig, das Upfront-Framework über den Kickstart installieren und aktivieren lassen.
4. Unter **Upfront > Upfront Builder** ein Theme erstellen oder auswählen.
5. Grundlayout, globale Stile und wiederverwendbare Regionen definieren.
6. Einzel-, Archiv- und Sonderseiten gestalten und responsiv prüfen.
7. Theme-Informationen und Vorschaubild vervollständigen.
8. Theme speichern, herunterladen und auf einer Testinstallation prüfen.

## Voraussetzungen

- ClassicPress
- PHP 7.0 oder neuer
- Upfront-Framework oder ausgehender HTTPS-Zugriff auf GitHub für die automatische Installation
- aktives Upfront-basiertes Theme nach Abschluss des Kickstarts
- aktivierte sprechende Permalinks
- Administrationsrechte für Einrichtung und Export

Für die Entwicklung am Plugin werden zusätzlich Node.js 20.19 oder neuer, npm 10 oder neuer und WP-CLI benötigt.

## Installation

1. Das Verzeichnis `upfront-builder` nach `wp-content/plugins/` kopieren oder das Plugin-Paket über die Plugin-Verwaltung hochladen.
2. **Upfront Builder** in ClassicPress aktivieren.
3. Falls Upfront fehlt, im eingeblendeten Hinweis **Framework installieren und Builder starten** wählen. Der Builder verwendet ausschließlich das offizielle Asset `upfront.zip` der neuesten stabilen Release.
4. Unter **Einstellungen > Permalinks** eine sprechende Struktur auswählen.
5. **Upfront > Upfront Builder** öffnen.

## Lokale Laufzeitbibliotheken

Der Builder lädt seine Laufzeitbibliotheken aus dem Plugin. Für Drag-and-drop und Größenänderungen wird Interact.js verwendet; serverseitig gelieferte Formularansichten werden vor der Ausgabe mit DOMPurify bereinigt. Es werden dafür keine CDN-Ressourcen benötigt.

Die jeweiligen Lizenztexte liegen zusammen mit den Bibliotheken im Verzeichnis [`vendor`](vendor/).

## Entwicklung

Abhängigkeiten installieren und alle Prüfungen ausführen:

```bash
npm ci
npm test
```

CSS neu erzeugen:

```bash
npm run build
```

Alle Voraussetzungen, Befehle und Hinweise zum Übersetzungsworkflow stehen in [DEVELOPMENT.md](DEVELOPMENT.md). Änderungen zwischen den Versionen dokumentiert [CHANGELOG.md](CHANGELOG.md).

## Dokumentation

- [Upfront-Builder-Dokumentation](https://psource.eimen.net/wiki/upfront-dokumentation/upfront-builder-dokumentation/)
- [Upfront-Theme-Entwicklung](https://psource.eimen.net/wiki/upfront-themes/upfront-theme-entwickler/)
- [Projektseite](https://cp-psource.github.io/upfront-builder/)

## Lizenz

Upfront Builder ist freie Software unter der [GNU General Public License, Version 2 oder neuer](license.txt).

Copyright 2014-2026 [PSOURCE](https://psource.eimen.net/)
