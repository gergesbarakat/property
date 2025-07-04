<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Export</title>
    <style>
        body, html { margin: 0; padding: 0; }
        img { width: 100%; height: auto; }
    </style>
</head>
<body>
    {{-- ✅ FIX: The <img> tag now embeds the image directly from base64 data. --}}
    {{-- The controller will pass the raw base64 string in the $imageData variable. --}}
    <img src="data:image/png;base64,{{ $imageData }}">
</body>
</html>
