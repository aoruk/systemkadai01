<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * テーブル名
     */
    protected $table = 'students';

    /**
     * 主キー
     */
    protected $primaryKey = 'id';

    /**
     * タイムスタンプの使用
     */
    public $timestamps = true;

    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'grade',
        'name',
        'address',
        'img_path',
        'comment'
    ];

    /**
     * 日付として扱う属性
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * 成績との関連（1対多）
     * 1人の学生は複数の成績を持つ
     */
    public function schoolGrades()
    {
        return $this->hasMany(SchoolGrade::class, 'student_id');
    }

    /**
     * エイリアス: $student->grades でもアクセスできるようにする
     */
    public function grades()
    {
        return $this->schoolGrades();
    }

    /**
     * 最新の成績を取得
     */
    public function latestGrade()
    {
        return $this->hasOne(SchoolGrade::class, 'student_id')->latest();
    }

    /**
     * 学年の表示用メソッド
     */
    public function getGradeDisplayAttribute()
    {
        return $this->grade . '年生';
    }

    /**
     * 画像パスの取得（デフォルト画像対応）
     */
    public function getImageUrlAttribute()
    {
        if ($this->img_path && file_exists(public_path('storage/' . $this->img_path))) {
            return asset('storage/' . $this->img_path);
        }
        return asset('images/default-student.png'); // デフォルト画像
    }
}
