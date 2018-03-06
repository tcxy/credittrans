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
                        <a href="Account.html">Account</a>
                    </li>
                    <li>
                        <a href="creditCard.html">Credit card</a>
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
                    var network = new vis.Network(container, data, options);
//                    network.on("showPopup", function (params) {
//                        document.getElementById('eventSpan').innerHTML = '<h2>showPopup event: </h2>' + JSON.stringify(params, null, 4);
//                    });
//                    network.on('doubleClick', function (params) {
//                        if (params.nodes.length!=0){
//                        var from = params.nodes[0];
//                        $.ajax({
//                            type:'POST',
//                            dataType:'json',
//                            url:'/shortest',
//                            error:function (e) {
//                                console.log(e);
//                            }
//                            success:function () {
//
//                            }
//                        })
//
//                        }else {
//                            alert('unselected');
//                        }
//                    });


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

    //    getNodesFunction();

    // Called when the Visualization API is loaded.
    //    function draw() {
    //        // Create a data table with nodes.
    //        nodes = [
    //            {id: 0, label: 'Process Center', shape: 'database'},
    //            {id: 10, label: 'Relay Station#10', shape: 'triangle', status: true, load: 0},
    //            {id: 11, label: 'Relay Station#11', shape: 'triangle', status: true, load: 0},
    //            {id: 12, label: 'Relay Station#12', shape: 'triangle', status: true, load: 0},
    //            {id: 13, label: 'Relay Station#13', shape: 'triangle', status: true, load: 0},
    //            {id: 14, label: 'Relay Station#14', shape: 'triangle', status: true, load: 0},
    //        ];
    //
    //        // Create a data table with links.
    //        edges = [
    //            {id: 0, from: 0, to: 12, length: EDGE_LENGTH_MAIN, color: {}},
    //            {id: 1, from: 0, to: 10, length: EDGE_LENGTH_MAIN},
    //            {id: 2, from: 10, to: 11, length: EDGE_LENGTH_MAIN},
    //            {id: 3, from: 12, to: 13, length: EDGE_LENGTH_MAIN},
    //            {id: 4, from: 10, to: 14, length: EDGE_LENGTH_MAIN},
    //            {id: 5, from: 12, to: 14, length: EDGE_LENGTH_MAIN},
    //        ];
    //
    //
    //        //draw stores
    //        for (var i = 100; i <= 103; i++) {
    //            nodes.push({id: i, label: 'Store#' + i, shape: 'circle', status: true});
    //            edges.push({from: 11, to: i, length: EDGE_LENGTH_SUB});
    //        }
    //
    //        for (var i = 107; i <= 108; i++) {
    //            nodes.push({id: i, label: 'Store#' + i, shape: 'circle', status: true});
    //            edges.push({from: 12, to: i, length: EDGE_LENGTH_SUB});
    //        }
    //        for (var i = 109; i <= 111; i++) {
    //            nodes.push({id: i, label: 'Store#' + i, shape: 'circle', status: true});
    //            edges.push({from: 13, to: i, length: EDGE_LENGTH_SUB});
    //        }
    //        for (var i = 112; i <= 114; i++) {
    //            nodes.push({id: i, label: 'Store#' + i, shape: 'circle', status: true});
    //            edges.push({from: 14, to: i, length: EDGE_LENGTH_SUB});
    //        }
    //        for (var i = 115; i <= 116; i++) {
    //            nodes.push({id: i, label: 'Store#' + i, shape: 'circle', status: true});
    //            edges.push({from: 10, to: i, length: EDGE_LENGTH_AROUND});
    //        }
    //
    //        // create a network
    //        var container = document.getElementById('mynetwork');
    //        var data = {
    //            nodes: nodes,
    //            edges: edges
    //        };
    //        var options = {
    //            interaction: {hover: true}
    //        };
    //        network = new vis.Network(container, data, options);
    //    }

    //add new relay


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
                    document.getElementById('path').innerHTML = 'Transaction path is:' + '</br>' + jsondata;
                    show(data);
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