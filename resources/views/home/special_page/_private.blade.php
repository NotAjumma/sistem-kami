<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;600&family=Imperial+Script&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Josefin Sans', sans-serif; background: #f9f7f4; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card {
            background: #fff; padding: 56px 48px; text-align: center;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08); max-width: 420px; width: 90%;
        }
        .lock-icon { font-size: 40px; margin-bottom: 20px; color: #ccc; }
        .brand { font-family: 'Imperial Script', cursive; font-size: 32px; color: #222; margin-bottom: 6px; }
        h2 { font-size: 13px; letter-spacing: 3px; text-transform: uppercase; color: #999; font-weight: 400; margin-bottom: 28px; }
        p { font-size: 13px; color: #aaa; line-height: 1.8; margin-bottom: 28px; }
        .btn {
            display: inline-block; font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;
            color: #fff; background: #222; padding: 12px 28px; text-decoration: none; transition: background 0.2s;
        }
        .btn:hover { background: #14b9d5; }
    </style>
</head>
<body>
    <div class="card">
        <div class="lock-icon">🔒</div>
        <div class="brand">{{ $organizer->name }}</div>
        <h2>Private Page</h2>
        <p>This page is currently set to private and is only accessible to its owner.</p>
        <a href="{{ route('organizer.business.dashboard') }}" class="btn">Organizer Login</a>
    </div>
</body>
</html>
