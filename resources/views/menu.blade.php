@extends('layout')

@section('title', 'メニュー画面 - 学生成績管理システム')

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

.menu-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    padding: 40px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.menu-container::before {
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

.menu-wrapper {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 50px;
    max-width: 600px;
    width: 100%;
    text-align: center;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255,255,255,0.3);
    position: relative;
    z-index: 2;
}

.system-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 10px;
    text-shadow: none;
}

.system-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    font-weight: 300;
    margin-bottom: 40px;
}

.current-year-display {
    background: var(--primary-gradient);
    color: white;
    padding: 15px 25px;
    border-radius: 25px;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 30px;
    display: inline-block;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.user-info {
    background: rgba(102, 126, 234, 0.1);
    padding: 15px;
    border-radius: 15px;
    margin-bottom: 30px;
    font-size: 0.9rem;
    color: #495057;
    border: 1px solid rgba(102, 126, 234, 0.2);
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

.error-message {
    background: var(--danger-gradient);
    color: white;
    padding: 15px 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    animation: slideIn 0.5s ease;
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* 学年更新セクション */
.year-update-section {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 20px;
    margin-bottom: 40px;
    border: 1px solid #e9ecef;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.year-update-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 15px;
}

.year-input-group {
    display: flex;
    align-items: center;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.year-select {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 10px 15px;
    font-weight: 500;
    min-width: 140px;
    text-align: center;
    transition: all 0.3s ease;
}

.year-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

.year-update-btn {
    background: var(--success-gradient);
    border: none;
    border-radius: 10px;
    color: white;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.year-update-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
}

/* メニューボタン群 */
.menu-buttons {
    display: grid;
    gap: 20px;
    margin-bottom: 40px;
}

.menu-btn {
    background: white;
    border: 2px solid #667eea;
    color: #667eea;
    padding: 20px 25px;
    border-radius: 15px;
    font-size: 1.2rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    min-height: 70px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.menu-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: var(--primary-gradient);
    transition: left 0.3s ease;
    z-index: -1;
}

.menu-btn:hover::before {
    left: 0;
}

.menu-btn:hover {
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    border-color: transparent;
}

.menu-btn i {
    font-size: 1.5rem;
    transition: transform 0.3s ease;
}

.menu-btn:hover i {
    transform: scale(1.1);
}

/* 下部アクション */
.bottom-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
    flex-wrap: wrap;
}

.btn-back {
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
}

.btn-back:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-logout {
    background: var(--danger-gradient);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    color: white;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .menu-wrapper {
        margin: 20px;
        padding: 30px 25px;
    }
    
    .system-title {
        font-size: 2rem;
    }
    
    .menu-btn {
        padding: 15px 20px;
        font-size: 1.1rem;
        min-height: 60px;
    }
    
    .year-input-group {
        flex-direction: column;
        gap: 15px;
    }
    
    .year-select, .year-update-btn {
        width: 100%;
        max-width: 200px;
    }
    
    .bottom-actions {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .btn-back, .btn-logout {
        width: 200px;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .menu-container {
        padding: 20px 10px;
    }
    
    .system-title {
        font-size: 1.8rem;
    }
    
    .current-year-display {
        font-size: 1rem;
        padding: 12px 20px;
    }
}

/* アニメーション効果 */
.menu-wrapper {
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

.menu-btn {
    animation: fadeInLeft 0.6s ease forwards;
    opacity: 0;
}

.menu-btn:nth-child(1) { animation-delay: 0.1s; }
.menu-btn:nth-child(2) { animation-delay: 0.2s; }

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<div class="menu-container">
    <div class="menu-wrapper">
        <!-- システムタイトル -->
        <h1 class="system-title">
            <i class="fas fa-graduation-cap"></i>
            学生成績管理システム
        </h1>
        <p class="system-subtitle">Student Grade Management System</p>

        <!-- 現在の年度表示 -->
        <div class="current-year-display">
            <i class="fas fa-calendar-alt"></i>
            現在の対象年度: {{ $currentYear ?? date('Y') }}年
        </div>

        <!-- ユーザー情報 -->
        @auth
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            ログイン中: <strong>{{ Auth::user()->name ?? Auth::user()->email }}</strong>
        </div>
        @endauth

        <!-- メッセージ表示 -->
        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- 学年更新セクション（仕様書の学年更新ボタン） -->
        <div class="year-update-section">
            <div class="year-update-title">
                <i class="fas fa-sync-alt"></i>
                学年更新
            </div>
            <form action="{{ route('menu.updateYear') }}" method="POST" id="yearUpdateForm">
                @csrf
                <div class="year-input-group">
                    <label for="year" class="form-label mb-0">対象年度:</label>
                    <select name="year" class="year-select" id="yearSelect">
                        @for($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
                            <option value="{{ $year }}" 
                                {{ ($currentYear ?? date('Y')) == $year ? 'selected' : '' }}>
                                {{ $year }}年
                            </option>
                        @endfor
                    </select>
                    <button type="submit" class="year-update-btn">
                        <i class="fas fa-save"></i>
                        更新
                    </button>
                </div>
            </form>
        </div>

        <!-- メインメニューボタン（仕様書準拠） -->
        <div class="menu-buttons">
            <!-- 学生登録ボタン -->
            <a href="{{ route('students.create') }}" class="menu-btn">
                <i class="fas fa-user-plus"></i>
                学生登録
            </a>

            <!-- 学生表示ボタン -->
            <a href="{{ route('students.index') }}" class="menu-btn">
                <i class="fas fa-users"></i>
                学生表示
            </a>
        </div>

        <!-- 下部アクション -->
        <div class="bottom-actions">
            <!-- 戻るボタン（仕様書準拠） -->
            <a href="{{ route('login') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                戻る
            </a>

            <!-- ログアウトボタン -->
            @auth
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout" onclick="return confirm('ログアウトしますか？')">
                    <i class="fas fa-sign-out-alt"></i>
                    ログアウト
                </button>
            </form>
            @endauth
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 学年更新フォームの処理
    const yearForm = document.getElementById('yearUpdateForm');
    const yearSelect = document.getElementById('yearSelect');
    
    yearForm.addEventListener('submit', function(e) {
        const selectedYear = yearSelect.value;
        const currentYear = new Date().getFullYear();
        
        // 年度の妥当性チェック
        if (Math.abs(selectedYear - currentYear) > 5) {
            e.preventDefault();
            alert('選択された年度が現在から大きく離れています。確認してください。');
            return;
        }
        
        // 確認ダイアログ
        if (!confirm(`年度を${selectedYear}年に変更しますか？`)) {
            e.preventDefault();
            return;
        }

        // ローディング表示
        const submitBtn = yearForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 更新中...';
        submitBtn.disabled = true;

        // エラー時のリセット用タイマー
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
    
    // メニューボタンのホバーエフェクト強化
    const menuButtons = document.querySelectorAll('.menu-btn');
    menuButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // 成功・エラーメッセージの自動フェードアウト
    const messages = document.querySelectorAll('.success-message, .error-message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });

    // キーボードナビゲーション
    document.addEventListener('keydown', function(e) {
        switch(e.key) {
            case '1':
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                    window.location.href = "{{ route('students.create') }}";
                }
                break;
            case '2':
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                    window.location.href = "{{ route('students.index') }}";
                }
                break;
            case 'Escape':
                window.location.href = "{{ route('login') }}";
                break;
        }
    });

    // フォーカス管理
    const focusableElements = document.querySelectorAll('button, a, input, select');
    let currentFocus = 0;

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            currentFocus = e.shiftKey ? 
                (currentFocus - 1 + focusableElements.length) % focusableElements.length :
                (currentFocus + 1) % focusableElements.length;
            focusableElements[currentFocus].focus();
        }
    });
});
</script>
@endsection