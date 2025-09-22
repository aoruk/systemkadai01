<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuController extends Controller
{
    /**
     * メニュー画面表示
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // 現在の年度情報を取得
            $currentYear = session('current_year', date('Y'));
            
            // 基本統計情報の取得（エラー回避版）
            $stats = $this->getBasicStats();
            
            return view('menu', [
                'currentYear' => $currentYear,
                'statistics' => $stats,
                'user' => Auth::user(),
                'studentCount' => $stats['student_count'],
                'gradeCount' => $stats['grade_count'],
                'userCount' => $stats['user_count'] ?? 1,
                'averageGrade' => round($stats['average_score'], 1),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('メニュー画面表示エラー: ' . $e->getMessage());
            
            return view('menu', [
                'currentYear' => date('Y'),
                'statistics' => $this->getDefaultStats(),
                'user' => Auth::user(),
                'studentCount' => 0,
                'gradeCount' => 0,
                'userCount' => 1,
                'averageGrade' => 0,
            ]);
        }
    }

    /**
     * 学年更新処理
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateYear(Request $request)
    {
        try {
            // バリデーション（フィールド名をyearに修正）
            $request->validate([
                'year' => 'required|integer|min:2020|max:2030'
            ]);
            
            $year = $request->input('year');
            
            // セッションに年度を保存
            session(['current_year' => $year]);
            
            // ログ記録（system_logsテーブルがある場合）
            $this->logAction('year_update', "{$year}年度に更新", true);
            
            return response()->json([
                'success' => true,
                'message' => "{$year}年度に更新しました",
                'year' => $year
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '年度更新に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 基本統計情報の取得（エラー回避版）
     * 
     * @return array
     */
    private function getBasicStats()
    {
        $stats = [];
        
        try {
            // 学生数の取得（studentsテーブルが存在する場合）
            try {
                $stats['student_count'] = DB::table('students')->count();
            } catch (\Exception $e) {
                $stats['student_count'] = 0;
            }
            
            // 科目数の取得（subjectsテーブルが存在する場合）
            try {
                $stats['subject_count'] = DB::table('subjects')->count();
            } catch (\Exception $e) {
                $stats['subject_count'] = 0;
            }
            
            // 成績データの取得（gradesテーブルが存在する場合）
            try {
                $stats['grade_count'] = DB::table('grades')->count();
                $stats['average_score'] = DB::table('grades')->avg('score') ?? 0;
            } catch (\Exception $e) {
                $stats['grade_count'] = 0;
                $stats['average_score'] = 0;
            }
            
            // ユーザー数の取得
            try {
                $stats['user_count'] = DB::table('users')->count();
            } catch (\Exception $e) {
                $stats['user_count'] = 1;
            }
            
        } catch (\Exception $e) {
            // 全体でエラーが発生した場合はデフォルト値を返す
            return $this->getDefaultStats();
        }
        
        // システム情報の追加
        $stats['current_year'] = date('Y');
        $stats['current_month'] = date('n');
        $stats['system_status'] = 'running';
        $stats['last_login'] = Auth::user()->created_at ?? now();
        
        return $stats;
    }

    /**
     * デフォルト統計情報
     * 
     * @return array
     */
    private function getDefaultStats()
    {
        return [
            'student_count' => 0,
            'subject_count' => 0,
            'grade_count' => 0,
            'average_score' => 0,
            'user_count' => 1,
            'current_year' => date('Y'),
            'current_month' => date('n'),
            'system_status' => 'running',
            'last_login' => now(),
        ];
    }

    /**
     * システム情報取得API
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSystemInfo()
    {
        try {
            $stats = $this->getBasicStats();
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => now()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'システム情報の取得に失敗しました',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ログアウト処理
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        try {
            // ログ記録
            $this->logAction('logout', 'ユーザーがログアウトしました', true);
            
            Auth::logout();
            session()->flush();
            session()->regenerate();
            
            return redirect('/login')->with('message', 'ログアウトしました');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'ログアウト処理でエラーが発生しました');
        }
    }

    /**
     * アクションログの記録
     * 
     * @param string $action
     * @param string $message
     * @param bool $success
     * @return void
     */
    private function logAction($action, $message, $success = true)
    {
        try {
            // system_logsテーブルが存在する場合のみログを記録
            DB::table('system_logs')->insert([
                'action' => $action,
                'level' => 'info',
                'message' => $message,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'ip_address' => request()->ip(),
                'url' => request()->url(),
                'method' => request()->method(),
                'success' => $success,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // ログテーブルが存在しない場合はLaravelログに記録
            \Log::info("Action: {$action}, Message: {$message}, Success: {$success}");
        }
    }

    /**
     * システムの稼働時間を取得
     * 
     * @return string
     */
    private function getSystemUptime()
    {
        try {
            // 簡易的な稼働時間（サーバー起動からの時間）
            $uptime = sys_getloadavg();
            return '稼働中';
        } catch (\Exception $e) {
            return '不明';
        }
    }
}