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
            position: absolute;
            width: 300px;
            height: 00px;
            right: 0px;
            margin-left: 10px;
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
    <button type="button" class="btn btn-primary" id="addRelay">Add new Relay</button>
    <div id="relayForm">
        <form id="relay_form" style="display: none">
            <input hidden="hidden" name="type" value="1">

            <label>Relay Station Ip:<input type="text" id="relayID1"
                                           name="ip"></label>
            <label>ConnectedTo Ip:<input type="text" id="connectedTo" name="to"></label>
            <label>Weight:<input type="text" id="weight" name="weight" style="margin-left: 77px;"></label>
            <input type="button" value="Submit" class="btn btn-primary" id="submit1" onclick="addRelay()">
            <input type="button" value="Clear" class="btn" id="clear1" onclick="clearRelayForm()">
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
                <input type="button" value="Clear" class="btn" id="clear2" onclick="clearStoreForm()">
            </form>

        </div>
    </div>
    <div id="sendTrans">
        <button type="button" class="btn btn-primary" id="send">New Transaction</button>
        <div id="sendForm">
            <form id="send_form" style="display: none">
                <label>Store IP:<input type="text" id="from" name="from" style="margin-left: 70px"></label>
                <label>Credit Card:<select id="cardNum" name='cardNum' style="margin-left: 48px"></select></label>
                <label>CVV:<input type="text" id="cvv" name="cvv" style="margin-left: 94px"></label>
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
<div id="eventSpan"></div>
<div id="mynetwork"></div>
<div id="queueField" style="margin-top:650px">
    <table class="table table-bordered table-striped" id="queueTable">
        <thead>
        <tr>
            <th>
                Store Ip
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
        </tr>
        </thead>
        <tbody id="queues">

        </tbody>

    </table>
</div>
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
        })
    });
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

//
//                        console.log(nodes['_data']['4'].status);

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
                                                document.getElementById('eventSpan').innerHTML = 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip
                                                    + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><input class="btn" type="button"  value="Inactivate" id="inactivate">';
                                            } else {
                                                status = 'Inactivated';
                                                document.getElementById('eventSpan').innerHTML = 'selected node id :' + id + '<br/>' + 'selected node ip :' + ip
                                                    + '<br/>' + 'status :' + status + '<br/>' + 'queues :' + data['data']['queues'] + '<br/><input  class="btn" type="button" value="Activate" id="activate">';
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
                                                            console.log(id);
                                                            nodes.update({id: id, color: {background: 'grey', highlight: {background: 'grey'}}});
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
                                                            console.log(id);
                                                            nodes.update({id: id, color: {
                                                                border: '#2B7CE9',
                                                                background: '#97C2FC',
                                                                highlight: {border: '#2B7CE9', background: '#D2E5FF'},
                                                                hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                                            }});
                                                        }
                                                    }

                                                })
                                            })
                                        });

                                    }
                                });

                            } else {
                                document.getElementById('eventSpan').innerHTML = '';
                            }


                        });
                        for (var i = 1; i<=nodes.length;i++){
                            if (nodes['_data'][i].status     == '0'){
                                nodes.update({
                                    id: nodes['_data'][i].id,
                                    color: {
                                        border: 'grey',
                                        background: 'grey',
                                        highlight: {border: 'grey', background: 'grey'},
                                        hover: {border: 'grey', background: 'grey'}
                                    }
                                });
                            }
                        }
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
                console.log(data);
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
        $.ajax({
            url: '/queues',
            type: 'get',
            dataType:'json',
            success: function (data) {
                if (data['code'] == '001') {
                    console.log(data);
                    var queues = data['data'];
                    if (queues == null) {
                        for (var i = 0; i < nodes.length; i++) {
                            nodes.update({
                                id: i,
                                color: {
                                    border: '#2B7CE9',
                                    background: '#97C2FC',
                                    highlight: {border: '#2B7CE9', background: '#D2E5FF'},
                                    hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                }
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
                                    color: {background: 'red', highlight: {background: 'red'}}
                                });
                                if (current != 0) {
                                    edges.update({
                                        id: path[current - 1]['edge']['id'],
                                        color: {
                                            color: "#848484",
                                            highlight: "#848484",
                                            hover: "#848484",
                                            opacity: 1,
                                            inherited: false
                                        }
                                    });
                                }
                            } else if (parseInt(path[current].type) == 2) {
                                edges.update({
                                    id: path[current]['edge']['id'],
                                    color: {color: 'red', highlight: 'red', hover: 'red'}
                                });
                                if (current != 0) {
                                    nodes.update({
                                        id: path[current - 1]['id'],
                                        color: {
                                            border: '#2B7CE9',
                                            background: '#97C2FC',
                                            highlight: {border: '#2B7CE9', background: '#D2E5FF'},
                                            hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                        }
                                    });
                                }
                            } else if (parseInt(path[current]['id']) == 1) {
                                nodes.update({
                                    id: 1,
                                    color: {
                                        border: '#2B7CE9',
                                        background: '#97C2FC',
                                        highlight: {border: '#2B7CE9', background: '#D2E5FF'},
                                        hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                    }
                                });
                            }

                        } else if (parseInt(queues[i]['status']) == 2) {
                            if (parseInt(path[current]['type']) == 1) {
                                nodes.update({
                                    id: path[current]['id'],
                                    color: {background: 'red', highlight: {background: 'red'}}
                                });
                                if (current != path.length - 1) {
                                    edges.update({
                                        id: path[current + 1]['edge']['id'],
                                        color: {
                                            color: "#848484",
                                            highlight: "#848484",
                                            hover: "#848484",
                                            opacity: 1,
                                            inherited: false
                                        }
                                    });
                                }
                                if (current == 0) {
                                    for (var i = 0; i < 4000; i++) {
                                        nodes.update({
                                            id: parseInt(path[0]['id']),
                                            color: {
                                                border: '#2B7CE9',
                                                background: '#97C2FC',
                                                highlight: {border: '#2B7CE9', background: '#D2E5FF'},
                                                hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                            }
                                        });
                                    }
                                }
                            } else if (parseInt(path[current]['type']) == 2) {
                                edges.update({
                                    id: path[current]['edge']['id'],
                                    color: {color: 'red', highlight: 'red', hover: 'red'}
                                });
                                if (current != 0) {
                                    nodes.update({
                                        id: path[current + 1]['id'],
                                        color: {
                                            border: '#2B7CE9',
                                            background: '#97C2FC',
                                            highlight: {border: '#2B7CE9', background: '#D2E5FF'},
                                            hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                        }
                                    });
                                }
                            } else if (parseInt(path[current]['id']) == 1) {
                                edges.update({
                                    id: path[current],
                                    color: {
                                        color: "#848484",
                                        highlight: "#848484",
                                        hover: "#848484",
                                        opacity: 1,
                                        inherited: false
                                    }
                                });
                            }

                        } else {
                            nodes.update({
                                id: path[0]['id'],
                                color: {
                                    border: '#2B7CE9',
                                    background: '#97C2FC',
                                    highlight: {border: '#2B7CE9', background: '#D2E5FF'},
                                    hover: {border: '#2B7CE9', background: '#D2E5FF'}
                                }
                            });
                        }
                    }
                }else {
                    console.log(data['message']);
                }
            },
            error:function (data) {
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
                    path = data['data']['path'];
                    queueData = data['data'];

                    step = 0;
                    clock = setInterval("animation()", 2000);
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

        for (var index in data['data']) {
            var queue = data['data'][index];
            if(queue.status == '1'){
                queue.status = 'sending';
            }else if(queue.status == '2'){
                queue.status = 'returning';
            }else if (queue.status == '3'){
                queue.status = 'finished';
            }else {
                queue.status = 'declined';
            }

            $('#queues').append('<tr class="loaded-data"><th id="Store Ip">' + queue.id +
                '</th><th id="CreditCard">' + queue.card + '</th><th id="HolderName">' + queue.holder_name +
                '</th><th id="Amount">' + queue.amount + '</th><th id="Status">' + queue.status + '</th> + ' +
                '<th id="result">' + queue.result + '</th><th id="message">' + queue.message + "</th>")
        }

        loadQueues();

    }

    function show(data) {
        for (var i = 0; i < data['data'].length; i++) {
            nodes.update({id: data['data'][i], color: {background: 'red', highlight: {background: 'red'}}});
        }
    }

</script>
</body>
</html>