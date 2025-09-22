@extends('layout')

@section('title', '学生編集画面 - 学生成績管理システム')

@section('content')
<style>
/* 学生登録画面と同じスタイルを継承 */
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

.edit-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
}

.edit-container::before {
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

.edit-wrapper {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 50px;
    max-width: 700px;
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
    background: var(--warning-gradient);
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

.student-info {
    background: var(--info-gradient);
    color: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
}

.student-info h3 {
    margin: 0 0 10px 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.student-info p {
    margin: 0;
    opacity: 0.9;
}

/* メッセージ表示 */
.error-message {
    background: var(--danger-gradient);
    color: white;
    padding: 15px 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    animation: slideIn 0.5s ease;
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
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

/* フォームスタイル（登録画面と共通） */
.student-form {
    text-align: left;
}

.form-section {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-group {
    margin-bottom: 25px;
    position: relative;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: #495057;
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.required {
    color: #dc3545;
    font-size: 0.8rem;
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

.form-control.is-invalid {
    border-color: #dc3545;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    appearance: none;
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.character-count {
    font-size: 0.85rem;
    color: #6c757d;
    text-align: right;
    margin-top: 5px;
}

/* 既存写真表示 */
.current-photo {
    margin-bottom: 15px;
    text-align: center;
}

.current-photo img {
    max-width: 150px;
    max-height: 150px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 3px solid #e9ecef;
}

.photo-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
}

.btn-remove-photo {
    background: var(--danger-gradient);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-remove-photo:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
    color: white;
}

/* ファイルアップロード */
.file-upload-container {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
}

.file-input {
    display: none;
}

.file-upload-btn {
    background: var(--primary-gradient);
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.file-upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    color: white;
}

.file-info {
    color: #6c757d;
    font-size: 0.9rem;
    flex: 1;
}

.preview-container {
    margin-top: 15px;
    text-align: center;
}

.preview-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 3px solid #e9ecef;
}

/* バリデーションエラー */
.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
    font-weight: 500;
    animation: slideIn 0.3s ease;
}

/* フォームアクション */
.form-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
    flex-wrap: wrap;
}

.btn-submit {
    background: var(--warning-gradient);
    border: none;
    color: white;
    padding: 15px 30px;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
    transition: left 0.3s ease;
}

.btn-submit:hover::before {
    left: 0;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.3);
}

.btn-submit span {
    position: relative;
    z-index: 1;
}

.btn-cancel {
    background: #6c757d;
    border: none;
    color: white;
    padding: 15px 30px;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-cancel:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .edit-wrapper {
        margin: 20px;
        padding: 30px 25px;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-submit, .btn-cancel {
        width: 200px;
        justify-content: center;
    }
    
    .file-upload-container {
        flex-direction: column;
        align-items: stretch;
    }
}

@media (max-width: 480px) {
    .edit-container {
        padding: 20px 10px;
    }
    
    .page-title {
        font-size: 1.6rem;
    }
    
    .edit-wrapper {
        padding: 25px 20px;
    }
    
    .form-section {
        padding: 20px;
    }
}

/* ローディング状態 */
.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-submit:disabled:hover {
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
</style>

<div class="edit-container">
    <div class="edit-wrapper">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user-edit"></i>
                学生編集画面
            </h1>
            <p class="page-subtitle">Student Edit Form</p>
        </div>

        <!-- 編集対象学生情報 -->
        <div class="student-info">
            <h3>
                <i class="fas fa-user"></i>
                編集対象: {{ $student->name }}
            </h3>
            <p>学生ID: {{ $student->id }} | 現在の学年: {{ $student->grade }}年</p>
        </div>

        <!-- エラーメッセージ表示 -->
        @if ($errors->any())
            <div class="error-message">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>入力エラーがあります</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

        <!-- 学生編集フォーム（仕様書準拠） -->
        <form method="POST" action="{{ route('students.update', $student->id) }}" class="student-form" id="studentForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- 基本情報セクション -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    基本情報
                </div>

                <div class="form-row">
                    <!-- 学年（仕様書準拠） -->
                    <div class="form-group">
                        <label for="grade" class="form-label">
                            <i class="fas fa-graduation-cap"></i>
                            学年
                            <span class="required">*</span>
                        </label>
                        <select class="form-control form-select @error('grade') is-invalid @enderror" 
                                id="grade" 
                                name="grade" 
                                required>
                            <option value="">学年を選択してください</option>
                            <option value="1" {{ (old('grade', $student->grade) == '1') ? 'selected' : '' }}>1年</option>
                            <option value="2" {{ (old('grade', $student->grade) == '2') ? 'selected' : '' }}>2年</option>
                            <option value="3" {{ (old('grade', $student->grade) == '3') ? 'selected' : '' }}>3年</option>
                            <option value="4" {{ (old('grade', $student->grade) == '4') ? 'selected' : '' }}>4年</option>
                            <option value="5" {{ (old('grade', $student->grade) == '5') ? 'selected' : '' }}>5年</option>
                            <option value="6" {{ (old('grade', $student->grade) == '6') ? 'selected' : '' }}>6年</option>
                        </select>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 名前（仕様書準拠） -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-signature"></i>
                            名前
                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $student->name) }}" 
                               placeholder="学生の氏名を入力"
                               required 
                               maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 住所（仕様書準拠） -->
                <div class="form-group">
                    <label for="address" class="form-label">
                        <i class="fas fa-map-marker-alt"></i>
                        住所
                        <span class="required">*</span>
                    </label>
                    <textarea class="form-control form-textarea @error('address') is-invalid @enderror" 
                              id="address" 
                              name="address" 
                              rows="4" 
                              placeholder="住所を入力してください"
                              required 
                              maxlength="500">{{ old('address', $student->address) }}</textarea>
                    <div class="character-count">
                        <span id="addressCount">{{ strlen($student->address) }}</span>/500文字
                    </div>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 顔写真セクション（仕様書準拠） -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-camera"></i>
                    顔写真
                </div>

                <div class="form-group">
                    <label for="img_path" class="form-label">
                        <i class="fas fa-image"></i>
                        顔写真
                        <small class="text-muted">(任意)</small>
                    </label>
                    
                    <!-- 現在の写真表示 -->
                    @if($student->img_path)
                        <div class="current-photo">
                            <p><strong>現在の写真:</strong></p>
                            <img src="{{ asset('storage/' . $student->img_path) }}" alt="現在の写真">
                            <div class="photo-actions">
                                <button type="button" class="btn-remove-photo" onclick="removeCurrentPhoto()">
                                    <i class="fas fa-trash"></i>
                                    現在の写真を削除
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    <!-- 新しい写真アップロード -->
                    <div class="file-upload-container">
                        <input type="file" 
                               class="file-input @error('img_path') is-invalid @enderror" 
                               id="img_path" 
                               name="img_path" 
                               accept="image/*"
                               onchange="previewImage(this)">
                        <button type="button" class="file-upload-btn" onclick="document.getElementById('img_path').click()">
                            <i class="fas fa-upload"></i>
                            {{ $student->img_path ? '写真を変更' : '写真を選択' }}
                        </button>
                        <div class="file-info">
                            <span id="fileName">新しいファイルが選択されていません</span>
                            <br>
                            <small>JPEG, PNG, GIF形式、最大2MBまで</small>
                        </div>
                    </div>
                    @error('img_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <!-- プレビュー表示 -->
                    <div class="preview-container" id="previewContainer" style="display: none;">
                        <img id="previewImage" class="preview-image" alt="プレビュー">
                    </div>
                    
                    <!-- 写真削除フラグ -->
                    <input type="hidden" id="remove_photo" name="remove_photo" value="0">
                </div>
            </div>

            <!-- コメントセクション（仕様書準拠） -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-comment"></i>
                    コメント
                </div>

                <div class="form-group">
                    <label for="comment" class="form-label">
                        <i class="fas fa-sticky-note"></i>
                        コメント
                        <small class="text-muted">(任意)</small>
                    </label>
                    <textarea class="form-control form-textarea @error('comment') is-invalid @enderror" 
                              id="comment" 
                              name="comment" 
                              rows="4" 
                              placeholder="学生に関するコメントがあれば入力してください"
                              maxlength="1000">{{ old('comment', $student->comment) }}</textarea>
                    <div class="character-count">
                        <span id="commentCount">{{ strlen($student->comment ?? '') }}</span>/1000文字
                    </div>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- フォームアクション -->
            <div class="form-actions">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span>
                        <i class="fas fa-save"></i>
                        <span class="btn-text">変更を保存</span>
                        <i class="fas fa-spinner spinner"></i>
                    </span>
                </button>
                <a href="{{ route('students.show', $student->id) }}" class="btn-cancel">
                    <i class="fas fa-eye"></i>
                    詳細表示
                </a>
                <a href="{{ route('students.index') }}" class="btn-cancel">
                    <i class="fas fa-list"></i>
                    一覧に戻る
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentForm = document.getElementById('studentForm');
    const submitBtn = document.getElementById('submitBtn');
    const addressTextarea = document.getElementById('address');
    const commentTextarea = document.getElementById('comment');

    // 文字数カウンター
    function updateCharacterCount(textarea, countElement) {
        const count = textarea.value.length;
        const maxLength = textarea.getAttribute('maxlength');
        countElement.textContent = count;
        
        // 警告表示
        if (count >= maxLength * 0.9) {
            countElement.style.color = '#dc3545';
        } else if (count >= maxLength * 0.7) {
            countElement.style.color = '#ffc107';
        } else {
            countElement.style.color = '#6c757d';
        }
    }

    // 住所の文字数カウント
    if (addressTextarea) {
        const addressCount = document.getElementById('addressCount');
        
        addressTextarea.addEventListener('input', function() {
            updateCharacterCount(this, addressCount);
        });
    }

    // コメントの文字数カウント
    if (commentTextarea) {
        const commentCount = document.getElementById('commentCount');
        
        commentTextarea.addEventListener('input', function() {
            updateCharacterCount(this, commentCount);
        });
    }

    // 画像プレビュー機能
    window.previewImage = function(input) {
        const fileName = document.getElementById('fileName');
        const previewContainer = document.getElementById('previewContainer');
        const previewImg = document.getElementById('previewImage');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            fileName.textContent = file.name;
            
            // ファイルサイズチェック
            if (file.size > 2048000) { // 2MB
                alert('ファイルサイズが大きすぎます。2MB以下のファイルを選択してください。');
                input.value = '';
                fileName.textContent = '新しいファイルが選択されていません';
                previewContainer.style.display = 'none';
                return;
            }
            
            // プレビュー表示
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            fileName.textContent = '新しいファイルが選択されていません';
            previewContainer.style.display = 'none';
        }
    };

    // 現在の写真削除
    window.removeCurrentPhoto = function() {
        if (confirm('現在の写真を削除しますか？')) {
            document.getElementById('remove_photo').value = '1';
            document.querySelector('.current-photo').style.display = 'none';
            
            // ボタンテキスト変更
            const uploadBtn = document.querySelector('.file-upload-btn');
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> 写真を選択';
        }
    };

    // フォーム送信処理
    studentForm.addEventListener('submit', function(e) {
        // 基本バリデーション
        const grade = document.getElementById('grade').value;
        const name = document.getElementById('name').value.trim();
        const address = document.getElementById('address').value.trim();

        if (!grade || !name || !address) {
            e.preventDefault();
            alert('必須項目をすべて入力してください。');
            return;
        }

        // 変更確認
        if (!confirm('学生情報を更新しますか？')) {
            e.preventDefault();
            return;
        }

        // ローディング状態に変更
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        const btnText = submitBtn.querySelector('.btn-text');
        btnText.textContent = '保存中...';
        
        // 10秒後にリセット（エラー時のフォールバック）
        setTimeout(() => {
            resetSubmitButton();
        }, 10000);
    });

    // 送信ボタンリセット
    function resetSubmitButton() {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        const btnText = submitBtn.querySelector('.btn-text');
        btnText.textContent = '変更を保存';
    }

    // 入力フィールドのリアルタイムバリデーション
    const requiredFields = ['grade', 'name', 'address'];
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                
                // 変更検知のフィードバック
                if (this.value.trim()) {
                    this.style.borderColor = '#ffc107';
                } else {
                    this.style.borderColor = '#e9ecef';
                }
            });
        }
    });

    // キーボードナビゲーション
    document.addEventListener('keydown', function(e) {
        // Ctrl+S: フォーム送信
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            studentForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        }
        
        // Escape: 一覧に戻る
        if (e.key === 'Escape') {
            if (confirm('変更内容が失われますが、一覧に戻りますか？')) {
                window.location.href = "{{ route('students.index') }}";
            }
        }
    });

    // 変更検知
    let originalFormData = new FormData(studentForm);
    let hasChanges = false;

    function checkForChanges() {
        const currentFormData = new FormData(studentForm);
        hasChanges = false;

        for (let [key, value] of currentFormData.entries()) {
            if (originalFormData.get(key) !== value) {
                hasChanges = true;
                break;
            }
        }

        // ボタンの状態更新
        if (hasChanges) {
            submitBtn.style.background = 'var(--warning-gradient)';
            submitBtn.querySelector('.btn-text').textContent = '変更を保存';
        } else {
            submitBtn.style.background = '#6c757d';
            submitBtn.querySelector('.btn-text').textContent = '変更なし';
        }
    }

    // 入力フィールドの変更を監視
    studentForm.addEventListener('input', checkForChanges);
    studentForm.addEventListener('change', checkForChanges);

    // ページ離脱時の確認
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '変更が保存されていません。このページを離れますか？';
            return e.returnValue;
        }
    });

    // メッセージの自動フェードアウト
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

    // フォーカス設定
    document.getElementById('grade').focus();
});
</script>
@endsection