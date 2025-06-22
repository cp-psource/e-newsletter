---
layout: psource-theme
title: "PS-eNewsletter Tags"
---

# 📚 PS-eNewsletter Tags

<div class="menu">
  <a href="https://github.com/cp-psource/e-newsletter/discussions" style="color:#38c2bb;">💬 Forum</a>
  <a href="https://github.com/cp-psource/e-newsletter/releases" style="color:#38c2bb;">📝 Download</a>
</div>

## Newsletter-Tags - So fügst du Abonnentendaten in Newsletter ein

Das Newsletter-Plugin bietet eine Reihe von Tags oder Platzhaltern, die du verwenden kannst, um Abonnentendaten, dynamische URLs oder Formulare auf Seiten oder in Newslettern einzufügen. Beispiele sind der Name des Abonnenten oder die persönliche Abmelde-URL.

Sie werden als `{tagname}` geschrieben, wie `{name}` (der Vorname des Abonnenten) oder `{email_url}` (der Link zur Online-Version des Newsletters).

Lass uns alle erkunden!

## 📋 Inhalt

- [Verwendung von URL-generierenden Tags](#verwendung-von-url-generierenden-tags)
- [Allgemeine Tags](#allgemeine-tags)
- [Abonnentenspezifische Tags](#abonnentenspezifische-tags)
- [Über Profilfeld-Tags {profile_N}](#über-profilfeld-tags-profile_n)
- [Anrede nach Geschlecht](#anrede-nach-geschlecht)
- [URL-Tags für Anmeldung, Abmeldung und Profilseite](#url-tags-für-anmeldung-abmeldung-und-profilseite)
- [Firmendaten-Tags](#firmendaten-tags)
- [Formulare](#formulare)

## Grundlagen

Tags können in Nachrichten, Betreffzeilen und auf Seitentexten verwendet werden (konfigurierbar über das Abonnement-Panel). Natürlich machen nicht alle Tags in jedem Kontext Sinn. Zum Beispiel macht ein Anmeldebestätigungs-Tag in der Willkommensnachricht wenig Sinn (die gesendet wird, wenn die Anmeldung bereits bestätigt ist).

> **⚠️ Wichtig:** Newsletter-Tags können **nicht** in Beiträgen oder Seiten verwendet werden - sie funktionieren dort nicht! Sie funktionieren nur, wenn der Text vom Newsletter-Plugin verarbeitet wird, z.B. beim Erstellen der finalen E-Mail oder der finalen Nachricht für den Abonnenten.

Viele Tags sind abonnentenverknüpft und benötigen daher einen Abonnentendatensatz zur Generierung. Klar braucht der `{name}`-Tag einen Abonnenten, aber sogar die `{subscription_confirm_url}` benötigt ihn, da die generierte URL die privaten Schlüssel des Abonnenten enthält.

## Verwendung von URL-generierenden Tags

Einige Tags generieren eine URL mit dem privaten Token des Abonnenten, um auf sein Profil zuzugreifen oder Aktionen wie Aktivierung oder Abmeldung zu starten. Wenn du direkt HTML-Code schreibst, sollte der Tag so verwendet werden:

```html
<a href="{unsubscription_url}">Zum Abmelden klicke hier</a>
```

Wenn du einen Editor verwendest, wähle einfach das Wort oder den Satz aus, der zu einem Link werden soll, drücke das Link-Tool und verwende den Tag als URL.

## Allgemeine Tags

| Tag | Beschreibung |
|-----|-------------|
| `{blog_url}` | Die Blog-URL, z.B. https://www.example.com |
| `{blog_title}` | Der Blog-Titel, wie in den WordPress-Grundeinstellungen konfiguriert |
| `{blog_description}` | Die Blog-Beschreibung, wie in den WordPress-Grundeinstellungen konfiguriert |
| `{date}` | Das aktuelle Datum (nicht die Zeit), formatiert wie in den WordPress-Grundeinstellungen konfiguriert |
| `{date_NNN}` | Das aktuelle Datum, formatiert nach NNN (kompatibel mit PHP date()-Funktion) |
| `{email_url}` | Die URL zur Online-Ansicht des aktuellen Newsletters |

## Abonnentenspezifische Tags

| Tag | Beschreibung |
|-----|-------------|
| `{id}` | Die eindeutige ID des Abonnenten |
| `{name}` | Der Name oder Vorname des Abonnenten (je nach Feldverwendung bei der Anmeldung) |
| `{surname}` | Der Nachname des Abonnenten |
| `{title}` | Der Titel oder die Anrede des Abonnenten (z.B. Herr/Frau), konfigurierbar im Anmeldepanel |
| `{email}` | Die E-Mail-Adresse des Abonnenten |
| `{profile_N}` | Das Profilfeld Nummer N, wie in den Anmeldeformular-Feldern konfiguriert |
| `{ip}` | Die IP-Adresse, von der die Anmeldung gestartet wurde |

## Über Profilfeld-Tags {profile_N}

Der `{profile_N}`-Tag muss verwendet werden, indem du das "N" durch die Nummer des Profilfelds ersetzt, das du einfügen möchtest.

**Beispiel:** Wenn dein Profilfeld Nummer 2 die Schuhgröße ist und du den Newsletter-Inhalt personalisieren möchtest, indem du etwa schreibst "Sieh dir alle unsere Angebote für Schuhe in Größe [Abonnenten-Schuhgröße] an", kannst du den Satz so schreiben: 

```
"Sieh dir alle unsere Angebote für Schuhe in Größe {profile_2} an"
```

## Anrede nach Geschlecht

Um einen Newsletter mit unterschiedlicher Anrede je nach Geschlecht zu beginnen, kannst du eine Kombination der Tags `{title}` und `{name}` verwenden:

```
Guten Morgen {title} {name},
```

Hier wird `{title}` durch "Herr" oder "Frau" oder die Texte ersetzt, die du im Panel "Anmeldung > Formularfelder" eingestellt hast.

## URL-Tags für Anmeldung, Abmeldung und Profilseite

| Tag | Beschreibung |
|-----|-------------|
| `{subscription_confirm_url}` | Zur Bestätigung einer Anmeldung - nur in Bestätigungs-E-Mails bei Double-Opt-In verwenden |
| `{unsubscription_url}` | Leitet den Nutzer zur Abmeldeseite, wo er bestätigen muss, dass er sich abmelden möchte; sollte in jeder E-Mail verwendet werden |
| `{unsubscription_confirm_url}` | Für die endgültige Abmeldung; kann für "Ein-Klick-Abmeldung" in jeder E-Mail oder auf der Abmeldeanfrage-Seite verwendet werden |
| `{profile_url}` | Zeigt direkt auf die Profilbearbeitungsseite; ich verwende diesen Tag lieber für die Abmeldefunktion und füge auf der Profilseite die `{unsubscription_confirm_url}` hinzu, damit der Abonnent (eventuell) sein Profil überprüfen kann, anstatt sich abzumelden |

> **💡 Tipp:** Ich verwende lieber `{profile_url}` für die Abmeldefunktion, da Abonnenten so die Möglichkeit haben, ihr Profil zu überprüfen, bevor sie sich endgültig abmelden.

## Firmendaten-Tags

Firmendaten können im Panel "Einstellungen > Firmendaten" eingestellt werden.

| Tag | Beschreibung |
|-----|-------------|
| `{company_name}` | Der Firmenname aus der Firmendaten-Konfiguration |
| `{company_address}` | Die Firmenadresse aus der Firmendaten-Konfiguration |
| `{company_legal}` | Der rechtliche Text aus den Firmendaten |

## Formulare

Formular-Tags sind spezifisch und können nur auf bestimmten Seiten verwendet werden. Sie können in verschiedenen Kontexten unterschiedlich funktionieren.

| Tag | Beschreibung |
|-----|-------------|
| `{subscription_form}` | Generiert das Hauptanmeldeformular und sollte nur auf der Anmeldeseite verwendet werden (konfigurierbar im Anmeldepanel) |
| `{subscription_form_N}` | Kann anstelle von `{subscription_form}` verwendet werden, um das benutzerdefinierte Formular Nummer N aufzurufen |
| `{profile_form}` | Muss im Profilseitentext verwendet werden (konfigurierbar im Anmeldepanel) und generiert das Formular, wo ein Abonnent seine Daten überprüfen und bearbeiten kann |

> **📝 Hinweis:** Das `{subscription_form}` wird im Widget durch ein anderes Formular mit denselben Feldern, aber einem anderen Layout ersetzt, das besser in eine Seitenleiste passt.

---

**🚀 Viel Erfolg mit deinen personalisierten Newslettern!**
