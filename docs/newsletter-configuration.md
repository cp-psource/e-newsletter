---
layout: psource-theme
title: "PS-eNewsletter Listen"
---

<h2 align="center" style="color:#38c2bb;">📚 PS-eNewsletter Listen</h2>

<div class="menu">
  <a href="https://github.com/cp-psource/e-newsletter/discussions" style="color:#38c2bb;">💬 Forum</a>
  <a href="https://github.com/cp-psource/e-newsletter/releases" style="color:#38c2bb;">📝 Download</a>
</div>

# Hauptkonfiguration

Hier erfährst du alles, was du für die Einrichtung des Newsletter-Plugins wissen musst. Wir wollen, dass du das Beste aus dem Plugin rausholst! Auf dieser Seite findest du eine Erklärung zu allen wichtigen Einstellungen, die du zu Beginn konfigurieren solltest.

Wenn du Fragen hast, nutze bitte das Support-Forum – dort findest du Antworten oder kannst neue Fragen stellen.

## Inhalte

- Die öffentliche Newsletter-Seite
- Öffentliche Seite auf mehrsprachigen Blogs
- Absender-E-Mail und Name
- Nutze eine echte E-Mail-Adresse
- Testen, testen, testen
- Wenn Mails nicht mit der eingestellten Adresse ankommen
- Return-Path
- Antwort-Adresse (Reply-To)
- Versandgeschwindigkeit
- Erweiterte Einstellungen
  - Eigene CSS
  - Erlaubte Rollen
  - Log-Level
  - Tracking-Standard
  - Speicherung von IP-Adressen & Datenschutz
  - Debug-Modus
  - E-Mail-Encoding
  - Shortcodes in E-Mails
  - Tracking- und Aktions-Links

## Öffentliche Newsletter-Seite

Das Newsletter-Plugin benötigt eine Standardseite in WordPress für Service-Nachrichten: Aktivierungs-, Willkommens- und Abmeldeseiten. Wenn du den Konfigurationsassistenten genutzt hast, wurde diese Seite bereits für dich angelegt.

**Wichtig:** Diese Seite muss den Shortcode `[newsletter]` enthalten – sonst funktioniert sie nicht richtig!

### Hinweise

- Bei Mehrsprachigkeit: siehe unten.
- Diese Seite kannst du im Design an dein Theme anpassen.
- Wenn du eine andere Seite nutzen möchtest: füge dort den Shortcode ein und hinterlege sie unter "Allgemeine Einstellungen" als öffentliche Seite.
- Warnung deaktivieren? Nutze `define('NEWSLETTER_PAGE_WARNING', false);` in deiner `wp-config.php`.

Achtung: Wenn die Seite gelöscht, offline gestellt oder der Shortcode entfernt wird → 404-Fehler!

**Keine weiteren Newsletter-Shortcodes (wie [newsletter\_form]) auf dieser Seite verwenden!**

Willst du das Layout anpassen? Checke, ob dein Theme Seitenvorlagen unterstützt oder erstelle dir eine eigene.

## Öffentliche Seite auf mehrsprachigen Blogs

Bei mehrsprachigen Blogs wird die Seite nur für die Hauptsprache "Newsletter" angelegt.

- Fallback auf Hauptsprache aktiviert → funktioniert auch für andere Sprachen.
- Kein Fallback → eigene Übersetzung für jede Sprache anlegen.
- Service-Nachrichten (Aktivierung, Willkommen usw.) lassen sich in den Newsletter-Einstellungen übersetzen.

## Absender-E-Mail und Name

Der Absender ist "wer" die E-Mails verschickt: Name (z. B. dein Name oder Firmenname) + E-Mail-Adresse. Wurde im Installationsassistenten eingerichtet.

### Nutze eine echte E-Mail-Adresse

Ideal: E-Mail-Adresse deiner Domain → z. B. `newsletter@meinedomain.de`

⚠️ **Keine Freemailer (Gmail, Yahoo, Outlook usw.) verwenden!** → Wird von vielen Servern blockiert, außer du nutzt ein SMTP-Plugin.

### Testen, testen, testen!

Jeder Provider tickt anders → Immer Testmail über "Hilfe → Zustellung" schicken.

### Wenn E-Mails mit falscher Adresse rausgehen

Mögliche Gründe:

1. SMTP-Plugin überschreibt Absender.
2. SMTP mit Gmail → Google ersetzt Absender.
3. Plugins wie WooCommerce ändern manchmal Absender.
4. Provider überschreibt Absender → ggf. Support kontaktieren.

## Return-Path

Hier landen unzustellbare Mails. Idealerweise echte Mailbox → dann kannst du die Empfänger bereinigen.

Wenn du über externe Dienste (z. B. Sendgrid, Amazon SES) versendest → brauchst du dich darum meist nicht kümmern.

## Antwort-Adresse (Reply-To)

Hier kommen Antworten von Empfängern an → Echte Adresse verwenden, am besten von deiner Domain.

## Versandgeschwindigkeit

### Max. E-Mails pro Stunde

Verhindert, dass Provider deine Mails blocken. Frag deinen Hoster nach dem Limit.

### Max. E-Mails pro Sekunde

Optional: Verzögerung zwischen den E-Mails → z. B. `0.1` = 10 Sekunden Verzögerung pro Mail.

Weitere Infos: Konstanten `NEWSLETTER_SEND_DELAY` verwenden.

## Erweiterte Einstellungen

### Eigene CSS

Für Design-Freaks: Passe Formulare, Buttons, etc. mit eigenem CSS an.

### Erlaubte Rollen

Wer darf das Newsletter-Backend nutzen? Standardmäßig Admins, optional z. B. Redakteure. Andere Rollen können durch Plugins hinzugefügt werden.

Manche Funktionen (z. B. SMTP-Konfig) immer nur für Admins.

### Log-Level

Fehler und Statusberichte → Erhöhter Log-Level → mehr Infos, aber große Logdateien!

### Tracking-Standard

Voreinstellung für Öffnungs- und Klick-Tracking → kann pro Newsletter geändert werden.

### Speicherung von IP-Adressen & Datenschutz

- **Speichern** → genaue Statistiken, Flood-Control, Geolokalisierung
- **Anonymisieren** → letzte Stelle der IP wird 0
- **Nicht speichern** → weniger Infos, keine Flood-Control

### Debug-Modus

Nur für Support-Fälle → Alle Fehler werden protokolliert → Nicht dauerhaft aktiv lassen!

### E-Mail-Encoding

Standard → passt fast immer. Bei uralten Mailservern → ggf. "base64" testen.

### Shortcodes in E-Mails

Immer aktiv → Aber: Verhalten kann je nach Plugin/Themes variieren.

### Tracking- und Aktions-Links

Standard-Links: `https://example.org/?na=s&...` → Problematisch bei Caches/CDNs → alternativ: `https://example.org/wp-admin/admin-ajax.php?action=tnp&...`

### Probleme & Lösungen

- Cache blockiert Links → Format wechseln.
- "Forbidden" beim Klicken → Basic Auth aktiv → unlock für `admin-ajax.php` z. B. mit:

```apacheconf
<Files "admin-ajax.php">
    Satisfy Any
    Allow from all
</Files>
```

Immer nach Änderung → Testnewsletter neu versenden!
