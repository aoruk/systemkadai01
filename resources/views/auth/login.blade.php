@extends('layout')

@section('title', 'ログイン - 学生成績管理システム')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    --background-color: #f8f9fa;
    --shadow: 0 10px 30px rgba(0,0,0,0.1);
    --shadow-hover: 0 15px 40px rgba(0,0,0,0.15);
}

.login-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    padding: 40px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.login-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, -30px) rotate(120deg); }
    66% { transform: translate(-20px, 20px) rotate(240deg); }
}

.login-wrapper {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 50px;
    max-width: 500px;
    width: 100%;
    text-align: center;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.3);
    position: relative;
    z-index: 2;
    animation: fadeInUp 0.8s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-header {
    margin-bottom: 40px;
}

.system-title {
    font-size: 2.2rem;
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 10px;
    text-shadow: none;
}

.login-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    font-weight: 300;
    margin-bottom: 20px;
}

.login-title {
    color: #495057;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0;
}

/* エラーメッセージ */
.error-message {
    background: var(--danger-gradient);
    color: white;
    padding: 15px 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    animation: slideIn 0.5s ease;
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    text-align: left;
}

.success-message {
    background: var(--success-gradient);
    color: white;
    padding: 15px 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    animation: slideIn 0.5s ease;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* フォームスタイル */
.login-form {
    text-align: left;
}

.form-group {
    margin-bottom: 25px;
    position: relative;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: #495057;
    font-weight: 600;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
    background: white;
    transform: translateY(-1px);
}

.form-control::placeholder {
    color: #adb5bd;
    font-weight: 400;
}

/* 入力フィールドアイコン */
.input-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1.1rem;
    pointer-events: none;
    transition: color 0.3s ease;
}

.form-control:focus + .input-icon {
    color: #667eea;
}

/* ログインボタン */
.btn-login {
    width: 100%;
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 15px;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    margin-bottom: 30px;
}

.btn-login::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transition: left 0.3s ease;
}

.btn-login:hover::before {
    left: 0;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-login span {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

/* アクションボタン群 */
.login-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-secondary {
    background: #6c757d;
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    flex: 1;
    justify-content: center;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-success {
    background: var(--success-gradient);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    flex: 1;
    justify-content: center;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    color: white;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .login-wrapper {
        margin: 20px;
        padding: 30px 25px;
    }
    
    .system-title {
        font-size: 1.8rem;
    }
    
    .login-actions {
        flex-direction: column;
    }
    
    .btn-secondary, .btn-success {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .login-container {
        padding: 20px 10px;
    }
    
    .system-title {
        font-size: 1.6rem;
    }
    
    .login-wrapper {
        padding: 25px 20px;
    }
}

/* バリデーションエラー表示 */
.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
    font-weight: 500;
}

.form-control.is-invalid {
    border-color: #dc3545;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* ローディング状態 */
.btn-login:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-login:disabled:hover {
    transform: none;
    box-shadow: none;
}

.spinner {
    display: none;
}

.loading .spinner {
    display: inline-block;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* フォーカス表示改善 */
.form-control:focus,
.btn-login:focus,
.btn-secondary:focus,
.btn-success:focus {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}
</style>

<div class="login-container">
    <div class="login-wrapper">
        <!-- システムヘッダー -->
        <div class="login-header">
            <h1 class="system-title">
                <i class="fas fa-graduation-cap"></i>
                学生成績管理システム
            </h1>
            <p class="login-subtitle">Student Grade Management System</p>
            <h2 class="login-title">
                <i class="fas fa-sign-in-alt"></i>
                管理ユーザーログイン
            </h2>
        </div>

        <!-- エラーメッセージ表示 -->
        @if ($errors->any())
            <div class="error-message">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>ログインエラー</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ログインフォーム -->
        <form action="{{ route('authenticate') }}" method="POST" class="login-form" id="loginForm">
            @csrf
            
            <!-- メールアドレス -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i>
                    メールアドレス
                </label>
                <div style="position: relative;">
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="example@email.com"
                           required 
                           autocomplete="email"
                           autofocus>
                    <i class="fas fa-at input-icon"></i>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- パスワード -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i>
                    パスワード
                </label>
                <div style="position: relative;">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="パスワードを入力"
                           required 
                           autocomplete="current-password">
                    <i class="fas fa-key input-icon"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- ログインボタン -->
            <button type="submit" class="btn-login" id="loginBtn">
                <span>
                    <i class="fas fa-sign-in-alt"></i>
                    <span class="btn-text">ログイン</span>
                    <i class="fas fa-spinner spinner"></i>
                </span>
            </button>
        </form>
        
        <!-- アクションボタン -->
        <div class="login-actions">
            <a href="{{ route('menu') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                戻る
            </a>
            <a href="{{ route('register') }}" class="btn-success">
                <i class="fas fa-user-plus"></i>
                新規登録
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    // フォーム送信時のローディング表示
    loginForm.addEventListener('submit', function(e) {
        // 基本バリデーション
        if (!emailInput.value.trim() || !passwordInput.value.trim()) {
            e.preventDefault();
            
            if (!emailInput.value.trim()) {
                emailInput.classList.add('is-invalid');
                emailInput.focus();
            }
            if (!passwordInput.value.trim()) {
                passwordInput.classList.add('is-invalid');
            }
            
            // エラーメッセージ表示
            showErrorMessage('メールアドレスとパスワードを入力してください。');
            return;
        }

        // ローディング状態に変更
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;
        
        // ボタンテキスト変更
        const btnText = loginBtn.querySelector('.btn-text');
        btnText.textContent = 'ログイン中...';
        
        // 10秒後にリセット（エラー時のフォールバック）
        setTimeout(() => {
            resetLoginButton();
        }, 10000);
    });

    // 入力フィールドのバリデーションリアルタイム
    emailInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        clearErrorMessage();
    });

    passwordInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        clearErrorMessage();
    });

    // Enterキーでのフォーム送信
    [emailInput, passwordInput].forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                loginForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            }
        });
    });

    // キーボードナビゲーション
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.location.href = "{{ route('menu') }}";
        }
    });

    // ローディングボタンリセット関数
    function resetLoginButton() {
        loginBtn.classList.remove('loading');
        loginBtn.disabled = false;
        const btnText = loginBtn.querySelector('.btn-text');
        btnText.textContent = 'ログイン';
    }

    // エラーメッセージ表示関数
    function showErrorMessage(message) {
        // 既存のエラーメッセージを削除
        clearErrorMessage();
        
        // 新しいエラーメッセージを作成
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        
        // フォームの前に挿入
        loginForm.parentNode.insertBefore(errorDiv, loginForm);
        
        // 5秒後に自動で削除
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // エラーメッセージクリア関数
    function clearErrorMessage() {
        const existingError = document.querySelector('.error-message');
        if (existingError && !existingError.hasAttribute('data-server-error')) {
            existingError.remove();
        }
    }

    // サーバーエラーメッセージに属性追加（自動削除を防ぐため）
    const serverErrors = document.querySelectorAll('.error-message');
    serverErrors.forEach(error => {
        error.setAttribute('data-server-error', 'true');
    });

    // 成功・エラーメッセージの自動フェードアウト
    const messages = document.querySelectorAll('.success-message, .error-message[data-server-error]');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });

    // フォーカス管理の改善
    const focusableElements = loginForm.querySelectorAll('input, button');
    let currentFocus = 0;

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && !e.shiftKey && !e.ctrlKey && !e.metaKey) {
            // 標準のTab動作を使用
            return;
        }
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentFocus = (currentFocus + 1) % focusableElements.length;
            focusableElements[currentFocus].focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentFocus = (currentFocus - 1 + focusableElements.length) % focusableElements.length;
            focusableElements[currentFocus].focus();
        }
    });

    // パスワード表示切り替え機能（オプション）
    const passwordToggle = document.createElement('button');
    passwordToggle.type = 'button';
    passwordToggle.className = 'password-toggle';
    passwordToggle.innerHTML = '<i class="fas fa-eye"></i>';
    passwordToggle.style.cssText = `
        position: absolute;
        right: 45px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 5px;
        z-index: 10;
    `;
    
    const passwordGroup = passwordInput.closest('.form-group');
    passwordGroup.style.position = 'relative';
    passwordGroup.appendChild(passwordToggle);
    
    passwordToggle.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    // 初期フォーカス設定
    if (emailInput.value === '') {
        emailInput.focus();
    } else {
        passwordInput.focus();
    }
});
</script>
@endsection