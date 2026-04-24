# Gründerpaket Frankfurt — Content-Cluster

Fertiger Content für das in Phase 5 geplante Content-Cluster. Jede Datei mappt auf genau eine Page im Filament-Admin; die Abschnitte unter `## Filament-Tabs` entsprechen 1:1 den dortigen Tabs, sodass sich Inhalte direkt kopieren lassen.

## Struktur

| # | Datei | Filament-Pfad | Typ | Primär-Keyword |
|---|---|---|---|---|
| Pillar | `01-pillar-gruenderpaket-frankfurt.md` | `Lösungen → Solution Hubs → Neu` | Solution Hub | Gründerpaket Webdesign Frankfurt |
| S1 | `02-spoke-website-fuer-existenzgruender.md` | unter Pillar | Solution Detail | Website Existenzgründer Frankfurt |
| S2 | `03-spoke-logo-corporate-identity.md` | unter Pillar | Solution Detail | Logo und Website für Gründer |
| S3 | `04-spoke-digitale-geschaeftsausstattung.md` | unter Pillar | Solution Detail | digitale Geschäftsausstattung Gründer |
| S7 | `05-spoke-social-media-setup.md` | unter Pillar | Solution Detail | Social Media für Gründer einrichten |
| S4 | `06-guide-gruendung-checkliste.md` | `Ratgeber → Neu` | Guide (TYPE_GUIDE) | Geschäftsausstattung Existenzgründung Checkliste |
| S5 | `07-guide-website-kosten-existenzgruender.md` | `Ratgeber → Neu` | Guide (TYPE_GUIDE) | Website Kosten Existenzgründer |
| S6 | `08-guide-impressum-pflicht-selbststaendige.md` | `Ratgeber → Neu` | Guide (TYPE_GUIDE) | Impressum Pflicht Einzelunternehmer |

## Reihenfolge der Umsetzung (empfohlen)

**Sprint 1 — MVP (Woche 1):** Pillar (`01`) + S1 (`02`) + S4 (`06`). Das reicht um Google Ads zu starten.

**Sprint 2 — Spokes (Woche 2–4):** S2 (`03`), S5 (`07`), S6 (`08`).

**Sprint 3 — Abrundung (Woche 4–6):** S3 (`04`), S7 (`05`).

## Internal Linking Matrix

```
         ┌─────────── Pillar ───────────┐
         │     gruenderpaket-frankfurt    │
         └───┬─────┬─────┬─────┬─────┬───┘
             │     │     │     │     │
           ┌─▼─┐ ┌─▼─┐ ┌─▼─┐ ┌─▼─┐ ┌─▼─┐
           │S1 │ │S2 │ │S3 │ │S7 │ │S4 │
           └─┬─┘ └─┬─┘ └───┘ └─┬─┘ └─┬─┘
             │     │           │     │
             ▼     ▼           ▼     ▼
           S6↔S1 S7↔S2       S2↔S7 S5→Pillar
```

Jeder Spoke verlinkt zurück zum Pillar. Zusätzlich:
- **S1 (Website) ↔ S2 (Logo/CI)** — natürlicher Branding-Zusammenhang
- **S1 (Website) ↔ S6 (Impressum)** — rechtliche Konsequenz
- **S3 (Digitale Geschäftsausstattung) → S4 (Checkliste)** — validiert die Leistung
- **S4 → S5 → S1/Pillar** — Funnel: Checkliste → Preis-Research → Commercial
- **S5 (Kosten) → Pillar** — stärkster Conversion-Link

## Arbeitsweise im Filament

1. **Pillar-Page anlegen** als neuen Solution Hub.
2. Slug-Feld: `gruenderpaket-frankfurt` (DE) + `founder-package-frankfurt` (EN, falls EN geplant).
3. Reihenfolge der Tabs im Doc entspricht der Tab-Reihenfolge im Admin. Einfach durcharbeiten.
4. Für die Spokes: Solution Detail Page anlegen, `parent_id` auf den Pillar-Hub setzen.
5. Für die Guides: Unter `Ratgeber` neue Page vom Typ GUIDE erstellen (keine Parent-Relation nötig).

## Keywords & SEO-Notes

Jede Datei enthält oben:
- Primäres Keyword + sekundäre Keywords
- Meta-Title (max. 60 Zeichen)
- Meta-Description (max. 155 Zeichen)
- Wortzahl-Ziel

Die Keywords wurden vom seo-cluster-Agent aus dem Phase-5-Plan validiert und mit der SERanking-CSV abgeglichen.

## Research-Basis

Siehe `00-competitor-research.md` für das Recherche-Briefing zu den Top-10-Frankfurter-Wettbewerbern. Dort steht, welche Positioning-Lücken sdWebdesign besetzt.
