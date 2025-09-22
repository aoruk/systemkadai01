@extends('layout')

@section('title', '成績一覧 - 学生成績管理システム')

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

.grades-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
}

.grades-container::before {
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

.grades-wrapper {
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-header {
    padding: 12px 20px;
    border-radius: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
}

.btn-primary {
    background: var(--success-gradient);
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-header:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    color: white;
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

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* 検索・フィルター */
.search-section {
    background: white;
    padding: 25px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #e9ecef;
}

.search-form {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.form-control {
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.btn-search {
    background: var(--info-gradient);
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(23, 162, 184, 0.3);
}

/* 統計情報 */
.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #e9ecef;
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 600;
}

/* テーブル */
.table-section {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #e9ecef;
}

.table-header {
    background: var(--primary-gradient);
    color: white;
    padding: 20px 25px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.grades-table {
    width: 100%;
    border-collapse: collapse;
}

.grades-table thead th {
    background: #f8f9fa;
    padding: 15px;
    font-weight: 600;
    color: #495057;
    text-align: left;
    border-bottom: 2px solid #e9ecef;
}

.grades-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f8f9fa;
}

.grades-table tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
}

.grades-table td {
    padding: 15px;
    vertical-align: middle;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.student-name {
    font-weight: 600;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
}

.student-name:hover {
    color: #667eea;
    text-decoration: underline;
}

.grade-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
    min-width: 50px;
    display: inline-block;
}

.term-badge {
    background: var(--info-gradient);
    color: white;
}

.score-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    font-size: 0.8rem;
}

.subject-score {
    padding: 4px 8px;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
}

.score-excellent { background: #d4edda; color: #155724; }
.score-good { background: #cce7ff; color: #004085; }
.score-average { background: #fff3cd; color: #856404; }
.score-poor { background: #f8d7da; color: #721c24; }
.score-empty { background: #f8f9fa; color: #6c757d; }

.average-score {
    font-size: 1.1rem;
    font-weight: 700;
    padding: 8px 15px;
    border-radius: 15px;
    text-align: center;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
    border: none;
    cursor: pointer;
}

.btn-detail {
    background: var(--info-gradient);
    color: white;
}

.btn-edit {
    background: var(--warning-gradient);
    color: white;
}

.btn-delete {
    background: var(--danger-gradient);
    color: white;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    color: white;
}

/* 空状態 */
.empty-state {
    text-align: center;
    padding: 60px 40px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 15px;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 30px;
    line-height: 1.6;
}

/* レスポンシブ対応 */
@media (max-width: 1200px) {
    .search-form {
        grid-template-columns: 1fr 1fr;
    }
    
    .score-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .grades-wrapper {
        margin: 20px;
        padding: 25px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-buttons {
        justify-content: center;
    }
    
    .search-form {
        grid-template-columns: 1fr;
    }
    
    .stats-section {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .table-section {
        overflow-x: auto;
    }
    
    .grades-table {
        min-width: 800px;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .stats-section {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="grades-container">
    <div class="grades-wrapper">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chart-bar"></i>
                成績一覧・集計
            </h1>
            <div class="header-buttons">
                <a href="{{ route('students.index') }}" class="btn-header btn-primary">
                    <i class="fas fa-plus"></i>
                    成績登録
                </a>
                <a href="{{ route('menu') }}" class="btn-header btn-secondary">
                    <i class="fas fa-home"></i>
                    メニュー
                </a>
            </div>
        </div>

        <!-- メッセージ表示 -->
        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($grades->count() > 0)
            <!-- 統計情報 -->
            <div class="stats-section">
                @php
                    $totalStudents = $grades->unique('student_id')->count();
                    $averageScore = 0;
                    $totalScores = 0;
                    $subjectCount = 0;
                    
                    foreach($grades as $grade) {
                        $subjects = ['japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'];
                        foreach($subjects as $subject) {
                            if($grade->$subject !== null) {
                                $totalScores += $grade->$subject;
                                $subjectCount++;
                            }
                        }
                    }
                    
                    $averageScore = $subjectCount > 0 ? round($totalScores / $subjectCount, 1) : 0;
                @endphp
                
                <div class="stat-card">
                    <div class="stat-number">{{ $grades->count() }}</div>
                    <div class="stat-label">登録件数</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $totalStudents }}</div>
                    <div class="stat-label">対象学生数</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $averageScore }}</div>
                    <div class="stat-label">全体平均点</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $subjectCount }}</div>
                    <div class="stat-label">総科目数</div>
                </div>
            </div>

            <!-- 成績テーブル -->
            <div class="table-section">
                <div class="table-header">
                    <i class="fas fa-table"></i>
                    成績詳細一覧
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="grades-table">
                        <thead>
                            <tr>
                                <th>学生情報</th>
                                <th>学年・学期</th>
                                <th>各科目成績</th>
                                <th>平均点</th>
                                <th>登録日</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $grade)
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <div>
                                            <a href="{{ route('students.show', $grade->student->id) }}" class="student-name">
                                                {{ $grade->student->name }}
                                            </a>
                                            <div style="font-size: 0.8rem; color: #6c757d;">
                                                ID: {{ $grade->student->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="grade-badge term-badge">
                                        {{ $grade->grade }}年 {{ $grade->term }}学期
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="score-grid">
                                        @php
                                            $subjects = [
                                                'japanese' => '国',
                                                'math' => '数',
                                                'science' => '理',
                                                'social_studies' => '社',
                                                'music' => '音',
                                                'home_economics' => '家',
                                                'english' => '英',
                                                'art' => '美',
                                                'health_and_physical_education' => '保'
                                            ];
                                        @endphp
                                        
                                        @foreach($subjects as $key => $name)
                                            @php
                                                $score = $grade->$key;
                                                $class = 'score-empty';
                                                if($score !== null) {
                                                    if($score >= 80) $class = 'score-excellent';
                                                    elseif($score >= 70) $class = 'score-good';
                                                    elseif($score >= 60) $class = 'score-average';
                                                    else $class = 'score-poor';
                                                }
                                            @endphp
                                            <div class="subject-score {{ $class }}">
                                                {{ $name }}: {{ $score ?? '-' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                
                                <td>
                                    @php
                                        $avg = $grade->average_score;
                                        $avgClass = 'background: #6c757d';
                                        if($avg >= 80) $avgClass = 'background: var(--success-gradient)';
                                        elseif($avg >= 70) $avgClass = 'background: var(--info-gradient)';
                                        elseif($avg >= 60) $avgClass = 'background: var(--warning-gradient)';
                                        elseif($avg > 0) $avgClass = 'background: var(--danger-gradient)';
                                    @endphp
                                    <div class="average-score" style="{{ $avgClass }}">
                                        {{ $avg }}点
                                    </div>
                                </td>
                                
                                <td>
                                    @if($grade->created_at)
                                        {{ \Carbon\Carbon::parse($grade->created_at)->format('Y/m/d') }}
                                    @else
                                        未設定
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('grades.show', $grade->id) }}" class="btn-action btn-detail">
                                            <i class="fas fa-eye"></i>
                                            詳細
                                        </a>
                                        <a href="{{ route('grades.edit', $grade->id) }}" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i>
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('grades.destroy', $grade->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('この成績データを削除してよろしいですか？')">
                                                <i class="fas fa-trash"></i>
                                                削除
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- 空状態 -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="empty-title">成績データがありません</h3>
                <p class="empty-text">
                    まずは学生を選択して成績を登録してください。<br>
                    各学生の詳細画面から成績を追加することができます。
                </p>
                <a href="{{ route('students.index') }}" class="btn-header btn-primary">
                    <i class="fas fa-plus"></i>
                    学生一覧から成績登録
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 成功メッセージの自動フェードアウト
    const successMessage = document.querySelector('.success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            successMessage.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                successMessage.remove();
            }, 300);
        }, 5000);
    }

    // テーブル行のアニメーション
    const tableRows = document.querySelectorAll('.grades-table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });

    // 統計カードのアニメーション
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100 + 200);
    });
});
</script>
@endsection