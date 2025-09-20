<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>学生管理システム - メニュー</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 48px;
                margin-bottom: 50px;
            }

            .menu-buttons {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }

            .menu-buttons a {
                display: inline-block;
                background-color: #3490dc;
                color: white;
                padding: 15px 30px;
                font-size: 18px;
                font-weight: 600;
                text-decoration: none;
                border-radius: 8px;
                min-width: 200px;
                transition: background-color 0.3s;
            }

            .menu-buttons a:hover {
                background-color: #2779bd;
            }

            .register-btn {
                background-color: #38c172 !important;
            }

            .register-btn:hover {
                background-color: #32a562 !important;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-right">
                <a href="{{ route('login') }}" style="color: #636b6f; text-decoration: none;">ログイン</a>
            </div>

            <div class="content">
                <div class="title">
                    学生管理システム<br>メニュー画面
                </div>

                <div class="menu-buttons">
                    <a href="{{ route('register') }}" class="register-btn">新規登録</a>
                    <a href="{{ route('students.create') }}">学生登録</a>
                    <a href="{{ route('students.index') }}">学生表示</a>
                </div>
            </div>
        </div>
    </body>
</html>
