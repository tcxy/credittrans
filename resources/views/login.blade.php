<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
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
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
        function getQuestions() {
            $.get("{{ route('admin.getquestions') }}", function (data, status) {
                if (data['code'] == '001') {
                    // alert(data['data'].length);
                    $.each(data['data'], function (index, value) {
                        alert(index + ' ' + value);
                    });
                }
            });
        }
    </script>
</head>
<body onload="getQuestions()">
<div class="flex-center position-ref full-height">
    <form method="POST" action="{{ route('admin.login') }}">
        {!! csrf_field() !!}

        <input type="username" name="username" placeholder="username">
        <input type="password" name="password" placeholder="password">
        <input type="questionid" name="questionid" placeholder="questionid">
        <input type="answer" name="answer" placeholder="answer">
        <div class="question">

        </div>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>