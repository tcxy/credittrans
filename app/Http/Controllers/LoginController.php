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
        $questionid = $request->input('questionid');
        $answer = $request->input('answer');

        $admins = Admin::all();

        foreach ($admins as $admin) {
            if ($username == $admin->username and $password == $admin->password) {
                if ($this->validateQuestion($questionid, $answer)) {
                    return response()->json(['code' => '001']);
                } else {
                    return response()->json(['code' => '002', 'message' => 'The answer don\'t match with validation question']);
                }
            }
        }
        return response()->json(['code' => '002', 'message' => "username/password don't match with our records"]);
    }

    public function validateQuestion($id, $answer) {
        $question = Question::find($id);

        if ($question->answer == $answer) {
            return true;
        } else {
            return false;
        }
    }

    public function getQuestions() {
        $questions = array();

        for ($i = 0; $i < 3; $i++) {
            $id = rand(1, 6);
            while (array_key_exists($id, $questions)) {
                $id = rand(1, 6);
            }
            $questions[$id] = Question::find($id)->question;
        }

        return response()->json(['code' => '001', 'data' => $questions]);
    }
}
