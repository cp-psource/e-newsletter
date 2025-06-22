---
layout: psource-theme
title: "PS-eNewsletter Listen"
---

<h2 align="center" style="color:#38c2bb;">📚 PS-eNewsletter Listen</h2>

<div class="menu">
  <a href="https://github.com/cp-psource/e-newsletter/discussions" style="color:#38c2bb;">💬 Forum</a>
  <a href="https://github.com/cp-psource/e-newsletter/releases" style="color:#38c2bb;">📝 Download</a>
</div>

# Listen

Das Newsletter-Plugin nutzt nur eine Datenbank mit eindeutigen E-Mail-Adressen. Jede Adresse gehört zu einem Abonnenten und kann einer oder mehreren Listen zugeordnet sein.

Listen helfen dir, gezielt Newsletter zu verschicken oder spezielle Dienste zu nutzen (z.B. das Automatisierungs-Addon). Hier erfährst du alles Wichtige für den Einstieg.

## Inhalt

- Verhalten der Listen bei Anmeldung
- Öffentliche und private Listen
- Listen in mehrsprachigen Blogs
- Listen je Sprache automatisch zuweisen
- Vorgegebene Listen
- Listen löschen
- Tipps & Best Practices
- Mehr als 40 Listen hinzufügen

---

## Verhalten der Listen bei Anmeldung

Abonnenten können auf verschiedene Arten Listen zugeordnet werden:

- Auswahl im Anmeldeformular, wenn Listen dort angezeigt werden
- Vorbelegung über die Seite Anmeldung/Listen, wo Listen für neue Abonnenten automatisch gesetzt werden können
- Automatisch über Shortcodes, z.B. `[newsletter_form lists="1"]` (die Listen müssen öffentlich sein)
- Über Add-ons (WooCommerce, Contact Form 7, WP User Registration …) – Listen werden dort in den Einstellungen gewählt

---

## Öffentliche und private Listen

Es gibt zwei Arten:

### Öffentliche Listen

→ Nutzer können selbst wählen, welche sie wollen (Anmeldeformular, Profilseite). Kombinierbar z.B. so:

- Zwangsanmeldung auf eine öffentliche Liste, aber später änderbar im Profil
- Anzeige im Formular → Nutzer kann aktiv auswählen
- Nur im Profil bearbeitbar → Formular bleibt schlicht, Anpassung erst nach Bestätigung

### Private Listen

→ Nur Admin kann Nutzer hinzufügen/entfernen, gut für z.B. Premium-Newsletter, interne Gruppen

### "Versteckte" Listen

→ Öffentliche Listen, die weder im Formular noch im Profil erscheinen

---

## Listen und mehrsprachige Blogs

Mit WPML oder Polylang kannst du im Listenbereich die Sprache wechseln und Listen sprachspezifisch benennen (z.B. „Travels“ → „Viajes“ → „Viaggi“). Newsletter zeigt dann im Formular den richtigen Namen an.

Beim Versenden kannst du Listen + Sprache filtern → z.B. italienischer Newsletter nur an italienische Abonnenten.

---

## Listen je Sprache automatisch zuweisen

Du kannst Listen automatisch je nach Sprache zuweisen lassen. Das macht Sinn, wenn du damit z.B. automatisierte Serien verbinden willst.

---

## Vorgegebene Listen (Enforced)

Wenn „erzwungen“ gesetzt → jeder neue Abonnent landet automatisch in dieser Liste (öffentlich oder privat).

---

## Formulare für verschiedene Listen

Wenn du mehrere Formulare hast (z.B. für verschiedene Themenbereiche), kannst du pro Formular unterschiedliche Listen festlegen:

```plaintext
[newsletter_form lists="x"]
[newsletter_form lists="y"]
```

Oder für beide Listen:

```plaintext
[newsletter_form lists="x, y"]
```

Newsletter verwaltet Dopplungen automatisch und aktualisiert das Profil, wenn nötig.

---

## Listen löschen

- Listen selbst können nicht gelöscht werden → nur „entleert“ (Verbindungen entfernen). → Gehe dazu auf Anmeldung/Listen.
- Komplett löschen (inkl. Abonnenten) → Abonnenten/Wartung.
- Wenn du eine Liste verstecken willst → einfach den Namen löschen.

---

## Best Practices & Tipps

Vor dem Versand → immer fragen:

- Was bringt’s den Abonnenten?
- Ist es relevant für sie?

Beispiel für sinnvolle Listenaufteilung:

- Produkt-Updates
- Entwickler-Kanal
- Tutorials & Anleitungen
- Promo-Aktionen → an alle Listen oder gezielt an bestimmte → Newsletter kümmert sich ums Zusammenführen.

**Tipp:** Immer den Platzhalter `{profile_url}` in deine Mails packen → damit die Leute ihre Präferenzen selbst anpassen können.

---

## Mehr als 40 Listen hinzufügen

Standard = 40 Listen. Mehr → in der `wp-config.php`:

```php
define('NEWSLETTER_LIST_MAX', X);
```

Danach Plugin deaktivieren & wieder aktivieren → keine Daten gehen verloren.

**Empfehlung:** Nicht über 100 Listen gehen.