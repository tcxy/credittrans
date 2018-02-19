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
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-responsive.css }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <style>
        .container {
            margin: 0 auto;
            width: 800px;

        }

        #scene {
            border: 1px solid black;
        }
    </style>
</head>
<body>
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
                        <a href="Account.html">Account</a>
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
    <form id="new-file" class="form-horizontal hidden">
        <fieldset>
            <legend>New credit card</legend>
            <div class="control-group">
                <label class="control-label">Account number:</label>
                <div class="controls">
                    <select style="width: 284px" id="accountNum">
                        <option>TEST1</option>
                        <option>TEST2</option>
                    </select>
                    <a href="#" data-toggle="modal" data-target=".bs-example-modal-sm">New account</a>

                    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <legend style="text-align: center">New Account</legend>
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
                                    <div class="controls">
                                        <select id="type" style="width: 284px">
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
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary" onclick="add()">Summit</button>
                                    <button class="btn" onclick="cancel()">Cancel</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <button type="button" class="btn btn-primary" onclick="createCard()">Summit</button>
                <button class="btn" onclick="clear()">Cancel</button>
            </div>
        </fieldset>
    </form>
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
        <tbody>
        <tr>
            <td>
                4751483065186767
            </td>
            <td>
                497399811
            </td>
            <td>
                497
            </td>
            <td>
                2019-02-02
            </td>
            <td>
                <a href="#" class="delete-link" onclick="editCard(this,1)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="del(this)">delete</a>
            </td>
        </tr>
        <tr>
            <td>
                6776152517399811
            </td>
            <td>
                525173998
            </td>
            <td>
                598
            </td>
            <td>
                2019-02-10
            </td>
            <td>
                <a href="#" class="delete-link" onclick="editCard(this,2)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="del(this)">delete</a>
            </td>
        </tr>
        <tr>
            <td>
                2454386462456247
            </td>
            <td>
                214872536
            </td>
            <td>
                216
            </td>
            <td>
                2019-03-02
            </td>
            <td>
                <a href="#" class="delete-link" onclick="editCard(this,3)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="del(this)">delete</a>
            </td>
        </tr>
        <tr>
            <td>
                9322864863218274
            </td>
            <td>
                129364725
            </td>
            <td>
                125
            </td>
            <td>
                2020-02-02
            </td>
            <td>
                <a href="#" class="delete-link" onclick="editCard(this,4)">edit</a>&nbsp;&nbsp;&nbsp;
                <a href="#" class="delete-link" onclick="del(this)">delete</a>
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

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/site.js"></script>
</body>
</html>