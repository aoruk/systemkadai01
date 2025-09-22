<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '学生管理システム')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap Icons -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Hiragino Kaku Gothic Pro', 'ヒラギノ角ゴ Pro W3', Meiryo, メイリオ, Osaka, 'MS PGothic', arial, helvetica, sans-serif;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .main-content {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .btn-custom {
            margin: 5px;
        }
    </style>
</head>
<body>
    <!-- ナビゲーションバー -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('menu') }}">学生管理システム</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('menu') }}">メニュー</a>
                <a class="nav-link" href="{{ route('login') }}">ログイン</a>
            </div>
        </div>
    </nav>

    <!-- メインコンテンツ -->
    <div class="container main-content">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>