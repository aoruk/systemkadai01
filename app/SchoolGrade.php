<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    /**
     * テーブル名
     */
    protected $table = 'school_grades';

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
        'student_id',
        'grade',
        'term',
        'japanese',
        'math',
        'science',
        'social_studies',
        'music',
        'home_economics',
        'english',
        'art',
        'health_and_physical_education'
    ];

    /**
     * 日付として扱う属性
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * 数値として扱う属性
     */
    protected $casts = [
        'student_id' => 'integer',
        'grade' => 'integer',
        'term' => 'integer',
        'japanese' => 'integer',
        'math' => 'integer',
        'science' => 'integer',
        'social_studies' => 'integer',
        'music' => 'integer',
        'home_economics' => 'integer',
        'english' => 'integer',
        'art' => 'integer',
        'health_and_physical_education' => 'integer'
    ];

    /**
     * 学生との関連（多対1）
     * 1つの成績は1人の学生に属する
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * 学期の表示用メソッド
     */
    public function getTermDisplayAttribute()
    {
        return $this->term . '学期';
    }

    /**
     * 学年・学期の表示用メソッド
     */
    public function getGradeTermDisplayAttribute()
    {
        return $this->grade . '年生 ' . $this->term . '学期';
    }

    /**
     * 全教科の平均点を計算
     */
    public function getAverageScoreAttribute()
    {
        $subjects = [
            'japanese', 'math', 'science', 'social_studies', 
            'music', 'home_economics', 'english', 'art', 
            'health_and_physical_education'
        ];
        
        $total = 0;
        $count = 0;
        
        foreach ($subjects as $subject) {
            if (!is_null($this->$subject)) {
                $total += $this->$subject;
                $count++;
            }
        }
        
        return $count > 0 ? round($total / $count, 1) : 0;
    }

    /**
     * 教科名の配列を取得
     */
    public static function getSubjects()
    {
        return [
            'japanese' => '国語',
            'math' => '数学',
            'science' => '理科',
            'social_studies' => '社会',
            'music' => '音楽',
            'home_economics' => '家庭科',
            'english' => '英語',
            'art' => '美術',
            'health_and_physical_education' => '保健体育'
        ];
    }

    /**
     * 教科の日本語名を取得
     */
    public function getSubjectName($subject)
    {
        $subjects = self::getSubjects();
        return isset($subjects[$subject]) ? $subjects[$subject] : $subject;
    }
}