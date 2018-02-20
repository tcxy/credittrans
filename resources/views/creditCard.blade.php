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
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/jquery-bootstrap-pagination.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
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

                $('#username').text(username);
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
            });
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
                    '                <a href="#" class="delete-link" onclick="editCard(this,' + (index+1) + ')">edit</a>&nbsp;&nbsp;&nbsp;\n' +
                    '                <a href="#" class="delete-link">view</a>&nbsp;&nbsp;&nbsp;\n' +
                    '                <a href="#" class="delete-link" onclick="deleteCard(' + card.cardId + ',' + card.accountid + ')">delete</a>' + '</td></tr>');
            }


            $('.pagination').unbind();
            $('.pagination').pagination({
                total_pages: data['total_pages'],
                current_page: data['current_page'],
                call_back: query
            });
        }
    </script>
</head>
<body onload="loadCards()">
<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span
                        class="icon-bar"></span>
                <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand"
                                                                                      href="transaction.html">Transaction</a>
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
                        <a href="#">@username</a>
                    </li>
                    <li>
                        <a href="login.html">Logout</a>
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
                    <a href="#" data-toggle="modal" data-target=".bs-example-modal-sm">New account</a>

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
                                        <input type="number" class="input-xlarge" id="balance" name="balance"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="textarea">Spending Limit:</label>
                                    <div class="controls">
                                        <input type="number" class="input-xlarge" id="spendlinglimit" name="spendlinglimit"/>
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
                    <input type="number" class="input-xlarge" id="cardId" name="cardId"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">CVV Number:</label>
                <div class="controls">
                    <input type="number" class="input-xlarge" id="csc" name="csc"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Date of expiration:</label>
                <div class="controls">
                    <input type="date" class="input-xlarge" id="expireDate" name="expireDate"/>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="addCard()">Summit</button>
                <button class="btn" onclick="clear()">Cancel</button>
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
</body>
</html>