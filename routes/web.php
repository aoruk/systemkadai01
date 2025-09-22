<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Laravel 6系では resource ルートを最初に定義
Route::resource('students', 'StudentController');

// ==============================
// 認証不要のルート（ログイン前）
// ==============================

// トップページ → ログイン画面へリダイレクト
Route::get('/', 'AuthController@login')->name('login');

// 管理ユーザーログイン処理
Route::post('/login', 'AuthController@authenticate')->name('authenticate');

// 管理ユーザー新規登録画面
Route::get('/register', 'AuthController@register')->name('register');
Route::post('/register', 'AuthController@store')->name('register.store');

// ==============================
// 認証が必要なルート（ログイン後）
// ==============================
Route::middleware('auth')->group(function () {
    
    // ----- メニュー関連 -----
    // メインメニュー画面
    Route::get('/menu', 'MenuController@index')->name('menu');
    
    // ----- Phase 2: メニュー画面拡張機能 -----
    
    // 学年更新機能（AJAX）
    Route::post('/menu/update-year', 'MenuController@updateYear')->name('menu.updateYear');
    
    // システム統計情報取得（AJAX）
    Route::get('/menu/stats', 'MenuController@getDetailedStats')->name('menu.stats');
    
    // システム詳細情報取得（AJAX）
    Route::get('/menu/system-info', 'MenuController@getSystemInfo')->name('menu.systemInfo');
    
    // ヘルスチェック（AJAX）
    Route::get('/menu/health', 'MenuController@healthCheck')->name('menu.health');
    
    // システムログ機能
    Route::get('/menu/logs', 'MenuController@getLogs')->name('menu.logs');
    Route::delete('/menu/logs/clear', 'MenuController@clearLogs')->name('menu.clearLogs');
    
    // データベース最適化
    Route::post('/menu/optimize', 'MenuController@optimizeDatabase')->name('menu.optimize');
    
    // バックアップ機能
    Route::post('/menu/backup', 'MenuController@createBackup')->name('menu.backup');
    
    // ----- API関連（メニュー画面で使用） -----
    Route::prefix('api/menu')->group(function () {
        // リアルタイム統計
        Route::get('/real-time-stats', 'MenuController@getRealTimeStats')->name('api.menu.realTimeStats');
        
        // システム状態監視
        Route::get('/system-status', 'MenuController@getSystemStatus')->name('api.menu.systemStatus');
        
        // 最新活動ログ
        Route::get('/recent-activities', 'MenuController@getRecentActivities')->name('api.menu.recentActivities');
    });

    // ----- 学生管理関連 -----
    // 学生一覧表示画面
    Route::get('/students', 'StudentController@index')->name('students.index');
    
    // 学生新規登録画面
    Route::get('/students/create', 'StudentController@create')->name('students.create');
    Route::post('/students', 'StudentController@store')->name('students.store');
    
    // 学生詳細表示画面
    Route::get('/students/{id}', 'StudentController@show')->name('students.show');
    
    // 学生編集画面
    Route::get('/students/{id}/edit', 'StudentController@edit')->name('students.edit');
    Route::put('/students/{id}', 'StudentController@update')->name('students.update');
    
    // 学生削除処理
    Route::delete('/students/{id}', 'StudentController@destroy')->name('students.destroy');
    
    // ----- 成績管理関連（修正版） -----
    // 成績登録画面（学生ID指定）
    Route::get('/grades/create', 'GradeController@create')->name('grades.create');
    Route::post('/grades', 'GradeController@store')->name('grades.store');
    
    // 学生別成績一覧
    Route::get('/grades/student/{student_id}', 'GradeController@index')->name('grades.index');
    
    // 成績詳細・編集・削除
    Route::get('/grades/{id}', 'GradeController@show')->name('grades.show');
    Route::get('/grades/{id}/edit', 'GradeController@edit')->name('grades.edit');
    Route::put('/grades/{id}', 'GradeController@update')->name('grades.update');
    Route::delete('/grades/{id}', 'GradeController@destroy')->name('grades.destroy');
    
    // ----- 旧ルート（削除または統合） -----
    // 個別学生の成績管理関連（既存のルートと重複するため削除）
    /*
    Route::get('/students/{id}/grades', 'GradeController@index')->name('grades.index.old');
    Route::get('/students/{id}/grades/create', 'GradeController@create')->name('grades.create.old');
    Route::post('/students/{id}/grades', 'GradeController@store')->name('grades.store.old');
    */
    
    // ----- 全体成績管理関連（新規追加） -----
    // 全学生の成績一覧表示
    Route::get('/school-grades', 'SchoolGradeController@index')->name('school_grades.index');
    
    // 全体成績管理から新規成績登録
    Route::get('/school-grades/create', 'SchoolGradeController@create')->name('school_grades.create');
    Route::post('/school-grades', 'SchoolGradeController@store')->name('school_grades.store');
    
    // 全体成績詳細表示
    Route::get('/school-grades/{id}', 'SchoolGradeController@show')->name('school_grades.show');
    
    // 全体成績編集
    Route::get('/school-grades/{id}/edit', 'SchoolGradeController@edit')->name('school_grades.edit');
    Route::put('/school-grades/{id}', 'SchoolGradeController@update')->name('school_grades.update');
    
    // 全体成績削除
    Route::delete('/school-grades/{id}', 'SchoolGradeController@destroy')->name('school_grades.destroy');
    
    // ----- 全体成績管理関連（追加機能） -----
    // 学生別成績一覧表示
    Route::get('/school-grades/student/{student}', 'SchoolGradeController@studentGrades')->name('school_grades.student_grades');
    
    // 学年・学期別成績一覧表示
    Route::get('/school-grades/grade/{grade}/term/{term}', 'SchoolGradeController@gradeTermList')->name('school_grades.grade_term_list');

    // ----- 認証関連 -----
    // ログアウト処理
    Route::post('/logout', 'AuthController@logout')->name('logout');
});

// ==============================
// ルート構成説明（更新版）
// ==============================
/*
画面遷移の流れ：

1. 【認証前】
   / → login画面
   /register → 新規登録画面

2. 【認証後メイン】
   /menu → メインメニュー

3. 【学生管理】
   /students → 学生一覧
   /students/create → 学生登録
   /students/{id} → 学生詳細
   /students/{id}/edit → 学生編集

4. 【成績管理】★修正版
   /grades/create?student_id=X → 成績登録画面（学生ID指定）
   /grades/student/{id} → 特定学生の成績一覧
   /grades/{id} → 成績詳細表示
   /grades/{id}/edit → 成績編集

5. 【全体成績管理】
   /school-grades → 全学生成績一覧（メニューからアクセス）
   /school-grades/create → 成績一括登録
   /school-grades/{id} → 成績詳細
   /school-grades/{id}/edit → 成績編集

実際のアクセスURL：
- 成績追加: /grades/create?student_id=1
- 成績一覧: /grades/student/1
*/