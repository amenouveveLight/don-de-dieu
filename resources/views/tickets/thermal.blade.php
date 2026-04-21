<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ticket d'Entrée</title>
  <style>
    @page { size: 80mm auto; margin: 10px; }
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #111;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    .logo { max-width: 60px; margin: 5px auto; display: block; }
    .title { font-size: 14px; font-weight: bold; margin-top: 4px; padding: 6px 0;
             border-top: 1px dashed #999; border-bottom: 1px dashed #999; 
             text-transform: uppercase; letter-spacing: 1px; }
    .date { font-size: 10px; margin-bottom: 8px; color: #555; }
    .section { margin: 10px 0; padding: 0 5px; text-align: left; }
    .section-title { font-weight: bold; margin-bottom: 5px; font-size: 12px; 
                     text-transform: uppercase; color: #333; text-align: center; }
    .info-line { display: flex; justify-content: space-between; padding: 2px 0; font-size: 11px; }
    .dashed { border-top: 1px dashed #ccc; margin: 12px 0; }
    .footer { font-size: 10px; margin-top: 8px; color: #444; line-height: 1.4; }
    .footer strong { display: block; margin-top: 3px; font-size: 11px; }
  </style>
</head>
<body>

  <img src="{{ asset('images/T.png') }}" alt="Logo" class="logo">

  <div class="title">
    Ticket de Entrée<br>
    N° {{ str_pad($entree->id, 5, '0', STR_PAD_LEFT) }}
  </div>

  <div class="date">{{ now()->format('d/m/Y H:i') }}</div>

  <div class="section">
    <div class="section-title">Véhicule</div>
    <div class="info-line"><span>Plaque :</span><span>{{ strtoupper($entree->plaque) }}</span></div>
    <div class="info-line"><span>Type :</span><span>{{ ucfirst($entree->type) }}</span></div>
    <div class="info-line"><span>Nom :</span><span>{{ $entree->name }}</span></div>
    <div class="info-line"><span>Tél :</span><span>{{ $entree->phone }}</span></div>
    <div class="info-line"><span>Nom d'agent:</span><span>{{ Auth::user()->firstname }}</span></div> 
  </div>

  <div class="dashed"></div>

  <div class="footer">
    Merci pour votre confiance<br>
    À bientôt et bonne route !<br>
    <strong>Viroscope</strong>
  </div>

  @if(session('print_ticket'))
  <script>
    window.onload = function () {
        window.print(); // imprime directement le ticket
    };
  </script>
  @endif

</body>
</html>
