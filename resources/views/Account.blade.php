<!DOCTYPE html>
<html lang="en"><!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="{{ asset('js/jquery-bootstrap-pagination.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <style>
        .container {
            margin: 0 auto;
            width: 800px;

        }

        .black_overlay {
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background-color: #333333;
            z-index: 1001;
            -moz-opacity: 0.8;
            opacity: 0.8;
        }

        .white_content {
            display: none;
            position: absolute;
            top: 10%;
            left: 10%;
            width: 80%;
            height: 80%;
            border: 16px solid #F6F6F6;
            background-color: white;
            z-index: 1002;
            overflow: auto;
        }

        .white_content_small {
            display: none;
            position: absolute;
            top: 20%;
            left: 30%;
            width: 40%;
            height: 50%;
            border: 16px solid #F6F6F6;
            background-color: white;
            opacity: 1;
            z-index: 1002;
            overflow: auto;
        }
    </style>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

//         function loadAccounts() {
//             var username = sessionStorage.getItem("username");
//             if (username == null) {
//                 alert("You should login first");
//                 window.location.replace("/");
//             } else {
//                 $('#username').text(username);
//                 $.ajax({
//                     type: "get",
//                     url: "{{ route('credit.getaccounts') }}",
//                     data: {"page": 1},
//                     success: function (data) {
//                         if (data['code'] == '001') {
//                             console.log(data['data']);
//                             var returnData = data['data'];
//
//                             loadList(returnData);
//                         }
//                     },
//                     error: function (data) {
//                         console.log("Connection failed");
//                         console.log(data);
//                     }
//                 });
                 function loadAccounts() {
                     var username = sessionStorage.getItem("username");
                     if (username == null) {
                         alert("You should login first");
                         window.location.replace("/");
                     } else {
                         $('#username').text(username);
                         $.ajax({
                             type: "get",
                             url: "{{ route('credit.getaccounts') }}",
                             data: {"page": 1},
                             success: function (data) {
                                 if (data['code'] == '001') {
                                     console.log(data['data']);
                                     var returnData = data['data'];
                                     loadList(returnData);
                                     loadDate();
                                 }
                             },
                             error: function (data) {
                                 console.log("Connection failed");
                                 console.log(data);
                             }
                         });
            }
        }

        function query(e, page) {
            $.getJSON('{{ route("credit.getaccounts") }}', page, loadList);
        }


        function deleteAccount(accountid) {
            var res = confirm('Are you sure to delete this account?');
            if(res == true){
                $.ajax({
                    type: 'post',
                    url: '{{ route('credit.deleteaccount') }}',
                    data: {
                    'accountid': accountid
                },
                success: function (data) {
                    if (data['code'] == '001') {
                        loadAccounts();
                    } else {
                        alert(data['message']);
                    }
                },
                error: function (data) {
                    console.log("Connection failed");
                    console.log(data);
                }
            })
            }
        }
        function viewAccount(accountid) {
            var id = parseInt(accountid);
            ShowDiv('view','fade');
            $.ajax({
                type:'get',
                url:'/cardwithaccount',
                data:{'id': parseInt(accountid)},
                success: function (data) {
                    if (data['code'] == '001') {
                        $('.loaded').remove();
                        console.log("data",data);
                        for (var index in data['data']) {
                            var id = data['data'][index]['cardId'];
                            var csc = data['data'][index]['csc'];
                            var expireDate = data['data'][index]['expireDate'];
                            console.log('id',id);
                            $('#cards').append('<tr class="loaded"><th id="ID">' + id +
                                '</th><th id="cardCSC">' + csc + '</th><th id="cardExpireDate">' + expireDate + '</th></tr>');

                        }

                    } else {
                        alert(data['message']);
                    }
                },
                error: function (e) {
                    console.log("Connection failed");
                    console.log(e);
                }

            });
            $.ajax({
                type:'get',
                url:'/queueswithaccount',
                data:{'id': parseInt(accountid)},
                success: function (data) {
                    if (data['code'] == '001') {
                        $('.loaded-queue').remove();
                        var returnData = data['data'][0]
                        console.log("data",returnData);
                        for (var index in returnData) {
                            var transid = returnData[index]['id'];
                            var message = returnData[index]['message'];
                            var from = returnData[index]['from'];
                            var card = returnData[index]['card'];
                            console.log('id',id);
                            $('#trans').append('<tr class="loaded-queue"><th id="transID">' + transid +
                                '</th><th id="message">' + message + '</th><th id="transfrom">' + from + '<th id="transCard">' + card +
                                '</th></tr>');
                        }

                    } else {
                        alert(data['message']);
                    }
                },
                error: function (e) {
                    console.log("Connection failed");
                    console.log(e);
                }

            });

        }


        function loadList(data) {
            $('.loaded-data').remove();

            for (var index in data['accounts']) {
                console.log('account:' ,data['accounts'][index]);
                var account = data['accounts'][index];
                $('#accounts').append('<tr class="loaded-data"><th id="holdername">' + account.holdername +
                    '</th><th id="phonenumber">' + account.phonenumber + '</th><th id="address">' + account.address +
                    '</th><th id="spendlinglimit">' + account.spendlinglimit + '</th><th id="balance">' + account.balance + '</th>' +
                    '<th id="accountid" hidden="hidden">' + account.accountid + '</th>' +
                    '<td>' +
                    '                <a href="#" class="delete-link" onclick="editAccount(this,' + (parseInt(index) + 1) + ')">edit</a>&nbsp;&nbsp;&nbsp;\n' +
                        '                <a href="#" onclick="viewAccount('+account.accountid+')">view</a>&nbsp;&nbsp;&nbsp;\n' +
                        '                <a href="#" class="delete-link" onclick="deleteAccount(' + account.accountid + ')">delete</a>' + '</td></tr>');
                console.log('id:',data['accounts'][index]['accountid']);
            }

            $('.pagination').unbind();
            $('.pagination').pagination({
                total_pages: data['total_pages'],
                current_page: data['current_page'],
                call_back: query
            });
        }
        function loadDate() {
            $('select#year').empty();
            $('select#month').empty();
            for (var y=19;y<=70;y++){
                $('#year').append('<option class="loaded-year" value=' + y + '>' + y + '</option>');
            }
            for (var m=1;m<=9;m++){
                $('#month').append('<option class="loaded-month" value='+'0'+ m +'>' + '0' + m + '</option>');
            }
            $('#month').append('<option class="loaded-month" value=10>' + '10' + '</option>');
            $('#month').append('<option class="loaded-month" value=11>' + '11' + '</option>');
            $('#month').append('<option class="loaded-month" value=12>' + '12' + '</option>');
        }

        function addAccount() {
            var form = $('#new-file');
            var validate = validateInput();
            form.submit(function (e) {
                e.preventDefault();
            });

            if(validate == true){
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data) {
                        if (data['code'] == '001') {
                            loadAccounts();
                        } else {
                            alert(data['message']);
                        }
                    },
                    error: function (data) {
                        console.log('form:',form.serialize());
                        console.log(data);
                    }
                });
            }else {
                alert('input required');
            }

        }
        function ShowDiv(show_div, bg_div) {
            document.getElementById(show_div).style.display = 'block';
            document.getElementById(bg_div).style.display = 'block';
            var bgdiv = document.getElementById(bg_div);
            bgdiv.style.width = document.body.scrollWidth;
            // bgdiv.style.height = $(document).height();
            $("#" + bg_div).height($(document).height());
        }

        function CloseDiv(show_div, bg_div) {
            document.getElementById(show_div).style.display = 'none';
            document.getElementById(bg_div).style.display = 'none';
        }
        function back() {
            CloseDiv('view','fade');
        }
        function validateInput() {
            var holdername = document.getElementById('holdername').value;
            var phonenumber = document.getElementById('phonenumber').value;
            var address = document.getElementById('address').value;
            var balance = document.getElementById('balance').value;
            var spendlinglimit = document.getElementById('spendlinglimit').value;
            var cardId = document.getElementById('cardId').value;
            var csc = document.getElementById('csc').value;
            if(holdername==''){
                return false;
            }
            else if (phonenumber==''){
                return false;
            }else if (address==''){
                return false;
            }else if (balance==''){
                return false;
            }else if (spendlinglimit==''){
                return false;
            }else if (cardId==''){
                return false;
            }else if (csc==''){
                return false;
            }else {
                return true;
            }
        }
        document.onkeydown = function (event) {
            var e = event || window.event || arguments.callee.caller.arguments[0];
            if (e && e.keyCode == 27) { // 按 Esc
                CloseDiv('view','fade');
            }
        };
    </script>
</head>
<body onload="loadAccounts()">
<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span
                        class="icon-bar"></span>
                <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand"
                                                                                      href="/transaction">Transaction</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li>
                        <a href="#">Account</a>
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
                        <a href="/login">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="span9">
    <h1>
        Account information
    </h1>
    <a class="toggle-link" href="#new-file">Create new account</a>
    <form id="new-file" class="form-horizontal hidden" method="post" action="{{ route('credit.addaccount') }}">
        <fieldset>
            <legend>New account</legend>
            <div class="control-group">
                <label class="control-label" for="textarea">Holder name:</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="holdername" name="holdername"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Phone Number:</label>
                <div class="controls">
                    <input type="tel" class="input-xlarge" id="phonenumber" name="phonenumber"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Address:</label>
                <div class="controls">
                    <input type="tel" class="input-xlarge" id="address" name="address"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Balance:</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="balance" name="balance"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Spending Limit:</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="spendlinglimit" name="spendlinglimit"/>
                </div>
            </div>
            <legend>New credit card</legend>
            <div class="control-group">
                <label class="control-label" for="textarea">Credit Number:</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="cardId" name="cardId"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">CVV Number:</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="csc" name="csc"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Date of expiration:</label>
                <div class="controls">
                   <select id="month" name="month" style="width: 80px"></select><select id="year" name="year" style="width: 80px;margin-left: 10px"></select>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="addAccount()">Submit</button>
                <input type="button" class="btn" value="Cancel"/>
            </div>
        </fieldset>
    </form>
    <form method="post" action="{{ route('credit.updateaccount') }}" id="updateaccount">
        <table class="table table-bordered table-striped" id="accounttable">
            <thead>
            <tr>
                <th>
                    Holder Name
                </th>
                <th>
                    Phone number
                </th>
                <th>
                    Address
                </th>
                <th>
                    Spending Limit
                </th>
                <th>
                    Balance
                </th>
                <th>

                </th>
            </tr>
            </thead>
            <tbody id="accounts">

            </tbody>
        </table>
    </form>


<!--    <div class="pagination">-->
<!--        <ul>-->
<!--            <li class="disabled">-->
<!--                <a href="#">&laquo;</a>-->
<!--            </li>-->
<!--            <li class="active">-->
<!--                <a href="#">1</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#">2</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#">3</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#">4</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#">&raquo;</a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </div>-->

</div>
<div id="fade" class="black_overlay"></div>
<div id="view" class="white_content" style="opacity: 1">
    <button type="button" class="btn btn-primary" id="back" onclick="back()">back</button>
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>View Account</h2>
    </div>
    <legend>Card List:</legend>
    <table id="cardsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>cardID</th>
                <th>csc</th>
                <th>expireDate</th>
            </tr>
        </thead>
        <tbody id="cards"></tbody>
    </table>
    <legend>Transactions List:</legend>
    <table id="queuesTable" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>transactionID</th>
            <th>message</th>
            <th>from</th>
            <th>card</th>
        </tr>
        </thead>
        <tbody id="trans"></tbody>
    </table>
</div>
</body>
</html>