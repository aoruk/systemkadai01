<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTableForSpecification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // 既存のemail, class_nameカラムを削除（データがある場合は注意）
            if (Schema::hasColumn('students', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('students', 'class_name')) {
                $table->dropColumn('class_name');
            }
            
            // gradeを数値型に変更（現在はstring）
            $table->integer('grade')->change();
            
            // 仕様書に合わせてカラムを追加（既存の場合はスキップ）
            if (!Schema::hasColumn('students', 'img_path')) {
                $table->string('img_path')->nullable()->after('address');
            }
            if (!Schema::hasColumn('students', 'comment')) {
                $table->text('comment')->nullable()->after('img_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // ロールバック時の処理
            $table->string('email')->nullable();
            $table->string('class_name')->nullable();
            $table->string('grade')->change();
        });
    }
}
