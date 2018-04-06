<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/jquery-bootstrap-pagination.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
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
            background-color: black;
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
            border: 16px solid lightblue;
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
            border: 16px solid lightblue;
            background-color: white;
            opacity: 1;
            z-index: 1002;
            overflow: auto;
        }
        #acc_form{
            position: absolute;
            top: 30%;
            left: 25%;
            width: 90%;
            height: 20%;
        }
    </style>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function loadCards() {
            var username = sessionStorage.getItem("username");
            if (username == null) {
                alert("You should login first");
                window.location.replace("/");
            } else {
                $('#username').text(username);
                $.ajax({
                    type: "get",
                    url: "{{ route('credit.getcards') }}",
                    data: {"page": 1},
                    success: function (data) {
                        if (data['code'] == '001') {
                            console.log(data['data']);
                            var returnData = data['data'];

                            loadList(returnData);
                        }
                    },
                    error: function (data) {
                        console.log("Connection failed");
                        console.log(data);
                    }
                });

                $.ajax({
                    type: "get",
                    url: "{{ route('credit.getaccounts') }}",
                    data: {"page": 1},
                    success: function (data) {
                        if (data['code'] == '001') {
                            $('select#accountid').empty();
                            console.log(data['data']);
                            var returnData = data['data'];
                            for (var index in returnData['accounts']) {
                                var account = returnData['accounts'][index];
                                $('select#accountid').append('<option class="loaded-accounts" value="' + account.accountid + '">' + account.holdername + '</option>');
                            }
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
            $.getJSON('{{ route("credit.getcards") }}', page, loadList);
        }

        function deleteCard(cardId, accountid) {
            var res = confirm('Are you sure to delete this card?');
            if(res == true){
                $.ajax({
                    type: 'post',
                    url: '{{ route('credit.deletecard') }}',
                    data: {
                    'cardId': cardId,
                        'accountid': accountid
                },
                success: function (data) {
                    if (data['code'] == '001') {
                        loadCards();
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

        function addCard() {
            var form = $('#new-file');

            form.submit(function (e) {
                e.preventDefault();
            });

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function (data) {
                    if (data['code'] == '001') {
                        loadCards();
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

        function loadList(data) {
            $('.loaded-data').remove();

            for (var index in data['cards']) {
                console.log('card:' + data['cards'][index]);
                var card = data['cards'][index];
                $('#cards').append('<tr class="loaded-data"><th id="cardId">' + card.cardId +
                    '</th><th id="accountid">' + card.accountid + '</th><th id="csc">' + card.csc +
                    '</th><th id="expireDate">' + card.expireDate + '</th>' +
                    '<td>' +
                    '                <a href="#" class="delete-link" onclick="editCard(this,' + (parseInt(index) + 1) + ')">edit</a>&nbsp;&nbsp;&nbsp;\n' +
                    '                <a href="#" class="delete-link" onclick="deleteCard(' + card.cardId + ',' + card.accountid + ')">delete</a>' + '</td></tr>');
            }


            $('.pagination').unbind();
            $('.pagination').pagination({
                total_pages: data['total_pages'],
                current_page: data['current_page'],
                call_back: query
            });
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
            CloseDiv('newAccount','fade');
        }
        function addAccount() {
            $form = $('#acc_form');
            console.log($form.serialize());
            $.ajax({
                type: 'post',
                url: '/addaccount',
                data: $form.serialize(),
                success: function (data) {
                    if (data['code'] == '001') {
                        alert('added');
                        loadCards();
                        CloseDiv('newAccount','fade');
                    } else {
                        alert(data['message']);
                    }
                },
                error: function (data) {
                    console.log("Connection failed");
                    console.log(data);
                    loadCards();
                    CloseDiv('newAccount','fade');
                }
            });
        }
        document.onkeydown = function (event) {
            var e = event || window.event || arguments.callee.caller.arguments[0];
            if (e && e.keyCode == 27) { // 按 Esc
                CloseDiv('newAccount','fade');
            }
        };

    </script>
</head>
<body onload="loadCards()">
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
                        <a href="/account">Account</a>
                    </li>
                    <li>
                        <a href="#">Credit card</a>
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
        Credit card information
    </h1>
    <a class="toggle-link" href="#new-file">Create new credit card</a>
    <form id="new-file" class="form-horizontal hidden" method="post" action="{{ route('credit.addcard') }}">
        <fieldset>
            <legend>New credit card</legend>
            <div class="control-group">
                <label class="control-label">Account number:</label>
                <div class="controls">
                    <select style="width: 284px" id="accountid" name="accountid">
                    </select>
                    <a href="#" onclick="ShowDiv('newAccount','fade')">New account</a>

                    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <legend style="text-align: center">New Account</legend>
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
                            </div>
                        </div>
                    </div>
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
                    <input type="text" class="input-xlarge" id="expireDate" name="expireDate"/>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="addCard()">Summit</button>
                <input type="button" class="btn" id="cancel" value="Cancel"/>
            </div>
        </fieldset>
    </form>
    <form id="editcard" method="post" action="{{ route('credit.updatecard') }}">
        <table class="table table-bordered table-striped" id="card">
            <thead>
            <tr>
                <th>
                    Credit card number
                </th>
                <th>
                    Account number
                </th>
                <th>
                    CVV Number
                </th>
                <th>
                    Date of expiration
                </th>
                <th>

                </th>
            </tr>
            </thead>
            <tbody id="cards">
            </tbody>
        </table>
    </form>


    <div class="pagination">
        <ul>
            <li class="disabled">
                <a href="#">&laquo;</a>
            </li>
            <li class="active">
                <a href="#">1</a>
            </li>
            <li>
                <a href="#">2</a>
            </li>
            <li>
                <a href="#">3</a>
            </li>
            <li>
                <a href="#">4</a>
            </li>
            <li>
                <a href="#">&raquo;</a>
            </li>
        </ul>
    </div>

</div>
<div id="fade" class="black_overlay"></div>
    <div id="newAccount" class="white_content_small" style="opacity: 1">
        <button type="button" class="btn btn-primary" id="back" onclick="back()">back</button>
        <div style="text-align: center; cursor: default; height: 40px;">
            <h2>New Account</h2>
        </div>
        <div>
        <form id="acc_form">
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
            <div>
                <button type="button" class="btn btn-primary" style="margin-left: 80px" onclick="addAccount()">Summit</button>
                <button type="button" class="btn" style="margin-left: 20px" onclick="back()">Cancel</button>
            </div>
        </form>
        </div>
    </div>
</body>
</html>