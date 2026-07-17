<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ibrahim Aqiqah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f1724 0%, #1a1a2e 30%, #16213e 70%, #0f3460 100%);
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Particles */
        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(46,125,50,0.15) 0%, transparent 70%);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0,188,212,0.1) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            animation: float 6s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(46,125,50,0.4); }
            70% { box-shadow: 0 0 0 20px rgba(46,125,50,0); }
            100% { box-shadow: 0 0 0 0 rgba(46,125,50,0); }
        }

        @keyframes rotate-in {
            from { transform: rotate(-180deg) scale(0); opacity: 0; }
            to { transform: rotate(0) scale(1); opacity: 1; }
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(76,175,80,0.3);
            border-radius: 50%;
            animation: float-particle 15s infinite;
        }

        .particle:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; animation-duration: 12s; }
        .particle:nth-child(2) { left: 20%; top: 60%; animation-delay: 2s; animation-duration: 18s; width: 6px; height: 6px; }
        .particle:nth-child(3) { left: 40%; top: 10%; animation-delay: 4s; animation-duration: 14s; }
        .particle:nth-child(4) { left: 60%; top: 70%; animation-delay: 1s; animation-duration: 16s; width: 3px; height: 3px; }
        .particle:nth-child(5) { left: 80%; top: 30%; animation-delay: 3s; animation-duration: 13s; }
        .particle:nth-child(6) { left: 30%; top: 80%; animation-delay: 5s; animation-duration: 19s; width: 5px; height: 5px; }
        .particle:nth-child(7) { left: 70%; top: 50%; animation-delay: 2.5s; animation-duration: 11s; }
        .particle:nth-child(8) { left: 90%; top: 80%; animation-delay: 4.5s; animation-duration: 17s; }

        @keyframes float-particle {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.3; }
            25% { transform: translateY(-30px) scale(1.5); opacity: 0.6; }
            50% { transform: translateY(0) scale(1); opacity: 0.3; }
            75% { transform: translateY(30px) scale(0.5); opacity: 0.8; }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2E7D32, #4CAF50, #8BC34A, #4CAF50, #2E7D32);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        .login-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-logo {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #2E7D32, #4CAF50);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
            box-shadow: 0 8px 30px rgba(46,125,50,0.3);
            animation: rotate-in 0.8s cubic-bezier(0.68, -0.55, 0.27, 1.55) both;
        }

        .login-header h3 {
            color: #fff;
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .input-group {
            position: relative;
        }

        .input-group .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            font-size: 1rem;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .input-group .form-control {
            width: 100%;
            padding: 14px 16px 14px 46px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            color: #fff;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-group .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .input-group .form-control:focus {
            border-color: #4CAF50;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(76,175,80,0.1);
        }

        .input-group .form-control:focus ~ .input-icon {
            color: #4CAF50;
        }

        .input-group .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            font-size: 1rem;
            z-index: 10;
            transition: all 0.3s ease;
            padding: 0;
        }

        .input-group .toggle-password:hover {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #2E7D32, #4CAF50);
            border: none;
            border-radius: 14px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(46,125,50,0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login .btn-shimmer {
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            animation: shimmer 2s infinite;
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        .btn-login.loading .btn-loader {
            display: flex;
        }

        .btn-loader {
            display: none;
            align-items: center;
            gap: 6px;
        }

        .btn-loader span {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: bounce 0.6s infinite alternate;
        }

        .btn-loader span:nth-child(2) { animation-delay: 0.2s; }
        .btn-loader span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            from { transform: translateY(0); opacity: 0.5; }
            to { transform: translateY(-6px); opacity: 1; }
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            animation: slideUp 0.4s ease-out;
        }

        .alert-danger {
            background: rgba(239,68,68,0.15);
            color: #fca5a5;
            border: 1px solid rgba(239,68,68,0.2);
        }

        .alert-success {
            background: rgba(34,197,94,0.15);
            color: #86efac;
            border: 1px solid rgba(34,197,94,0.2);
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.75rem;
        }

        .login-footer i {
            color: #4CAF50;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 12px;
            }
            .login-card {
                padding: 28px 24px;
            }
            .login-header h3 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-mosque"></i>
                </div>
                <h3>Selamat Datang</h3>
                <p>Masuk ke Sistem Penjadwalan Ibrahim Aqiqah</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="/auth/login" method="POST" id="loginForm">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user me-1"></i> Username
                    </label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Masukkan username" required autocomplete="username" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock me-1"></i> Password
                    </label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="btn-shimmer"></span>
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </span>
                    <span class="btn-loader">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </form>

            <div class="login-footer">
                <i class="fas fa-mosque me-1"></i> Ibrahim Aqiqah - Sistem Penjadwalan &copy; <?= date('Y') ?>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
        });

        // Keyboard shortcut: Enter to submit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginBtn').click();
            }
        });
    </script>
</body>
</html>