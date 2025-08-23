<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }
        .error-container {
            min-height: 95vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #293145;
        }
        .error-message {
            font-size: 1.5rem;
            color: #6c757d;
        }
        .btn-home {
            margin-top: 2rem;
            font-weight: 600;
        }
        .logo {
            max-height: 100px;
        }
    </style>
</head>
<body>

    <div class="error-container">
        <img src="/assets/img/LUNA LOGO.png" alt="Logo" class="logo">
        <div class="error-code">404</div>
        <div class="error-message">Oops! The page you're looking for doesn't exist.</div>
        <a href="/" class="btn btn-primary btn-home">Go Back Home</a>
    </div>

</body>
</html>
