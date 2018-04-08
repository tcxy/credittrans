<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Model\Connection\Connection;
use App\Model\Station\Station;
use App\Custom\Graph;
use App\Model\Queue\Queue;
use function MongoDB\BSON\fromJSON;

class StationController extends Controller
{
    //
    public function getGraph(Request $request)
    {
        $stations = Station::all();
        $nodes = [];

        foreach ($stations as $station) {
            if ($station->type == 0) {
                $station->shape = 'star';
                $station->label = 'process center';
            } else if ($station->type == 1) {
                $station->shape = 'triangle';
                $station->label = 'relay station';
            } else {
                $station->shape = 'circle';
                $station->label = 'store';
            }
            array_push($nodes, $station);
        }

        $connections = Connection::all();
        $edges = [];
        foreach ($connections as $connection) {
            $connection->label = '' . $connection->weight;
            array_push($edges, $connection);
        }

        return response()->json(['code' => '001', 'data' => [
            'nodes' => $nodes, 'edges' => $edges
        ]]);

    }

    public function add(Request $request)
    {
        $type = $request->input('type');
        $status = true;
        $ip = $request->input('ip');
        $connect_to = $request->input('to');
        $weight = $request->input('weight');

        if ($connect_to == '32.181.121.11' && $type == 2) {
            return response()->json(['code' => '002', 'message' => 'The store cannot be connected to Processing Center']);
        }

        if ($type == 1) {
            $station = Station::where('ip', '=', $connect_to)->get()->first();
            if ($station->type == 2) {
                return response()->json(['code' => '002', 'message' => 'The relay cannot be connected to a store']);
            }
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) == False) {
            return response()->json(['code' => '002', 'message' => 'The ip address is not validate ipv4 or ipv6 address, please check it and reinput again']);
        }

        $stations = Station::all();
        foreach ($stations as $station) {
            if ($station->ip == $ip) {
                return response()->json(['code' => '002', 'message' => 'The ip address has already exist']);
            }
        }
        $station = new Station;
        $station->status = $status;
        $station->ip = $ip;
        $station->type = $type;

        // $station = Station::all()->where('ip', $ip);
        $to_station = Station::where('ip', '=', $connect_to)->get()->first();

        if ($to_station == null) {
            return response()->json(['code' => '002', 'message' => 'The station doesn\'t exist']);
        }
        $to = $to_station->id;
        $station->region = $to_station->region;
        if ($type == 2) {
            $station->merchantName = $request->input('merchantName');
        }
        $station->save();

        $connection = new Connection;
        $connection->from = $station->id;
        $connection->to = $to;
        $connection->weight = $weight;
        $connection->region = $to_station->region;
        $connection->save();

        return response()->json(['code' => '001']);
    }

    /**
     * The shortest without queue
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    function shortest(Request $request)
//    {
//
//        $from_id = $request->input('from');
//        $start_station = Station::where('ip', '=', $from_id)->get()->first();
//
//        $to = '192.168.0.1';
//        $graph = Graph::create();
//        $edges = Connection::all();
//        foreach ($edges as $edge) {
//            $from_station = Station::find($edge->from);
//            $to_station = Station::find($edge->to);
//            $graph->add($from_station->ip, $to_station->ip, $edge->weight);
//        }
//
//        $route = $graph->search($start_station->ip, $to);
//        $route_id = array();
//
//        foreach ($route as $node) {
//            $station = Station::where('ip', '=', $node)->get()->first();
//            array_push($route_id, $station->id);
//        }
//
//        $edges = array();
//        for ($i = 0; $i < sizeof($route) - 1; $i++) {
//            $from_station = Station::where('ip', '=',$route[$i])->get()->first();
//            $to_station = Station::where('ip', '=', $route[$i + 1])->get()->first();
//
//            $edge = Connection::where(['from' => $from_station->id, 'to' => $to_station->id])->get()->first();
//
//            array_push($edges, ['type' => '1', 'id' => $from_station->id]);
//            array_push($edges, ['type' => '2', 'edge' => $edge]);
//        }
//        $station = Station::where('ip', '=', $route[sizeof($route) - 1])->get()->first();
//        array_push($edges, ['type' => '1', 'id' => $station->id]);
//
//        return response()->json(['code' => '001', 'data' => $edges, 'test' => $route]);
//    }

    function shortest(Request $request) {
        $from_ip = $request->input('from');
        $merchantName = $request->input('mName');
        $start_station = Station::where('merchantName', '=', $merchantName)->get()->first();

        if (!$start_station) {
            return response()->json(['code' => '002', 'message' => 'The store with this name doesn\'t exist']);
        }

        $to = Station::find(1)->ip;
        $graph = Graph::create();
        $edges = Connection::all();
        foreach ($edges as $edge) {
            $from_station = Station::find($edge->from);
            $to_station = Station::find($edge->to);
            $graph->add(strval($from_station->ip), strval($to_station->ip), $edge->weight);
        }

        $route = $graph->search($start_station->ip, $to);
        $route_id = array();


        foreach ($route as $node) {
            $station = Station::where('ip', '=', $node)->get()->first();
            array_push($route_id, $station->id);
        }

        $edges = array();
        for ($i = 0; $i < sizeof($route) - 1; $i++) {
            $from_station = Station::where('ip', '=', $route[$i])->get()->first();
            $to_station = Station::where('ip', '=', $route[$i + 1])->get()->first();

            $edge = Connection::where(['from' => $from_station->id, 'to' => $to_station->id])->get()->first();
            array_push($edges, ['type' => '1', 'id' => $from_station->id]);
            array_push($edges, ['type' => '2', 'edge' => $edge]);
        }
        $station = Station::where('ip', '=', $route[sizeof($route) - 1])->get()->first();
        array_push($edges, ['type' => '1', 'id' => $station->id]);

        $queue = new Queue;
        $queue->message = 'Sending';
        $queue->from = $start_station->id;
        $queue->path = json_encode($edges);
        $card = $request->input('cardNum');
        $queue->billing_address = $request->input('address');
        $queue->create_at = date('Y-m-d H:i:s');
        $queue->merchant = $merchantName;
        if (!$card) {
            $card = '000000000000000';
        }
        $cvv = $request->input('cvv');
        if (!$cvv) {
            $cvv = '000';
        }
        $holder_name = $request->input('holder_name');
        if (!$holder_name) {
            $holder_name = 'null';
        }
        $amount = $request->input('amount');
        if (!$amount) {
            $amount = 0;
        }
        $queue->card = $card;
        $queue->cvv = $cvv;
        $queue->holder_name = $holder_name;
        $queue->amount = $amount;
        $queue->status = 0;
        $queue->current = 0;
        $queue->save();

        return response()->json(['code' => '001', 'data' => $queue]);
    }

    function graphtest()
    {
        $graph = Graph::create();
//        $graph->add('1','2',3);
//        $graph->add('1','3',2);
//        $graph->add('2','4',3);
        $graph->add('192.168.1.10', '192.168.2.10', 1);
        $graph->add('192.168.1.10', '192.168.1.15', 2);
        $graph->add('192.168.2.10', '192.168.10.1', 2);
        $graph->add('192.168.10.1', '192.168.20.3', 4);
        $graph->add('192.168.1.10', '192.168.2.13', 5);
        $graph->add('192.168.2.13', '192.168.3.20', 2);
//            $graph->add('b', 'd', 5);
//            $graph->add('c', 'd', 1);
//            $graph->add('c', 't', 3);
//            $graph->add('d', 't', 1);
        $route = $graph->search('192.168.1.10', '192.168.10.1');
//        console.log($route);
        return response()->json($route);
    }

    public function stationInfo(Request $request)
    {
        $id = $request->input('id');
        $station = Station::find($id);

        return response()->json(['code' => '001', 'data' => $station]);
    }

    public function inActivateStation(Request $request) {
        $id = $request->input('id');
        $station = Station::find($id);
        $queues = json_decode($station->queues, true);
        if ($queues) {
            foreach ($queues as $queue) {
                $queue = Queue::find($queue);
                $queue->f_status = $queue->status;
                $queue->status = 4;
                $queue->save();
            }
        }

        $station->status = false;
        $station->save();

        return response()->json(['code' => '001']);

    }

    public function activateStation(Request $request) {
        $id = $request->input('id');
        $station = Station::find($id);
        $queues = json_decode($station->queues, true);
        if ($queues) {
            foreach ($queues as $queue) {
                $queue = Queue::find($queue);
                $queue->status = $queue->f_status;
                $queue->save();
            }
        }

        $station->status = true;
        $station->save();

        return response()->json(['code' => '001']);
    }

    public function getGateway(Request $request) {
        $ip = $request->input('ip');
        $pc = Station::find(1);
        $gateway = new Station;
        $gateway->ip = $ip;
        $gateway->type = 3;
        $gateway->status = true;
        $regions = Station::all()->groupBy('region')->count();
        $gateway->region = $regions + 1;
        $gateway->save();
        $connection = new Connection;
        $connection->from = $gateway->id;
        $connection->to = 1;
        $connection->weight = $request->input('weight_for_pc');
        $connection->region = 0;
        $connection->save();
        $station = new Station();
        $station->ip = $request->input('stationIp');
        $station->region = $gateway->region;
        $station->status = true;
        $station->type = 1;
        $station->save();
        $connection = new Connection;
        $connection->from = $station->id;
        $connection->to = $gateway->id;
        $connection->region = $gateway->region;
        $connection->weight = $request->input('weight_for_station');
        $connection->save();

        return response()->json(['code' => '001']);
    }
}
