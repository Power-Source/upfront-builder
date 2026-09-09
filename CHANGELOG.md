Change Log
============

1.0.8 - 2026-09-10
---------------------------------------------------------
- Fix: Der Builder startet mit dem aktiven Upfront-Child-Theme statt mit einem nicht vorhandenen Standard-Slug und lädt die Startseiten-Cascade im regulären WordPress-Kontext
- Fix: Builder und Seiteneditor halten Post-, Sidebar- und Layout-Kontext beim Laden und Wechseln von Layouts getrennt
- Fix: Builder-Module verwenden wieder versionierte RequireJS-URLs, damit Änderungen an Styles, Layout-Hilfen, Sidebar und Layoutdialog nicht aus einem veralteten Browser-Cache stammen
- Fix: Der Bildexport bewahrt exportierte Theme-Bilder beim Aufräumen und Bildgrößen werden ohne lange Dezimalwerte dargestellt

1.0.7 - 2026-09-08
---------------------------------------------------------
- Fix: Builder-Endpunkte unterdrücken den allgemeinen Upfront-Autostart und lassen ausschließlich den Theme Builder initialisieren
- Fix: Theme bearbeiten verwendet eine stabile URL zum aktiven Upfront-Child-Theme
- Fix: Das Builder-Menü zeigt genau einen Hilfeeintrag und verlinkt kontextabhängig auf die Upfront-Builder-Dokumentation

1.0.6 - 2026-09-05
---------------------------------------------------------
- Fix: Der unkomprimierte Layout-Export bewahrt gültige JSON-Unicode-Sequenzen und exportiert Umlaute wie in Aktivität wieder korrekt
- Fix: Navigationselemente ohne Menü-ID werden beim Ermitteln von Lightboxes und beim Exportieren von Menüs sicher übersprungen
- Sicherheit: Theme-, Editor- und Download-Ziele werden vor der Navigation auf gültige Slugs, HTTP(S) und Same-Origin beschränkt

1.0.5 - 2026-09-02
---------------------------------------------------------
- Fix Creation of dynamic Property
- Modernisierung der Bildvarianten: Drag-and-drop und Größenänderung verwenden jetzt das lokal integrierte Interact.js statt jQuery UI
- Fix: Der veraltete jQuery-UI-Sortable-Fallback wird aus der Medien-Queue entfernt, wenn ClassicPress bereits SortableJS verwendet
- Neuer informativer Builder-Header mit Theme-Status und direktem Zugriff auf den CodePen-Styleguide
- Modernisierte Entwicklungsumgebung mit Node.js 20+, Dart Sass, ESLint, reproduzierbarem npm-Lockfile und WP-CLI-Übersetzungsworkflow
- Security: Vier mögliche DOM-XSS-Senken im Theme-Dialog durch sichere Text-APIs und lokal integriertes DOMPurify geschlossen
- Kickstart installiert bei fehlendem Framework automatisch das offizielle `upfront.zip` der neuesten stabilen GitHub-Release, prüft den SHA-256-Digest und aktiviert Upfront

1.0.4 - 2026-08-16
---------------------------------------------------------
- Fix: Der create_new-Editor lädt die No-Build-Core-Abhängigkeiten von Upfront wieder zuverlässig
- Fix: Virtuelle Builder-Seiten starten ohne editmode-Parameter direkt im Theme-Modus
- Fix: Weiße Builder-Seiten durch einen fehlenden Core-Bootstrap verhindert
- Fix: Post-Data-Inhalte werden auch ohne initialisierten Post-Editor geladen, statt den Builder mit einem JavaScript-Fehler abzubrechen

1.0.3 - 2026-08-16
---------------------------------------------------------
- Bessere Texte und Beschreibungen

1.0.2 - 2026-08-10
---------------------------------------------------------
- Verbessere die Fehlerbehandlung 
- Aktualisiere die Übersetzungen in mehreren Dateien 
- Skriptregistrierung angepasst
- Neue Eigenschaften in der Theme-Klasse hinzugefügt

1.0.1 - 2026-08-06
---------------------------------------------------------
- Regionen stabilisiert, neue Regionen speichern wieder korrekt
- Mehrer veraltete Skripts modernisiert
- Ui Anpassungen

1.0.0 - 2026-08-02
---------------------------------------------------------
- Initial public release.
