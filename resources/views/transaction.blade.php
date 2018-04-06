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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/vis.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
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


        #send {
            margin-left: 50px;
            margin-top: 20px;
        }

        #path {
            width: 300px;
            height: 100px;
            margin-top: 20px;
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
            opacity: .80;
            filter: alpha(opacity=80);
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
            z-index: 1002;
            overflow: auto;
        }

        #view {
            margin-left: 200px;
            margin-top: 50px;
        }
        #relayForm{
            position: absolute;
            top: 20%;
            left: 20%;
            width: 90%;
            height: 20%;
        }
        #storeForm{
            position: absolute;
            top: 20%;
            left: 10%;
            width: 90%;
            height: 20%;
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
                        <a href="/login">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="inputField">
    <button type="button" class="btn btn-primary" id="addRelay" onclick="ShowDiv('newRelay','fade')">Add new Relay
    </button>
    <div>
        <form style="display: none">
            <input hidden="hidden" name="type" value="1">

            <label>Relay Station Ip:<input type="text" id="relayID1"
                                           name="ip"></label>
            <!--            <label>Merchant's Name:<input type="text" id="merchantName" name="merchantName" style="margin-left: 8px"></label>-->
            <label>ConnectedTo Ip:<input type="text" id="connectedTo" name="to"></label>
            <label>Weight:<input type="text" id="weight" name="weight" style="margin-left: 77px;"></label>
            <input type="button" value="Submit" class="btn btn-primary" id="submit1" onclick="addRelay()">
            <input type="button" value="Clear" class="btn" id="clear1" onclick="clearRelayForm()">
        </form>
    </div>
    <div id="addNewStore">
        <button type="button" class="btn btn-primary" id="addStore" onclick="ShowDiv('newStore','fade')">Add new Store
        </button>
        <div>
            <form style="display: none">
                <label>Relay Station Ip:<input type="text" id="relayID2" name="to"></label>
                <label>Store Ip:<input type="text" id="storeID2" name="ip" style="margin-left: 70px;"></label>
                <label>Weight:<input type="text" id="weight2" name="weight" style="margin-left: 77px;"></label>
                <input name="type" hidden="hidden" value="2">
                <input type="button" value="Submit" class="btn btn-primary" id="submit2" onclick="addNewStore()">
                <input type="button" value="Clear" class="btn" id="clear2" onclick="clearStoreForm()">
            </form>

        </div>
    </div>
    <div id="sendTrans">
        <button type="button" class="btn btn-primary" id="send" onclick="ShowDiv('newTrans','fade')">New Transaction
        </button>
        <div>
            <form style="display: none">
                <!--                <label>Merchant's Name:<select id="mName" name="mName"-->
                <!--                                               style="margin-left: 10px;margin-top: 5px"></select></label>-->
                <label>Ip<input type="text" id="from" name="from" style="margin-left: 112px"></label>
                <label>Type:<select id="type" name="type" style="margin-left: 92px">
                        <option value="credit">Credit</option>
                        ;
                        <option value="debit">Debit</option>
                        ;
                    </select></label>
                <label>Credit Card:<input type="text" id="cardNum" name='cardNum' style="margin-left: 48px"/></label>
                <label>CVV:<input type="text" id="cvv" name="cvv" style="margin-left: 94px"></label>
                <label>Expiration Date:<input type="text" id="expire" name="expire" style="margin-left: 24px"></label>
                <label>Billing Address:<input type="text" id="billing" name="billing" style="margin-left: 27px"></label>
                <label>Holder Name:<input type="text" id="holder_name" name="holder_name"
                                          style="margin-left: 38px"></label>
                <label>Amount:<input type="text" id="amount" name="amount" style="margin-left: 71px"></label>
                <input type="button" value="Submit" class="btn btn-primary" id="submit3" onclick="sendNewTrans()">
                <input type="button" value="Clear" class="btn" id="clear2" onclick="clearTransForm()">
            </form>
        </div>
    </div>
    <div id="path"></div>
    <div id="action" style="margin-left:50px;margin-top:20px">
        <button type="button" class="btn btn-success" onclick="restart()">Resume</button>
        <button type="button" class="btn btn-danger" style="margin-left: 20px" onclick="pause()">Pause</button>
    </div>
</div>
<!--<div id="eventSpan"></div>-->
<div id="mynetwork"></div>
<div id="fade" class="black_overlay">
</div>
<div id="newRelay" class="white_content_small">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>Add new relay</h2>
    </div>
    <div id="relayForm">
        <form id="relay_form">
            <input hidden="hidden" name="type" value="1">
            <label>Relay Station Ip:<input type="text" id="relayID1"
                                           name="ip"></label>
            <!--            <label>Merchant's Name:<input type="text" id="merchantName" name="merchantName" style="margin-left: 8px"></label>-->
            <label>ConnectedTo Ip:<input type="text" id="connectedTo" name="to"></label>
            <label>Weight:<input type="text" id="weight" name="weight" style="margin-left: 77px;"></label>
           <div id="buttonField">
            <input type="button" value="Submit" class="btn btn-primary" id="submit1" onclick="addRelay()">
            <input type="button" value="Clear" class="btn" id="clear1" onclick="CloseDiv('newRelay','fade')">
           </div>
        </form>
    </div>
</div>
<div id="newStore" class="white_content_small">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>Add new store</h2>
    </div>
    <div id="storeForm" style="margin-left: 50px">
        <form id="store_form">
            <label>Relay Station Ip:<input type="text" id="relayID2" name="to"></label>
            <label>Store Ip:<input type="text" id="storeID2" name="ip" style="margin-left: 70px;"></label>
            <label>Weight:<input type="text" id="weight2" name="weight" style="margin-left: 77px;"></label>
            <input name="type" hidden="hidden" value="2">
            <input type="button" value="Submit" class="btn btn-primary" id="submit2" onclick="addNewStore()">
            <input type="button" value="Clear" class="btn" id="clear2" onclick="CloseDiv('newStore','fade')">
        </form>
    </div>
</div>
<div id="newTrans" class="white_content">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>Add new transaction</h2>
    </div>
    <div id="sendForm" style="margin-left:350px">
        <form id="send_form">
            <!--                <label>Merchant's Name:<select id="mName" name="mName"-->
            <!--                                               style="margin-left: 10px;margin-top: 5px"></select></label>-->
            <label>Ip<input type="text" id="from" name="from" style="margin-left: 112px"></label>
            <label>Type:<select id="type" name="type" style="margin-left: 92px">
                    <option value="credit">Credit</option>
                    ;
                    <option value="debit">Debit</option>
                    ;
                </select></label>
            <label>Credit Card:<input type="text" id="cardNum" name='cardNum' style="margin-left: 48px"/></label>
            <label>CVV:<input type="text" id="cvv" name="cvv" style="margin-left: 94px"></label>
            <label>Expiration Date:<input type="text" id="expire" name="expire" style="margin-left: 24px"></label>
            <label>Billing Address:<input type="text" id="billing" name="billing" style="margin-left: 27px"></label>
            <label>Holder Name:<input type="text" id="holder_name" name="holder_name"
                                      style="margin-left: 38px"></label>
            <label>Amount:<input type="text" id="amount" name="amount" style="margin-left: 71px"></label>
            <div id="form_button" style="margin-left: 150px">
                <input type="button" value="Submit" class="btn btn-primary" id="submit3" onclick="sendNewTrans()">
                <input type="button" value="Clear" class="btn" id="clear2" onclick="CloseDiv('newTrans','fade')">
            </div>
        </form>
    </div>
</div>
<div id="viewNodes" class="white_content_small">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>View Selected</h2>
    </div>
</div>
<div id="queueField" style="margin-top:650px">
    <table class="table table-bordered table-striped" id="queueTable">
        <thead>
        <tr>
            <th>
                Queue Id
            </th>
            <th>
                Credit Card
            </th>
            <th>
                Holder Name
            </th>
            <th>
                Amount
            </th>
            <th>
                Status
            </th>
            <th>
                Result
            </th>
            <th>
                Message
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody id="queues">

        </tbody>

    </table>
</div>
<script type="text/javascript">
    function ShowDiv(show_div, bg_div) {
        document.getElementById(show_div).style.display = 'block';
        document.getElementById(bg_div).style.display = 'block';
        var bgdiv = document.getElementById(bg_div);
        bgdiv.style.width = document.body.scrollWidth;
        // bgdiv.style.height = $(document).height();
        $("#" + bg_div).height($(document).height());
    };

    function CloseDiv(show_div, bg_div) {
        document.getElementById(show_div).style.display = 'none';
        document.getElementById(bg_div).style.display = 'none';
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var clock = 0;
    var queueData;
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
        var testNodes = [
            {id: 0, type: 0, status: 1, region: 0},
            {id: 1, type: 3, status: 1, region: 1, limit: 10},
            {id: 2, type: 1, status: 1, region: 1, limit: 10},
            {id: 3, type: 2, status: 1, region: 1},
            {id: 4, type: 3, status: 1, region: 2, limit: 10},
            {id: 5, type: 1, status: 1, region: 2, limit: 10},
            {id: 6, type: 2, status: 1, region: 2},
            {id: 7, type: 3, status: 1, region: 3, limit: 10},
            {id: 8, type: 1, status: 1, region: 3, limit: 10},
            {id: 9, type: 2, status: 1, region: 3}
        ];
        var testEdges = [
            {id: 0, from: 1, to: 0, region: 0},
            {id: 1, from: 2, to: 1, region: 1},
            {id: 2, from: 3, to: 2, region: 1},
            {id: 3, from: 4, to: 0, region: 0},
            {id: 4, from: 5, to: 4, region: 2},
            {id: 5, from: 6, to: 5, region: 2},
            {id: 6, from: 7, to: 4, region: 0},
            {id: 7, from: 8, to: 7, region: 3},
            {id: 8, from: 9, to: 8, region: 3}
        ];
        var username = sessionStorage.getItem("username");
        if (username == null) {
            alert("You should login first");
            window.location.replace("/");
        } else {
            $('#username').text(username);
            $.ajax({

                type: 'GET',
                dataType: "json",
                url: "/graph",
                error: function (data) {
                    console.log(data);
                },
                success: function (data) {

                    if (data['code'] == '001') {
                        loadCards();
                        loadQueues();
                        var jsondata = data['data'];
                        nodes = new vis.DataSet(jsondata['nodes']);
                        edges = new vis.DataSet(jsondata['edges']);
//                        nodes = new vis.DataSet(testNodes);
//                        edges = new vis.DataSet(testEdges);
//
//                        console.log(nodes['_data']['4'].status);

                        console.log(jsondata);

                        // create a network
                        var container = document.getElementById('mynetwork');
                        var data = {
                            nodes: nodes,
                            edges: edges
                        };
                        var options = {
                            nodes: {
                                size: 24
                            },
                            edges: {
                                width: 2
                            },
                            interaction: {hover: true}

                        };
                        network = new vis.Network(container, data, options);
                        var colorList = ["#473C8B", "#00008B", "#006400", "#8B1A1A", "#B22222", "#CD6600", "#708090", "#7D26CD", "#00688B", "#27408B"];
//                        for (var i = 0; i < colorList.length; i++) {
//                            var bgColor = getColorByRandom(colorList);
//                        }
                        var color1 = getColorByRandom(colorList);
                        var color2 = getColorByRandom(colorList);
                        var color3 = getColorByRandom(colorList);
                        var color4 = getColorByRandom(colorList);
                        var color5 = getColorByRandom(colorList);
                        var color6 = getColorByRandom(colorList);

                        console.log('nodes:', nodes['_data']);
                        console.log('edges:', edges['_data']);
//test graph
//                        for (var index in nodes['_data']) {
//                            var region = nodes['_data'][index]['region'];
//                            var id = nodes['_data'][index]['id'];
//                            var type = nodes['_data'][index]['type'];
//                            var status = nodes['_data'][index]['status'];
//                            console.log('type:', type);
//                            if (region == 1) {
//                                if (type == 1) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'square',
//                                        label: 'Relay Station',
//                                        color: {
//                                            border: color1,
//                                            background: color1,
//                                            highlight: {border: color1, background: color1},
//                                            hover: {border: color1, background: color1}
//                                        }
//                                    });
//                                } else if (type == 2) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'circle',
//                                        label: 'Store',
//                                        color: {
//                                            border: color1,
//                                            background: color1,
//                                            highlight: {border: color1, background: color1},
//                                            hover: {border: color1, background: color1}
//                                        }
//                                    });
//                                } else if (type == 3) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'diamond',
//                                        label: 'Gateway',
//                                        color: {
//                                            border: color1,
//                                            background: color1,
//                                            highlight: {border: color1, background: color1},
//                                            hover: {border: color1, background: color1}
//                                        }
//                                    });
//                                }
//                            } else if (region == 2) {
//                                if (type == 1) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'square',
//                                        label: 'Relay Station',
//                                        color: {
//                                            border: color2,
//                                            background: color2,
//                                            highlight: {border: color2, background: color2},
//                                            hover: {border: color2, background: color2}
//                                        }
//                                    });
//                                } else if (type == 2) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'circle',
//                                        label: 'Store',
//                                        color: {
//                                            border: color2,
//                                            background: color2,
//                                            highlight: {border: color2, background: color2},
//                                            hover: {border: color2, background: color2}
//                                        }
//                                    });
//                                } else if (type == 3) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'diamond',
//                                        label: 'Gateway',
//                                        color: {
//                                            border: color2,
//                                            background: color2,
//                                            highlight: {border: color2, background: color2},
//                                            hover: {border: color2, background: color2}
//                                        }
//                                    });
//                                }
//                            } else if (region == 3) {
//                                if (type == 1) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'square',
//                                        label: 'Relay Station',
//                                        color: {
//                                            border: color3,
//                                            background: color3,
//                                            highlight: {border: color3, background: color3},
//                                            hover: {border: color3, background: color3}
//                                        }
//                                    });
//                                } else if (type == 2) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'circle',
//                                        label: 'Store',
//                                        color: {
//                                            border: color3,
//                                            background: color3,
//                                            highlight: {border: color3, background: color3},
//                                            hover: {border: color3, background: color3}
//                                        }
//                                    });
//                                } else if (type == 3) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'diamond',
//                                        label: 'Gateway',
//                                        color: {
//                                            border: color3,
//                                            background: color3,
//                                            highlight: {border: color3, background: color3},
//                                            hover: {border: color3, background: color3}
//                                        }
//                                    });
//                                }
//                            } else if (region == 4) {
//                                if (type == 1) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'square',
//                                        label: 'Relay Station',
//                                        color: {
//                                            border: color4,
//                                            background: color4,
//                                            highlight: {border: color4, background: color4},
//                                            hover: {border: color4, background: color4}
//                                        }
//                                    });
//                                } else if (type == 2) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'circle',
//                                        label: 'Store',
//                                        color: {
//                                            border: color4,
//                                            background: color4,
//                                            highlight: {border: color4, background: color4},
//                                            hover: {border: color4, background: color4}
//                                        }
//                                    });
//                                } else if (type == 3) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'diamond',
//                                        label: 'Gateway',
//                                        color: {
//                                            border: color4,
//                                            background: color4,
//                                            highlight: {border: color4, background: color4},
//                                            hover: {border: color4, background: color4}
//                                        }
//                                    });
//                                }
//                            }else if (region == 5) {
//                                if (type == 1) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'square',
//                                        label: 'Relay Station',
//                                        color: {
//                                            border: color5,
//                                            background: color5,
//                                            highlight: {border: color5, background: color5},
//                                            hover: {border: color5, background: color5}
//                                        }
//                                    });
//                                } else if (type == 2) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'circle',
//                                        label: 'Store',
//                                        color: {
//                                            border: color5,
//                                            background: color5,
//                                            highlight: {border: color5, background: color5},
//                                            hover: {border: color5, background: color5}
//                                        }
//                                    });
//                                } else if (type == 3) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'diamond',
//                                        label: 'Gateway',
//                                        color: {
//                                            border: color5,
//                                            background: color5,
//                                            highlight: {border: color5, background: color5},
//                                            hover: {border: color5, background: color5}
//                                        }
//                                    });
//                                }
//                            }
//                            else if (region == 6) {
//                                if (type == 1) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'square',
//                                        label: 'Relay Station',
//                                        color: {
//                                            border: color6,
//                                            background: color6,
//                                            highlight: {border: color6, background: color6},
//                                            hover: {border: color6, background: color6}
//                                        }
//                                    });
//                                } else if (type == 2) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'circle',
//                                        label: 'Store',
//                                        color: {
//                                            border: color6,
//                                            background: color6,
//                                            highlight: {border: color6, background: color6},
//                                            hover: {border: color6, background: color6}
//                                        }
//                                    });
//                                } else if (type == 3) {
//                                    nodes.update({
//                                        id: id,
//                                        shape: 'diamond',
//                                        label: 'Gateway',
//                                        color: {
//                                            border: color6,
//                                            background: color6,
//                                            highlight: {border: color6, background: color6},
//                                            hover: {border: color6, background: color6}
//                                        }
//                                    });
//                                }
//                            }
//                            else if(type==0){
//                                nodes.update({
//                                    id: id,
//                                    shape: 'star',
//                                    label: 'Process Center'
//                                });
//                            }
//                            if (status == 0) {
//                                nodes.update({
//                                    id: id,
//                                    color: {
//                                        border: 'grey',
//                                        background: 'grey',
//                                        highlight: {border: 'grey', background: 'grey'},
//                                        hover: {border: 'grey', background: 'grey'}
//                                    }
//                                });
//                            }
//                        }
//                        for (var index in edges['_data']) {
//                            var id = edges['_data'][index]['id'];
//                            var region = edges['_data'][index]['region'];
//                            if (region == 0) {
//                                edges.update({
//                                    id: id,
//                                    color: {
//                                        color: '#97C2FC',
//                                        highlight: "#2B7CE9",
//                                        hover: "#2B7CE9",
//                                        opacity: 1,
//                                        inherited: false
//                                    }
//                                });
//                            }
//                        }
//END TEST GRAPH
                        for (var index in jsondata['nodes']) {

                            var type = jsondata['nodes'][index]['type'];
                            var id = jsondata['nodes'][index]['id'];
                            var regionId = jsondata['nodes'][index]['regionID'];
                            var color = getColorByRandom(colorList);
                            var status = jsondata['nodes'][index]['status'];
                            console.log('status:', status);
                            if (regionId == 1) {

                                nodes.update({
                                    id: id,
                                    color: {
                                        border: color1,
                                        background: color1,
                                        highlight: {border: color1, background: color1},
                                        hover: {border: color1, background: color1}
                                    }
                                });
                            } else if (regionId == 2) {
                                nodes.update({
                                    id: id,
                                    color: {
                                        border: color2,
                                        background: color2,
                                        highlight: {border: color2, background: color2},
                                        hover: {border: color2, background: color2}
                                    }
                                });
                            } else if (regionId == 3) {
                                nodes.update({
                                    id: id,
                                    color: {
                                        border: color3,
                                        background: color3,
                                        highlight: {border: color3, background: color3},
                                        hover: {border: color3, background: color3}
                                    }
                                });
                            }
                            else if (regionId == 4) {
                                nodes.update({
                                    id: id,
                                    color: {
                                        border: color4,
                                        background: color4,
                                        highlight: {border: color4, background: color4},
                                        hover: {border: color4, background: color4}
                                    }
                                });
                            }
                            else if (regionId == 5) {
                                nodes.update({
                                    id: id,
                                    color: {
                                        border: color5,
                                        background: color5,
                                        highlight: {border: color5, background: color5},
                                        hover: {border: color5, background: color5}
                                    }
                                });
                            }
                            else if (regionId == 6) {
                                nodes.update({
                                    id: id,
                                    color: {
                                        border: color6,
                                        background: color6,
                                        highlight: {border: color6, background: color6},
                                        hover: {border: color6, background: color6}
                                    }
                                });
                            }
                            if (status == 0) {
                                nodes.update({
                                    id: id,
                                    color: {
                                        border: 'grey',
                                        background: 'grey',
                                        highlight: {border: 'grey', background: 'grey'},
                                        hover: {border: 'grey', background: 'grey'}
                                    }
                                });
                            }

                        }


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
                                            ShowDiv('viewNodes', 'fade');
                                            var ip = data['data']['ip'];
                                            var status = data['data']['status'];
                                            var type = data['data']['type'];
                                            console.log('data:', data);
                                            if (status == 1 && type == 1) {
                                                status = 'Activated';
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' + 'regionId:' + regionId
                                                    + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><div><button class="btn" id="inactivate" style="display:block">Inactivate</button>' +
                                                    '<button class="btn" id="activate" style="display: none">Activate</button></div>' + '</div>';
                                            } else if (status == 0 && type == 1) {
                                                status = 'Inactivated';
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' + 'regionId:' + regionId
                                                    + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><div><button class="btn" id="inactivate" style="display:none">Inactivate</button>' +
                                                    '<button class="btn" id="activate" style="display: block">Activate</button></div>' + '</div>';
                                            } else {
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' + 'regionId:' + regionId + '</div>'

                                            }

                                        } else {
                                            alert(data['message']);
                                        }
                                        $(document).ready(function () {
                                            $('#inactivate').click(function () {
                                                console.log('selected id:' + id);
                                                $.ajax({
                                                    type: 'post',
                                                    url: '/inactivate',
                                                    data: {'id': id},
                                                    error: function (data) {
                                                        console.log(data);
                                                    },
                                                    success: function (data) {
                                                        console.log(data);
                                                        if (data['code'] == '001') {
                                                            $('#inactivate').css('display', 'none');
                                                            $('#activate').css('display', 'block');
                                                            console.log(id);
                                                            nodes.update({
                                                                id: id,
                                                                color: {
                                                                    background: 'grey',
                                                                    highlight: {background: 'grey'}
                                                                }
                                                            });
                                                            CloseDiv('viewNodes','fade');
                                                        }
                                                    }

                                                })
                                            })
                                        });
                                        $(document).ready(function () {
                                            $('#activate').click(function () {
                                                console.log('selected id:' + id);
                                                $.ajax({
                                                    type: 'post',
                                                    url: '/activate',
                                                    data: {'id': id},
                                                    error: function (data) {
                                                        console.log(data);
                                                    },
                                                    success: function (data) {
                                                        console.log(data);
                                                        if (data['code'] == '001') {
                                                            $('#inactivate').css('display', 'block');
                                                            $('#activate').css('display', 'none');
                                                            console.log(id);
                                                            nodes.update({
                                                                id: id, color: {
                                                                    border: '#2B7CE9',
                                                                    background: '#97C2FC',
                                                                    highlight: {
                                                                        border: '#2B7CE9',
                                                                        background: '#D2E5FF'
                                                                    },
                                                                    hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                                                }
                                                            });
                                                            CloseDiv('viewNodes','fade');
                                                        }
                                                    }

                                                })
                                            })
                                        });
                                            $('#back').click(function () {
                                                CloseDiv('viewNodes','fade');
                                            });

                                    }
                                });

                            }
//                            else {
//                                document.getElementById('eventSpan').innerHTML = '';
//                            }

                        });
                    }
                }
            });

        }

    }


    function clearRelayForm() {
        document.getElementById('relayID1').value = '';
        document.getElementById('connectedTo').value = '';
        document.getElementById('weight').value = '';
    }

    function clearStoreForm() {
        document.getElementById('relayID2').value = '';
        document.getElementById('storeID2').value = '';
        document.getElementById('weight2').value = '';
    }


    function clearTransForm() {
        document.getElementById('from').value = '';
        document.getElementById('cardNum').value = '';
        document.getElementById('cvv').value = '';
        document.getElementById('holder_name').value = '';
        document.getElementById('amount').value = '';
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
                console.log(data);
                if (data['code'] == '001') {
                    getNodesFunction();
                    CloseDiv('newRelay', 'fade');
                }
                else alert(data['message']);
            },
            error: function (e) {
                console.log(e);
            }
        })

    }

    // add new store
    function addNewStore() {
        console.log(nodes);
        $form = $('#store_form');
        console.log($form.serialize());
        $.ajax({
            url: "/addstation",
            type: 'post',
            data: $form.serialize(),
            success: function (data) {
                console.log(data);
                if (data['code'] == '001') {
                    getNodesFunction();
                    CloseDiv('newStore', 'fade');
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
        $.ajax({
            url: '/queues',
            type: 'get',
            dataType: 'json',
            success: function (data) {
                if (data['code'] == '001') {
                    console.log(data);
                    var queues = data['data'];
                    if (queues == null) {
                        console.log('null');
                        for (var i = 0; i < nodes.length; i++) {
                            nodes.update({
                                id: i,
                                size: 24
                            });
                        }
                    }
                    for (var i = 0; i < queues.length; i++) {
                        var current = queues[i]['current'];
                        var path = JSON.parse(queues[i]['path']);
                        console.log(path);
                        var currentNode = path[current];
                        console.log(currentNode);
                        if (parseInt(queues[i]['status']) == 1) {
                            if (parseInt(currentNode['type']) == 1) {
                                nodes.update({
                                    id: path[current]['id'],
                                    size: 40
                                });
                                if (current != 0) {
                                    edges.update({
                                        id: path[current - 1]['edge']['id'],
                                        width: 2
                                    });
                                }
                            } else if (parseInt(path[current].type) == 2) {
                                edges.update({
                                    id: path[current]['edge']['id'],
                                    width: 7
                                });
                                if (current != 0) {
                                    nodes.update({
                                        id: path[current - 1]['id'],
                                        size: 24
                                    });
                                }
                            }
                            else if (parseInt(path[current]['id']) == 1) {
                                nodes.update({
                                    id: 1,
                                    size: 24
                                });
                            }

                        } else if (parseInt(queues[i]['status']) == 2) {
                            if (parseInt(path[current]['type']) == 1) {
                                nodes.update({
                                    id: path[current]['id'],
                                    size: 40
                                });
                                if (current != path.length - 1) {
                                    edges.update({
                                        id: path[current + 1]['edge']['id'],
                                        width: 2
                                    });
                                }
                                if (current == 0) {
                                    for (var i = 0; i < 4000; i++) {
                                        nodes.update({
                                            id: parseInt(path[0]['id']),
                                            size: 24
                                        });
                                    }
                                }
                            } else if (parseInt(path[current]['type']) == 2) {
                                edges.update({
                                    id: path[current]['edge']['id'],
                                    width: 7
                                });
                                if (current != 0) {
                                    nodes.update({
                                        id: path[current + 1]['id'],
                                        size: 24
                                    });
                                }
                            } else if (parseInt(path[current]['id']) == 1) {
                                edges.update({
                                    id: path[current],
                                    width: 2
                                });
                            }

                        } else {
                            nodes.update({
                                id: path[0]['id'],
                                size: 24
                            });
                        }
                    }
                } else {
                    console.log(data['message']);
                }
            },
            error: function (data) {
                console.log(data);

            }

        });


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
                console.log($form.serialize());
                var jsondata = data['data'];
                if (data['code'] == '001') {
                    CloseDiv('newTrans', 'fade');
//                    path = data['data']['path'];
//                    queueData = data['data'];
//
//                    step = 0;
//                    clock = setInterval("animation()", 2000);
//                     show(data);
                } else {
                    alert(data['message']);
                }
            },
            error: function (e) {
                console.log(e);
            }
        });


    }


    function pause() {
        clearInterval(clock);
    }

    function restart() {
        clock = setInterval("animation()", 2000);
    }

    function loadCards() {
        $.ajax({
            type: "get",
            url: "{{ route('credit.getcards') }}",
            data: {"page": 1},
            success: function (data) {
                if (data['code'] == '001') {
                    $('select#cardNum').empty();
                    console.log(data['data']);
                    var returnData = data['data'];
                    for (var index in returnData['cards']) {
                        var card = returnData['cards'][index];
                        $('select#cardNum').append('<option class="loaded-cards" value="' + card.cardId + '">' + card.cardId + '</option>');
                    }
                }
            },
            error: function (data) {
                console.log("Connection failed");
                console.log(data);
            }
        });
    }

    function loadQueues() {
        $.ajax({
            type: "get",
            url: '/getqueue',
            data: {"page": 1},
            success: function (data) {
                if (data['code'] == '001') {
                    console.log('data:', data);
//                    var returnData = JSON.parse(data['data']);
                    loadList(data);
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
        var index;
        for (index in data['data']) {
            var queue = data['data'][index];
            var sendId = queue.from;
            if (queue.status == '1') {
                queue.status = 'sending';
            } else if (queue.status == '2') {
                queue.status = 'returning';
            } else if (queue.status == '3') {
                queue.status = 'finished';
            } else {
                queue.status = 'declined';
            }

            $('#queues').append('<tr class="loaded-data"><th id="Store Ip">' + queue.id +
                '</th><th id="CreditCard">' + queue.card + '</th><th id="HolderName">' + queue.holder_name +
                '</th><th id="Amount">' + queue.amount + '</th><th id="Status">' + queue.status + '</th> + ' +
                '<th id="result">' + queue.result + '</th><th id="message">' + queue.message + "</th>" +
                '<td>' + ' <button type="button" class="btn btn-primary" onclick="disable(this.id); send(' + sendId + ');">Send</button>' + '</td></tr>');
            $('#queues button').eq(index).attr('id', "btn" + index);
            console.log('sendid:', sendId);
        }
//        loadQueues();
    }

    function disable(id) {
        console.log("id: ", id);
        $('#' + id).attr('disabled', "true");
    }

    function send(sendId) {
        console.log("sengId: ", sendId);
        var from = sendId;
//        $.ajax({
//            url: '/shortest',
//            type: 'post',
//            data: {'from': '64.233.161.148'},
//            success: function (data) {
//                console.log('data:', data);
//                var jsondata = data['data'];
//                if (data['code'] == '001') {
//                    clock = setInterval("animation()", 2000);
//                } else {
//                    alert(data['message']);
//                }
//            },
//            error: function (e) {
//                console.log(e);
//            }
//        });
    }


    function getColorByRandom(colorList) {
        var colorIndex = Math.floor(Math.random() * colorList.length);
        var color = colorList[colorIndex];
        colorList.splice(colorIndex, 1);
        return color;
    }


    //    function randomColor(){
    //        var r=Math.floor(Math.random()*256);
    //        var g=Math.floor(Math.random()*256);
    //        var b=Math.floor(Math.random()*256);
    //        return "rgb("+r+','+g+','+b+")";
    //    }

</script>
</body>
</html>