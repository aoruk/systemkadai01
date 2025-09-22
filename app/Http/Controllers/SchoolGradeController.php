<?php

namespace App\Http\Controllers;

use App\SchoolGrade;
use App\Student;
use Illuminate\Http\Request;

class SchoolGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     * 成績一覧画面
     */
    public function index()
    {
        $schoolGrades = SchoolGrade::with('student')
            ->orderBy('grade', 'desc')
            ->orderBy('term', 'desc')
            ->paginate(10);
            
        return view('school_grades.index', compact('schoolGrades'));
    }

    /**
     * Show the form for creating a new resource.
     * 成績新規登録画面
     */
    public function create()
    {
        $students = Student::orderBy('grade')->orderBy('name')->get();
        
        return view('school_grades.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     * 成績新規保存処理
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'grade' => 'required|integer|between:1,6',
            'term' => 'required|integer|between:1,3',
            'japanese' => 'nullable|integer|between:0,100',
            'math' => 'nullable|integer|between:0,100',
            'science' => 'nullable|integer|between:0,100',
            'social_studies' => 'nullable|integer|between:0,100',
            'music' => 'nullable|integer|between:0,100',
            'home_economics' => 'nullable|integer|between:0,100',
            'english' => 'nullable|integer|between:0,100',
            'art' => 'nullable|integer|between:0,100',
            'health_and_physical_education' => 'nullable|integer|between:0,100'
        ]);

        // 同じ学生の同じ学年・学期のデータが既に存在するかチェック
        $existingGrade = SchoolGrade::where('student_id', $validatedData['student_id'])
            ->where('grade', $validatedData['grade'])
            ->where('term', $validatedData['term'])
            ->first();

        if ($existingGrade) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'この学生の同じ学年・学期の成績は既に登録されています。']);
        }

        SchoolGrade::create($validatedData);

        return redirect()->route('school_grades.index')
            ->with('success', '成績が正常に登録されました。');
    }

    /**
     * Display the specified resource.
     * 成績詳細表示画面
     */
    public function show($id)
    {
        $schoolGrade = SchoolGrade::with('student')->findOrFail($id);
        
        return view('school_grades.show', compact('schoolGrade'));
    }

    /**
     * Show the form for editing the specified resource.
     * 成績編集画面
     */
    public function edit($id)
    {
        $schoolGrade = SchoolGrade::findOrFail($id);
        $students = Student::orderBy('grade')->orderBy('name')->get();
        
        return view('school_grades.edit', compact('schoolGrade', 'students'));
    }

    /**
     * Update the specified resource in storage.
     * 成績更新処理
     */
    public function update(Request $request, $id)
    {
        $schoolGrade = SchoolGrade::findOrFail($id);
        
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'grade' => 'required|integer|between:1,6',
            'term' => 'required|integer|between:1,3',
            'japanese' => 'nullable|integer|between:0,100',
            'math' => 'nullable|integer|between:0,100',
            'science' => 'nullable|integer|between:0,100',
            'social_studies' => 'nullable|integer|between:0,100',
            'music' => 'nullable|integer|between:0,100',
            'home_economics' => 'nullable|integer|between:0,100',
            'english' => 'nullable|integer|between:0,100',
            'art' => 'nullable|integer|between:0,100',
            'health_and_physical_education' => 'nullable|integer|between:0,100'
        ]);

        // 重複チェック（自分以外のレコード）
        $existingGrade = SchoolGrade::where('student_id', $validatedData['student_id'])
            ->where('grade', $validatedData['grade'])
            ->where('term', $validatedData['term'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingGrade) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'この学生の同じ学年・学期の成績は既に登録されています。']);
        }

        $schoolGrade->update($validatedData);

        return redirect()->route('school_grades.index')
            ->with('success', '成績が正常に更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     * 成績削除処理
     */
    public function destroy($id)
    {
        $schoolGrade = SchoolGrade::findOrFail($id);
        $schoolGrade->delete();

        return redirect()->route('school_grades.index')
            ->with('success', '成績が正常に削除されました。');
    }

    /**
     * 学生別成績一覧
     */
    public function studentGrades($studentId)
    {
        $student = Student::findOrFail($studentId);
        $schoolGrades = SchoolGrade::where('student_id', $studentId)
            ->orderBy('grade', 'desc')
            ->orderBy('term', 'desc')
            ->get();

        return view('school_grades.student_grades', compact('student', 'schoolGrades'));
    }

    /**
     * 学年・学期別成績一覧
     */
    public function gradeTermList($grade, $term)
    {
        $schoolGrades = SchoolGrade::with('student')
            ->where('grade', $grade)
            ->where('term', $term)
            ->orderBy('student_id')
            ->get();

        return view('school_grades.grade_term_list', compact('schoolGrades', 'grade', 'term'));
    }

    /**
     * 点数を5段階評価に変換するヘルパーメソッド
     */
    private function convertScoreToGrade($score)
    {
        if ($score === null) return null;
        if ($score >= 90) return 'S';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        return 'D';
    }

    /**
     * 平均点を計算するヘルパーメソッド
     */
    private function calculateAverage($schoolGrade)
    {
        $subjects = [
            'japanese', 'math', 'science', 'social_studies', 'music',
            'home_economics', 'english', 'art', 'health_and_physical_education'
        ];
        
        $scores = [];
        foreach ($subjects as $subject) {
            if ($schoolGrade->$subject !== null) {
                $scores[] = $schoolGrade->$subject;
            }
        }
        
        return !empty($scores) ? round(array_sum($scores) / count($scores), 1) : 0;
    }
}