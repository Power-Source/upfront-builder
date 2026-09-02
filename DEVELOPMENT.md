# Entwicklung

## Voraussetzungen

- Node.js 20.19 oder neuer
- npm 10 oder neuer
- PHP 7.0 oder neuer für die Syntaxprüfung
- WP-CLI mit dem Befehl `wp i18n make-pot` für Übersetzungen

## Installation

```bash
npm ci
```

## Befehle

```bash
npm test             # JavaScript, PHP und Sass prüfen
npm run build        # exporter.css inklusive Source Map erstellen
npm run build:css    # nur CSS erstellen
npm run build:i18n   # languages/upfront_thx.pot aktualisieren
```

Die PHP-Prüfung überspringt `templates/theme`, weil diese Dateien Export-Platzhalter enthalten und vor ihrer Verarbeitung kein gültiges PHP darstellen.

Abhängigkeiten werden ausschließlich für die Entwicklung installiert. Laufzeitbibliotheken des Plugins liegen weiterhin direkt im Plugin und werden nicht aus `node_modules` ausgeliefert.
