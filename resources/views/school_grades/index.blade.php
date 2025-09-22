@extends('layout')

@section('title', '成績管理')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-clipboard-data me-2"></i>成績管理
    </h2>
    <div>
        <a href="{{ route('school_grades.create') }}" class="btn btn-success">
            <i class="bi bi-plus me-1"></i>新しい成績を追加
        </a>
        <a href="{{ route('menu') }}" class="btn btn-secondary ms-2">
            <i class="bi bi-arrow-left me-1"></i>メニューに戻る
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-list me-2"></i>成績一覧
        </h5>
    </div>
    <div class="card-body">
        @if(isset($grades) && $grades->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>学生番号</th>
                            <th>氏名</th>
                            <th>科目</th>
                            <th>点数</th>
                            <th>評価</th>
                            <th>登録日</th>
                            <th>アクション</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            <tr>
                                <td>{{ $grade->student->student_number ?? 'N/A' }}</td>
                                <td>{{ $grade->student->name ?? 'N/A' }}</td>
                                <td>{{ $grade->subject }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $grade->score }}点</span>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        switch($grade->evaluation) {
                                            case 'S':
                                                $badgeClass = 'bg-success';
                                                break;
                                            case 'A':
                                                $badgeClass = 'bg-primary';
                                                break;
                                            case 'B':
                                                $badgeClass = 'bg-info';
                                                break;
                                            case 'C':
                                                $badgeClass = 'bg-warning text-dark';
                                                break;
                                            case 'D':
                                                $badgeClass = 'bg-danger';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $grade->evaluation }}</span>
                                </td>
                                <td>{{ $grade->test_date ? $grade->test_date->format('Y年m月d日') : $grade->created_at->format('Y年m月d日') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('school_grades.show', $grade->id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="詳細">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('school_grades.edit', $grade->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="編集">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('school_grades.destroy', $grade->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('この成績を削除してもよろしいですか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="削除">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-clipboard-data display-1 text-muted mb-3"></i>
                <h5 class="text-muted">成績データがありません</h5>
                <p class="text-muted">最初の成績を登録してみましょう。</p>
                <a href="{{ route('school_grades.create') }}" class="btn btn-success">
                    <i class="bi bi-plus me-1"></i>成績を追加
                </a>
            </div>
        @endif
    </div>
</div>
@endsection