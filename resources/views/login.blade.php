<html>

<head>
    <title>Welcome</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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

            if ($('#username').val() == '') {
                alert('Please enter your username!');
            } else if ($('#password').val() == '') {
                alert('Please enter your password!');
            } else if ($('#answer').val() == '') {
                alert('Please enter your answer to the validation question!');
            } else {
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data) {
                        if (data['code'] == '001') {
                            console.log(data['data']);
                            sessionStorage.setItem('userId', data['data']);
                            window.location.replace("{{ route('admin.validation') }}");
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


        }

        function validation() {

        }
    </script>
</head>

<body>
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
            <td><input type="password" name="password" type="text" id="password" value="" placeholder="password"></td>
        </tr>
        <tr>
            <td><input type="button" class="submit" value="Login" onclick="login()"></td>
        </tr>
    </table>
</form>
</body>

</html>