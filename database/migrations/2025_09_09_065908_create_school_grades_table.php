<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');  // 外部キー用のカラム
            $table->integer('grade')->nullable();                    // 学年
            $table->string('term');                                  // 学期
            $table->integer('japanese')->nullable();                 // 国語
            $table->integer('math')->nullable();                     // 数学
            $table->integer('science')->nullable();                  // 理科
            $table->integer('social_studies')->nullable();           // 社会
            $table->integer('music')->nullable();                    // 音楽
            $table->integer('home_economics')->nullable();           // 家庭科
            $table->integer('english')->nullable();                  // 英語
            $table->integer('art')->nullable();                      // 美術
            $table->integer('health_and_physical_education')->nullable(); // 保健体育
            $table->timestamps();
            
            // 外部キー制約の追加
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_grades');
    }
}
