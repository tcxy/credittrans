<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]>
<html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]>
<html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]>
<html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{!! asset('css/bootstrap.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap-responsive.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/site.css') !!}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <style>
        .container {
            margin: 0 auto;
            width: 800px;

        }

        #scene {
            border: 1px solid black;
        }
    </style>


    <script>
        function loadData() {
            var username = sessionStorage.getItem('username');
            if (username != null) {
                $('#username').text(username);
            } else {
                alert('You should login first');
                window.location.replace('/');
            }
        }
    </script>

</head>
<body onload="loadData()">
<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand"
                                                                                      href="#">Transaction</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li>
                        <a href="/account">Account</a>
                    </li>
                    <li>
                        <a href="/card">Credit card</a>
                    </li>
                </ul>
                <ul class="nav pull-right">
                    <li>
                        <a href="#" id="username">@username</a>
                    </li>
                    <li>
                        <a href="login.html">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <canvas id="scene" width="800" height="500">Your browser does not support the canvas element.</canvas>

</div>

<script type="text/javascript">


    //    var c = document.getElementById("scene");
    //    var cxt = c.getContext("2d");
    //    var img = new Image();
    //    img.src = "creditCard.png";
    //    img.onload = function () {
    //        cxt.drawImage(img, 0, 0);
    //    }


</script>
</body>
</html>