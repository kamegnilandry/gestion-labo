<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte-rendu {{ $demande->reference }}</title>
    <style>
        @page { margin: 28px 34px; }
        * { box-sizing: border-box; }
        body { font-family: "Helvetica", "Arial", sans-serif; color: #0E2321; font-size: 11.5px; line-height: 1.5; }

        .header-table { width: 100%; border-bottom: 2.5px solid #0B3D3C; padding-bottom: 12px; margin-bottom: 18px; }
        .lab-name { font-size: 19px; font-weight: bold; color: #0B3D3C; }
        .lab-sub { font-size: 10px; color: #4B5F5D; letter-spacing: 0.06em; text-transform: uppercase; margin-top: 2px; }
        .doc-title { text-align: right; font-size: 13px; font-weight: bold; color: #0B3D3C; }
        .doc-ref { text-align: right; font-size: 10.5px; color: #4B5F5D; font-family: "Courier New", monospace; margin-top: 3px; }

        .info-table { width: 100%; margin-bottom: 16px; border-collapse: collapse; }
        .info-table td { padding: 10px 14px; background: #F6F8F7; vertical-align: top; }
        .info-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.06em; color: #4B5F5D; margin-bottom: 2px; }
        .info-value { font-size: 12.5px; font-weight: bold; color: #0E2321; }

        table.resultats { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.resultats th {
            background: #0B3D3C; color: #ffffff; text-align: left; padding: 8px 10px;
            font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.04em;
        }
        table.resultats td { padding: 9px 10px; border-bottom: 1px solid #DCE4E2; font-size: 11px; }
        table.resultats tr:nth-child(even) td { background: #FAFBFA; }
        .valeur-cell { font-family: "Courier New", monospace; font-weight: bold; }
        .tag { padding: 2px 7px; border-radius: 9px; font-size: 9px; font-weight: bold; }
        .tag-normal { background: #DFF3E8; color: #1F8A5F; }
        .tag-anormal { background: #FBEEDA; color: #C98A2C; }
        .tag-critique { background: #FBE4E1; color: #C1443A; }

        .section-title { font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #4B5F5D; margin: 20px 0 6px; }

        .signature-table { width: 100%; margin-top: 34px; }
        .signature-box { width: 48%; }
        .signature-line { border-top: 1px solid #0E2321; margin-top: 40px; padding-top: 4px; font-size: 10px; color: #4B5F5D; }

        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #DCE4E2; font-size: 9px; color: #4B5F5D; text-align: center; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="lab-name">LaboSuite</div>
                <div class="lab-sub">Laboratoire d'analyses médicales — Douala</div>
            </td>
            <td style="width: 40%;">
                <div class="doc-title">COMPTE-RENDU D'ANALYSES</div>
                <div class="doc-ref">{{ $demande->reference }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 33%;">
                <div class="info-label">Patient</div>
                <div class="info-value">{{ $demande->patient->nomComplet() }}</div>
            </td>
            <td style="width: 22%;">
                <div class="info-label">Dossier</div>
                <div class="info-value">{{ $demande->patient->code_patient }}</div>
            </td>
            <td style="width: 22%;">
                <div class="info-label">Sexe / Âge</div>
                <div class="info-value">{{ $demande->patient->sexe }} · {{ $demande->patient->age() }} ans</div>
            </td>
            <td style="width: 23%;">
                <div class="info-label">Date de la demande</div>
                <div class="info-value">{{ $demande->date_demande->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    @if ($demande->prelevement)
        <div class="section-title">Prélèvement</div>
        <table class="info-table">
            <tr>
                <td style="width: 33%;">
                    <div class="info-label">Type d'échantillon</div>
                    <div class="info-value">{{ $demande->prelevement->type_echantillon }}</div>
                </td>
                <td style="width: 33%;">
                    <div class="info-label">Date de prélèvement</div>
                    <div class="info-value">{{ $demande->prelevement->date_prelevement->format('d/m/Y H:i') }}</div>
                </td>
                <td style="width: 34%;">
                    <div class="info-label">Prélevé par</div>
                    <div class="info-value">{{ $demande->prelevement->technicien?->name ?? '—' }}</div>
                </td>
            </tr>
        </table>
    @endif

    <div class="section-title">Résultats des analyses</div>
    <table class="resultats">
        <thead>
            <tr>
                <th style="width: 30%;">Analyse</th>
                <th style="width: 20%;">Résultat</th>
                <th style="width: 25%;">Valeur de référence</th>
                <th style="width: 12%;">Interprétation</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($demande->lignes as $ligne)
                <tr>
                    <td>
                        {{ $ligne->examen->nom }}<br>
                        <span style="font-family: 'Courier New', monospace; font-size: 9px; color: #4B5F5D;">{{ $ligne->examen->code }}</span>
                    </td>
                    <td class="valeur-cell">{{ $ligne->resultat->valeur ?? '—' }} {{ $ligne->resultat->unite ?? '' }}</td>
                    <td>{{ $ligne->resultat->valeur_reference ?? '—' }}</td>
                    <td>
                        @if ($ligne->resultat?->interpretation)
                            <span class="tag tag-{{ $ligne->resultat->interpretation }}">{{ $ligne->resultat->interpretationLabel() }}</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @if ($ligne->resultat?->observations)
                    <tr>
                        <td colspan="4" style="font-size: 10px; font-style: italic; color: #4B5F5D; padding-top: 0;">
                            Observation : {{ $ligne->resultat->observations }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    @if ($demande->notes)
        <div class="section-title">Notes cliniques</div>
        <p style="font-size: 11px; color: #0E2321;">{{ $demande->notes }}</p>
    @endif

    <table class="signature-table">
        <tr>
            <td class="signature-box">
                <div class="signature-line">Cachet du laboratoire</div>
            </td>
            <td class="signature-box" style="text-align: right;">
                <div class="signature-line" style="text-align: left;">
                    Validé par {{ $demande->valideePar?->name ?? '—' }}<br>
                    Responsable médical / Biologiste — le {{ $demande->validee_at?->format('d/m/Y à H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Ce document est un compte-rendu officiel émis par LaboSuite. Toute reproduction partielle est interdite sans le rapport complet.<br>
        Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
