@extends('layout')

@section('title', $student->name . ' - 成績一覧')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $student->name }} の成績一覧</h4>
                    <div class="float-right">
                        <a href="{{ route('school_grades.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> 新規成績追加
                        </a>
                        <a href="{{ route('school_grades.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> 全体成績一覧
                        </a>
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-user"></i> 学生詳細
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- 学生基本情報 --}}
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>学生名:</strong> {{ $student->name }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>現在の学年:</strong> {{ $student->grade }}年
                                    </div>
                                    <div class="col-md-3">
                                        <strong>生年月日:</strong> {{ $student->birth_date->format('Y年m月d日') }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>年齢:</strong> {{ $student->birth_date->age }}歳
                                    </div>
                                    <div class="col-md-2">
                                        <strong>登録成績数:</strong> {{ $schoolGrades->count() }}件
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($schoolGrades->count() > 0)
                        {{-- 成績データが存在する場合 --}}
                        
                        {{-- 統計情報 --}}
                        @php
                            $allScores = [];
                            $subjectTotals = [];
                            $subjectCounts = [];
                            $subjectLabels = [
                                'japanese' => '国語',
                                'math' => '数学',
                                'science' => '理科',
                                'social_studies' => '社会',
                                'music' => '音楽',
                                'home_economics' => '家庭科',
                                'english' => '英語',
                                'art' => '美術',
                                'health_and_physical_education' => '体育'
                            ];
                            
                            // 全体の統計計算
                            foreach($schoolGrades as $grade) {
                                foreach($subjectLabels as $subject => $label) {
                                    if ($grade->$subject !== null) {
                                        $allScores[] = $grade->$subject;
                                        if (!isset($subjectTotals[$subject])) {
                                            $subjectTotals[$subject] = 0;
                                            $subjectCounts[$subject] = 0;
                                        }
                                        $subjectTotals[$subject] += $grade->$subject;
                                        $subjectCounts[$subject]++;
                                    }
                                }
                            }
                            
                            $overallAverage = !empty($allScores) ? round(array_sum($allScores) / count($allScores), 1) : 0;
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">全体統計情報</h5>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">全科目平均</h5>
                                        <h3 class="card-text 
                                            @if($overallAverage >= 90) text-success 
                                            @elseif($overallAverage >= 70) text-primary 
                                            @elseif($overallAverage >= 60) text-warning 
                                            @else text-danger @endif">
                                            {{ $overallAverage }}点
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">最高点</h5>
                                        <h3 class="card-text text-success">
                                            {{ !empty($allScores) ? max($allScores) : 0 }}点
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-danger">最低点</h5>
                                        <h3 class="card-text text-danger">
                                            {{ !empty($allScores) ? min($allScores) : 0 }}点
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-info">総評価数</h5>
                                        <h3 class="card-text text-info">
                                            {{ count($allScores) }}回
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 科目別平均 --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">科目別平均点</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                @foreach($subjectLabels as $subject => $label)
                                                    <th class="text-center">{{ $label }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach($subjectLabels as $subject => $label)
                                                    <td class="text-center">
                                                        @if(isset($subjectCounts[$subject]) && $subjectCounts[$subject] > 0)
                                                            @php
                                                                $subjectAvg = round($subjectTotals[$subject] / $subjectCounts[$subject], 1);
                                                            @endphp
                                                            <span class="badge 
                                                                @if($subjectAvg >= 90) badge-success 
                                                                @elseif($subjectAvg >= 80) badge-info 
                                                                @elseif($subjectAvg >= 70) badge-primary 
                                                                @elseif($subjectAvg >= 60) badge-warning 
                                                                @else badge-danger @endif">
                                                                {{ $subjectAvg }}点
                                                            </span><br>
                                                            <small class="text-muted">({{ $subjectCounts[$subject] }}回)</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- 学期別成績一覧 --}}
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">学期別成績詳細</h5>
                            </div>
                        </div>

                        @foreach($schoolGrades as $schoolGrade)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-0">
                                                <i class="fas fa-calendar"></i> 
                                                {{ $schoolGrade->grade }}年 {{ $schoolGrade->term }}学期
                                            </h6>
                                        </div>
                                        <div class="col-md-3">
                                            @php
                                                $gradeScores = [];
                                                foreach($subjectLabels as $subject => $label) {
                                                    if ($schoolGrade->$subject !== null) {
                                                        $gradeScores[] = $schoolGrade->$subject;
                                                    }
                                                }
                                                $gradeAverage = !empty($gradeScores) ? round(array_sum($gradeScores) / count($gradeScores), 1) : 0;
                                            @endphp
                                            <strong>平均点:</strong> 
                                            <span class="badge 
                                                @if($gradeAverage >= 90) badge-success 
                                                @elseif($gradeAverage >= 70) badge-primary 
                                                @elseif($gradeAverage >= 60) badge-warning 
                                                @else badge-danger @endif">
                                                {{ $gradeAverage }}点
                                            </span>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <a href="{{ route('school_grades.show', $schoolGrade->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> 詳細
                                            </a>
                                            <a href="{{ route('school_grades.edit', $schoolGrade->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> 編集
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($subjectLabels as $subject => $label)
                                            <div class="col-md-4 col-lg-3 mb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="font-weight-bold">{{ $label }}:</span>
                                                    @if($schoolGrade->$subject !== null)
                                                        <span class="
                                                            @if($schoolGrade->$subject >= 90) text-success font-weight-bold
                                                            @elseif($schoolGrade->$subject >= 80) text-info font-weight-bold
                                                            @elseif($schoolGrade->$subject >= 70) text-primary
                                                            @elseif($schoolGrade->$subject >= 60) text-warning
                                                            @else text-danger @endif">
                                                            {{ $schoolGrade->$subject }}点
                                                            @php
                                                                $score = $schoolGrade->$subject;
                                                                if ($score >= 90) echo '(S)';
                                                                elseif ($score >= 80) echo '(A)';
                                                                elseif ($score >= 70) echo '(B)';
                                                                elseif ($score >= 60) echo '(C)';
                                                                else echo '(D)';
                                                            @endphp
                                                        </span>
                                                    @else
                                                        <span class="text-muted">未入力</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> 
                                                登録日: {{ $schoolGrade->created_at->format('Y年m月d日') }}
                                                @if($schoolGrade->updated_at != $schoolGrade->created_at)
                                                    / 更新日: {{ $schoolGrade->updated_at->format('Y年m月d日') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @else
                        {{-- 成績データが存在しない場合 --}}
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h4>成績データがありません</h4>
                            <p>{{ $student->name }} の成績はまだ登録されていません。</p>
                            <a href="{{ route('school_grades.create') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus"></i> 最初の成績を登録する
                            </a>
                        </div>
                    @endif

                    {{-- アクションボタン --}}
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('school_grades.create') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus"></i> 新しい成績を追加
                            </a>
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-lg ml-2">
                                <i class="fas fa-user"></i> 学生詳細を見る
                            </a>
                            <a href="{{ route('school_grades.index') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-list"></i> 全体成績一覧
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* カスタムスタイル */
.card-header h6 {
    font-size: 1.1rem;
    font-weight: 600;
}

.badge {
    font-size: 0.9rem;
}

.table-responsive {
    border-radius: 5px;
}

.card.mb-3 {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
}

.card.mb-3:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
</style>
@endsection