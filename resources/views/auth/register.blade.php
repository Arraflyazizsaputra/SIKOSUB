<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; display: flex; min-height: 100vh; background: #fff; }

        /* Sisi Kiri (Background Gambar Unsplash + Logo) */
        .left-side {
            flex: 1;
            /* Overlay biru transparan + Gambar dari Unsplash */
            background: linear-gradient(rgba(74, 133, 246, 0.5), rgba(46, 81, 209, 0.7)), 
                        url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-card {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-icon { width: 70px; height: auto; object-fit: contain; } 
        
        .logo-text h2 {
            color: #0d47a1;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }
        .logo-text p {
            color: #666;
            font-size: 11px;
            font-weight: 500;
        }

        /* Sisi Kanan (Form Registrasi) */
        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            font-size: 26px;
            font-weight: 800;
            color: #000;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        .login-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }

        .form-group { margin-bottom: 15px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            background: #e5e7eb;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: 0.2s;
        }
        .form-group input:focus {
            border-color: #1a00b8;
            background: #fff;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #1a00b8;
            color: white;
            border: none;
            border-radius: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 15px;
        }
        .btn-submit:hover { background: #110080; }

        .auth-footer { margin-top: 20px; text-align: center; font-size: 14px; color: #64748b; }
        .auth-footer a { color: #1a00b8; font-weight: 600; text-decoration: none; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .left-side { flex: none; padding: 40px 20px; min-height: 250px; }
            .right-side { padding: 40px 20px; align-items: flex-start; }
        }
    </style>
</head>
<body>

    <div class="left-side">
        <div class="logo-card">
            <img src="{{ asset('images/sikosub.png') }}" class="logo-icon" alt="Logo SIKOSUB">
            <div class="logo-text">
                <h2>SIKOSUB</h2>
                <p>Sistem Informasi Kost Subang</p>
            </div>
        </div>
    </div>

    <div class="right-side">
        <div class="login-container">
            <div class="login-header">
                <h1>BUAT AKUN BARU</h1>
                <h3>Buat Akun Sebagai Pencari Kost</h3>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label>Email aktif</label>
                    <input type="email" name="email" required value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label>No Hp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label>Buat Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn-submit">Daftar Sekarang</button>
            </form>

            <div class="auth-footer">
                Sudah Punya akun? <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </div>

</body>
</html>