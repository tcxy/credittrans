<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Connection\Connection;
use App\Model\Station\Station;
use App\Custom\Graph;

class StationController extends Controller
{
    //
    function getGraph(Request $request) {
        $stations = Station::all();
        $nodes = [];
        foreach ($stations as $station) {
            if ($station->type == 0) {
                $station->shape = 'database';
            } else if ($station->type == 1) {
                $station->shape = 'triangle';
            } else {
                $station->shape = 'circle';
            }
            array_push($nodes, $station);
        }

        $connections = Connection::all();
        $edges = [];
        foreach ($connections as $connection) {
            $connection->from = $connection->from_s;
            $connection->to = $connection->to_s;
            array_push($edges, $connection);
        }

        return response()->json(['code' => '001', 'data' => [
            'nodes' => $nodes, 'edges' => $edges
        ]]);

    }

    function add(Request $request) {
        $type = $request->input('type');
        $status = true;
        $ip = $request->input('ip');
        $connect_to = $request->input('to');
        $weight = $request->input('weight');

        if (inet_pton($ip)) {
            return response()->json(['code' => '002', 'message' => 'The ip address you input is not validate ipv4 or ipv6 address, please check it and reinput again']);
        }

        $station = new Station;
        $station->status = $status;
        $station->ip = $ip;
        $station->type = $type;
        $station->save();
        // $station = Station::all()->where('ip', $ip);

        $connection = new Connection;
        $connection->from = $station->ip;
        $connection->to = $connect_to;
        $connection->weight = $weight;
        $connection->save();

        return response()->json(['code' => '001']);
    }

    function shortest(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');
        $graph = Graph::create();
        $edges = Connection::all();
        foreach ($edges as $edge) {
            $graph->add($edge->from, $edge->to, $edge->weight);
        }

        $route = $graph->search($from, $to);

        return response()->json(['code' => '001', 'data' => $route]);
    }

    function graphtest() {
        $graph = Graph::create();
//        $graph->add('1','2',3);
//        $graph->add('1','3',2);
//        $graph->add('2','4',3);
            $graph->add('1', 'b', 1);
            $graph->add('1', '3', 2);
            $graph->add('a', 'b', 2);
            $graph->add('a', 'c', 4);
            $graph->add('a', 't', 5);
            $graph->add('b', 'c', 2);
            $graph->add('b', 'd', 5);
            $graph->add('c', 'd', 1);
            $graph->add('c', 't', 3);
            $graph->add('d', 't', 1);
        $route = $graph->search('a', 't');
        return response()->json($route);
    }
}
