<?php

namespace App\Http\Controllers;

use App\Model\Station\Station;
use Illuminate\Http\Request;
use App\Model\Queue\Queue;
use App\Model\Credit\CreditCard;

class QueueController extends Controller
{
    //
    public function updateQueue(Request $request) {
        $queues = Queue::where('status', '=', 1)->orwhere('status', '=', '2')->get();

        foreach ($queues as $queue) {
            $path = json_decode($queue->path);
            $position = $queue->current;
            $station = null;
            // If the queue is returning, then move to the next node in path
            if ($queue->status == 2) {
                // when the queue is reaching its end, change its status
                if ($position == 0) {
                    $queue->status = 3;
                    $stationip = $path[2]['id'];
                    // Find the former relay station, and delete this queue from the queue array
                    // If the current position is zero, then former relay is the second element in the path
                    $station = Station::find($stationip);
                    if ($station->queues) {
                        $queue_array = json_decode($station->queues);
                        $key = array_search($queue->id, $queue_array);
                        if ($key) {
                            array_splice($queue_array, $key, 1);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        }
                    }
                } else { // or just move the path
                    $queue->current = $position - 1;
                    $last = $path[$position];
                    if ($last['type'] == 1) {
                        $station = Station::find( $last['id']);
                    } else {
                        $station = Station::find($path[$position + 1]['id']);
                    }
                    if ($station->queues) {
                        $queue_array = json_decode($station->queues);
                        $key = array_search($queue->id, $queue_array);
                        if ($key) {
                            array_splice($queue_array, $key, 1);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        }
                    }
                    // Take care of next node
                    $current = $path[$queue->current];
                    // If next node is relay, then add current queue into the relay
                    if ($current['type'] == 1) {
                        $station = Station::find($current['id']);
                        // If the next relay already had queues
                        if ($station->queues) {
                            $queue_array = json_decode($station->queues);
                            array_push($queue->id);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        } else { // If the next relay doesn't have any queue, then create a new queue for it
                            $array = array();
                            array_push($array, $queue->id);
                            $station->queues = json_encode($array);
                            $station->save();
                        }
                    }
                }
            } else {
                // if the queue is processing, move it to the former node in path
                // when the queue is reaching the last node, return it
                if ($position == sizeof($path) - 1) {
                    $queue->status = 2;
                    $card = $queue->card;
                    $creditcard = CreditCard::find($card);
                    if (!$creditcard) {
                        $queue->result = 'Declined';
                        $queue->message = 'The card don\'t exist';
                    } else {
                        $cvv = $creditcard->csc;
                        if ($cvv != $queue->cvv) {
                            $queue->result = 'Declined';
                            $queue->message = 'The information of the card doesn\'t match with our records';
                        } else {
                            $queue->result = 'Approved';
                            $queue->message = 'The transaction is finished';
                        }
                    }
                    // The queue is at the processing center
                    // Remove current queue from the processing center
                    $station = Station::find(1);
                    if ($station->queues) {
                        $queue_array = json_decode($station->queues);
                        $key = array_search($queue->id, $queue_array);
                        if ($key) {
                            array_splice($queue_array, $key, 1);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        }
                    }

                    // Delete this queue from former relay
                    // The former relay should be current position minus 2
                    $former = $path[$position - 2];
                    $station = Station::find($former['id']);
                    if ($station->queues) {
                        $queue_array = json_decode($station->queues);
                        $key = array_search($queue->id, $queue_array);
                        if ($key) {
                            array_splice($queue_array, $key, 1);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        }
                    }

                } else { // or move it to next position
                    $position = $position + 1;
                    $queue->current = $position;
                    $last = $path[$position];
                    if ($last['type'] == 1) {
                        // If the former node is relay
                        // Delete the queue from it
                        $station = Station::find($last['id']);
                    } else {
                        $station = Station::find($path[$position - 1]);
                    }
                    if ($station->queues) {
                        $queue_array = json_decode($station->queues);
                        $key = array_search($queue->id, $queue_array);
                        if ($key) {
                            array_splice($queue_array, $key, 1);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        }
                    }

                    $current = $path[$queue->current];
                    if ($current['type'] == 1) {
                        $station = Station::find($current['id']);
                        if ($station->queues) {
                            $queue_array = json_decode($station->queues);
                            array_push($queue_array, $queue->id);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        } else {
                            $queue_array = array();
                            array_push($queue_array, $queue->id);
                            $station->queeus = json_encode($queue_array);
                            $station->save();
                        }
                    }
                }
            }
            $queue->save();
        }

        $queues = Queue::where('status', '=', 1)->orwhere('status', '=', '2')->get();
        return response()->json(['code' => '001', 'data' => $queues]);
    }

    public function getQueue(Request $request) {
        $status = $request->input('status');
        $queues = null;
        if ($status) {
            $queues = Queue::where('status', '=', $status)->get();
        } else {
            $queues = Queue::all();
        }

        return response()->json(['code' => '001', 'data' => $queues]);
    }

}
