<html>

<head>
    <title>Welcome</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <script language="javascript" type="text/javascript" src="{!! asset('js/login.js') !!}"></script>
    <link href="{!! asset('css/style.css') !!}" type="text/css" rel="stylesheet">
</head>

<body>
<form>
    <table width="202" border="0" align="center" cellpadding="05" cellspacing="0" id="logintable">
        <tr>
            <td width="192">
                <div class="message">Welcome!</div>
            </td>
        </tr>
        <tr>
            <td><input name="name" type="text" id="username" value="" placeholder="userName"></td>
        </tr>
        <tr>
            <td><input name="psd" type="password" id="password" value="" placeholder="password"></td>
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