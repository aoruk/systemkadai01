@extends('layout')

@section('title', '成績編集')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- ページヘッダー -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                <i class="bi bi-pencil-square"></i> 成績編集
            </h2>
            <p class="mb-0" style="color: rgba(255,255,255,0.8);">
                成績ID: {{ $grade->id }} の編集
            </p>
        </div>
        <div>
            <a href="{{ route('grades.show', $grade->id) }}" class="btn btn-outline-light me-2">
                <i class="bi bi-eye"></i> 詳細表示
            </a>
            <a href="{{ route('grades.index', $grade->student_id) }}" class="btn btn-outline-light">
                <i class="bi bi-list"></i> 一覧に戻る
            </a>
        </div>
    </div>

    <!-- エラー・成功メッセージ -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-exclamation-triangle"></i> 入力エラーがあります</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, rgba(102,126,234,0.8), rgba(118,75,162,0.8)); border: none;">
                    <h4 class="mb-1 text-white">
                        <i class="bi bi-clipboard-data"></i> 成績情報編集
                    </h4>
                    <p class="mb-0 text-white-50">
                        学生: {{ $grade->student->name }} ({{ $grade->student->grade }}年)
                    </p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('grades.update', $grade->id) }}" method="POST" id="gradeEditForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- 基本情報 -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="grade" class="form-label fw-bold">
                                    <i class="bi bi-building"></i> 学年 <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="grade" name="grade" required>
                                    <option value="">選択してください</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('grade', $grade->grade) == $i ? 'selected' : '' }}>{{ $i }}年</option>
                                    @endfor
                                </select>
                                <div class="form-text">対象学年を選択してください</div>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="term" class="form-label fw-bold">
                                    <i class="bi bi-calendar3"></i> 学期 <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="term" name="term" required>
                                    <option value="">選択してください</option>
                                    <option value="1学期" {{ old('term', $grade->term) == '1学期' ? 'selected' : '' }}>1学期</option>
                                    <option value="2学期" {{ old('term', $grade->term) == '2学期' ? 'selected' : '' }}>2学期</option>
                                    <option value="3学期" {{ old('term', $grade->term) == '3学期' ? 'selected' : '' }}>3学期</option>
                                </select>
                                <div class="form-text">対象学期を選択してください</div>
                            </div>
                        </div>

                        <!-- 各科目の成績入力 -->
                        <div class="mb-4">
                            <h5 class="mb-3 pb-2 border-bottom">
                                <i class="bi bi-book"></i> 各科目の成績入力
                            </h5>
                            
                            <div class="row g-3">
                                <!-- 国語 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="japanese" class="form-label fw-bold">国語</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="japanese" 
                                               name="japanese" 
                                               value="{{ old('japanese', $grade->japanese) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 数学 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="math" class="form-label fw-bold">数学</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="math" 
                                               name="math" 
                                               value="{{ old('math', $grade->math) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 理科 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="science" class="form-label fw-bold">理科</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="science" 
                                               name="science" 
                                               value="{{ old('science', $grade->science) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 社会 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="social_studies" class="form-label fw-bold">社会</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="social_studies" 
                                               name="social_studies" 
                                               value="{{ old('social_studies', $grade->social_studies) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 音楽 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="music" class="form-label fw-bold">音楽</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="music" 
                                               name="music" 
                                               value="{{ old('music', $grade->music) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 家庭科 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="home_economics" class="form-label fw-bold">家庭科</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="home_economics" 
                                               name="home_economics" 
                                               value="{{ old('home_economics', $grade->home_economics) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 英語 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="english" class="form-label fw-bold">英語</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="english" 
                                               name="english" 
                                               value="{{ old('english', $grade->english) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 美術 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="art" class="form-label fw-bold">美術</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="art" 
                                               name="art" 
                                               value="{{ old('art', $grade->art) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>

                                <!-- 保健体育 -->
                                <div class="col-md-6 col-lg-4">
                                    <label for="health_and_physical_education" class="form-label fw-bold">保健体育</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               id="health_and_physical_education" 
                                               name="health_and_physical_education" 
                                               value="{{ old('health_and_physical_education', $grade->health_and_physical_education) }}" 
                                               min="0" 
                                               max="100" 
                                               placeholder="0-100">
                                        <span class="input-group-text">点</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 平均点表示エリア -->
                        <div class="mb-4">
                            <div class="alert alert-info" style="background: rgba(13,202,240,0.1); border-color: rgba(13,202,240,0.3);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calculator me-2"></i>
                                    <strong>平均点: </strong>
                                    <span id="averageScore" class="ms-2 fs-5">--</span>点
                                </div>
                            </div>
                        </div>

                        <!-- 操作ボタン -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                    <i class="bi bi-arrow-counterclockwise"></i> リセット
                                </button>
                            </div>
                            
                            <div>
                                <a href="{{ route('grades.show', $grade->id) }}" class="btn btn-outline-primary me-2">
                                    <i class="bi bi-x-lg"></i> キャンセル
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="bi bi-check-lg"></i> 更新
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoreInputs = document.querySelectorAll('.score-input');
    const averageDisplay = document.getElementById('averageScore');
    let formChanged = false;

    // 平均点計算関数
    function calculateAverage() {
        let total = 0;
        let count = 0;
        
        scoreInputs.forEach(input => {
            const value = parseInt(input.value);
            if (!isNaN(value) && value >= 0) {
                total += value;
                count++;
            }
        });
        
        if (count > 0) {
            const average = (total / count).toFixed(1);
            averageDisplay.textContent = average;
            
            // 平均点による色分け
            averageDisplay.className = 'ms-2 fs-5 fw-bold';
            if (average >= 80) {
                averageDisplay.style.color = '#198754'; // success
            } else if (average >= 60) {
                averageDisplay.style.color = '#fd7e14'; // warning
            } else {
                averageDisplay.style.color = '#dc3545'; // danger
            }
        } else {
            averageDisplay.textContent = '--';
            averageDisplay.style.color = '#6c757d';
        }
    }

    // 入力値の検証
    function validateScore(input) {
        const value = parseInt(input.value);
        if (value < 0) {
            input.value = 0;
        } else if (value > 100) {
            input.value = 100;
        }
    }

    // イベントリスナー設定
    scoreInputs.forEach(input => {
        // 初期計算
        calculateAverage();
        
        input.addEventListener('input', function() {
            validateScore(this);
            calculateAverage();
            formChanged = true;
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.value = '';
            }
            calculateAverage();
        });
    });

    // フォーム変更検知
    document.getElementById('gradeEditForm').addEventListener('change', function() {
        formChanged = true;
    });

    // ページ離脱警告
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '変更が保存されていません。ページを離れてもよろしいですか？';
        }
    });

    // フォーム送信時は警告を無効化
    document.getElementById('gradeEditForm').addEventListener('submit', function() {
        formChanged = false;
    });
});

// リセット機能
function resetForm() {
    if (confirm('入力内容をリセットしてもよろしいですか？')) {
        document.getElementById('gradeEditForm').reset();
        document.getElementById('averageScore').textContent = '--';
        document.getElementById('averageScore').style.color = '#6c757d';
        formChanged = false;
    }
}
</script>

<!-- カスタムスタイル -->
<style>
.score-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection