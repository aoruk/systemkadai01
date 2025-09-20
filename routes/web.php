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

// welcomeページをメニュー画面として使用
Route::get('/', function () {
    return view('welcome');
})->name('menu');

// 管理ユーザーログイン画面
Route::get('/login', function () {
    return view('login');
})->name('login');

// 管理ユーザー新規登録画面
Route::get('/register', function () {
    return view('register');
})->name('register');

// 学生登録画面
Route::get('/students/create', function () {
    return view('students.create');
})->name('students.create');

// 学生表示画面
Route::get('/students', function () {
    return view('students.index');
})->name('students.index');

// 学生詳細表示画面
Route::get('/students/{id}', function ($id) {
    return view('students.show', compact('id'));
})->name('students.show');

// 学生編集画面
Route::get('/students/{id}/edit', function ($id) {
    return view('students.edit', compact('id'));
})->name('students.edit');

// 学生成績追加画面
Route::get('/students/{id}/grades/create', function ($id) {
    return view('grades.create', compact('id'));
})->name('grades.create');

// 成績編集画面
Route::get('/grades/{id}/edit', function ($id) {
    return view('grades.edit', compact('id'));
})->name('grades.edit');