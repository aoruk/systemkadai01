@extends('layout')

@section('title', '成績編集')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>成績編集</h4>
                    <div class="float-right">
                        <a href="{{ route('school_grades.show', $schoolGrade->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> 詳細表示
                        </a>
                        <a href="{{ route('school_grades.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> 一覧に戻る
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- バリデーションエラー表示 --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 現在の情報表示 --}}
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>編集対象:</strong> {{ $schoolGrade->student->name }} - {{ $schoolGrade->grade }}年{{ $schoolGrade->term }}学期の成績
                    </div>

                    <form action="{{ route('school_grades.update', $schoolGrade->id) }}" method="POST" id="gradeEditForm">
                        @csrf
                        @method('PUT')
                        
                        {{-- 基本情報セクション --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id"><span class="text-danger">*</span> 学生名</label>
                                    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                        <option value="">-- 学生を選択してください --</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" 
                                                {{ (old('student_id') ?? $schoolGrade->student_id) == $student->id ? 'selected' : '' }}>
                                                {{ $student->grade }}年 - {{ $student->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="grade"><span class="text-danger">*</span> 学年</label>
                                    <select name="grade" id="grade" class="form-control @error('grade') is-invalid @enderror" required>
                                        <option value="">-- 選択 --</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" 
                                                {{ (old('grade') ?? $schoolGrade->grade) == $i ? 'selected' : '' }}>
                                                {{ $i }}年
                                            </option>
                                        @endfor
                                    </select>
                                    @error('grade')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="term"><span class="text-danger">*</span> 学期</label>
                                    <select name="term" id="term" class="form-control @error('term') is-invalid @enderror" required>
                                        <option value="">-- 選択 --</option>
                                        @for($i = 1; $i <= 3; $i++)
                                            <option value="{{ $i }}" 
                                                {{ (old('term') ?? $schoolGrade->term) == $i ? 'selected' : '' }}>
                                                {{ $i }}学期
                                            </option>
                                        @endfor
                                    </select>
                                    @error('term')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- 成績入力セクション --}}
                        <h5 class="mb-3">各教科の成績（0-100点）</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="japanese">国語</label>
                                    <input type="number" name="japanese" id="japanese" 
                                           class="form-control @error('japanese') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('japanese') ?? $schoolGrade->japanese }}" 
                                           placeholder="0-100">
                                    @error('japanese')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="math">数学</label>
                                    <input type="number" name="math" id="math" 
                                           class="form-control @error('math') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('math') ?? $schoolGrade->math }}" 
                                           placeholder="0-100">
                                    @error('math')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="science">理科</label>
                                    <input type="number" name="science" id="science" 
                                           class="form-control @error('science') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('science') ?? $schoolGrade->science }}" 
                                           placeholder="0-100">
                                    @error('science')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="social_studies">社会</label>
                                    <input type="number" name="social_studies" id="social_studies" 
                                           class="form-control @error('social_studies') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('social_studies') ?? $schoolGrade->social_studies }}" 
                                           placeholder="0-100">
                                    @error('social_studies')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="music">音楽</label>
                                    <input type="number" name="music" id="music" 
                                           class="form-control @error('music') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('music') ?? $schoolGrade->music }}" 
                                           placeholder="0-100">
                                    @error('music')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="home_economics">家庭科</label>
                                    <input type="number" name="home_economics" id="home_economics" 
                                           class="form-control @error('home_economics') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('home_economics') ?? $schoolGrade->home_economics }}" 
                                           placeholder="0-100">
                                    @error('home_economics')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="english">英語</label>
                                    <input type="number" name="english" id="english" 
                                           class="form-control @error('english') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('english') ?? $schoolGrade->english }}" 
                                           placeholder="0-100">
                                    @error('english')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="art">美術</label>
                                    <input type="number" name="art" id="art" 
                                           class="form-control @error('art') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('art') ?? $schoolGrade->art }}" 
                                           placeholder="0-100">
                                    @error('art')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="health_and_physical_education">体育</label>
                                    <input type="number" name="health_and_physical_education" id="health_and_physical_education" 
                                           class="form-control @error('health_and_physical_education') is-invalid @enderror" 
                                           min="0" max="100" 
                                           value="{{ old('health_and_physical_education') ?? $schoolGrade->health_and_physical_education }}" 
                                           placeholder="0-100">
                                    @error('health_and_physical_education')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- 現在の平均点表示 --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-light">
                                    <h6>現在の統計情報</h6>
                                    @php
                                        $subjects = ['japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'];
                                        $scores = [];
                                        foreach ($subjects as $subject) {
                                            if ($schoolGrade->$subject !== null) {
                                                $scores[] = $schoolGrade->$subject;
                                            }
                                        }
                                        $average = !empty($scores) ? round(array_sum($scores) / count($scores), 1) : 0;
                                        $maxScore = !empty($scores) ? max($scores) : 0;
                                        $minScore = !empty($scores) ? min($scores) : 0;
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>現在の平均点:</strong> 
                                            <span class="badge badge-primary">{{ $average }}点</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>最高点:</strong> 
                                            <span class="badge badge-success">{{ $maxScore }}点</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>最低点:</strong> 
                                            <span class="badge badge-danger">{{ $minScore }}点</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>入力済み:</strong> 
                                            <span class="badge badge-info">{{ count($scores) }}/9科目</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- 送信ボタン --}}
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> 更新する
                            </button>
                            <a href="{{ route('school_grades.show', $schoolGrade->id) }}" class="btn btn-info btn-lg ml-2">
                                <i class="fas fa-eye"></i> 詳細表示
                            </a>
                            <a href="{{ route('school_grades.index') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-times"></i> キャンセル
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 学生選択時に学年を自動設定（新規登録と同じ機能）
document.getElementById('student_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        const gradeText = selectedOption.text;
        const gradeMatch = gradeText.match(/(\d)年/);
        if (gradeMatch) {
            document.getElementById('grade').value = gradeMatch[1];
        }
    }
});

// フォーム送信前のバリデーション
document.getElementById('gradeEditForm').addEventListener('submit', function(e) {
    const studentId = document.getElementById('student_id').value;
    const grade = document.getElementById('grade').value;
    const term = document.getElementById('term').value;
    
    if (!studentId || !grade || !term) {
        e.preventDefault();
        alert('学生名、学年、学期は必須項目です。');
        return false;
    }
    
    // 確認ダイアログ
    if (!confirm('成績を更新しますか？')) {
        e.preventDefault();
        return false;
    }
});

// リアルタイム平均点計算（オプション機能）
function updateRealTimeStats() {
    const subjects = ['japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'];
    let scores = [];
    
    subjects.forEach(function(subject) {
        const input = document.getElementById(subject);
        if (input.value && input.value !== '') {
            const score = parseInt(input.value);
            if (!isNaN(score) && score >= 0 && score <= 100) {
                scores.push(score);
            }
        }
    });
    
    if (scores.length > 0) {
        const average = Math.round((scores.reduce((a, b) => a + b, 0) / scores.length) * 10) / 10;
        const max = Math.max(...scores);
        const min = Math.min(...scores);
        
        // コンソールに表示（デバッグ用）
        console.log(`リアルタイム統計 - 平均: ${average}点, 最高: ${max}点, 最低: ${min}点, 入力済み: ${scores.length}/9科目`);
    }
}

// 各入力フィールドにイベントリスナーを追加
['japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'].forEach(function(subject) {
    document.getElementById(subject).addEventListener('input', updateRealTimeStats);
});
</script>
@endsection