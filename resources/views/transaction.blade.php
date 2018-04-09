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
            width: 1300px;
            height: 600px;
            margin-left: 75px;
            margin-top: 10px;
            border: 1px solid lightgray;

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
            z-index: 1002;
            overflow: auto;
        }

        #view {
            margin-left: 180px;
            margin-top: 50px;
        }
        #buttonField{
            margin-left: -20px;
        }

        #relayForm {
            position: absolute;
            top: 20%;
            left: 20%;
            width: 90%;
            height: 20%;
        }

        #storeForm {
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Action <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" onclick="ShowDiv('newRelay','fade')">Add new relay</a>
                            </li>
                            <li>
                                <a href="#" onclick="ShowDiv('newStore','fade')">Add new store</a>
                            </li>
                            <li>
                                <a href="#" onclick="ShowDiv('newTrans','fade')">Add new transaction</a>
                            </li>
                            <li>
                                <a href="#" onclick="ShowDiv('addRegion','fade')">Add new region</a>
                            </li>
                            <li>
                                <a href="#" >TBD...</a>
                            </li>
                        </ul>
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
    <div id="action" style="margin-left:50px;margin-top:20px">
        <button type="button" class="btn btn-success" onclick="restart()">Resume</button>
        <button type="button" class="btn btn-danger" style="margin-left: 20px" onclick="pause()">Pause</button>
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
            <input type="hidden" name="type" value="1">
            <label>Relay Station Ip:<input type="text" id="relayID1"
                                           name="ip"></label>
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
            <label>Relay Station Ip:<input type="text" id="relayID2" class="store_input" name="to"></label>
            <label>Store Ip:<input type="text" id="storeID2" name="ip" class="store_input" style="margin-left: 70px;"></label>
            <label>Merchant's Name:<input type="text" id="merchantName" name="merchantName" class="store_input"
                                           style="margin-left: 10px;margin-top: 5px"></label>
            <label>Weight:<input type="text" id="weight2" name="weight" class="store_input" style="margin-left: 77px;"></label>
            <input name="type" type="hidden" value="2">
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
            <!--            <label>Ip<input type="text" id="from" name="from" style="margin-left: 112px"></label>-->
            <label>Type:<select id="type" name="type" style="margin-left: 90px">
                    <option value="credit">Credit</option>
                    <option value="debit">Debit</option>
                </select></label>
            <label>Merchant's Name:
                <select id="mName" name="mName" style="margin-left: 4px;margin-top: 5px"></select>
            </label>
            <label>Credit Card:<input type="text" id="cardNum" name='cardNum' style="margin-left: 48px"/></label>
            <label>CVV:<input type="text" id="cvv" name="cvv" style="margin-left: 94px"></label>
            <label>Expiration Date:<select id="month" name="month" style="width: 80px;margin-left: 24px"></select><select id="year" name="year" style="width: 80px;margin-left: 10px"></select></label>
            <label>Billing Address:<input type="text" id="billing" name="billing" style="margin-left: 27px"></label>
            <label>Holder Name:<input type="text" id="holder_name" name="holder_name"
                                      style="margin-left: 38px"></label>
            <label>Amount:<input type="text" id="amount" name="amount" style="margin-left: 71px"></label>
            <div style="margin-left: 150px">
                <input type="button" value="Submit" class="btn btn-primary" id="submit3" onclick="sendNewTrans()">
                <input type="button" value="Clear" class="btn" id="clear3" onclick="CloseDiv('newTrans','fade')">
            </div>
        </form>
    </div>
</div>
<div id="addRegion" class="white_content">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>Add new region</h2>
    </div>
    <div id="regionForm" style="margin-left:350px">
        <form id="region_form">
            <label>Gateway Ip:<input type="text" id="ip" name="ip" style="margin-left: 65px"></label>
            <label>Weight to PC:<input type="text" id="weight_for_pc" name="weight_for_pc" style="margin-left: 54px"></label>
            <label>Relay Station Ip:<input type="text" id="stationIp" name="stationIp" style="margin-left: 38px"></label>
            <label>Weight to Gateway:<input type="text" id="weight_for_station" name="weight_for_station"></label>
            <div style="margin-left: 150px">
                <input type="button" value="Submit" class="btn btn-primary" id="submit4" onclick="addRegion()">
                <input type="button" value="Clear" class="btn" id="clear4" onclick="CloseDiv('addRegion','fade')">
            </div>
        </form>
    </div>
</div>
<div id="limitChanging" class="white_content_small">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>Limit Changing</h2>
    </div>
    <div id="showLimit" style="text-align: center;height: 40px"></div>
    <div id="limitForm" style="margin-left:100px">
        <form id="limit_form">
            <label>New Limit:<input type="text" id="limit" name="limit"></label>
            <div style="margin-left: 120px">
                <input type="button" value="Submit" class="btn btn-primary" id="submit5">
                <input type="button" value="Clear" class="btn" id="clear5" onclick="CloseDiv('limitChanging','fade')">
            </div>
        </form>
    </div>
</div>
<div id="viewNodes" class="white_content_small">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>View Selected Node</h2>
    </div>
</div>
<div id="viewEdges" class="white_content_small">
    <div style="text-align: center; cursor: default; height: 40px;">
        <h2>View Selected Edge</h2>
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
    var color7 = getColorByRandom(colorList);
    var color8 = getColorByRandom(colorList);

    var region;

    var username = sessionStorage.getItem("username");
    if (username == null) {
        alert("You should login first");
        window.location.replace("/");
    } else {
        $('#username').text(username);
    }

    function getNodesFunction(){
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
                        loadMerchantName();
                        loadDate();
                        var jsondata = data['data'];
                        nodes = new vis.DataSet(jsondata['nodes']);
                        edges = new vis.DataSet(jsondata['edges']);
//                        nodes = new vis.DataSet(testNodes);
//                        edges = new vis.DataSet(testEdges);
//
//                        console.log(nodes['_data']['4'].status);

//                        console.log(jsondata);

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

                        console.log('nodes:', nodes['_data']);
                        console.log('edges:', edges['_data']);
//test graph
//Draw graph
                        for (var index in nodes['_data']) {
                            region = nodes['_data'][index]['region'];
                            var id = nodes['_data'][index]['id'];
                            var type = nodes['_data'][index]['type'];
                            var status = nodes['_data'][index]['status'];

                            if (region == 1) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color1,
                                            background: color1,
                                            highlight: {border: color1, background: color1},
                                            hover: {border: color1, background: color1}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color1,
                                            background: color1,
                                            highlight: {border: color1, background: color1},
                                            hover: {border: color1, background: color1}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color1,
                                            background: color1,
                                            highlight: {border: color1, background: color1},
                                            hover: {border: color1, background: color1}
                                        }
                                    });
                                }
                            }
                            else if (region == 2) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color2,
                                            background: color2,
                                            highlight: {border: color2, background: color2},
                                            hover: {border: color2, background: color2}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color2,
                                            background: color2,
                                            highlight: {border: color2, background: color2},
                                            hover: {border: color2, background: color2}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color2,
                                            background: color2,
                                            highlight: {border: color2, background: color2},
                                            hover: {border: color2, background: color2}
                                        }
                                    });
                                }
                            } else if (region == 3) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color3,
                                            background: color3,
                                            highlight: {border: color3, background: color3},
                                            hover: {border: color3, background: color3}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color3,
                                            background: color3,
                                            highlight: {border: color3, background: color3},
                                            hover: {border: color3, background: color3}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color3,
                                            background: color3,
                                            highlight: {border: color3, background: color3},
                                            hover: {border: color3, background: color3}
                                        }
                                    });
                                }
                            } else if (region == 4) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color4,
                                            background: color4,
                                            highlight: {border: color4, background: color4},
                                            hover: {border: color4, background: color4}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color4,
                                            background: color4,
                                            highlight: {border: color4, background: color4},
                                            hover: {border: color4, background: color4}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color4,
                                            background: color4,
                                            highlight: {border: color4, background: color4},
                                            hover: {border: color4, background: color4}
                                        }
                                    });
                                }
                            }else if (region == 5) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color5,
                                            background: color5,
                                            highlight: {border: color5, background: color5},
                                            hover: {border: color5, background: color5}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color5,
                                            background: color5,
                                            highlight: {border: color5, background: color5},
                                            hover: {border: color5, background: color5}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color5,
                                            background: color5,
                                            highlight: {border: color5, background: color5},
                                            hover: {border: color5, background: color5}
                                        }
                                    });
                                }
                            } else if (region == 6) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color6,
                                            background: color6,
                                            highlight: {border: color6, background: color6},
                                            hover: {border: color6, background: color6}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color6,
                                            background: color6,
                                            highlight: {border: color6, background: color6},
                                            hover: {border: color6, background: color6}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color6,
                                            background: color6,
                                            highlight: {border: color6, background: color6},
                                            hover: {border: color6, background: color6}
                                        }
                                    });
                                }
                            }
                            else if (region == 7) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color7,
                                            background: color7,
                                            highlight: {border: color7, background: color7},
                                            hover: {border: color7, background: color7}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color7,
                                            background: color7,
                                            highlight: {border: color7, background: color7},
                                            hover: {border: color7, background: color7}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color7,
                                            background: color7,
                                            highlight: {border: color7, background: color7},
                                            hover: {border: color7, background: color7}
                                        }
                                    });
                                }
                            }
                            else if (region == 8) {
                                if (type == 1) {
                                    nodes.update({
                                        id: id,
                                        shape: 'square',
                                        label: 'Relay Station',
                                        color: {
                                            border: color8,
                                            background: color8,
                                            highlight: {border: color8, background: color8},
                                            hover: {border: color8, background: color8}
                                        }
                                    });
                                } else if (type == 2) {
                                    nodes.update({
                                        id: id,
                                        shape: 'circle',
                                        label: 'Store',
                                        color: {
                                            border: color8,
                                            background: color8,
                                            highlight: {border: color8, background: color8},
                                            hover: {border: color8, background: color8}
                                        }
                                    });
                                } else if (type == 3) {
                                    nodes.update({
                                        id: id,
                                        shape: 'diamond',
                                        label: 'Gateway',
                                        color: {
                                            border: color8,
                                            background: color8,
                                            highlight: {border: color8, background: color8},
                                            hover: {border: color8, background: color8}
                                        }
                                    });
                                }
                            }
                            else if(type==0){
                                nodes.update({
                                    id: id,
                                    shape: 'star',
                                    label: 'Process Center'
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
                        for (var index in edges['_data']) {
                            var id = edges['_data'][index]['id'];
                            var region = edges['_data'][index]['region'];
                            if (region == 0) {
                                edges.update({
                                    id: id,
                                    color: {
                                        color: '#97C2FC',
                                        highlight: "#2B7CE9",
                                        hover: "#2B7CE9",
                                        opacity: 1,
                                        inherited: false
                                    }
                                });
                            }
                        }
//END TEST GRAPH
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
                                            var region = data['data']['region'];
                                            var limit = data['data']['limit'];
                                            var merchantName = data['data']['merchantName'];
                                            console.log('data:', data);
                                            if (status == 1 && type == 1) {
                                                status = 'Activated';
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' + 'regionId:' + region
                                                    + '<br/>' + 'current limit:' + limit + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><div id="buttonField"><input class="btn" id="inactivate" style="display:block" value="Inactivate">' +
                                                    '<input class="btn" id="activate" style="display: none" value="Activate"></div>' + '</div>';
                                            } else if (status == 0 && type == 1) {
                                                status = 'Inactivated';
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' + 'regionId:' + region
                                                    + '<br/>' + 'current limit:' + limit + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><div id="buttonField"><input type="button" class="btn" id="inactivate" style="display:none" value="Inactivate">' +
                                                    '<input class="btn" id="activate" style="display: block" value="Activate">' + '<input class="btn" id="changeLimit" style="display: block" value="Change Limit">' + '</div>' + '</div>';
                                            } else if (status == 1 && type == 3) {
                                                status = 'Activated';
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' + 'regionId:' + region
                                                    + '<br/>' + 'ccurrent limit:' + limit + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><div id="buttonField"><input class="btn" id="inactivate" style="display:block" value="Inactivate">' +
                                                    '<input class="btn" id="activate" style="display: none" value="Activate"></div>' + '</div>';
                                            } else if (status == 0 && type == 3) {
                                                status = 'Inactivated';
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' + 'regionId:' + region
                                                    + '<br/>' + 'current limit:' + limit + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><div id="buttonField"><input type="button" class="btn" id="inactivate" style="display:none" value="Inactivate">' +
                                                    '<input class="btn" id="activate" style="display: block" value="Activate">' + '<input class="btn" id="changeLimit" style="display: block" value="Change Limit">' + '</div>' + '</div>';
                                            }
                                            else {
                                                document.getElementById('viewNodes').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip + '<br/>' +
                                                    'regionId:' + region + '<br/>' + 'Merchant Name:' + merchantName + '</div>'

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
                                                                id: id, color: {
                                                                    border: 'grey',
                                                                    background: 'grey',
                                                                    highlight: {
                                                                        border: 'grey',
                                                                        background: 'grey'
                                                                    },
                                                                    hover: {border: 'grey', background: 'grey'}
                                                                }
                                                            });
                                                            CloseDiv('viewNodes', 'fade');
                                                        }
                                                    }

                                                })
                                            })
                                        });
                                        $(document).ready(function () {
                                            $('#activate').click(function () {
                                                $.ajax({
                                                    type: 'post',
                                                    url: '/activate',
                                                    data: {'id': id},
                                                    error: function (data) {
                                                        console.log(data);
                                                    },
                                                    success: function (data) {
                                                        console.log("data:",data);
                                                        if (data['code'] == '001') {
                                                            console.log('region :',region);
                                                            $('#inactivate').css('display', 'block');
                                                            $('#activate').css('display', 'none');
                                                            console.log("id:",id);
                                                               if(region=='1'){
                                                                   nodes.update({
                                                                       id: id, color: {
                                                                           border: color1,
                                                                           background: color1,
                                                                           highlight: {
                                                                               border: color1,
                                                                               background: color1
                                                                           },
                                                                           hover: {border: color1, background: color1}
                                                                       }
                                                                   });
                                                               }
                                                               else if(region=='2'){
                                                                   nodes.update({
                                                                       id: id, color: {
                                                                           border: color2,
                                                                           background: color2,
                                                                           highlight: {
                                                                               border: color2,
                                                                               background: color2
                                                                           },
                                                                           hover: {border: color2, background: color2}
                                                                       }
                                                                   });
                                                               }
                                                            else if(region=='3'){
                                                                nodes.update({
                                                                    id: id, color: {
                                                                        border: color3,
                                                                        background: color3,
                                                                        highlight: {
                                                                            border: color3,
                                                                            background: color3
                                                                        },
                                                                        hover: {border: color3, background: color3}
                                                                    }
                                                                });
                                                            }
                                                            else if(region=='4'){
                                                                nodes.update({
                                                                    id: id, color: {
                                                                        border: color4,
                                                                        background: color4,
                                                                        highlight: {
                                                                            border: color4,
                                                                            background: color4
                                                                        },
                                                                        hover: {border: color4, background: color4}
                                                                    }
                                                                });
                                                            }else if(region=='5'){
                                                                nodes.update({
                                                                    id: id, color: {
                                                                        border: color5,
                                                                        background: color5,
                                                                        highlight: {
                                                                            border: color5,
                                                                            background: color5
                                                                        },
                                                                        hover: {border: color5, background: color5}
                                                                    }
                                                                });
                                                            }
                                                            else if(region=='6'){
                                                                nodes.update({
                                                                    id: id, color: {
                                                                        border: color6,
                                                                        background: color6,
                                                                        highlight: {
                                                                            border: color6,
                                                                            background: color6
                                                                        },
                                                                        hover: {border: color6, background: color6}
                                                                    }
                                                                });
                                                            }
                                                            else if(region=='7'){
                                                                nodes.update({
                                                                    id: id, color: {
                                                                        border: color7,
                                                                        background: color7,
                                                                        highlight: {
                                                                            border: color7,
                                                                            background: color7
                                                                        },
                                                                        hover: {border: color7, background: color7}
                                                                    }
                                                                });
                                                            }
                                                            else if(region=='8'){
                                                                nodes.update({
                                                                    id: id, color: {
                                                                        border: color8,
                                                                        background: color8,
                                                                        highlight: {
                                                                            border: color8,
                                                                            background: color8
                                                                        },
                                                                        hover: {border: color8, background: color8}
                                                                    }
                                                                });
                                                            }
                                                            CloseDiv('viewNodes', 'fade');
                                                        }
                                                    }

                                                })
                                            })
                                        });
                                        $('#changeLimit').click(function () {
                                            document.getElementById('showLimit').innerHTML = 'Current Limit:' + limit;
                                            CloseDiv('viewNodes','fade');
                                            ShowDiv('limitChanging','fade');
                                        });
                                        $('#back').click(function () {
                                            CloseDiv('viewNodes', 'fade');
                                        });

                                    }
                                });

                            }
                            else if(params.edges.length != 0){
                                var edgeId = params.edges[0];
                                document.getElementById('viewEdges').innerHTML = '<button type="button" class="btn btn-primary" id="back">' + 'back' + '</button>' + '<div id="view">' + 'selected edge id :' + edgeId + '<div id="buttonField"><input class="btn" id="inactivate" style="display:block" value="Inactivate">' +
                                    '<input class="btn" id="activate" style="display: none" value="Activate"></div>' + '</div>';
                                ShowDiv('viewEdges','fade');
                                console.log('edgeid:',edgeId);

                                $('#back').click(function () {
                                    CloseDiv('viewEdges', 'fade');
                                });
                                $(document).ready(function () {
                                    $('#inactivate').click(function () {
                                        $('#inactivate').css('display', 'none');
                                        $('#activate').css('display', 'block');
                                        edges.update({
                                            id: edgeId,
                                            color: {
                                                color: 'grey',
                                                highlight: 'grey',
                                                hover: 'grey',
                                                opacity: 1,
                                                inherited: false
                                            }
                                        });
                                    })
                                });
                                $(document).ready(function () {
                                    $('#activate').click(function () {
                                        $('#inactivate').css('display', 'block');
                                        $('#activate').css('display', 'none');
                                    })
                                });

                            }

                        });
                    }
                }
            });

        }

    }



    function addRelay() {
        $form = $('#relay_form');
        console.log($form.serialize());
        var validate_relay = validateRelay();
        console.log('validate:',validate_relay);
        if(validate_relay==true){
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
        }else{
            alert('input required');
        }
    }

    // add new store

    function addNewStore() {
        console.log(nodes);
        $form = $('#store_form');
        console.log($form.serialize());
        var validate_store = validateStore();
        if(validate_store==true){
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
        }else {
            alert('input required');
        }

    }

    function addRegion() {
        $form = $('#region_form');
        console.log($form.serialize());
        var validate_region = validateRegion();
        if(validate_region==true){
            $.ajax({
                url: "/addgateway",
                type: 'post',
                data: $form.serialize(),
                success:function (data) {
                    if(data['code']=='001'){
                        console.log('data:',data);
                        getNodesFunction();
                        CloseDiv('addRegion', 'fade');
                    } else {
                        console.log($form.serialize());
                    }
                },
                error:function (e) {
                    console.log(e);
                }
            })
        }else{
            alert('input required');
        }
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
        var validate_trans = validateTrans();
        if(validate_trans==true){
            $.ajax({
                url: '/shortest',
                type: 'post',
                data: $form.serialize(),
                success: function (data) {
                    console.log($form.serialize());
                    var jsondata = data['data'];
                    if (data['code'] == '001') {
                        CloseDiv('newTrans', 'fade');
//                        clock = setInterval("animation()", 2000);
                    } else {
                        alert(data['message']);
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }else{
            alert('input required');
        }
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
    function loadMerchantName(){
        $.ajax({
            type: 'GET',
            dataType: "json",
            url: "/graph",
            success: function(data){
                if (data['code'] == '001') {
                    $('select#mName').empty();
                    var payType = document.getElementById('type').value;
                    console.log('type:',payType);
                    var returnData = data['data']['nodes'];
                    for (var index in returnData) {
                        var name = returnData[index]['merchantName'];
                        var type = returnData[index]['type'];
                        if(type == '2'){
                            $('#mName').append('<option class="loaded-name" value=' + name + '>' + name + '</option>');
                        }
                    }
                }else {
                    console.log(data['message']);
                }
            },
            error: function (e) {
                console.log(e);
            }
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
        $.ajax({
            url: '/shortest',
            type: 'post',
            data: {'from': '64.233.161.148'},
            success: function (data) {
                console.log('data:', data);
                var jsondata = data['data'];
                if (data['code'] == '001') {
                    clock = setInterval("animation()", 2000);
                } else {
                    alert(data['message']);
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }
    function validateRelay(){
        var relayID1 = document.getElementById('relayID1').value;
        var connectedTo = document.getElementById('connectedTo').value;
        var weight = document.getElementById('weight').value;
        if(relayID1==''){
            return false;
        }else if(connectedTo==''){
            return false;
        }else if(weight==''){
            return false;
        }else{
            return true;
        }
    }
    function validateStore(){
        var relayID2 = document.getElementById('relayID2').value;
        var storeID2 = document.getElementById('storeID2').value;
        var merchantName = document.getElementById('merchantName').value;
        var weight2 = document.getElementById('weight2').value;
        if(relayID2==''){
            return false;
        }else if(storeID2==''){
            return false;
        }else if(merchantName==''){
            return false;
        }else if(weight2==''){
            return false;
        }else{
            return true;
        }
    }
    function validateTrans() {
        var cardNum = document.getElementById('cardNum').value;
        var cvv = document.getElementById('cvv').value;
        var billing = document.getElementById('billing').value;
        var holder_name = document.getElementById('holder_name').value;
        var amount = document.getElementById('amount').value;
        if(cardNum==''){
            return false;
        }else if(cvv==''){
            return false;
        }else if(billing==''){
            return false;
        }else if(holder_name==''){
            return false;
        }else if(amount==''){
            return false;
        }else{
            return true;
        }
    }
    function validateRegion() {
        var ip = document.getElementById('ip').value;
        var weight_for_pc = document.getElementById('weight_for_pc').value;
        var stationIp = document.getElementById('stationIp').value;
        var weight_for_station = document.getElementById('weight_for_station').value;
        if(ip==''){
            return false;
        }else if(weight_for_station==''){
            return false;
        }else if(stationIp==''){
            return false;
        }else if(weight_for_pc==''){
            return false;
        }else{
            return true;
        }
    }


    function getColorByRandom(colorList) {
        var colorIndex = Math.floor(Math.random() * colorList.length);
        var color = colorList[colorIndex];
        colorList.splice(colorIndex, 1);
        return color;
    }
    document.onkeydown = function (event) {
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if (e && e.keyCode == 27) {
            CloseDiv('viewNodes', 'fade');
            CloseDiv('newTrans', 'fade');
            CloseDiv('newStore', 'fade');
            CloseDiv('newRelay', 'fade');
            CloseDiv('viewEdges', 'fade');
            CloseDiv('addRegion', 'fade');
        }
    };

    $('#type')
        .change( function () {
            var val = $('#type').val();
            $('#self').remove();
            if(val == 'debit') {
                $('#mName').append('<option class="loaded-name" value="self" id="self">SELF</option>');
            }
        })
        .trigger('change');

</script>
</body>
</html>