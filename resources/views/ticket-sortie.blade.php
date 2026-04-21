<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Sortie - {{ $sortie->plaque }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px; /* Adapté pour 80mm. Mettre 200px pour 58mm */
            margin: 0 auto;
            padding: 10px;
            color: #000;
            font-size: 14px;
        }
        h2, h3 {
            margin: 5px 0;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .divider { 
            border-top: 1px dashed #000; 
            margin: 10px 0; 
        }
        .info-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .montant-total {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 2px solid #000;
        }
        .qr-container {
            text-align: center;
            margin: 15px 0;
            display: flex;
            justify-content: center;
        }
        .qr-container svg {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <h2>PARKING NOM_DU_PARKING</h2>
    <h3>REÇU DE PAIEMENT</h3>

    <div class="divider"></div>

    <div class="info-line">
        <span>Reçu N°:</span>
        <span class="text-bold">#{{ $sortie->id }}</span>
    </div>
    <div class="info-line">
        <span>Plaque:</span>
        <span class="text-bold">{{ strtoupper($sortie->plaque) }}</span>
    </div>
    <div class="info-line">
        <span>Client:</span>
        <span class="text-bold">{{ $sortie->owner_name ?? 'Inconnu' }}</span>
    </div>

    <div class="divider"></div>

    <div class="info-line">
        <span>Entrée:</span>
        <span class="text-bold">{{ $entree ? $entree->created_at->format('d/m/Y H:i') : 'Non trouvée' }}</span>
    </div>
    <div class="info-line">
        <span>Sortie:</span>
        <span class="text-bold">{{ $sortie->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info-line">
        <span>Durée:</span>
        <span class="text-bold">{{ $joursPasses }} Jour(s)</span>
    </div>

    <div class="divider"></div>

    <div class="info-line">
        <span>Mode de Paiement:</span>
        <span class="text-bold">
            @if($sortie->paiement == 'cash') Espèces 
            @elseif($sortie->paiement == 'card') Carte 
            @elseif($sortie->paiement == 'app') Application 
            @else {{ strtoupper($sortie->paiement) }} 
            @endif
        </span>
    </div>

    <div class="montant-total">
        TOTAL : {{ number_format($montantTotal, 0, ',', ' ') }} FCFA
    </div>

    <div class="divider"></div>

    <!-- AFFICHAGE DU QR CODE ICI -->
    <div class="qr-container">
        {!! $qrCode !!}
    </div>

    <div class="divider"></div>

    <p class="text-center text-bold" style="font-size: 13px;">
        Merci de votre visite et<br>
        à très bientôt !
    </p>

</body>
</html>