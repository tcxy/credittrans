<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link href='{{ asset("css/bootstrap.css") }}' rel="stylesheet">
    <link href='{{ asset("css/bootstrap-responsive.css") }}' rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    <link href='{{ asset("css/vis-network.min.css") }}' rel="stylesheet"/>
    <script type="text/javascript" src="{{ asset('js/vis.js') }}"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <style type="text/css">
        #mynetwork {
            position: absolute;
            width: 700px;
            height: 600px;
            border: 1px solid lightgray;
            margin-left: 370px;

        }

        #addStore {
            margin-left: 50px;
        }

        #addRelay {
            margin-left: 50px;
        }

        #inputField {
            float: left;
        }

        input {
            margin-left: 20px;
            margin-top: 10px;
        }

        #submit1 {
            margin-left: 150px;
        }

        #submit2 {
            margin-left: 150px;
        }

        #addNewStore {
            float: left;
            margin-top: 20px;
        }

        #eventSpan {
            float: right;
            width: 300px;
            height: 300px;
        }

        #send {
            margin-left: 50px;
            margin-top: 20px;
        }

        #path {
            width: 300px;
            height: 300px;
            margin-top: 20px;
        }
    </style>
</head>
<body onload="getNodesFunction()">
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
<div id="inputField">
    <button type="button" class="btn btn-primary" id="addRelay">Add new Relay</button>
    <div id="relayForm">
        <form id="relay_form" style="display: none">
            <input hidden="hidden" name="type" value="1">

            <label>Relay Station Ip:<input type="text" id="relayID1"
                                           name="ip"></label>
            <label>ConnectedTo Ip:<input type="text" id="connectedTo" name="to"></label>
            <label>Weight:<input type="text" id="weight" name="weight" style="margin-left: 77px;"></label>
            <input type="button" value="Submit" class="btn btn-primary" id="submit1" onclick="addRelay()">
            <input type="button" value="Clear" class="btn btn-primary" id="clear1" onclick="clearRelayForm()">
        </form>
    </div>
    <div id="addNewStore">
        <button type="button" class="btn btn-primary" id="addStore">Add new Store</button>
        <div id="storeForm">
            <form id="store_form" style="display: none">
                <label>Relay Station Ip:<input type="text" id="relayID2" name="to"></label>
                <label>Store Ip:<input type="text" id="storeID2" name="ip" style="margin-left: 70px;"></label>
                <label>Weight:<input type="text" id="weight2" name="weight" style="margin-left: 77px;"></label>
                <input name="type" hidden="hidden" value="2">
                <input type="button" value="Submit" class="btn btn-primary" id="submit2" onclick="addNewStore()">
                <input type="button" value="Clear" class="btn btn-primary" id="clear2" onclick="clearStoreForm()">
            </form>

        </div>
    </div>
    <div id="sendTrans">
        <button type="button" class="btn btn-primary" id="send">New Transaction</button>
        <div id="sendForm">
            <form id="send_form" style="display: none">
                <label>Store IP:<input type="text" id="from" name="from"></label>
                <input type="button" value="Submit" class="btn btn-primary" id="submit2" onclick="sendNewTrans()">
                <input type="button" value="Clear" class="btn btn-primary" id="clear2" onclick="clearTransForm()">
            </form>
        </div>
    </div>
    <div id="path"></div>


</div>
<div id="eventSpan"></div>
<div id="mynetwork"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#addRelay').click(function () {
            $('#relay_form').toggle();
            clearRelayForm();
        })
    });
    $(document).ready(function () {
        $('#addStore').click(function () {
            $('#store_form').toggle();
            clearStoreForm();
        })
    });
    $(document).ready(function () {
        $('#send').click(function () {
            $('#send_form').toggle();
            clearTransForm();
        })
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var clock = 0;
    var path;
    var step;
    var network;

    var username = sessionStorage.getItem("username");
    if (username == null) {
        alert("You should login first");
        window.location.replace("/");
    } else {
        $('#username').text(username);
    }

    function getNodesFunction() {
        $.ajax({
            type: 'GET',
            dataType: "json",
            url: "/graph",
            error: function (data) {
                console.log(data);
            },
            success: function (data) {
                if (data['code'] == '001') {
                    var jsondata = data['data'];
                    nodes = new vis.DataSet(jsondata['nodes']);
                    edges = new vis.DataSet(jsondata['edges']);

//
                    console.log(nodes);


                    // create a network
                    var container = document.getElementById('mynetwork');
                    var data = {
                        nodes: nodes,
                        edges: edges
                    };
                    var options = {
                        interaction: {hover: true},

                    };
                    network = new vis.Network(container, data, options);

                    network.on('click', function (params) {
                        if (params.nodes.length != 0) {

                            var id = params.nodes[0];

                            $.ajax({
                                type: 'GET',
                                dataType: "json",
                                url: "/stationinfo",
                                data: {'id': id},
                                error: function (data) {
                                    console.log(data);
                                },
                                success: function (data) {
                                    if (data['code'] == 001) {
                                        console.log(data);
                                        var ip = data['data']['ip'];
                                        var status = data['data']['status'];
                                        if (status == 1) {
                                            status = 'Activated';
                                        } else {
                                            status = 'Inactivated';
                                        }
                                        document.getElementById('eventSpan').innerHTML = 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip
                                            + '<br/>' + 'status :' + status;

                                    } else {
                                        alert(data['message']);
                                    }
                                }
                            });


                        }


                    });
                }
            }
        });


    }


    function clearRelayForm() {
        document.getElementById('relayID1').value = '';
        document.getElementById('connectedTo').value = '';
        document.getElementById('weight').value = '';
    }

    function clearTransForm() {
        document.getElementById('from').value = '';
        document.body.onload(getNodesFunction());

    }

    function addRelay() {
        $form = $('#relay_form');
        console.log($form.serialize());
        $.ajax({
            url: '/addstation',
            type: 'post',
            data: $form.serialize(),
            success: function (data) {
                if (data['code'] == '001') {
                    getNodesFunction();
                }
                else alert(data['message']);
            },
            error: function(e) {
                console.log(e);
        }
        })

    }

    // add new store
    function addNewStore() {
//        var sid = document.getElementById('storeID2');
//        var rid = document.getElementById('relayID2');
//        nodes.push({id: sid, label: 'Store#' + sid, shape: 'circle', status: true});
//        edges.push({from: rid, to: sid, length: EDGE_LENGTH_SUB});
        console.log(nodes);
        $form = $('#store_form');
        console.log($form.serialize());
        $.ajax({
            url: "/addstation",
            type: 'post',
            data: $form.serialize(),
            success: function (data) {
                if (data['code'] == '001') {
                    getNodesFunction();
                } else {
                    alert(data['message']);
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

    function animation() {
        if (step == path.length) {
            clearInterval(clock);
            nodes.update({id: path[step - 1]['id'], color: {border: '#2B7CE9', background: '#97C2FC', highlight: {border: '#2B7CE9', background: '#D2E5FF'}, hover: {border: '#2B7CE9', background: '#D2E5FF'}}});
            step = 0;
            return ;
        }

        var node = path[step];
        console.log(step);
        console.log(node);
        if (node['type'] == '1') {
            nodes.update({id: node['id'], color: {background: 'red', highlight: {background: 'red'}}});
            if (step != 0) {
                edges.update({id: path[step-1]['edge']['id'], color: {color: '#848484', highlight: '#848484', hover: '#848484', inherit: 'from', opacity: 1}});
            }
        } else if (node['type'] == '2') {
            edges.update({id: node['edge']['id'], color: {color: 'red', highlight: 'red', hover: 'red'}});
            if (step != 0) {
                nodes.update({id: path[step-1]['id'], color: {border: '#2B7CE9', background: '#97C2FC', highlight: {border: '#2B7CE9', background: '#D2E5FF'}, hover: {border: '#2B7CE9', background: '#D2E5FF'}}});
            }
        }

        step++;
    }


    function sendNewTrans() {
        $form = $('#send_form');
        console.log($form.serialize());
        $.ajax({
            url: '/shortest',
            type: 'post',
            data: $form.serialize(),
            success: function (data) {
                var jsondata = data['data'];
                if (data['code'] == '001') {
                    console.log(data);
                    // path = data['data'];
                    // step = 0;
                    // clock = setInterval("animation()", 2000);
                    // show(data);
                } else {
                    alert(data['message']);
                }
            },
            error: function (e) {
                console.log(e);
            }
        })
    }


    function show(data) {
        for (var i = 0; i < data['data'].length; i++) {
            nodes.update({id: data['data'][i], color: {background: 'red', highlight: {background: 'red'}}});
        }
    }

</script>
</body>
</html>