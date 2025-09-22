@extends('layout')

@section('title', '成績詳細')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>成績詳細</h4>
                    <div class="float-right">
                        <a href="{{ route('school_grades.edit', $schoolGrade->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> 編集
                        </a>
                        <a href="{{ route('school_grades.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> 戻る
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- 基本情報 --}}
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2">基本情報</h5>
                        </div>
                        <div class="col-md-4">
                            <strong>学生名:</strong><br>
                            <span class="h6">{{ $schoolGrade->student->name }}</span>
                        </div>
                        <div class="col-md-2">
                            <strong>学年:</strong><br>
                            <span class="h6">{{ $schoolGrade->grade }}年</span>
                        </div>
                        <div class="col-md-2">
                            <strong>学期:</strong><br>
                            <span class="h6">{{ $schoolGrade->term }}学期</span>
                        </div>
                        <div class="col-md-2">
                            <strong>平均点:</strong><br>
                            @php
                                $subjects = ['japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'];
                                $scores = [];
                                foreach ($subjects as $subject) {
                                    if ($schoolGrade->$subject !== null) {
                                        $scores[] = $schoolGrade->$subject;
                                    }
                                }
                                $average = !empty($scores) ? round(array_sum($scores) / count($scores), 1) : 0;
                            @endphp
                            <span class="h6 
                                @if($average >= 90) text-success 
                                @elseif($average >= 70) text-primary 
                                @elseif($average >= 60) text-warning 
                                @else text-danger @endif">
                                {{ $average }}点
                            </span>
                        </div>
                        <div class="col-md-2">
                            <strong>登録日:</strong><br>
                            <span class="text-muted">{{ $schoolGrade->created_at->format('Y/m/d') }}</span>
                        </div>
                    </div>

                    {{-- 成績詳細 --}}
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2">各教科成績</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>教科</th>
                                            <th class="text-center">点数</th>
                                            <th class="text-center">評価</th>
                                            <th class="text-center">グラフ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
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
                                            
                                            function convertScoreToGrade($score) {
                                                if ($score === null) return '-';
                                                if ($score >= 90) return 'S';
                                                if ($score >= 80) return 'A';
                                                if ($score >= 70) return 'B';
                                                if ($score >= 60) return 'C';
                                                return 'D';
                                            }
                                            
                                            function getScoreColorClass($score) {
                                                if ($score === null) return 'text-muted';
                                                if ($score >= 90) return 'text-success font-weight-bold';
                                                if ($score >= 80) return 'text-info font-weight-bold';
                                                if ($score >= 70) return 'text-primary';
                                                if ($score >= 60) return 'text-warning';
                                                return 'text-danger';
                                            }
                                            
                                            function getGradeColorClass($grade) {
                                                switch($grade) {
                                                    case 'S': return 'badge badge-success';
                                                    case 'A': return 'badge badge-info';
                                                    case 'B': return 'badge badge-primary';
                                                    case 'C': return 'badge badge-warning';
                                                    case 'D': return 'badge badge-danger';
                                                    default: return 'badge badge-secondary';
                                                }
                                            }
                                        @endphp
                                        
                                        @foreach($subjectLabels as $subject => $label)
                                            <tr>
                                                <td class="font-weight-bold">{{ $label }}</td>
                                                <td class="text-center">
                                                    @if($schoolGrade->$subject !== null)
                                                        <span class="{{ getScoreColorClass($schoolGrade->$subject) }}">
                                                            {{ $schoolGrade->$subject }}点
                                                        </span>
                                                    @else
                                                        <span class="text-muted">未入力</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="{{ getGradeColorClass(convertScoreToGrade($schoolGrade->$subject)) }}">
                                                        {{ convertScoreToGrade($schoolGrade->$subject) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($schoolGrade->$subject !== null)
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar 
                                                                @if($schoolGrade->$subject >= 90) bg-success
                                                                @elseif($schoolGrade->$subject >= 80) bg-info
                                                                @elseif($schoolGrade->$subject >= 70) bg-primary
                                                                @elseif($schoolGrade->$subject >= 60) bg-warning
                                                                @else bg-danger @endif" 
                                                                role="progressbar" 
                                                                style="width: {{ $schoolGrade->$subject }}%">
                                                                {{ $schoolGrade->$subject }}%
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%">
                                                                未入力
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 統計情報 --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2">統計情報</h5>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">平均点</h5>
                                    <h3 class="card-text 
                                        @if($average >= 90) text-success 
                                        @elseif($average >= 70) text-primary 
                                        @elseif($average >= 60) text-warning 
                                        @else text-danger @endif">
                                        {{ $average }}点
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-success">最高点</h5>
                                    <h3 class="card-text text-success">
                                        @if(!empty($scores))
                                            {{ max($scores) }}点
                                        @else
                                            -
                                        @endif
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-danger">最低点</h5>
                                    <h3 class="card-text text-danger">
                                        @if(!empty($scores))
                                            {{ min($scores) }}点
                                        @else
                                            -
                                        @endif
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-info">入力済科目</h5>
                                    <h3 class="card-text text-info">
                                        {{ count($scores) }}/9科目
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- アクションボタン --}}
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('school_grades.edit', $schoolGrade->id) }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-edit"></i> 編集する
                            </a>
                            <button type="button" class="btn btn-danger btn-lg ml-2" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i> 削除する
                            </button>
                            <a href="{{ route('school_grades.student_grades', $schoolGrade->student->id) }}" class="btn btn-info btn-lg ml-2">
                                <i class="fas fa-user"></i> この学生の全成績
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 削除確認モーダル --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle"></i> 削除確認
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>{{ $schoolGrade->student->name }}</strong> の <strong>{{ $schoolGrade->grade }}年{{ $schoolGrade->term }}学期</strong> の成績を削除しますか？</p>
                <p class="text-danger">この操作は取り消せません。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                <form action="{{ route('school_grades.destroy', $schoolGrade->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">削除する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection