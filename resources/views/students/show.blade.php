@extends('layout')

@section('title', '学生詳細表示 - 学生成績管理システム')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    --info-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    --background-color: #f8f9fa;
    --shadow: 0 10px 30px rgba(0,0,0,0.1);
    --shadow-hover: 0 15px 40px rgba(0,0,0,0.15);
}

.show-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
}

.show-container::before {
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

.show-wrapper {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 50px;
    max-width: 900px;
    width: 100%;
    margin: 0 auto;
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

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    background: var(--info-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 10px;
}

.page-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    font-weight: 300;
    margin-bottom: 0;
}

.student-header {
    background: var(--primary-gradient);
    color: white;
    padding: 25px;
    border-radius: 20px;
    margin-bottom: 30px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.student-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.student-header .badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 1rem;
    padding: 8px 15px;
    border-radius: 20px;
    margin: 0 5px;
}

/* メッセージ表示 */
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

/* 詳細情報セクション */
.detail-section {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
    margin-bottom: 30px;
}

.info-card {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 20px;
    border: 1px solid #e9ecef;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.info-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 120px;
}

.info-value {
    font-weight: 600;
    color: #495057;
    text-align: right;
    flex: 1;
}

.grade-badge {
    background: var(--info-gradient);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 1rem;
    font-weight: 600;
    display: inline-block;
}

/* 写真表示セクション */
.photo-card {
    background: white;
    padding: 25px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.student-photo {
    width: 250px;
    height: 250px;
    border-radius: 20px;
    object-fit: cover;
    border: 5px solid #e9ecef;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 20px;
}

.student-photo:hover {
    transform: scale(1.05);
    border-color: #667eea;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
}

.no-photo {
    width: 250px;
    height: 250px;
    border-radius: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.2rem;
    font-weight: 600;
    border: 3px dashed #dee2e6;
    margin-bottom: 20px;
}

.no-photo i {
    font-size: 4rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.photo-caption {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 10px;
}

/* 住所・コメント表示 */
.address-section, .comment-section {
    background: white;
    padding: 25px;
    border-radius: 20px;
    margin-bottom: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #e9ecef;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    font-weight: 600;
    color: #495057;
}

.address-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    border-left: 5px solid #667eea;
    line-height: 1.6;
    color: #495057;
}

.comment-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    border-left: 5px solid #28a745;
    line-height: 1.6;
    color: #495057;
    font-style: italic;
}

.no-comment {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-comment i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.3;
}

/* アクションボタン */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
    flex-wrap: wrap;
}

.btn-action {
    padding: 15px 25px;
    border-radius: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.btn-edit {
    background: var(--warning-gradient);
    color: white;
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.3);
    color: white;
}

.btn-delete {
    background: var(--danger-gradient);
    color: white;
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
    color: white;
}

.btn-back {
    background: #6c757d;
    color: white;
}

.btn-back:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

/* タイムスタンプ表示 */
.timestamp-info {
    background: rgba(102, 126, 234, 0.1);
    padding: 15px 20px;
    border-radius: 15px;
    margin-top: 20px;
    text-align: center;
    font-size: 0.9rem;
    color: #6c757d;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .show-wrapper {
        margin: 20px;
        padding: 30px 25px;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .detail-section {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .student-photo, .no-photo {
        width: 200px;
        height: 200px;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-action {
        width: 200px;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .show-container {
        padding: 20px 10px;
    }
    
    .page-title {
        font-size: 1.6rem;
    }
    
    .show-wrapper {
        padding: 25px 20px;
    }
    
    .info-card, .photo-card {
        padding: 20px;
    }
    
    .student-photo, .no-photo {
        width: 180px;
        height: 180px;
    }
}

/* モーダル用スタイル */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    border-radius: 20px 20px 0 0;
    background: var(--primary-gradient);
    color: white;
}

.modal-body {
    padding: 30px;
    text-align: center;
}

.modal-photo {
    max-width: 100%;
    max-height: 70vh;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* 成績管理ボタンのスタイル */
.btn-grades-add {
    background: var(--success-gradient);
    color: white;
}

.btn-grades-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
    color: white;
}

.btn-grades-list {
    background: var(--info-gradient);
    color: white;
}

.btn-grades-list:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(23, 162, 184, 0.3);
    color: white;
}

/* レスポンシブ対応での成績ボタン */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .btn-action {
        width: 250px;
        justify-content: center;
    }
}
</style>

<div class="show-container">
    <div class="show-wrapper">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user-circle"></i>
                学生詳細表示
            </h1>
            <p class="page-subtitle">Student Detail View</p>
        </div>

        <!-- 学生基本情報ヘッダー -->
        <div class="student-header">
            <h2>
                <i class="fas fa-user"></i>
                {{ $student->name }}
            </h2>
            <div>
                <span class="badge">ID: {{ $student->id }}</span>
                <span class="badge">{{ $student->grade }}年生</span>
            </div>
        </div>

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

        <!-- 詳細情報セクション -->
        <div class="detail-section">
            <!-- 基本情報 -->
            <div class="info-card">
                <div class="info-title">
                    <i class="fas fa-info-circle"></i>
                    基本情報
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-id-badge"></i>
                        学生ID
                    </div>
                    <div class="info-value">{{ $student->id }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-graduation-cap"></i>
                        学年
                    </div>
                    <div class="info-value">
                        <span class="grade-badge">{{ $student->grade }}年</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-user"></i>
                        氏名
                    </div>
                    <div class="info-value">{{ $student->name }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-plus"></i>
                        登録日
                    </div>
                    <div class="info-value">
                        @if($student->created_at)
                            {{ \Carbon\Carbon::parse($student->created_at)->format('Y年m月d日') }}
                        @else
                            未設定
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-check"></i>
                        更新日
                    </div>
                    <div class="info-value">
                        @if($student->updated_at)
                            {{ \Carbon\Carbon::parse($student->updated_at)->format('Y年m月d日') }}
                        @else
                            未設定
                        @endif
                    </div>
                </div>
            </div>

            <!-- 顔写真 -->
            <div class="photo-card">
                <div class="info-title">
                    <i class="fas fa-camera"></i>
                    顔写真
                </div>
                
                @if($student->img_path)
                    <img src="{{ asset('storage/' . $student->img_path) }}" 
                         alt="{{ $student->name }}の写真" 
                         class="student-photo"
                         onclick="showPhotoModal('{{ asset('storage/' . $student->img_path) }}', '{{ $student->name }}')">
                    <div class="photo-caption">
                        クリックで拡大表示
                    </div>
                @else
                    <div class="no-photo">
                        <i class="fas fa-user"></i>
                        写真未登録
                    </div>
                    <div class="photo-caption">
                        写真が登録されていません
                    </div>
                @endif
            </div>
        </div>

        <!-- 住所情報 -->
        <div class="address-section">
            <div class="section-header">
                <i class="fas fa-map-marker-alt"></i>
                住所
            </div>
            <div class="address-content">
                {{ $student->address }}
            </div>
        </div>

        <!-- コメント -->
        <div class="comment-section">
            <div class="section-header">
                <i class="fas fa-comment"></i>
                コメント
            </div>
            
            @if($student->comment)
                <div class="comment-content">
                    {{ $student->comment }}
                </div>
            @else
                <div class="no-comment">
                    <i class="fas fa-comment-slash"></i>
                    <p>コメントが登録されていません</p>
                </div>
            @endif
        </div>

        <!-- タイムスタンプ情報 -->
        <div class="timestamp-info">
            <i class="fas fa-clock"></i>
            登録: 
            @if($student->created_at)
                {{ \Carbon\Carbon::parse($student->created_at)->format('Y年m月d日 H:i') }}
            @else
                未設定
            @endif
             | 
            最終更新: 
            @if($student->updated_at)
                {{ \Carbon\Carbon::parse($student->updated_at)->format('Y年m月d日 H:i') }}
            @else
                未設定
            @endif
        </div>

        <!-- アクションボタン -->
        <div class="action-buttons">
            <!-- 成績管理ボタン -->
            <a href="/grades/create?student_id={{ $student->id }}" class="btn-action btn-grades-add">
                <i class="fas fa-plus-circle"></i>
                成績追加
            </a>
            
            <a href="/grades/student/{{ $student->id }}" class="btn-action btn-grades-list">
                <i class="fas fa-chart-line"></i>
                成績一覧
            </a>
            
            <!-- 学生管理ボタン -->
            <a href="{{ route('students.edit', $student->id) }}" class="btn-action btn-edit">
                <i class="fas fa-edit"></i>
                学生を編集
            </a>
            
            <form method="POST" action="{{ route('students.destroy', $student->id) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete('{{ $student->name }}')">
                    <i class="fas fa-trash"></i>
                    学生を削除
                </button>
            </form>
            
            <a href="{{ route('students.index') }}" class="btn-action btn-back">
                <i class="fas fa-list"></i>
                一覧に戻る
            </a>
        </div>
    </div>
</div>
<!-- 写真拡大モーダル -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">
                    <i class="fas fa-expand"></i>
                    学生写真
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalPhoto" src="" alt="" class="modal-photo">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 削除確認ダイアログ
    window.confirmDelete = function(studentName) {
        return confirm(`「${studentName}」を完全に削除してよろしいですか？\n\nこの操作は取り消せません。関連するすべてのデータが削除されます。`);
    };

    // 写真拡大モーダル
    window.showPhotoModal = function(imgSrc, studentName) {
        const modal = new bootstrap.Modal(document.getElementById('photoModal'));
        const modalPhoto = document.getElementById('modalPhoto');
        const modalTitle = document.getElementById('photoModalLabel');
        
        modalPhoto.src = imgSrc;
        modalPhoto.alt = `${studentName}の写真`;
        modalTitle.innerHTML = `<i class="fas fa-expand"></i> ${studentName}の写真`;
        
        modal.show();
    };

    // キーボードナビゲーション
    document.addEventListener('keydown', function(e) {
        switch(e.key) {
            case 'e':
            case 'E':
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                    window.location.href = "{{ route('students.edit', $student->id) }}";
                }
                break;
                
            case 'Escape':
                window.location.href = "{{ route('students.index') }}";
                break;
                
            case 'Backspace':
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                    window.location.href = "{{ route('students.index') }}";
                }
                break;
        }
    });

    // 写真のエラーハンドリング
    const studentPhoto = document.querySelector('.student-photo');
    if (studentPhoto) {
        studentPhoto.addEventListener('error', function() {
            this.style.display = 'none';
            const noPhotoDiv = document.createElement('div');
            noPhotoDiv.className = 'no-photo';
            noPhotoDiv.innerHTML = '<i class="fas fa-user"></i>写真読み込みエラー';
            this.parentNode.insertBefore(noPhotoDiv, this);
        });
    }

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

    // 情報項目のアニメーション
    const infoItems = document.querySelectorAll('.info-item');
    infoItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 100);
    });

    // スムーズスクロール
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // 印刷機能（オプション）
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            // 印刷用のスタイルを適用
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>${document.title}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            .student-info { margin-bottom: 20px; }
                            .photo-section img { max-width: 200px; }
                        </style>
                    </head>
                    <body>
                        <h1>学生詳細情報</h1>
                        <div class="student-info">
                            <h2>{{ $student->name }}</h2>
                            <p>学生ID: {{ $student->id }}</p>
                            <p>学年: {{ $student->grade }}年</p>
                            <p>住所: {{ $student->address }}</p>
                            <p>コメント: {{ $student->comment ?? 'なし' }}</p>
                            <p>登録日: 
                                @if($student->created_at)
                                    {{ \Carbon\Carbon::parse($student->created_at)->format('Y年m月d日') }}
                                @else
                                    未設定
                                @endif
                            </p>
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    });
});
</script>
@endsection