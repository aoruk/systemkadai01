@extends('layout')

@section('title', '成績詳細')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- ページヘッダー -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                <i class="bi bi-clipboard-data"></i> 成績詳細
            </h2>
            <p class="mb-0" style="color: rgba(255,255,255,0.8);">
                成績ID: {{ $grade->id }} の詳細情報
            </p>
        </div>
        <div>
            <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> 編集
            </a>
            <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> 削除
            </button>
            <a href="{{ route('grades.index', $grade->student_id) }}" class="btn btn-outline-light">
                <i class="bi bi-list"></i> 一覧に戻る
            </a>
        </div>
    </div>

    <!-- 成功メッセージ -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- メイン情報 -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg border-0" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                <div class="card-header py-4" style="background: linear-gradient(135deg, rgba(102,126,234,0.8), rgba(118,75,162,0.8)); border: none;">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-graph-up"></i> 成績情報詳細
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    <!-- 基本情報 -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">成績ID</h6>
                            <p class="fs-5 mb-0">{{ $grade->id }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-2">学年</h6>
                            <span class="badge bg-primary fs-6">{{ $grade->grade }}年</span>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-2">学期</h6>
                            <span class="badge bg-info fs-6">{{ $grade->term }}</span>
                        </div>
                    </div>

                    <!-- 成績一覧 -->
                    <h5 class="mb-3 pb-2 border-bottom">
                        <i class="bi bi-book"></i> 各科目の成績
                    </h5>
                    
                    <div class="row g-3 mb-4">
                        @php
                            $subjects = [
                                'japanese' => '国語',
                                'math' => '数学', 
                                'science' => '理科',
                                'social_studies' => '社会',
                                'music' => '音楽',
                                'home_economics' => '家庭科',
                                'english' => '英語',
                                'art' => '美術',
                                'health_and_physical_education' => '保健体育'
                            ];
                            
                            $total = 0;
                            $count = 0;
                        @endphp
                        
                        @foreach($subjects as $key => $name)
                            @if($grade->$key !== null)
                                @php
                                    $score = $grade->$key;
                                    $total += $score;
                                    $count++;
                                    
                                    if($score >= 90) {
                                        $badgeClass = 'bg-success';
                                        $evaluation = 'S';
                                    } elseif($score >= 80) {
                                        $badgeClass = 'bg-primary';
                                        $evaluation = 'A';
                                    } elseif($score >= 70) {
                                        $badgeClass = 'bg-info';
                                        $evaluation = 'B';
                                    } elseif($score >= 60) {
                                        $badgeClass = 'bg-warning';
                                        $evaluation = 'C';
                                    } else {
                                        $badgeClass = 'bg-danger';
                                        $evaluation = 'D';
                                    }
                                @endphp
                                
                                <div class="col-md-4 col-sm-6">
                                    <div class="card h-100 border-0 shadow-sm subject-card">
                                        <div class="card-body text-center p-3">
                                            <h6 class="card-title mb-2">{{ $name }}</h6>
                                            <div class="mb-2">
                                                <span class="badge {{ $badgeClass }} fs-5">{{ $score }}点</span>
                                            </div>
                                            <small class="text-muted">評価: {{ $evaluation }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- 統計情報 -->
                    @if($count > 0)
                        @php $average = round($total / $count, 1); @endphp
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-primary" style="background: linear-gradient(135deg, rgba(13,202,240,0.1), rgba(102,126,234,0.1));">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <h6 class="mb-1">合計点</h6>
                                            <span class="fs-4 fw-bold">{{ $total }}</span>点
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">科目数</h6>
                                            <span class="fs-4 fw-bold">{{ $count }}</span>科目
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">平均点</h6>
                                            <span class="fs-4 fw-bold 
                                                {{ $average >= 80 ? 'text-success' : ($average >= 60 ? 'text-warning' : 'text-danger') }}">
                                                {{ $average }}
                                            </span>点
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- タイムスタンプ -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-muted small">
                            <div class="col-md-6">
                                <i class="bi bi-calendar-plus"></i> 
                                登録日時: {{ $grade->created_at->format('Y年m月d日 H:i') }}
                            </div>
                            @if($grade->updated_at != $grade->created_at)
                            <div class="col-md-6">
                                <i class="bi bi-calendar-check"></i> 
                                更新日時: {{ $grade->updated_at->format('Y年m月d日 H:i') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- サイドバー -->
        <div class="col-lg-4">
            <!-- 学生情報カード -->
            <div class="card shadow-lg border-0 mb-4" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                <div class="card-header py-3" style="background: linear-gradient(135deg, rgba(40,167,69,0.8), rgba(32,201,151,0.8)); border: none;">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-person"></i> 学生情報
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        @if($grade->student->img_path)
                            <img src="{{ asset('storage/' . $grade->student->img_path) }}" 
                                 alt="{{ $grade->student->name }}" 
                                 class="rounded-circle mb-2 student-photo"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" 
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-person fs-1 text-muted"></i>
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $grade->student->name }}</h5>
                        <span class="badge bg-primary">{{ $grade->student->grade }}年生</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">学生ID</small><br>
                        <span>{{ $grade->student->id }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">住所</small><br>
                        <span>{{ $grade->student->address }}</span>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('students.show', $grade->student->id) }}" 
                           class="btn btn-outline-primary">
                            <i class="bi bi-person-lines-fill"></i> 学生詳細を見る
                        </a>
                    </div>
                </div>
            </div>

            <!-- この学生の他の成績 -->
            @if(isset($otherGrades) && $otherGrades->count() > 0)
            <div class="card shadow-lg border-0" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                <div class="card-header py-3" style="background: linear-gradient(135deg, rgba(255,193,7,0.8), rgba(255,154,0,0.8)); border: none;">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-graph-up"></i> この学生の他の成績
                    </h5>
                </div>
                <div class="card-body p-3">
                    @foreach($otherGrades as $otherGrade)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" 
                         style="background: rgba(248,249,250,0.8);">
                        <div>
                            <small class="text-muted">{{ $otherGrade->grade }}年 {{ $otherGrade->term }}</small><br>
                            <span class="fw-bold">成績ID: {{ $otherGrade->id }}</span>
                        </div>
                        <div class="text-end">
                            @php
                                $otherTotal = 0;
                                $otherCount = 0;
                                foreach(['japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'] as $subject) {
                                    if($otherGrade->$subject !== null) {
                                        $otherTotal += $otherGrade->$subject;
                                        $otherCount++;
                                    }
                                }
                                $otherAverage = $otherCount > 0 ? round($otherTotal / $otherCount, 1) : 0;
                            @endphp
                            <span class="badge bg-{{ $otherAverage >= 80 ? 'success' : ($otherAverage >= 60 ? 'warning' : 'danger') }} mb-1">
                                平均 {{ $otherAverage }}点
                            </span><br>
                            <a href="{{ route('grades.show', $otherGrade->id) }}" 
                               class="btn btn-sm btn-outline-info">詳細</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- 削除確認モーダル -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger"></i> 削除確認
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>成績ID: <strong>{{ $grade->id }}</strong> を削除してもよろしいですか？</p>
                <p class="text-danger small">
                    <i class="bi bi-exclamation-circle"></i> この操作は取り消せません。
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> 削除実行
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- カスタムスタイル -->
<style>
.subject-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.1) !important;
}

.subject-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
}

.student-photo {
    transition: all 0.3s ease;
    border: 3px solid rgba(102,126,234,0.2);
}

.student-photo:hover {
    transform: scale(1.05);
    border-color: rgba(102,126,234,0.5);
}

.card {
    transition: all 0.3s ease;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .subject-card .card-body {
        padding: 1rem !important;
    }
}

/* アニメーション */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.5s ease;
}

.subject-card:nth-child(1) { animation-delay: 0.1s; }
.subject-card:nth-child(2) { animation-delay: 0.2s; }
.subject-card:nth-child(3) { animation-delay: 0.3s; }
.subject-card:nth-child(4) { animation-delay: 0.4s; }
.subject-card:nth-child(5) { animation-delay: 0.5s; }
.subject-card:nth-child(6) { animation-delay: 0.6s; }
.subject-card:nth-child(7) { animation-delay: 0.7s; }
.subject-card:nth-child(8) { animation-delay: 0.8s; }
.subject-card:nth-child(9) { animation-delay: 0.9s; }

/* バッジのアニメーション */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* モーダルアニメーション */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

/* プログレスバー風の統計表示 */
.alert-primary {
    position: relative;
    overflow: hidden;
}

.alert-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255,255,255,0.2), 
        transparent);
    animation: shine 2s infinite;
}

@keyframes shine {
    0% { left: -100%; }
    100% { left: 100%; }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ツールチップの初期化
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 学生写真のクリックで拡大表示
    const studentPhoto = document.querySelector('.student-photo');
    if (studentPhoto) {
        studentPhoto.style.cursor = 'pointer';
        studentPhoto.addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $grade->student->name }} の写真</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${this.src}" alt="${this.alt}" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.addEventListener('hidden.bs.modal', function() {
                document.body.removeChild(modal);
            });
        });
    }

    // カードのアニメーション
    const cards = document.querySelectorAll('.subject-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // スムーズスクロール
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
