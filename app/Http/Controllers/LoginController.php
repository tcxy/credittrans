<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin\Admin;
use App\Model\Question\Question;

class LoginController extends Controller
{
    //
    public function login(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $admins = Admin::all();

        foreach ($admins as $admin) {
            if ($username == $admin->username and $password == $admin->password) {
                if ($admin->isBlocked) {
                    return response()->json(['code' => '002', 'message' => 'Your account has been blocked, please contract IT stuff to unblock your account.']);
                } else {
                    return response()->json(['code' => '001', 'data' => $admin->uid]);
                }

            }
        }
        return response()->json(['code' => '002', 'message' => "username/password don't match with our records"]);
    }

    public function validateQuestion(Request $request) {
        $questionid = $request->input('questionid');
        $answer = $request->input('answer');
        $question = Question::find($questionid);

        if ($question->answer == $answer) {
            return response()->json(['code' => '001']);
        } else {
            return response()->json(['code' => '002']);
        }
    }

    public function getQuestion(Request $request) {
        $uid = $request->input('userId');
        $questions = Admin::find($uid)->questions;
        $questionlist = array();

        foreach ($questions as $question) {
            array_push($questionlist, $question);
        }
        $questionid = rand(0,2);

        return response()->json(['code' => '001', 'data' => ['id' => $questionlist[$questionid]->qid, 'question' => $questionlist[$questionid]->question]]);
    }

    public function questionWithBlock(Request $request) {
        $uid = $request->input('userId');
        $questions = $request->input('questions');
        $questionList = $request->input('questionlist');

        if ($questions == 3) {
            $user = Admin::find($uid);
            $user->isBlocked = true;
            $user->save();
            return response()->json(['code' => '003', 'message' => 'Your account has been blocked because you input wrong answer for all your secure questions. Please contact IT stuff to unblock your account']);
        } else {
            $userquestions = Admin::find($uid)->questions;
            foreach ($userquestions as $question) {
                if (!in_array($question->qid, $questionList)) {
                    return response()->json(['code' => '001', 'data' => ['id' => $question->qid, 'question' => $question->question]]);
                }
            }
        }
    }
}
