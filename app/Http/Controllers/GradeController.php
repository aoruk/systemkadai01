<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\SchoolGrade;
use App\Student;
use Exception;

class GradeController extends Controller
{
    /**
     * 学生別成績一覧画面を表示（修正版）
     * URL: /grades/student/{student_id}
     */
    public function index($student_id)
    {
        try {
            // 学生情報を取得
            $student = Student::findOrFail($student_id);
            
            // 該当学生の成績一覧を取得
            $grades = SchoolGrade::where('student_id', $student_id)
                ->orderBy('grade', 'desc')
                ->orderBy('term', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // 統計情報を計算
            $gradeStats = $this->calculateStudentGradeStats($grades);
            
            Log::info('Student grades viewed', [
                'student_id' => $student_id,
                'grade_count' => $grades->count(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return view('grades.index', compact('student', 'grades', 'gradeStats'));
            
        } catch (ModelNotFoundException $e) {
            Log::warning('Student not found for grades', [
                'student_id' => $student_id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '指定された学生が見つかりません。');
                
        } catch (Exception $e) {
            Log::error('Grades index error', [
                'student_id' => $student_id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '成績情報の取得中にエラーが発生しました。');
        }
    }

    /**
     * 成績詳細画面を表示
     */
    public function show($id)
    {
        try {
            $grade = SchoolGrade::with('student')->findOrFail($id);
            
            // 同じ学生の他の成績を取得
            $otherGrades = SchoolGrade::where('student_id', $grade->student_id)
                ->where('id', '!=', $id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            Log::info('Grade detail viewed', [
                'grade_id' => $id,
                'student_id' => $grade->student_id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return view('grades.show', compact('grade', 'otherGrades'));
            
        } catch (ModelNotFoundException $e) {
            Log::warning('Grade not found', [
                'grade_id' => $id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '指定された成績が見つかりません。');
        }
    }

    /**
     * 成績追加画面を表示（修正版）
     * URL: /grades/create?student_id=X
     */
    public function create(Request $request)
    {
        try {
            // クエリパラメータから学生IDを取得
            $studentId = $request->get('student_id');
            
            if (!$studentId) {
                return redirect()->route('students.index')
                    ->with('error', '学生を選択してから成績を登録してください。');
            }
            
            $student = Student::findOrFail($studentId);
            
            // 学生一覧も取得（プルダウン用）
            $students = Student::orderBy('grade')->orderBy('name')->get();
            
            Log::info('Grade create page accessed', [
                'selected_student_id' => $studentId,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return view('grades.create', compact('student', 'students'));
            
        } catch (ModelNotFoundException $e) {
            Log::warning('Student not found for grade creation', [
                'student_id' => $studentId ?? 'null',
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '指定された学生が見つかりません。');
                
        } catch (Exception $e) {
            Log::error('Grade create page error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '成績登録画面の表示中にエラーが発生しました。');
        }
    }

    /**
     * 成績登録処理（修正版）
     * URL: POST /grades
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|exists:students,id',
                'grade' => 'required|integer|between:1,6',
                'term' => 'required|in:1,2,3',
                'japanese' => 'nullable|integer|between:0,100',
                'math' => 'nullable|integer|between:0,100',
                'science' => 'nullable|integer|between:0,100',
                'social_studies' => 'nullable|integer|between:0,100',
                'english' => 'nullable|integer|between:0,100',
                'music' => 'nullable|integer|between:0,100',
                'art' => 'nullable|integer|between:0,100',
                'home_economics' => 'nullable|integer|between:0,100',
                'health_and_physical_education' => 'nullable|integer|between:0,100',
            ]);

            $studentId = $validatedData['student_id'];
            
            // 重複チェック
            $existingGrade = SchoolGrade::where('student_id', $studentId)
                ->where('grade', $validatedData['grade'])
                ->where('term', $validatedData['term'])
                ->first();

            if ($existingGrade) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['duplicate' => 'この学生の同じ学年・学期の成績は既に登録されています。']);
            }

            $grade = SchoolGrade::create($validatedData);
            
            Log::info('Grade created', [
                'grade_id' => $grade->id,
                'student_id' => $studentId,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('grades.show', $grade->id)
                ->with('success', '成績を登録しました');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (Exception $e) {
            Log::error('Grade store error', [
                'error' => $e->getMessage(),
                'input' => $request->all(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->back()
                ->with('error', '成績登録中にエラーが発生しました。')
                ->withInput();
        }
    }

    /**
     * 成績編集画面を表示
     */
    public function edit($id)
    {
        try {
            $grade = SchoolGrade::with('student')->findOrFail($id);
            
            Log::info('Grade edit page accessed', [
                'grade_id' => $id,
                'student_id' => $grade->student_id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return view('grades.edit', compact('grade'));
            
        } catch (ModelNotFoundException $e) {
            Log::warning('Grade not found for edit', [
                'grade_id' => $id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '指定された成績が見つかりません。');
        }
    }

    /**
     * 成績更新処理
     */
    public function update(Request $request, $id)
    {
        try {
            $grade = SchoolGrade::findOrFail($id);
            
            $validatedData = $request->validate([
                'grade' => 'required|integer|between:1,6',
                'term' => 'required|in:1,2,3',
                'japanese' => 'nullable|integer|between:0,100',
                'math' => 'nullable|integer|between:0,100',
                'science' => 'nullable|integer|between:0,100',
                'social_studies' => 'nullable|integer|between:0,100',
                'english' => 'nullable|integer|between:0,100',
                'music' => 'nullable|integer|between:0,100',
                'art' => 'nullable|integer|between:0,100',
                'home_economics' => 'nullable|integer|between:0,100',
                'health_and_physical_education' => 'nullable|integer|between:0,100',
            ]);

            // 重複チェック（自分以外のレコード）
            $existingGrade = SchoolGrade::where('student_id', $grade->student_id)
                ->where('grade', $validatedData['grade'])
                ->where('term', $validatedData['term'])
                ->where('id', '!=', $id)
                ->first();

            if ($existingGrade) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['duplicate' => 'この学生の同じ学年・学期の成績は既に登録されています。']);
            }

            $grade->update($validatedData);
            
            Log::info('Grade updated', [
                'grade_id' => $id,
                'student_id' => $grade->student_id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('grades.show', $id)
                ->with('success', '成績を更新しました');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (ModelNotFoundException $e) {
            Log::warning('Grade not found for update', [
                'grade_id' => $id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '指定された成績が見つかりません。');
                
        } catch (Exception $e) {
            Log::error('Grade update error', [
                'grade_id' => $id,
                'error' => $e->getMessage(),
                'input' => $request->all(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->back()
                ->with('error', '成績更新中にエラーが発生しました。')
                ->withInput();
        }
    }

    /**
     * 成績削除処理
     */
    public function destroy($id)
    {
        try {
            $grade = SchoolGrade::findOrFail($id);
            $studentId = $grade->student_id;
            
            $grade->delete();
            
            Log::info('Grade deleted', [
                'grade_id' => $id,
                'student_id' => $studentId,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('grades.index', $studentId)
                ->with('success', '成績を削除しました');
                
        } catch (ModelNotFoundException $e) {
            Log::warning('Grade not found for delete', [
                'grade_id' => $id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->route('students.index')
                ->with('error', '指定された成績が見つかりません。');
                
        } catch (Exception $e) {
            Log::error('Grade delete error', [
                'grade_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            return redirect()->back()
                ->with('error', '成績削除中にエラーが発生しました。');
        }
    }

    /**
     * 学生成績統計を計算
     */
    private function calculateStudentGradeStats($grades)
    {
        if ($grades->isEmpty()) {
            return [
                'total_terms' => 0,
                'overall_average' => 0,
                'best_term' => null,
                'term_stats' => []
            ];
        }
        
        $subjects = ['japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'];
        
        $termStats = [];
        
        foreach ($grades as $grade) {
            $termTotal = 0;
            $termCount = 0;
            
            foreach ($subjects as $subject) {
                if ($grade->$subject !== null) {
                    $termTotal += $grade->$subject;
                    $termCount++;
                }
            }
            
            if ($termCount > 0) {
                $termAverage = round($termTotal / $termCount, 1);
                $termStats[] = [
                    'grade' => $grade->grade,
                    'term' => $grade->term,
                    'average' => $termAverage,
                    'total' => $termTotal,
                    'subject_count' => $termCount,
                    'created_at' => $grade->created_at
                ];
            }
        }
        
        // 全体平均を計算
        $overallAverage = 0;
        if (!empty($termStats)) {
            $totalAverage = array_sum(array_column($termStats, 'average'));
            $overallAverage = round($totalAverage / count($termStats), 1);
        }
        
        // 最高成績学期を特定
        $bestTerm = null;
        if (!empty($termStats)) {
            $bestTerm = collect($termStats)->sortByDesc('average')->first();
        }
        
        return [
            'total_terms' => count($termStats),
            'overall_average' => $overallAverage,
            'best_term' => $bestTerm,
            'term_stats' => $termStats
        ];
    }

    /**
     * 点数を評価に変換するヘルパーメソッド
     */
    public static function convertScoreToGrade($score)
    {
        if ($score === null) {
            return '-';
        }
        
        if ($score >= 90) {
            return 'S';
        } elseif ($score >= 80) {
            return 'A';
        } elseif ($score >= 70) {
            return 'B';
        } elseif ($score >= 60) {
            return 'C';
        } else {
            return 'D';
        }
    }

    /**
     * 平均点を計算するヘルパーメソッド
     */
    public static function calculateAverage($grade)
    {
        $subjects = [
            'japanese', 'math', 'science', 'social_studies', 'english',
            'music', 'art', 'home_economics', 'health_and_physical_education'
        ];
        
        $total = 0;
        $count = 0;
        
        foreach ($subjects as $subject) {
            if ($grade->$subject !== null) {
                $total += $grade->$subject;
                $count++;
            }
        }
        
        return $count > 0 ? round($total / $count, 1) : 0;
    }
}