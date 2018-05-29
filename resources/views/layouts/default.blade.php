<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Micro_blog') - 你的世界</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    {{-------------引入头部------------}}
        @include('layouts._header')

    <div class="container">
        @yield('content')
    </div>

    {{-------------引入尾部------------}}
        @include('layouts._footer')
</body>
</html>
