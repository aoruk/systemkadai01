<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // ログの基本情報
            $table->string('action', 100)->comment('実行されたアクション');
            $table->string('level', 20)->default('info')->comment('ログレベル（info, warning, error, debug）');
            $table->text('message')->comment('ログメッセージ');
            
            // ユーザー情報
            $table->unsignedBigInteger('user_id')->nullable()->comment('実行ユーザーID');
            $table->string('user_name', 100)->nullable()->comment('実行ユーザー名');
            
            // リクエスト情報
            $table->string('ip_address', 45)->nullable()->comment('IPアドレス');
            $table->string('user_agent', 500)->nullable()->comment('ユーザーエージェント');
            $table->string('url', 500)->nullable()->comment('リクエストURL');
            $table->string('method', 10)->nullable()->comment('HTTPメソッド');
            
            // 実行結果
            $table->boolean('success')->default(true)->comment('処理成功フラグ');
            $table->integer('response_time')->nullable()->comment('処理時間（ミリ秒）');
            
            // 追加データ（JSON形式）
            $table->json('additional_data')->nullable()->comment('追加情報（JSON形式）');
            
            // システム情報
            $table->string('session_id', 100)->nullable()->comment('セッションID');
            $table->decimal('memory_usage', 10, 2)->nullable()->comment('メモリ使用量（MB）');
            
            // タイムスタンプ
            $table->timestamps();
            
            // インデックス設定
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['level', 'created_at']);
            $table->index('created_at');
            
            // 外部キー制約（usersテーブルが存在する場合）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
}
