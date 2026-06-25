<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; display: flex; min-height: 100vh; background: #fff; }

        /* Sisi Kiri (Background Gambar Unsplash + Logo) */
        .left-side {
            flex: 1;
            /* Menggunakan gambar dari Unsplash dengan overlay biru transparan agar logo tetap menonjol */
            background: linear-gradient(rgba(74, 133, 246, 0.5), rgba(46, 81, 209, 0.7)), 
                        url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
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
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        /* Ukuran logo disesuaikan agar proporsional */
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

        /* Sisi Kanan (Form Login) */
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
            margin-bottom: 25px;
        }
        .login-header h1 {
            font-size: 26px;
            font-weight: 800;
            color: #000;
            margin-bottom: 5px; /* Spasi sedikit dikurangi */
            letter-spacing: 0.5px;
        }
        .login-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #4A85F6; /* Warna biru senada dengan background kiri */
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Badge Keterangan Akses Super Admin */
        .admin-badge {
            background: #e0e7ff; 
            border: 1px solid #c7d2fe;
            color: #3730a3; 
            padding: 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 20px;
        }
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

        .error-msg {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 12px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
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
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: #110080;
        }

        /* Responsif untuk layar kecil (HP) */
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
                <h1>SELAMAT DATANG</h1>
                <h3>Superadmin</h3> 
            </div>

            <div class="admin-badge">
                <span>🔐</span> AKSES TERBATAS: KHUSUS SUPER ADMIN
            </div>

            @if($errors->any())
                <div class="error-msg">⚠ {{ $errors->first() }}</div>
            @endif

            <form action="{{ route('superadmin.login.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required autocomplete="off">
                </div>

                <button type="submit" class="btn-submit">Masuk Sekarang</button>
            </form>
        </div>
    </div>

</body>
</html>