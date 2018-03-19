<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Queue\Queue;
use App\Model\Credit\CreditCard;

class QueueController extends Controller
{
    //
    function updateQueue(Request $request) {
        $queues = Queue::where('status', '=', 1)->orwhere('status', '=', '2')->get();

        foreach ($queues as $queue) {
            $path = $queue->path;
            $position = $queue->current;
            // If the queue is processing, then move to the next node in path
            if ($queue->status == 2) {
                // when the queue is reaching its end, change its status
                if ($position == 0) {
                    $queue->status = 3;
                } else { // or just move the path
                    $queue->current = $position - 1;
                }
            } else {
                // if the queue is returing, move it to the former node in path
                // when the queue is reaching the last node, return it
                if ($position == sizeof(json_decode($path)) - 1) {
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
                } else { // or move it to next position
                    $position = $position + 1;
                    $queue->current = $position;
                }
            }
            $queue->save();
        }

        $queues = Queue::where('status', '=', 1)->orwhere('status', '=', '2')->get();
        return response()->json(['code' => '001', 'data' => $queues]);
    }

    function getQueue(Request $request) {
        $status = $request->input('status');
        $queues = null;
        if ($status) {
            $queues = Queue::where('status', '=', $status);
        } else {
            $queues = Queue::all()->get();
        }
    }
}
