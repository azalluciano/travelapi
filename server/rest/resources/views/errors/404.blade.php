<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page non trouvée</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-card {
            max-width: 500px;
            padding: 2.5rem;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            background: linear-gradient(45deg, #FF416C, #FF4B2B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            line-height: 1;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .error-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .back-button {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(52, 152, 219, 0.4);
        }

        .back-button:active {
            transform: translateY(1px);
        }

        .error-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            display: block;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#FF416C"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Page non trouvée</h2>
            <p class="error-message">La page que vous recherchez n'existe pas ou n'est plus disponible. Veuillez
                vérifier l'URL ou retourner à la page de connexion.</p>
            <a href="/admin/login" class="back-button">Retour</a>
        </div>
    </div>
</body>

</html>