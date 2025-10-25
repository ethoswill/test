<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { color: #333; }
        .links { margin-top: 20px; }
        .links a { margin-right: 15px; color: #007cba; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Laravel</h1>
        <p>Your Laravel application is running successfully!</p>
        <div class="links">
            <a href="/api/user">API User</a>
        </div>
    </div>
</body>
</html>





