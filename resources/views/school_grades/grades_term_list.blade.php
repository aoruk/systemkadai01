@extends('layout')

@section('title', $grade . '年' . $term . '学期 - 成績一覧')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $grade }}年{{ $term }}学期 成績一覧</h4>
                    <div class="float-right">
                        <a href="{{ route('school_grades.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> 新規成績追加
                        </a>
                        <a href="{{ route('school_grades.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> 全体成績一覧
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($schoolGrades->count() > 0)
                        {{-- 成績データが存在する場合 --}}
                        
                        {{-- 統計情報 --}}
                        @php
                            $allScores = [];
                            $subjectTotals = [];
                            $subjectCounts = [];
                            $studentAverages = [];
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
                            
                            // 統計計算
                            foreach($schoolGrades as $schoolGrade) {
                                $studentScores = [];
                                foreach($subjectLabels as $subject => $label) {
                                    if ($schoolGrade->$subject !== null) {
                                        $allScores[] = $schoolGrade->$subject;
                                        $studentScores[] = $schoolGrade->$subject;
                                        
                                        if (!isset($subjectTotals[$subject])) {
                                            $subjectTotals[$subject] = 0;
                                            $subjectCounts[$subject] = 0;
                                        }
                                        $subjectTotals[$subject] += $schoolGrade->$subject;
                                        $subjectCounts[$subject]++;
                                    }
                                }
                                $studentAverages[] = !empty($studentScores) ? round(array_sum($studentScores) / count($studentScores), 1) : 0;
                            }
                            
                            $classAverage = !empty($allScores) ? round(array_sum($allScores) / count($allScores), 1) : 0;
                            $highestAverage = !empty($studentAverages) ? max($studentAverages) : 0;
                            $lowestAverage = !empty($studentAverages) ? min($studentAverages) : 0;
                        @endphp

                        {{-- クラス統計 --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">
                                    <i class="fas fa-chart-bar"></i> {{ $grade }}年{{ $term }}学期 クラス統計
                                </h5>
                            </div>
                            <div class="col-md-2">
                                <div class="card text-center bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">在籍者数</h6>
                                        <h4 class="card-text">{{ $schoolGrades->count() }}人</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card text-center bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">クラス平均</h6>
                                        <h4 class="card-text">{{ $classAverage }}点</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card text-center bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">最高平均</h6>
                                        <h4 class="card-text">{{ $highestAverage }}点</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card text-center bg-warning text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">最低平均</h6>
                                        <h4 class="card-text">{{ $lowestAverage }}点</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card text-center bg-secondary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">最高点</h6>
                                        <h4 class="card-text">{{ !empty($allScores) ? max($allScores) : 0 }}点</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card text-center bg-danger text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">最低点</h6>
                                        <h4 class="card-text">{{ !empty($allScores) ? min($allScores) : 0 }}点</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 科目別クラス平均 --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">
                                    <i class="fas fa-book"></i> 科目別クラス平均
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center">科目</th>
                                                @foreach($subjectLabels as $subject => $label)
                                                    <th class="text-center">{{ $label }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold text-center bg-light">クラス平均</td>
                                                @foreach($subjectLabels as $subject => $label)
                                                    <td class="text-center">
                                                        @if(isset($subjectCounts[$subject]) && $subjectCounts[$subject] > 0)
                                                            @php
                                                                $subjectAvg = round($subjectTotals[$subject] / $subjectCounts[$subject], 1);
                                                            @endphp
                                                            <span class="badge badge-lg
                                                                @if($subjectAvg >= 90) badge-success 
                                                                @elseif($subjectAvg >= 80) badge-info 
                                                                @elseif($subjectAvg >= 70) badge-primary 
                                                                @elseif($subjectAvg >= 60) badge-warning 
                                                                @else badge-danger @endif">
                                                                {{ $subjectAvg }}
                                                            </span>
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

                        {{-- 学生別成績一覧表 --}}
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">
                                    <i class="fas fa-users"></i> 学生別成績詳細
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center" style="width: 60px;">順位</th>
                                                <th style="width: 120px;">学生名</th>
                                                @foreach($subjectLabels as $subject => $label)
                                                    <th class="text-center" style="width: 70px;">{{ $label }}</th>
                                                @endforeach
                                                <th class="text-center" style="width: 80px;">平均</th>
                                                <th class="text-center" style="width: 120px;">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // 平均点で並び替え
                                                $sortedGrades = $schoolGrades->map(function($grade) use ($subjectLabels) {
                                                    $scores = [];
                                                    foreach($subjectLabels as $subject => $label) {
                                                        if ($grade->$subject !== null) {
                                                            $scores[] = $grade->$subject;
                                                        }
                                                    }
                                                    $grade->calculated_average = !empty($scores) ? round(array_sum($scores) / count($scores), 1) : 0;
                                                    return $grade;
                                                })->sortByDesc('calculated_average')->values();
                                            @endphp
                                            
                                            @foreach($sortedGrades as $index => $schoolGrade)
                                                <tr class="@if($index < 3) table-warning @endif">
                                                    <td class="text-center font-weight-bold">
                                                        @if($index == 0)
                                                            <i class="fas fa-trophy text-warning"></i> {{ $index + 1 }}
                                                        @elseif($index == 1)
                                                            <i class="fas fa-medal text-secondary"></i> {{ $index + 1 }}
                                                        @elseif($index == 2)
                                                            <i class="fas fa-medal text-warning"></i> {{ $index + 1 }}
                                                        @else
                                                            {{ $index + 1 }}
                                                        @endif
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        <a href="{{ route('school_grades.student_grades', $schoolGrade->student->id) }}" 
                                                           class="text-decoration-none">
                                                            {{ $schoolGrade->student->name }}
                                                        </a>
                                                    </td>
                                                    @foreach($subjectLabels as $subject => $label)
                                                        <td class="text-center">
                                                            @if($schoolGrade->$subject !== null)
                                                                <span class="badge
                                                                    @if($schoolGrade->$subject >= 90) badge-success
                                                                    @elseif($schoolGrade->$subject >= 80) badge-info
                                                                    @elseif($schoolGrade->$subject >= 70) badge-primary
                                                                    @elseif($schoolGrade->$subject >= 60) badge-warning
                                                                    @else badge-danger @endif">
                                                                    {{ $schoolGrade->$subject }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center">
                                                        <span class="badge badge-lg font-weight-bold
                                                            @if($schoolGrade->calculated_average >= 90) badge-success
                                                            @elseif($schoolGrade->calculated_average >= 80) badge-info
                                                            @elseif($schoolGrade->calculated_average >= 70) badge-primary
                                                            @elseif($schoolGrade->calculated_average >= 60) badge-warning
                                                            @else badge-danger @endif">
                                                            {{ $schoolGrade->calculated_average }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('school_grades.show', $schoolGrade->id) }}" 
                                                           class="btn btn-info btn-sm" title="詳細">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('school_grades.edit', $schoolGrade->id) }}" 
                                                           class="btn btn-warning btn-sm" title="編集">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- 成績分布グラフ（文字版） --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2">
                                    <i class="fas fa-chart-pie"></i> 成績分布状況
                                </h5>
                                @php
                                    $gradeCounts = ['S' => 0, 'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
                                    foreach($allScores as $score) {
                                        if ($score >= 90) $gradeCounts['S']++;
                                        elseif ($score >= 80) $gradeCounts['A']++;
                                        elseif ($score >= 70) $gradeCounts['B']++;
                                        elseif ($score >= 60) $gradeCounts['C']++;
                                        else $gradeCounts['D']++;
                                    }
                                    $totalGrades = array_sum($gradeCounts);
                                @endphp
                                
                                <div class="row">
                                    @foreach($gradeCounts as $gradeLabel => $count)
                                        <div class="col-md-2">
                                            <div class="card text-center
                                                @if($gradeLabel == 'S') bg-success text-white
                                                @elseif($gradeLabel == 'A') bg-info text-white
                                                @elseif($gradeLabel == 'B') bg-primary text-white
                                                @elseif($gradeLabel == 'C') bg-warning text-white
                                                @else bg-danger text-white @endif">
                                                <div class="card-body">
                                                    <h5 class="card-title">評価{{ $gradeLabel }}</h5>
                                                    <h4 class="card-text">{{ $count }}個</h4>
                                                    <small>
                                                        ({{ $totalGrades > 0 ? round(($count / $totalGrades) * 100, 1) : 0 }}%)
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-md-2">
                                        <div class="card text-center bg-dark text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">総評価数</h5>
                                                <h4 class="card-text">{{ $totalGrades }}個</h4>
                                                <small>(全科目合計)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- 成績データが存在しない場合 --}}
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h4>{{ $grade }}年{{ $term }}学期の成績データがありません</h4>
                            <p>この学年・学期の成績はまだ登録されていません。</p>
                            <a href="{{ route('school_grades.create') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus"></i> 成績を登録する
                            </a>
                        </div>
                    @endif

                    {{-- アクションボタン --}}
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('school_grades.create') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus"></i> 新規成績追加
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
.badge-lg {
    font-size: 0.95rem;
    padding: 0.5rem 0.75rem;
}

.table th {
    font-size: 0.9rem;
}

.table td {
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}

.card-body h6 {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.card-body h4 {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .table {
        font-size: 0.8rem;
    }
    
    .btn-sm {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
    }
}
</style>
@endsection