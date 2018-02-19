<!DOCTYPE html>
<html lang="en"><!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/jquery-bootstrap-pagination.js }}"></script>
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
        function loadAccounts() {
            var username = sessionStorage.getItem("username");
            if (username == null) {
                alert("You should login first");
                window.location.replace("/");
            } else {
                $('#username').val(username);
                $.ajax({
                    type: "get",
                    url: "{{ route('credit.getaccouts') }}",
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
            }
        }

        function query(e, page) {
            $.getJSON('{{ route("credit.getaccouts") }}', page, loadList);
        }

        function loadList(data) {
            $('.loaded-data').remove();

            for (var account in data['accounts']) {
                $('#accounts').append('<th class="loaded-data"><th>' + account["holdername"] +
                    '</th><th>' + account['phonenumber'] + '</th><th>' + account['address'] +
                    '</th><th>' + account['spendlinglimit'] + '</th><th>' + account['balance'] + '</th>' +
                    '<td>' + '</td></tr>');
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
<body onload="loadAccounts()">
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
                        <a href="#">Account</a>
                    </li>
                    <li>
                        <a href="creditCard.html">Credit card</a>
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
<div class="span9">
    <h1>
        Account information
    </h1>
    <a class="toggle-link" href="#new-file">Create new account</a>
    <form id="new-file" class="form-horizontal hidden">
        <fieldset>
            <legend>New account</legend>
            <div class="control-group">
                <label class="control-label" for="textarea">First name:</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="fName"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Last name:</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="lName"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Type:</label>
                <div class="controls"><select id="type" style="width: 284px">
                        <option>CHECKING</option>
                        <option>SAVING</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Balance:</label>
                <div class="controls">
                    <input type="number" class="input-xlarge" id="balance"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Max Limit:</label>
                <div class="controls">
                    <input type="number" class="input-xlarge" id="limit"/>
                </div>
            </div>
            <legend>New credit card</legend>
            <div class="control-group">
                <label class="control-label" for="textarea">CVV Number:</label>
                <div class="controls">
                    <input type="number" class="input-xlarge" id="cvv"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea">Date of expiration:</label>
                <div class="controls">
                    <input type="date" class="input-xlarge" id="date"/>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="add()">Summit</button>
                <button class="btn" onclick="cancel()">Cancel</button>
            </div>

        </fieldset>
    </form>
    <table class="table table-bordered table-striped" id="account">
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
        <tr>
            <td>
                497399811
            </td>
            <td>
                Keanu
            </td>
            <td>
                Reeves
            </td>
            <td>
                CHECKING
            </td>
            <th>
                20
            </th>
            <th>
                20000
            </th>
            <td>
                <!--<a href="#" class="delete-link" onclick="addCard()">add</a>&nbsp;&nbsp;&nbsp;-->
                <a href="#" class="delete-link" class="edit" onclick="editAccount(this,1)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" data-toggle="modal" data-target=".bs-example-modal-sm">view</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="delRow(this)">delete</a>
            </td>
        </tr>
        <tr>
            <td>
                677615251
            </td>
            <td>
                Mariah
            </td>
            <td>
                Carey
            </td>
            <td>
                SAVING
            </td>
            <th>
                20
            </th>
            <th>
                20000
            </th>
            <td>
                <!--<a href="#" class="delete-link">add</a>&nbsp;&nbsp;&nbsp;-->
                <a href="#" class="delete-link" onclick="editAccount(this,2)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" id="pop">view</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="delRow(this)">delete</a>
            </td>
        </tr>
        <tr>
            <td>
                245438646
            </td>
            <td>
                Micheal
            </td>
            <td>
                Jackson
            </td>
            <td>
                SAVING
            </td>
            <th>
                20
            </th>
            <th>
                20000
            </th>
            <td>
                <!--<a href="#" class="delete-link">add</a>&nbsp;&nbsp;&nbsp;-->
                <a href="#" class="delete-link" onclick="editAccount(this,3)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link">view</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="delRow(this)">delete</a>
            </td>
        </tr>
        <tr>
            <td>
                932286486
            </td>
            <td>
                Jean
            </td>
            <td>
                Leo
            </td>
            <td>
                SAVING
            </td>
            <th>
                20
            </th>
            <th>
                20000
            </th>
            <td>
                <!--<a href="#" class="delete-link">add</a>&nbsp;&nbsp;&nbsp;-->
                <a href="#" class="delete-link" onclick="editAccount(this,4)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link">view</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="delRow(this)">delete</a>
            </td>
        </tr>
        <tr>
            <td>
                247112623
            </td>
            <td>
                Tom
            </td>
            <td>
                Hanks
            </td>
            <td>
                CHECKING
            </td>
            <th>
                20
            </th>
            <th>
                20000
            </th>
            <td>
                <!--<a href="#" class="delete-link">add</a>&nbsp;&nbsp;&nbsp;-->
                <a href="#" class="delete-link" onclick="editAccount(this,5)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link">view</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="delRow(this)">delete</a>
            </td>
        </tr>
        </tbody>
    </table>

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
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <legend style="text-align: center">Credit card information</legend>
            <div class="control-group">
                <label class="control-label" for="textarea">credit cards:</label>
                <div class="controls">
                    <select style="width: 284px">
                        <option>23782683264832</option>
                        <option>23343432434878</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>