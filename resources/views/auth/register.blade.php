@extends('layout')

@section('title', '管理ユーザー新規登録')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --background-color: #f8f9fa;
    --shadow: 0 0 20px rgba(0,0,0,0.1);
}

.register-container {
    min-height: 100vh;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
}

.register-card {
    background: white;
    border-radius: 15px;
    box-shadow: var(--shadow);
    padding: 40px;
    width: 100%;
    max-width: 500px;
    position: relative;
    overflow: hidden;
}

.register-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.register-header {
    text-align: center;
    margin-bottom: 40px;
}

.register-header h3 {
    color: #333;
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.register-header p {
    color: #666;
    font-size: 1rem;
    margin: 0;
}

.form-floating {
    position: relative;
    margin-bottom: 25px;
}

.form-floating input {
    width: 100%;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
    background: transparent;
    transition: all 0.3s ease;
}

.form-floating input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
}

.form-floating label {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
    transition: all 0.3s ease;
    pointer-events: none;
    background: white;
    padding: 0 5px;
}

.form-floating input:focus + label,
.form-floating input:not(:placeholder-shown) + label {
    top: 0;
    font-size: 14px;
    color: #667eea;
    font-weight: 600;
}

.register-btn {
    width: 100%;
    padding: 15px;
    background: var(--primary-gradient);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.register-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.register-btn:active {
    transform: translateY(0);
}

.back-link {
    display: inline-block;
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 10px 20px;
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.back-link:hover {
    color: #667eea;
    border-color: #667eea;
    text-decoration: none;
    transform: translateY(-1px);
}

.login-link {
    display: inline-block;
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 10px 20px;
    border-radius: 8px;
    background: rgba(102, 126, 234, 0.1);
    margin-left: 10px;
}

.login-link:hover {
    background: var(--primary-gradient);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #dc3545;
    font-size: 14px;
}

.success-message {
    background: #d1edff;
    color: #0c5460;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #17a2b8;
    font-size: 14px;
}

.field-error {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: block;
}

.form-floating input.is-invalid {
    border-color: #dc3545;
}

.form-floating input.is-valid {
    border-color: #28a745;
}

.password-strength {
    margin-top: 5px;
    font-size: 12px;
}

.strength-weak { color: #dc3545; }
.strength-medium { color: #ffc107; }
.strength-strong { color: #28a745; }

.form-links {
    text-align: center;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e9ecef;
}

@media (max-width: 576px) {
    .register-card {
        margin: 10px;
        padding: 30px 20px;
    }
    
    .register-header h3 {
        font-size: 1.5rem;
    }
    
    .form-links a {
        display: block;
        margin-bottom: 10px;
    }
    
    .login-link {
        margin-left: 0;
    }
}
</style>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h3>管理ユーザー新規登録</h3>
            <p>管理システムへのアクセス用アカウントを作成</p>
        </div>

        <!-- エラーメッセージ表示 -->
        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- 成功メッセージ表示 -->
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" id="registerForm" novalidate>
            @csrf
            
            <!-- ユーザー名入力 -->
            <div class="form-floating">
                <input 
                    type="text" 
                    id="user_name" 
                    name="user_name" 
                    placeholder="ユーザー名"
                    value="{{ old('user_name') }}"
                    class="@error('user_name') is-invalid @enderror"
                    required
                >
                <label for="user_name">ユーザー名</label>
                @error('user_name')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- メールアドレス入力 -->
            <div class="form-floating">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="メールアドレス"
                    value="{{ old('email') }}"
                    class="@error('email') is-invalid @enderror"
                    required
                >
                <label for="email">メールアドレス</label>
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- パスワード入力 -->
            <div class="form-floating">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="パスワード"
                    class="@error('password') is-invalid @enderror"
                    required
                >
                <label for="password">パスワード（8文字以上）</label>
                <div id="passwordStrength" class="password-strength"></div>
                @error('password')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- パスワード確認入力 -->
            <div class="form-floating">
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="パスワード確認"
                    class="@error('password_confirmation') is-invalid @enderror"
                    required
                >
                <label for="password_confirmation">パスワード確認</label>
                <div id="passwordMatch" class="password-strength"></div>
                @error('password_confirmation')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 登録ボタン -->
            <button type="submit" class="register-btn" id="submitBtn">
                <span>📝 アカウント登録</span>
            </button>
        </form>

        <!-- フォームリンク -->
        <div class="form-links">
            <a href="{{ route('menu') }}" class="back-link">← メニューに戻る</a>
            <a href="{{ route('login') }}" class="login-link">既にアカウントをお持ちの方はログイン</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    
    // パスワード強度チェック
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strengthDiv = document.getElementById('passwordStrength');
        
        if (password.length === 0) {
            strengthDiv.textContent = '';
            return;
        }
        
        let strength = 0;
        let message = '';
        
        // 長さチェック
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        
        // 文字種チェック
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        if (strength <= 2) {
            strengthDiv.className = 'password-strength strength-weak';
            message = '⚠️ パスワードが弱いです';
        } else if (strength <= 4) {
            strengthDiv.className = 'password-strength strength-medium';
            message = '⚡ パスワードは普通です';
        } else {
            strengthDiv.className = 'password-strength strength-strong';
            message = '✅ パスワードは強力です';
        }
        
        strengthDiv.textContent = message;
    });
    
    // パスワード確認チェック
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const matchDiv = document.getElementById('passwordMatch');
        
        if (confirmPassword.length === 0) {
            matchDiv.textContent = '';
            return;
        }
        
        if (password === confirmPassword) {
            matchDiv.className = 'password-strength strength-strong';
            matchDiv.textContent = '✅ パスワードが一致しています';
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
        } else {
            matchDiv.className = 'password-strength strength-weak';
            matchDiv.textContent = '⚠️ パスワードが一致しません';
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordInput.classList.add('is-invalid');
        }
    }
    
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    passwordInput.addEventListener('input', checkPasswordMatch);
    
    // フォーム送信時のバリデーション
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('パスワードが一致しません。確認してください。');
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('パスワードは8文字以上で入力してください。');
            return;
        }
        
        // ボタンを一時的に無効化（二重送信防止）
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>登録中...</span>';
        
        // 3秒後に再有効化（エラーの場合）
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>📝 アカウント登録</span>';
        }, 3000);
    });
    
    // 入力フィールドのリアルタイムバリデーション
    const inputs = form.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
});
</script>
@endsection