<?php

namespace App\Http\Controllers;

use App\Model\Credit\CreditAccount;
use App\Model\Station\Station;
use Illuminate\Http\Request;
use App\Model\Queue\Queue;
use App\Model\Credit\CreditCard;

class QueueController extends Controller
{
    //
    public function updateQueue(Request $request) {
        $queues = Queue::where('status', '=', 1)->orwhere('status', '=', '2')->orwhere('status', '=', '4')->get();
        $array = array();

        foreach ($queues as $queue) {
            $path = json_decode($queue->path, true);
            $position = $queue->current;
            $station = null;
            // If the queue is returning, then move to the next node in path
            if ($queue->status == 2) {
                // when the queue is reaching its end, change its status
                if ($position == 0) {
                    $queue->status = 3;
                    $station = Station::find(intval($path[0]['id']));
                    $queue_array = json_decode($station->queues);
                    if ($queue_array) {
                        $key = array_search(intval($queue->id), $queue_array);
                        if (in_array(intval($queue->id), $queue_array)) {
                            array_splice($queue_array, $key, 1);
                            $station->queues = json_encode($queue_array);
                            $station->save();
                        }
                    }
                } else { // or just move the path
                    $last = $path[$position];

                    $queue->current = $position - 1;

                    // Take care of next node
                    $current = $path[$queue->current];
                    // If next node is relay, then add current queue into the relay
                    if ($current['type'] == 1) {
                        $station = Station::find(intval($current['id']));
                        if ($station->status == true) {
                            $queue_array = array();
                            // If the next relay already had queues
                            if ($station->queues) {
                                $queue_array = json_decode($station->queues);
                            }
                            // Check if the queues reached the limit of station
                            if ($station->limint == count($queue_array)) {
                                $queue->current = $position;
                            } else {
                                // First move the queue from last node
                                if ($last['type'] == 1) {
                                    $station = Station::find(intval($path[$position]['id']));
                                    if ($station->queues) {
                                        $queue_array = json_decode($station->queues);
                                        $key = array_search($queue->id, $queue_array);
                                        if (in_array(intval($queue->id), $queue_array)) {
                                            array_splice($queue_array, $key, 1);
                                            $station->queues = json_encode($queue_array);
                                            $station->save();
                                        }
                                    }
                                }

                                // Then push the queue into next station
                                array_push($queue_array, $queue->id);
                                $station->queues = json_encode($queue_array);
                                $station->save();
                            }

                        } else {
                            $queues->current = $position;
                        }

                    }
                }
            } else {
                // if the queue is processing, move it to the former node in path
                // when the queue is reaching the last node, return it
                if ($position == sizeof($path) - 1) {
                    $queue->status = 2;
                    $card = $queue->card;
                    $creditcard = CreditCard::find(intval($card));
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
                            $accountid = $creditcard->accountid;
                            $account = CreditAccount::find($accountid);

                            if ($account->balance + $queue->amount > $account->spendlinglimit) {
                                $queue->result = "Declined";
                                $queue->message = 'Exceed spending limit';
                            } else {
                                $account->balance = $account->balance + $queue->amount;
                                $account->save();
                            }
                        }
                    }

                } else { // or move it to next position

                    $queue->current = $position + 1;
                    $last = $path[$position];


                    $current = $path[$queue->current];
                    if ($current['type'] == 1) {
                        $station = Station::find(intval($current['id']));
                        if ($station->status == true) {
                            $queue_array = array();
                            if ($station->queues) {
                                $queue_array = json_decode($station->queues);
                            }

                            if ($station->limit == count($queue_array)) {
                                $queue->current = $position;
                            } else {
                                if ($position != 0) {
                                    if ($last['type'] == 1) {
                                        // If the former node is relay
                                        // Delete the queue from it
                                        $station = Station::find(intval($last['id']));
                                        if ($station->queues) {
                                            $queue_array = json_decode($station->queues);
                                            $key = array_search(intval($queue->id), $queue_array);
                                            if (in_array(intval($queue->id), $queue_array)) {
                                                array_splice($queue_array, $key, 1);
                                                $station->queues = json_encode($queue_array);
                                                $station->save();
                                            }
                                            array_push($array, $queue_array);
                                        }
                                    }
                                }

                                array_push($queue_array, intval($queue->id));
                                $station->queues = json_encode($queue_array);
                                $station->save();
                            }

                        } else {
                            $queue->current = $position;
                            if ($queue->current < 2) {
                                $queue->status = 3;
                                $queue->message = "The nearest relay is inactivated, please resend a new transaction later";
                            }
                        }
                    }
                }
            }
            $queue->save();
        }

        $queues = Queue::where('status', '=', 1)->orwhere('status', '=', '2')->get();
        return response()->json(['code' => '001', 'data' => $queues, 'array' => $array]);
    }

    public function getQueue(Request $request) {
        $status = $request->input('status');
        $queues = null;
        if ($status) {
            $queues = Queue::where('status', '=', $status)->get();
        } else {
            $queues = Queue::all();
        }
        $handled_queues = array();

        foreach ($queues as $queue) {
            if ($queue->status != 3 && $queue->current != sizeof(json_decode($queue->path)) - 1) {
                $queue->message = md5($queue->message);
                $queue->card = md5($queue->card);
                $queue->cvv = md5($queue->cvv);
                $queue->holder_name = md5($queue->holder_name);
            }
            array_push($handled_queues, $queue);
        }

        return response()->json(['code' => '001', 'data' => $handled_queues]);
    }

    public function getQueueWithAccount(Request $request) {
        $accountid = $request->input('id');
        $cards = CreditCard::where('accountid', '=', $accountid)->get();
        $queues = array();
        foreach ($cards as $card) {
            $card_queues = Queue::where('card', '=', $card->cardId)->get();
            array_push($queues, $card_queues);
        }

        return response()->json(['code' => '001', 'data' => $queues]);
    }

    public function sendQueue(Request $request) {
        $id = $request->input('id');
        $queue = Queue::find($id);
        $queue->status = 1;
        $queue->save();

        return response()->json(['code' => '001']);
    }

}
