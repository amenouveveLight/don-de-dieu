<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket d'entrée - {{ $entree->plaque }}</title>
    <style>
        /* Styles optimisés pour imprimante thermique */
        body {
            font-family: 'Courier New', Courier, monospace; 
            width: 300px; 
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
            margin: 15px 0; 
        }
        .info-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
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
    <h3>TICKET D'ENTRÉE</h3>

    <div class="divider"></div>

    <div class="info-line">
        <span>N° Ticket:</span>
        <span class="text-bold">#{{ $entree->id }}</span>
    </div>
    <div class="info-line">
        <span>Plaque:</span>
        <span class="text-bold">{{ strtoupper($entree->plaque) }}</span>
    </div>
    <div class="info-line">
        <span>Type:</span>
        <span class="text-bold">{{ ucfirst($entree->type) }}</span>
    </div>
    <div class="info-line">
        <span>Client:</span>
        <span class="text-bold">{{ $entree->name }}</span>
    </div>
    <div class="info-line">
        <span>Tél:</span>
        <span class="text-bold">{{ $entree->phone }}</span>
    </div>
    <div class="info-line">
        <span>Date:</span>
        <span class="text-bold">{{ $entree->created_at->format('d/m/Y H:i') }}</span>
    </div>

    <div class="divider"></div>

    <!-- AFFICHAGE DU QR CODE ICI -->
    <div class="qr-container">
        {!! $qrCode !!}
    </div>

    <div class="divider"></div>

    <p class="text-center text-bold" style="font-size: 12px;">
        Veuillez conserver ce ticket pour<br>
        votre sortie.
    </p>
    <p class="text-center" style="font-size: 12px;">Merci de votre visite !</p>

</body>
</html>