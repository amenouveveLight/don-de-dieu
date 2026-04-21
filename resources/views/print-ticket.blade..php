<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket d'Entrée</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }

        .container {
            padding: 5px 8px;
            width: 100%;
        }

        .header {
            text-align: center;
        }

        .logo img {
            max-height: 60px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 14px;
            margin: 4px 0;
            border-bottom: 1px dashed #444;
            padding-bottom: 4px;
        }

        .ticket-id {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .section {
            margin: 8px 0;
            border: 1px dashed #ccc;
            padding: 6px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .info-line {
            margin: 3px 0;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 40%;
        }

        .value {
            display: inline-block;
            width: 58%;
            text-align: right;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 10px;
            border-top: 1px dashed #aaa;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                {{-- Utilise asset() si c’est pour web --}}
                <img src="{{ asset('images/R.png') }}" alt="Viroscope">
            </div>
            <h2>Ticket d'Entrée   #TCK-{{ str_pad($entree->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <div class="ticket-id"></div>
        </div>

        <div class="section">
            <div class="info-line">
                <span class="label">Plaque :</span>
                <span class="value">{{ strtoupper($entree->plaque) }}</span>
            </div>
            <div class="info-line">
                <span class="label">Type :</span>
                <span class="value">{{ ucfirst($entree->type) }}</span>
            </div>
            <div class="info-line">
                <span class="label">Nom :</span>
                <span class="value">{{ $entree->name }}</span>
            </div>
            <div class="info-line">
                <span class="label">Téléphone :</span>
                <span class="value">{{ $entree->phone }}</span>
            </div>
            <div class="info-line">
                <span class="label">Date :</span>
                <span class="value">{{ $entree->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="footer">
            Merci pour votre visite chez <strong>Viroscope</strong><br>
            Veuillez conserver ce ticket comme preuve d’entrée
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
            // Optionnel : fermer l’onglet après impression
            // window.onafterprint = () => window.close();
        };
    </script>
</body>
</html>
