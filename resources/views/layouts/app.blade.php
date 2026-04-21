<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/T.png') }}">
        <title>Ak Light Solution</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
   <body class="bg-green-600 text-white">
        
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
            @yield('content')
            </main>
             <!-- ========================================================= -->
<!-- ZONE TICKET ENTRÉE (MASQUÉE)                              -->
<!-- ========================================================= -->
<div id="ticket-entree-print" class="print-only" style="display: none;">
    <div style="font-family: 'Courier New', Courier, monospace; width: 300px; margin: 0 auto; padding: 10px; color: #000; font-size: 14px; background: white;">
        <h2 style="text-align: center; margin: 5px 0;">PARKING PRO</h2>
        <h3 style="text-align: center; margin: 5px 0;">TICKET D'ENTRÉE</h3>
        <div style="border-top: 1px dashed #000; margin: 15px 0;"></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>N° Ticket:</span> <span id="e-id" style="font-weight: bold;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Plaque:</span> <span id="e-plaque" style="font-weight: bold; font-size: 18px;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Type:</span> <span id="e-type" style="font-weight: bold;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Client:</span> <span id="e-name" style="font-weight: bold;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Tél:</span> <span id="e-phone" style="font-weight: bold;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Date:</span> <span id="e-date" style="font-weight: bold;"></span>
        </div>
        <div style="border-top: 1px dashed #000; margin: 15px 0;"></div>
        <div style="display: flex; justify-content: center; margin: 15px 0;">
            <canvas id="e-qrcode"></canvas>
        </div>
        <div style="border-top: 1px dashed #000; margin: 15px 0;"></div>
        <p style="text-align: center; font-weight: bold; font-size: 12px;">Veuillez conserver ce ticket pour<br>votre sortie.</p>
        <p style="text-align: center; font-size: 12px;">Merci de votre visite !</p>
    </div>
</div>

<!-- ========================================================= -->
<!-- ZONE TICKET SORTIE (MASQUÉE)                               -->
<!-- ========================================================= -->
<div id="ticket-sortie-print" class="print-only" style="display: none;">
    <div style="font-family: 'Courier New', Courier, monospace; width: 300px; margin: 0 auto; padding: 10px; color: #000; font-size: 14px; background: white;">
        <h2 style="text-align: center; margin: 5px 0;">PARKING PRO</h2>
        <h3 style="text-align: center; margin: 5px 0;">REÇU DE PAIEMENT</h3>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Reçu N°:</span> <span id="s-id" style="font-weight: bold;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Plaque:</span> <span id="s-plaque" style="font-weight: bold; font-size: 18px;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Client:</span> <span id="s-name" style="font-weight: bold;"></span>
        </div>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Entrée:</span> <span id="s-date-e" style="font-weight: bold;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Sortie:</span> <span id="s-date-s" style="font-weight: bold;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Durée:</span> <span id="s-duree" style="font-weight: bold;"></span>
        </div>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>Paiement:</span> <span id="s-pay-mode" style="font-weight: bold; text-transform: uppercase;"></span>
        </div>
        <div style="font-size: 18px; font-weight: bold; text-align: center; margin: 15px 0; padding: 10px; border: 2px solid #000;">
            TOTAL : <span id="s-total"></span> FCFA
        </div>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <div style="display: flex; justify-content: center; margin: 15px 0;">
            <canvas id="s-qrcode"></canvas>
        </div>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <p style="text-align: center; font-weight: bold; font-size: 13px;">Merci de votre visite et<br>à très bientôt !</p>
    </div>
</div>

<style>
/* CSS CRUCIAL POUR L'IMPRESSION */
@media print {
    body * { visibility: hidden !important; }
    #ticket-entree-print, #ticket-entree-print *,
    #ticket-sortie-print, #ticket-sortie-print * { visibility: visible !important; }
    
    #ticket-entree-print, #ticket-sortie-print {
        position: absolute;
        left: 0; top: 0; width: 100%;
    }
}
</style>
        </div>
    </body>
</html>
