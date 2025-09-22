@extends('layout')

@section('title', '成績登録 - 学生成績管理システム')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    --info-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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
    padding: 40px;
    max-width: 1000px;
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
    padding-bottom: 30px;
    border-bottom: 2px solid #e9ecef;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.student-info {
    background: var(--primary-gradient);
    color: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.student-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.student-meta {
    font-size: 1rem;
    opacity: 0.9;
}

/* エラーメッセージ */
.error-message {
    background: var(--danger-gradient);
    color: white;
    padding: 15px 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    animation: slideIn 0.5s ease;
}

.error-message ul {
    margin: 0;
    padding-left: 20px;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* フォームセクション */
.form-section {
    background: white;
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #e9ecef;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.basic-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.form-group {
    position: relative;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.required {
    color: #dc3545;
    font-weight: 700;
}

.form-control {
    width: 100%;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
    background: white;
}

.form-control.is-invalid {
    border-color: #dc3545;
    background: #fff5f5;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
    font-weight: 500;
}

/* 成績入力セクション */
.subjects-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.subject-category {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
    border: 2px solid #e9ecef;
}

.category-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 20px;
    padding: 10px 15px;
    border-radius: 10px;
    text-align: center;
    color: white;
}

.main-subjects {
    background: var(--primary-gradient);
}

.sub-subjects {
    background: var(--info-gradient);
}

.subject-item {
    margin-bottom: 20px;
}

.subject-item:last-child {
    margin-bottom: 0;
}

.score-input-wrapper {
    position: relative;
}

.score-input {
    padding-right: 50px;
    text-align: center;
    font-weight: 600;
    font-size: 1.1rem;
}

.score-unit {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-weight: 600;
    pointer-events: none;
}

.score-slider {
    width: 100%;
    margin-top: 10px;
    -webkit-appearance: none;
    appearance: none;
    height: 8px;
    border-radius: 4px;
    background: #e9ecef;
    outline: none;
}

.score-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--primary-gradient);
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.score-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--primary-gradient);
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.grade-indicator {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 5px;
}

/* 注意事項 */
.info-box {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(19, 132, 150, 0.1) 100%);
    border: 2px solid rgba(23, 162, 184, 0.2);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.info-title {
    font-weight: 700;
    color: #138496;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-list {
    margin: 0;
    padding-left: 20px;
    color: #495057;
}

.info-list li {
    margin-bottom: 5px;
    line-height: 1.5;
}

/* アクションボタン */
.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 30px;
    border-top: 2px solid #e9ecef;
    gap: 20px;
}

.btn-action {
    padding: 15px 30px;
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

.btn-back {
    background: #6c757d;
    color: white;
}

.btn-submit {
    background: var(--success-gradient);
    color: white;
    font-size: 1.1rem;
    padding: 15px 40px;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    color: white;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .create-wrapper {
        margin: 20px;
        padding: 25px;
    }
    
    .page-title {
        font-size: 1.8rem;
        flex-direction: column;
        gap: 10px;
    }
    
    .basic-info-grid,
    .subjects-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column-reverse;
    }
    
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .create-container {
        padding: 20px 10px;
    }
    
    .form-section {
        padding: 20px;
    }
    
    .subject-category {
        padding: 20px;
    }
}

/* 入力値のリアルタイム表示 */
.score-preview {
    text-align: center;
    font-size: 0.9rem;
    color: #6c757d;
    margin-top: 5px;
    font-weight: 600;
}

.grade-A { color: #28a745; }
.grade-B { color: #17a2b8; }
.grade-C { color: #ffc107; }
.grade-D { color: #fd7e14; }
.grade-F { color: #dc3545; }
</style>

<div class="create-container">
    <div class="create-wrapper">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i>
                成績登録
            </h1>
            <div class="student-info">
                <div class="student-name">{{ $student->name }}</div>
                <div class="student-meta">
                    学生ID: {{ $student->id }} | {{ $student->grade }}年生
                </div>
            </div>
        </div>

        <!-- エラーメッセージ -->
        @if ($errors->any())
            <div class="error-message">
                <strong><i class="fas fa-exclamation-circle"></i> 入力エラーがあります</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('grades.store') }}" method="POST" id="gradeForm">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            
            <!-- 基本情報 -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    基本情報
                </div>
                
                <div class="basic-info-grid">
                    <div class="form-group">
                        <label for="grade" class="form-label">
                            <i class="fas fa-graduation-cap"></i>
                            対象学年 <span class="required">*</span>
                        </label>
                        <select name="grade" id="grade" class="form-control @error('grade') is-invalid @enderror" required>
                            <option value="">学年を選択してください</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('grade', $student->grade) == $i ? 'selected' : '' }}>
                                    {{ $i }}年生
                                </option>
                            @endfor
                        </select>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="term" class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            学期 <span class="required">*</span>
                        </label>
                        <select name="term" id="term" class="form-control @error('term') is-invalid @enderror" required>
                            <option value="">学期を選択してください</option>
                            <option value="1" {{ old('term') == 1 ? 'selected' : '' }}>1学期</option>
                            <option value="2" {{ old('term') == 2 ? 'selected' : '' }}>2学期</option>
                            <option value="3" {{ old('term') == 3 ? 'selected' : '' }}>3学期</option>
                        </select>
                        @error('term')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 成績入力 -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-chart-line"></i>
                    各教科成績（100点満点）
                </div>
                
                <div class="subjects-grid">
                    <!-- 主要教科 -->
                    <div class="subject-category">
                        <div class="category-title main-subjects">
                            <i class="fas fa-book"></i> 主要教科
                        </div>
                        
                        @php
                            $mainSubjects = [
                                'japanese' => ['国語', 'fas fa-language'],
                                'math' => ['数学', 'fas fa-calculator'],
                                'science' => ['理科', 'fas fa-flask'],
                                'social_studies' => ['社会', 'fas fa-globe'],
                                'english' => ['英語', 'fas fa-comments']
                            ];
                        @endphp
                        
                        @foreach($mainSubjects as $key => $subject)
                            <div class="subject-item">
                                <label for="{{ $key }}" class="form-label">
                                    <i class="{{ $subject[1] }}"></i>
                                    {{ $subject[0] }}
                                </label>
                                <div class="score-input-wrapper">
                                    <input type="number" 
                                           name="{{ $key }}" 
                                           id="{{ $key }}" 
                                           class="form-control score-input @error($key) is-invalid @enderror"
                                           min="0" 
                                           max="100" 
                                           value="{{ old($key) }}"
                                           placeholder="0-100"
                                           data-subject="{{ $key }}">
                                    <span class="score-unit">点</span>
                                </div>
                                <input type="range" 
                                       class="score-slider" 
                                       min="0" 
                                       max="100" 
                                       value="{{ old($key, 0) }}"
                                       data-target="{{ $key }}">
                                <div class="grade-indicator">
                                    <span>0点</span>
                                    <span>50点</span>
                                    <span>100点</span>
                                </div>
                                <div class="score-preview" id="preview-{{ $key }}">-</div>
                                @error($key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <!-- 副教科 -->
                    <div class="subject-category">
                        <div class="category-title sub-subjects">
                            <i class="fas fa-palette"></i> 副教科
                        </div>
                        
                        @php
                            $subSubjects = [
                                'music' => ['音楽', 'fas fa-music'],
                                'art' => ['美術', 'fas fa-paint-brush'],
                                'home_economics' => ['家庭科', 'fas fa-home'],
                                'health_and_physical_education' => ['体育', 'fas fa-running']
                            ];
                        @endphp
                        
                        @foreach($subSubjects as $key => $subject)
                            <div class="subject-item">
                                <label for="{{ $key }}" class="form-label">
                                    <i class="{{ $subject[1] }}"></i>
                                    {{ $subject[0] }}
                                </label>
                                <div class="score-input-wrapper">
                                    <input type="number" 
                                           name="{{ $key }}" 
                                           id="{{ $key }}" 
                                           class="form-control score-input @error($key) is-invalid @enderror"
                                           min="0" 
                                           max="100" 
                                           value="{{ old($key) }}"
                                           placeholder="0-100"
                                           data-subject="{{ $key }}">
                                    <span class="score-unit">点</span>
                                </div>
                                <input type="range" 
                                       class="score-slider" 
                                       min="0" 
                                       max="100" 
                                       value="{{ old($key, 0) }}"
                                       data-target="{{ $key }}">
                                <div class="grade-indicator">
                                    <span>0点</span>
                                    <span>50点</span>
                                    <span>100点</span>
                                </div>
                                <div class="score-preview" id="preview-{{ $key }}">-</div>
                                @error($key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 注意事項 -->
            <div class="info-box">
                <div class="info-title">
                    <i class="fas fa-info-circle"></i>
                    入力についての注意事項
                </div>
                <ul class="info-list">
                    <li>学年と学期は必須項目です</li>
                    <li>各教科の成績は0〜100点で入力してください</li>
                    <li>未実施の科目は空欄のまま登録できます</li>
                    <li>同じ学生の同じ学年・学期の成績は重複登録できません</li>
                    <li>スライダーを使用して直感的に点数を入力することも可能です</li>
                </ul>
            </div>

            <!-- アクションボタン -->
            <div class="action-buttons">
                <a href="{{ route('students.show', $student->id) }}" class="btn-action btn-back">
                    <i class="fas fa-arrow-left"></i>
                    学生詳細に戻る
                </a>
                
                <button type="submit" class="btn-action btn-submit">
                    <i class="fas fa-save"></i>
                    成績を登録
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // スライダーと入力フィールドの連動
    const sliders = document.querySelectorAll('.score-slider');
    const inputs = document.querySelectorAll('.score-input');
    
    // 評価判定関数
    function getGradeInfo(score) {
        if (score >= 90) return { grade: 'S', class: 'grade-A', text: 'S評価' };
        if (score >= 80) return { grade: 'A', class: 'grade-A', text: 'A評価' };
        if (score >= 70) return { grade: 'B', class: 'grade-B', text: 'B評価' };
        if (score >= 60) return { grade: 'C', class: 'grade-C', text: 'C評価' };
        if (score > 0) return { grade: 'D', class: 'grade-F', text: 'D評価' };
        return { grade: '-', class: '', text: '未入力' };
    }
    
    // プレビュー更新関数
    function updatePreview(subject, score) {
        const preview = document.getElementById(`preview-${subject}`);
        const gradeInfo = getGradeInfo(parseInt(score) || 0);
        
        if (preview) {
            preview.textContent = gradeInfo.text;
            preview.className = `score-preview ${gradeInfo.class}`;
        }
    }
    
    // スライダーのイベントリスナー
    sliders.forEach(slider => {
        const targetField = slider.dataset.target;
        const input = document.getElementById(targetField);
        
        slider.addEventListener('input', function() {
            if (input) {
                input.value = this.value;
                updatePreview(targetField, this.value);
            }
        });
    });
    
    // 入力フィールドのイベントリスナー
    inputs.forEach(input => {
        const subject = input.dataset.subject;
        const slider = document.querySelector(`[data-target="${subject}"]`);
        
        input.addEventListener('input', function() {
            let value = parseInt(this.value) || 0;
            
            // 範囲制限
            if (value > 100) {
                value = 100;
                this.value = 100;
            }
            if (value < 0) {
                value = 0;
                this.value = 0;
            }
            
            if (slider) {
                slider.value = value;
            }
            
            updatePreview(subject, value);
        });
        
        // 初期値のプレビュー設定
        updatePreview(subject, input.value);
    });
    
    // フォーム送信前の確認
    const form = document.getElementById('gradeForm');
    form.addEventListener('submit', function(e) {
        const grade = document.getElementById('grade').value;
        const term = document.getElementById('term').value;
        
        if (!grade || !term) {
            e.preventDefault();
            alert('学年と学期は必須項目です。');
            return false;
        }
        
        // 少なくとも1科目は入力されているかチェック
        const hasScore = inputs.some(input => input.value && parseInt(input.value) > 0);
        
        if (!hasScore) {
            const confirm = window.confirm('全ての科目が未入力ですが、このまま登録してもよろしいですか？');
            if (!confirm) {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });
    
    // 入力フィールドのアニメーション
    inputs.forEach((input, index) => {
        input.parentElement.style.opacity = '0';
        input.parentElement.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            input.parentElement.style.transition = 'all 0.5s ease';
            input.parentElement.style.opacity = '1';
            input.parentElement.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection