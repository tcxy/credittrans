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

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) == False) {
            return response()->json(['code' => '002', 'message' => 'The ip address is not validate ipv4 or ipv6 address, please check it and reinput again']);
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
        $station->save();

        $connection = new Connection;
        $connection->from = $station->id;
        $connection->to = $to;
        $connection->weight = $weight;
        $connection->save();

        return response()->json(['code' => '001']);
    }

    function shortest(Request $request) {
        $from = $request->input('from');
        $from_station = Station::find($from);
        $to = '192.168.0.1';
        $graph = Graph::create();
        $edges = Connection::all();
        foreach ($edges as $edge) {
            $from_station = Station::find($edge->from);
            $to_station = Station::find($edge->to);
            $graph->add($from_station->ip, $to_station->ip, $edge->weight);
        }

        $route = $graph->search($from_station->ip, $to);

        return response()->json(['code' => '001', 'data' => $route]);
    }

    function graphtest() {
        $graph = Graph::create();
//        $graph->add('1','2',3);
//        $graph->add('1','3',2);
//        $graph->add('2','4',3);
            $graph->add('192.168.1.10', '192.168.2.10', 1);
            $graph->add('192.168.1.10', '192.168.1.15', 2);
            $graph->add('192,168.2.10', '192.168.10.1', 2);
            $graph->add('192.168.10.1', '192.168.20.3', 4);
            $graph->add('192.168.1.10', '192.168.2.13', 5);
            $graph->add('192.168.2.13', '192.168.3.20', 2);
//            $graph->add('b', 'd', 5);
//            $graph->add('c', 'd', 1);
//            $graph->add('c', 't', 3);
//            $graph->add('d', 't', 1);
        $route = $graph->search('192.168.1.10', '192.168.3.20');
//        console.log($route);
        return response()->json($route);
    }

    function validateIP($IP) {
        $valid = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $IP);

        if ($valid) {
            return True;
        } else {
            return False;
        }
    }
}
