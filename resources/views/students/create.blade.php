@extends('layout')

@section('title', '学生登録画面 - 学生成績管理システム')

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

.create-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
}

.create-container::before {
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

.create-wrapper {
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

/* フォームスタイル */
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
    background: var(--success-gradient);
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
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    transition: left 0.3s ease;
}

.btn-submit:hover::before {
    left: 0;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
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
    .create-wrapper {
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
    .create-container {
        padding: 20px 10px;
    }
    
    .page-title {
        font-size: 1.6rem;
    }
    
    .create-wrapper {
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

<div class="create-container">
    <div class="create-wrapper">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user-plus"></i>
                学生登録画面
            </h1>
            <p class="page-subtitle">Student Registration Form</p>
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

        <!-- 学生登録フォーム（仕様書準拠） -->
        <form method="POST" action="{{ route('students.store') }}" class="student-form" id="studentForm" enctype="multipart/form-data">
            @csrf
            
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
                            <option value="1" {{ old('grade') == '1' ? 'selected' : '' }}>1年</option>
                            <option value="2" {{ old('grade') == '2' ? 'selected' : '' }}>2年</option>
                            <option value="3" {{ old('grade') == '3' ? 'selected' : '' }}>3年</option>
                            <option value="4" {{ old('grade') == '4' ? 'selected' : '' }}>4年</option>
                            <option value="5" {{ old('grade') == '5' ? 'selected' : '' }}>5年</option>
                            <option value="6" {{ old('grade') == '6' ? 'selected' : '' }}>6年</option>
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
                               value="{{ old('name') }}" 
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
                              maxlength="500">{{ old('address') }}</textarea>
                    <div class="character-count">
                        <span id="addressCount">0</span>/500文字
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
                    <div class="file-upload-container">
                        <input type="file" 
                               class="file-input @error('img_path') is-invalid @enderror" 
                               id="img_path" 
                               name="img_path" 
                               accept="image/*"
                               onchange="previewImage(this)">
                        <button type="button" class="file-upload-btn" onclick="document.getElementById('img_path').click()">
                            <i class="fas fa-upload"></i>
                            写真を選択
                        </button>
                        <div class="file-info">
                            <span id="fileName">ファイルが選択されていません</span>
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
                              maxlength="1000">{{ old('comment') }}</textarea>
                    <div class="character-count">
                        <span id="commentCount">0</span>/1000文字
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
                        <span class="btn-text">学生を登録</span>
                        <i class="fas fa-spinner spinner"></i>
                    </span>
                </button>
                <a href="{{ route('students.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    キャンセル
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
        updateCharacterCount(addressTextarea, addressCount);
        
        addressTextarea.addEventListener('input', function() {
            updateCharacterCount(this, addressCount);
        });
    }

    // コメントの文字数カウント
    if (commentTextarea) {
        const commentCount = document.getElementById('commentCount');
        updateCharacterCount(commentTextarea, commentCount);
        
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
                fileName.textContent = 'ファイルが選択されていません';
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
            fileName.textContent = 'ファイルが選択されていません';
            previewContainer.style.display = 'none';
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

        // 名前の妥当性チェック
        if (name.length < 2) {
            e.preventDefault();
            alert('名前は2文字以上で入力してください。');
            document.getElementById('name').focus();
            return;
        }

        // 住所の妥当性チェック
        if (address.length < 10) {
            e.preventDefault();
            alert('住所をより詳しく入力してください。');
            document.getElementById('address').focus();
            return;
        }

        // ローディング状態に変更
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        const btnText = submitBtn.querySelector('.btn-text');
        btnText.textContent = '登録中...';
        
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
        btnText.textContent = '学生を登録';
    }

    // 入力フィールドのリアルタイムバリデーション
    const requiredFields = ['grade', 'name', 'address'];
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                
                // 即座のフィードバック
                if (this.value.trim()) {
                    this.style.borderColor = '#28a745';
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
        
        // Escape: キャンセル
        if (e.key === 'Escape') {
            if (confirm('入力内容が失われますが、キャンセルしますか？')) {
                window.location.href = "{{ route('students.index') }}";
            }
        }
    });

    // オートセーブ機能（オプション）
    let autoSaveTimer;
    function autoSave() {
        const formData = {
            grade: document.getElementById('grade').value,
            name: document.getElementById('name').value,
            address: document.getElementById('address').value,
            comment: document.getElementById('comment').value
        };
        
        localStorage.setItem('student_form_draft', JSON.stringify(formData));
    }

    // 入力時にオートセーブ
    [document.getElementById('grade'), document.getElementById('name'), 
     document.getElementById('address'), document.getElementById('comment')].forEach(field => {
        if (field) {
            field.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(autoSave, 2000);
            });
        }
    });

    // ページロード時にドラフト復元
    function restoreDraft() {
        const draft = localStorage.getItem('student_form_draft');
        if (draft && confirm('保存された下書きがあります。復元しますか？')) {
            const formData = JSON.parse(draft);
            if (formData.grade) document.getElementById('grade').value = formData.grade;
            if (formData.name) document.getElementById('name').value = formData.name;
            if (formData.address) document.getElementById('address').value = formData.address;
            if (formData.comment) document.getElementById('comment').value = formData.comment;
            
            // 文字数カウンター更新
            updateCharacterCount(addressTextarea, document.getElementById('addressCount'));
            updateCharacterCount(commentTextarea, document.getElementById('commentCount'));
        }
    }

    // 成功時にドラフトクリア
    if (window.location.search.includes('success')) {
        localStorage.removeItem('student_form_draft');
    }

    // フォーカス管理
    document.getElementById('grade').focus();

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

    // ドラフト復元（必要に応じて）
    // restoreDraft();
});
</script>
@endsection