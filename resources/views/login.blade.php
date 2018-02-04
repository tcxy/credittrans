<html>

<head>
    <title>Welcome</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- <script language="javascript" type="text/javascript" src="{!! asset('js/login.js') !!}"></script> -->
    <script language="javascript" type="text/javascript" ></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link href="{!! asset('css/style.css') !!}" type="text/css" rel="stylesheet">
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function login() {
            var form = $('#loginform');
            console.log('Before submit');

            form.submit(function (e) {
               e.preventDefault();
            });

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function (data) {
                    if (data['code'] == '001') {
                        window.location.replace("{{ route('admin.transaction') }}");
                    } else {
                        alert(data['message']);
                    }
                },
                error: function (data) {
                    console.log("Connection failed");
                    console.log(data);
                }
            });
        }

        function loadQuestions() {
            return null;
        }
    </script>
</head>

<body onload="loadQuestions()">
<form id="loginform" method="post" action="{{ route('admin.login') }}">
    <table width="202" border="0" align="center" cellpadding="05" cellspacing="0" id="logintable">
        <tr>
            <td width="192">
                <div class="message">Welcome!</div>
            </td>
        </tr>
        <tr>
            <td><input type="username" name="username" type="text" id="username" value="" placeholder="userName"></td>
        </tr>
        <tr>
            <td><input type="password" name="password" type="password" id="password" value="" placeholder="password"></td>
        </tr>
        <tr>
            <td id="tip"> </td>
        </tr>
        <tr>
            <td><input type="button" class="submit" value="Login" onclick="login()"></td>
        </tr>
    </table>
</form>
</body>

</html>