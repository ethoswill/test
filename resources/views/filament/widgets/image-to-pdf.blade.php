<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $productName }}</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <img src="data:image/{{ pathinfo($imagePath, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($imagePath)) }}" alt="{{ $productName }}">
</body>
</html>

