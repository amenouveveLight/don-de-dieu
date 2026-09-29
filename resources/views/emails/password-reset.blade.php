<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Réinitialisation de votre mot de passe</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f7f8f7;
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
        }

        .page {
            width: 100%;
            padding: 45px 20px;
        }

        .email {
            max-width: 520px;
            margin: 0 auto;
        }

        .brand {
            text-align: center;
            margin-bottom: 22px;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 700;
            color: #15803d;
        }

        .card {
            background: #ffffff;
            padding: 36px;
        }

        .title {
            margin: 0 0 12px;
            font-size: 22px;
            line-height: 30px;
            font-weight: 700;
            color: #111827;
        }

        .text {
            margin: 0 0 16px;
            font-size: 14px;
            line-height: 22px;
            color: #4b5563;
        }

        .button-wrap {
            margin: 28px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 22px;
            background: #16a34a;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        .notice {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }

        .notice p {
            margin: 0;
            font-size: 12px;
            line-height: 19px;
            color: #6b7280;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .footer p {
            margin: 0;
            font-size: 11px;
            line-height: 18px;
            color: #9ca3af;
        }

        @media only screen and (max-width: 600px) {
            .page {
                padding: 25px 12px;
            }

            .card {
                padding: 26px 20px;
            }

            .title {
                font-size: 20px;
            }

            .button {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="email">

        <div class="brand">
            <div class="brand-name">
                <h2>{{ config('app.name') }}</h2>
            </div>
        </div>

        <div class="card">

            <h1 class="title">
                Réinitialisation de votre mot de passe
            </h1>

            <p class="text">
                Bonjour,
            </p>

            <p class="text">
                Nous avons reçu une demande de réinitialisation
                du mot de passe associé à votre compte.
            </p>

            <p class="text">
                Cliquez sur le bouton ci-dessous pour choisir
                un nouveau mot de passe.
            </p>

            <div class="button-wrap">
                <a
                    href="{{ $url }}"
                    class="button"
                    target="_blank"
                >
                    Réinitialiser mon mot de passe
                </a>
                 <p>
                    Ce lien est valable pendant <strong>60 minutes</strong>.
                    Si vous n'êtes pas à l'origine de cette demande,
                    vous pouvez ignorer cet email.
                </p>
            </div>

           
        </div>

        <div class="footer">
         

            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}.
                Tous droits réservés.
            </p>
        </div>

    </div>

</div>

</body>
</html>
