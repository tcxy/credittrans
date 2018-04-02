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
            var res = confirm('confirm?');
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


        function loadList(data) {
            $('.loaded-data').remove();

            for (var index in data['accounts']) {
                console.log('account:' + data['accounts'][index]);
                var account = data['accounts'][index];
                $('#accounts').append('<tr class="loaded-data"><th id="holdername">' + account.holdername +
                    '</th><th id="phonenumber">' + account.phonenumber + '</th><th id="address">' + account.address +
                    '</th><th id="spendlinglimit">' + account.spendlinglimit + '</th><th id="balance">' + account.balance + '</th>' +
                    '<th id="accountid" hidden="hidden">' + account.accountid + '</th>' +
                    '<td>' +
                    '                <a href="#" class="delete-link" onclick="editAccount(this,' + (parseInt(index) + 1) + ')">edit</a>&nbsp;&nbsp;&nbsp;\n' +
                        '                <a href="#" data-toggle="modal" data-target="#myModal">view</a>&nbsp;&nbsp;&nbsp;\n' +
                        '                <a href="#" class="delete-link" onclick="deleteAccount(' + account.accountid + ')">delete</a>' + '</td></tr>');
            }


            $('.pagination').unbind();
            $('.pagination').pagination({
                total_pages: data['total_pages'],
                current_page: data['current_page'],
                call_back: query
            });
        }

        function addAccount() {
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
                        loadAccounts();
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
                    <input type="text" class="input-xlarge" id="expireDate" name="expireDate"/>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="addAccount()">Summit</button>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>



</body>
</html>