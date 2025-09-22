<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * 学生一覧表示（画面No.4: 学生表示画面）
     * 仕様書準拠：検索機能付き
     */
    public function index(Request $request)
    {
        try {
            // 検索条件の取得
            $searchName = $request->get('search_name');
            $searchGrade = $request->get('search_grade');

            // クエリビルダーで検索処理
            $query = DB::table('students');

            // 学生名での検索（部分一致）
            if (!empty($searchName)) {
                $query->where('name', 'LIKE', '%' . $searchName . '%');
            }

            // 学年での検索（完全一致）
            if (!empty($searchGrade)) {
                $query->where('grade', $searchGrade);
            }

            // データ取得（学年、名前順でソート）
            $students = $query->orderBy('grade')
                            ->orderBy('name')
                            ->get();

            // 学年の選択肢を動的に取得
            $availableGrades = DB::table('students')
                                ->select('grade')
                                ->distinct()
                                ->orderBy('grade')
                                ->pluck('grade');

            // システムログに記録
            $this->logSystemAction('students.index', [
                'search_name' => $searchName,
                'search_grade' => $searchGrade,
                'result_count' => $students->count()
            ]);

            return view('students.index', compact('students', 'availableGrades', 'searchName', 'searchGrade'));

        } catch (\Exception $e) {
            Log::error('学生一覧表示エラー: ' . $e->getMessage());
            return redirect()->route('menu')->with('error', '学生一覧の表示中にエラーが発生しました。');
        }
    }

    /**
     * 学生登録画面表示（画面No.5: 学生登録画面）
     */
    public function create()
    {
        try {
            return view('students.create');
        } catch (\Exception $e) {
            Log::error('学生登録画面表示エラー: ' . $e->getMessage());
            return redirect()->route('menu')->with('error', '学生登録画面の表示中にエラーが発生しました。');
        }
    }

    /**
     * 学生新規登録処理
     * 仕様書準拠：学年、名前、住所、顔写真、コメント
     */
    public function store(Request $request)
    {
        try {
            // バリデーション
            $validatedData = $request->validate([
                'grade' => 'required|integer|min:1|max:6',
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'comment' => 'nullable|string|max:1000',
            ], [
                'grade.required' => '学年を選択してください。',
                'grade.integer' => '学年は数値で入力してください。',
                'grade.min' => '学年は1年以上を選択してください。',
                'grade.max' => '学年は6年以下を選択してください。',
                'name.required' => '名前を入力してください。',
                'name.max' => '名前は255文字以内で入力してください。',
                'address.required' => '住所を入力してください。',
                'address.max' => '住所は500文字以内で入力してください。',
                'img_path.image' => 'ファイルは画像である必要があります。',
                'img_path.mimes' => '画像はjpeg、png、jpg、gif形式のみ対応しています。',
                'img_path.max' => '画像ファイルは2MB以下にしてください。',
                'comment.max' => 'コメントは1000文字以内で入力してください。',
            ]);

            // 画像アップロード処理
            $imgPath = null;
            if ($request->hasFile('img_path')) {
                $file = $request->file('img_path');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $imgPath = $file->storeAs('students', $fileName, 'public');
            }

            // データベースに挿入
            $studentId = DB::table('students')->insertGetId([
                'grade' => $validatedData['grade'],
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'img_path' => $imgPath,
                'comment' => $validatedData['comment'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // システムログに記録
            $this->logSystemAction('students.store', [
                'student_id' => $studentId,
                'name' => $validatedData['name'],
                'grade' => $validatedData['grade']
            ]);

            return redirect()->route('students.index')->with('success', '学生を登録しました。');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('学生登録エラー: ' . $e->getMessage());
            return back()->with('error', '学生登録中にエラーが発生しました。')->withInput();
        }
    }

    /**
     * 学生詳細表示（学生詳細表示画面）
     */
    public function show($id)
    {
        try {
            $student = DB::table('students')->where('id', $id)->first();
            
            if (!$student) {
                return redirect()->route('students.index')->with('error', '指定された学生が見つかりません。');
            }

            // システムログに記録
            $this->logSystemAction('students.show', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);

            return view('students.show', compact('student'));

        } catch (\Exception $e) {
            Log::error('学生詳細表示エラー: ' . $e->getMessage());
            return redirect()->route('students.index')->with('error', '学生詳細の表示中にエラーが発生しました。');
        }
    }

    /**
     * 学生編集画面表示（画面No.6: 学生編集画面）
     */
    public function edit($id)
    {
        try {
            $student = DB::table('students')->where('id', $id)->first();
            
            if (!$student) {
                return redirect()->route('students.index')->with('error', '指定された学生が見つかりません。');
            }

            return view('students.edit', compact('student'));

        } catch (\Exception $e) {
            Log::error('学生編集画面表示エラー: ' . $e->getMessage());
            return redirect()->route('students.index')->with('error', '学生編集画面の表示中にエラーが発生しました。');
        }
    }

    /**
     * 学生データ更新処理
     */
    public function update(Request $request, $id)
    {
        try {
            // 学生の存在確認
            $student = DB::table('students')->where('id', $id)->first();
            if (!$student) {
                return redirect()->route('students.index')->with('error', '指定された学生が見つかりません。');
            }

            // バリデーション
            $validatedData = $request->validate([
                'grade' => 'required|integer|min:1|max:6',
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'comment' => 'nullable|string|max:1000',
            ]);

            // 画像アップロード処理
            $imgPath = $student->img_path; // 既存の画像パスを保持
            if ($request->hasFile('img_path')) {
                // 古い画像を削除
                if ($student->img_path && Storage::disk('public')->exists($student->img_path)) {
                    Storage::disk('public')->delete($student->img_path);
                }

                // 新しい画像をアップロード
                $file = $request->file('img_path');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $imgPath = $file->storeAs('students', $fileName, 'public');
            }

            // データベースを更新
            DB::table('students')->where('id', $id)->update([
                'grade' => $validatedData['grade'],
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'img_path' => $imgPath,
                'comment' => $validatedData['comment'],
                'updated_at' => now(),
            ]);

            // システムログに記録
            $this->logSystemAction('students.update', [
                'student_id' => $id,
                'name' => $validatedData['name'],
                'grade' => $validatedData['grade']
            ]);

            return redirect()->route('students.index')->with('success', '学生情報を更新しました。');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('学生更新エラー: ' . $e->getMessage());
            return back()->with('error', '学生情報の更新中にエラーが発生しました。')->withInput();
        }
    }

    /**
     * 学生削除処理
     */
    public function destroy($id)
    {
        try {
            $student = DB::table('students')->where('id', $id)->first();
            
            if (!$student) {
                return redirect()->route('students.index')->with('error', '指定された学生が見つかりません。');
            }

            // 画像ファイルを削除
            if ($student->img_path && Storage::disk('public')->exists($student->img_path)) {
                Storage::disk('public')->delete($student->img_path);
            }

            // 関連する成績データも削除（もしあれば）
            DB::table('grades')->where('student_id', $id)->delete();

            // 学生データを削除
            DB::table('students')->where('id', $id)->delete();

            // システムログに記録
            $this->logSystemAction('students.destroy', [
                'student_id' => $id,
                'student_name' => $student->name
            ]);

            return redirect()->route('students.index')->with('success', '学生データを削除しました。');

        } catch (\Exception $e) {
            Log::error('学生削除エラー: ' . $e->getMessage());
            return redirect()->route('students.index')->with('error', '学生削除中にエラーが発生しました。');
        }
    }

    /**
     * システムログ記録
     */
    private function logSystemAction($action, $details = [])
    {
        try {
            DB::table('system_logs')->insert([
                'user_id' => auth()->id(),
                'action' => $action,
                'details' => json_encode($details),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('システムログ記録エラー: ' . $e->getMessage());
        }
    }
}