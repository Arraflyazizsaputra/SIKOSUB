<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Menggunakan background agak gelap/berbeda untuk membedakan dengan halaman user */
        body { margin: 0; font-family: 'Outfit', sans-serif; background: linear-gradient(135deg, #1f2937 0%, #111827 100%); height: 100vh; display: flex; justify-content: center; align-items: center; }
        .auth-card { background: white; padding: 40px; border-radius: 15px; width: 100%; max-width: 350px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); text-align: center; }
        .auth-card h2 { color: #1f2937; margin-bottom: 5px; font-size: 28px; }
        .auth-card p { color: #666; font-size: 14px; margin-bottom: 25px; }
        .input-group { margin-bottom: 15px; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        .btn-submit { width: 100%; background: #ef4444; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; }
        .error { color: red; font-size: 12px; margin-bottom: 10px; text-align: left;}
    </style>
</head>
<body>
    <div class="auth-card">
        <h2>Admin Portal</h2>
        <p>Khusus Pemilik/Admin Kost</p>
        
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="E-mail Admin" required value="{{ old('email') }}">
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password Admin" required>
            </div>
            
            <button type="submit" class="btn-submit">Login Admin</button>
        </form>
    </div>
</body>
</html>