@extends('layout')

@section('title', '学生表示画面 - 学生成績管理システム')

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

.students-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
}

.students-container::before {
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

.students-wrapper {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 40px;
    max-width: 1400px;
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
    font-size: 2.5rem;
    font-weight: 700;
    background: var(--primary-gradient);
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

/* 検索フォーム */
.search-section {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    border: 1px solid #e9ecef;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.search-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 20px;
    text-align: center;
}

.search-form {
    display: flex;
    gap: 20px;
    align-items: end;
    justify-content: center;
    flex-wrap: wrap;
}

.search-group {
    display: flex;
    flex-direction: column;
    min-width: 200px;
}

.search-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.search-input, .search-select {
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus, .search-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

.search-buttons {
    display: flex;
    gap: 10px;
    align-self: end;
}

.btn-search {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-clear {
    background: #6c757d;
    border: none;
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-clear:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    color: white;
}

/* アクションバー */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.results-info {
    color: #6c757d;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-add-student {
    background: var(--success-gradient);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-add-student:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    color: white;
}

/* 学生一覧テーブル */
.students-table-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.students-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
}

.students-table thead {
    background: var(--primary-gradient);
    color: white;
}

.students-table th {
    padding: 20px 15px;
    font-weight: 600;
    text-align: left;
    font-size: 1rem;
    border: none;
}

.students-table td {
    padding: 18px 15px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.students-table tbody tr {
    transition: all 0.3s ease;
}

.students-table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: translateY(-1px);
}

.student-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e9ecef;
    transition: all 0.3s ease;
}

.student-photo:hover {
    transform: scale(1.1);
    border-color: #667eea;
}

.no-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.2rem;
    border: 2px solid #e9ecef;
}

.grade-badge {
    background: var(--info-gradient);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
}

.student-name {
    font-weight: 600;
    color: #495057;
    font-size: 1.05rem;
}

.student-address {
    color: #6c757d;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-comment {
    color: #6c757d;
    font-size: 0.9rem;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* アクションボタン */
.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-action {
    padding: 8px 12px;
    border-radius: 8px;
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.btn-detail {
    background: var(--info-gradient);
    color: white;
}

.btn-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
    color: white;
}

.btn-edit {
    background: var(--warning-gradient);
    color: white;
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(255, 193, 7, 0.3);
    color: white;
}

.btn-delete {
    background: var(--danger-gradient);
    color: white;
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
    color: white;
}

/* 空データ表示 */
.no-data {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.no-data i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 20px;
}

.no-data h4 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #495057;
}

.no-data p {
    font-size: 1.1rem;
    margin-bottom: 25px;
}

/* 下部アクション */
.bottom-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
}

.btn-back {
    background: #6c757d;
    border: none;
    color: white;
    padding: 15px 30px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-back:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .students-wrapper {
        margin: 20px;
        padding: 25px 20px;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .search-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-group {
        min-width: 100%;
    }
    
    .search-buttons {
        justify-content: center;
        margin-top: 15px;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    
    .students-table-container {
        overflow-x: auto;
    }
    
    .students-table {
        min-width: 800px;
    }
    
    .bottom-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-back {
        width: 200px;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .students-container {
        padding: 20px 10px;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .search-section {
        padding: 20px;
    }
    
    .students-table th,
    .students-table td {
        padding: 12px 8px;
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-action {
        font-size: 0.8rem;
        padding: 6px 10px;
        justify-content: center;
    }
}
</style>

<div class="students-container">
    <div class="students-wrapper">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                学生表示画面
            </h1>
            <p class="page-subtitle">Student Display Screen</p>
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

        <!-- 検索フォーム（仕様書準拠） -->
        <div class="search-section">
            <div class="search-title">
                <i class="fas fa-search"></i>
                学生検索機能
            </div>
            <form method="GET" action="{{ route('students.index') }}" class="search-form">
                <!-- 学生名検索 -->
                <div class="search-group">
                    <label for="search_name" class="search-label">
                        <i class="fas fa-user"></i>
                        学生名
                    </label>
                    <input type="text" 
                           id="search_name" 
                           name="search_name" 
                           class="search-input"
                           placeholder="学生名を入力"
                           value="{{ $searchName }}">
                </div>

                <!-- 学年検索 -->
                <div class="search-group">
                    <label for="search_grade" class="search-label">
                        <i class="fas fa-graduation-cap"></i>
                        学年
                    </label>
                    <select id="search_grade" name="search_grade" class="search-select">
                        <option value="">すべての学年</option>
                        @foreach($availableGrades as $grade)
                            <option value="{{ $grade }}" 
                                    {{ $searchGrade == $grade ? 'selected' : '' }}>
                                {{ $grade }}年
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 検索ボタン -->
                <div class="search-buttons">
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i>
                        検索
                    </button>
                    <a href="{{ route('students.index') }}" class="btn-clear">
                        <i class="fas fa-undo"></i>
                        クリア
                    </a>
                </div>
            </form>
        </div>

        <!-- アクションバー -->
        <div class="action-bar">
            <div class="results-info">
                <i class="fas fa-info-circle"></i>
                {{ $students->count() }}件の学生が見つかりました
                @if($searchName || $searchGrade)
                    <span class="ms-2">
                        <i class="fas fa-filter"></i>
                        (検索結果)
                    </span>
                @endif
            </div>
            <a href="{{ route('students.create') }}" class="btn-add-student">
                <i class="fas fa-user-plus"></i>
                新規学生登録
            </a>
        </div>

        <!-- 学生一覧テーブル -->
        <div class="students-table-container">
            @if($students->count() > 0)
                <table class="students-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-badge"></i> 学生ID</th>
                            <th><i class="fas fa-graduation-cap"></i> 学年</th>
                            <th><i class="fas fa-user"></i> 名前</th>
                            <th><i class="fas fa-map-marker-alt"></i> 住所</th>
                            <th><i class="fas fa-camera"></i> 顔写真</th>
                            <th><i class="fas fa-comment"></i> コメント</th>
                            <th><i class="fas fa-cogs"></i> 操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td><strong>{{ $student->id }}</strong></td>
                                <td>
                                    <span class="grade-badge">{{ $student->grade }}年</span>
                                </td>
                                <td class="student-name">{{ $student->name }}</td>
                                <td class="student-address" title="{{ $student->address }}">
                                    {{ $student->address }}
                                </td>
                                <td>
                                    @if($student->img_path)
                                        <img src="{{ asset('storage/' . $student->img_path) }}" 
                                             alt="{{ $student->name }}の写真" 
                                             class="student-photo"
                                             onclick="showPhotoModal('{{ asset('storage/' . $student->img_path) }}', '{{ $student->name }}')">
                                    @else
                                        <div class="no-photo">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="student-comment" title="{{ $student->comment }}">
                                    {{ $student->comment ?: '－' }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('students.show', $student->id) }}" 
                                           class="btn-action btn-detail" 
                                           title="詳細表示">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-sm-inline">詳細</span>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" 
                                           class="btn-action btn-edit" 
                                           title="学生編集">
                                            <i class="fas fa-edit"></i>
                                            <span class="d-none d-sm-inline">編集</span>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('students.destroy', $student->id) }}" 
                                              style="display: inline-block;"
                                              onsubmit="return confirmDelete('{{ $student->name }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn-action btn-delete" 
                                                    title="削除">
                                                <i class="fas fa-trash"></i>
                                                <span class="d-none d-sm-inline">削除</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <i class="fas fa-users-slash"></i>
                    <h4>学生データがありません</h4>
                    @if($searchName || $searchGrade)
                        <p>検索条件に一致する学生が見つかりませんでした。</p>
                        <a href="{{ route('students.index') }}" class="btn-clear">
                            <i class="fas fa-undo"></i>
                            すべての学生を表示
                        </a>
                    @else
                        <p>まだ学生が登録されていません。</p>
                        <a href="{{ route('students.create') }}" class="btn-add-student">
                            <i class="fas fa-user-plus"></i>
                            最初の学生を登録する
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- 下部アクション -->
        <div class="bottom-actions">
            <a href="{{ route('menu') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                メニューに戻る
            </a>
        </div>
    </div>
</div>

<!-- 写真拡大モーダル -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">学生写真</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" alt="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 削除確認ダイアログ
    window.confirmDelete = function(studentName) {
        return confirm(`「${studentName}」を削除してよろしいですか？\n\nこの操作は取り消せません。`);
    };

    // 写真拡大モーダル
    window.showPhotoModal = function(imgSrc, studentName) {
        const modal = new bootstrap.Modal(document.getElementById('photoModal'));
        const modalPhoto = document.getElementById('modalPhoto');
        const modalTitle = document.getElementById('photoModalLabel');
        
        modalPhoto.src = imgSrc;
        modalPhoto.alt = `${studentName}の写真`;
        modalTitle.textContent = `${studentName}の写真`;
        
        modal.show();
    };

    // 検索フォームの機能強化
    const searchForm = document.querySelector('.search-form');
    const searchName = document.getElementById('search_name');
    const searchGrade = document.getElementById('search_grade');

    // Enterキーで検索実行
    searchName.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchForm.submit();
        }
    });

    // 検索条件変更時のハイライト
    [searchName, searchGrade].forEach(element => {
        element.addEventListener('input', function() {
            if (this.value) {
                this.style.borderColor = '#667eea';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });
    });

    // テーブル行のクリックで詳細画面へ（オプション）
    const tableRows = document.querySelectorAll('.students-table tbody tr');
    tableRows.forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function(e) {
            // ボタンクリック時は無視
            if (e.target.closest('.action-buttons') || e.target.closest('form')) {
                return;
            }
            
            const detailLink = this.querySelector('.btn-detail');
            if (detailLink) {
                window.location.href = detailLink.href;
            }
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

    // キーボードショートカット
    document.addEventListener('keydown', function(e) {
        // Ctrl+N: 新規登録
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = "{{ route('students.create') }}";
        }
        
        // Ctrl+F: 検索フィールドにフォーカス
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchName.focus();
        }
        
        // Escape: メニューに戻る
        if (e.key === 'Escape') {
            window.location.href = "{{ route('menu') }}";
        }
    });

    // 学生数の動的表示
    function updateResultsInfo() {
        const rows = document.querySelectorAll('.students-table tbody tr').length;
        const resultsInfo = document.querySelector('.results-info');
        
        if (resultsInfo) {
            const icon = resultsInfo.querySelector('i');
            const filterInfo = resultsInfo.querySelector('.ms-2');
            const hasFilter = searchName.value || searchGrade.value;
            
            resultsInfo.innerHTML = '';
            resultsInfo.appendChild(icon);
            resultsInfo.innerHTML += ` ${rows}件の学生が見つかりました`;
            
            if (hasFilter && filterInfo) {
                resultsInfo.appendChild(filterInfo);
            }
        }
    }

    // ローディング状態の表示
    const searchBtn = document.querySelector('.btn-search');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 検索中...';
            
            setTimeout(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-search"></i> 検索';
            }, 3000);
        });
    }
});
</script>
@endsection