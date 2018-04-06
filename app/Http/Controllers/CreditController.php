<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Credit\CreditAccount;
use App\Model\Credit\CreditCard;
use phpDocumentor\Reflection\Types\Integer;

class CreditController extends Controller
{
    //
    public function getAccountList(Request $request) {
        $page = $request->input('page');
        $offset = (intval($page)-1) * 15;
        $accountlist = [];
        $accounts = CreditAccount::offset($offset)->limit(15)->get();
        foreach ($accounts as $account) {
            array_push($accountlist, $account);
        }

        $amount = CreditAccount::count();
        $total_page = 0;

        if ($amount % 15 == 0) {
            $total_page = $amount / 15;
        } else {
            $total_page = intval($amount / 15) + 1;
        }

        return response()->json(['code' => '001', 'data' => ['accounts' => $accountlist, 'total_pages' => $total_page, 'current_page' => $page]]);
    }

    public function getCardList(Request $request) {
        $page = $request->input('page');
        $offset = (intval($page)-1) * 15;
        $cardlist = [];
        $cards = CreditCard::offset($offset)->limit(15)->get();
        foreach ($cards as $card) {
            $card->cardId = strval($card->cardId);
            $card->expireDate = date('Y-m', strtotime($card->expireDate));
            array_push($cardlist, $card);
        }
        $amount = CreditCard::count();
        $total_page = 0;
        if ($amount % 15 == 0) {
            $total_page = $amount / 15;
        } else {
            $total_page = intval($amount / 15) + 1;
        }

        return response()->json(['code' => '001', 'data' => ['cards' => $cardlist, 'total_pages' => $total_page, 'current_page' => $page]]);
    }

    public function addAccount(Request $request) {
        $phonenumber = $request->input('phonenumber');
        $holdername = $request->input('holdername');
        $address = $request->input('address');
        $spendlinglimit = doubleval($request->input('spendlinglimit'));
        $balance = doubleval($request->input('balance'));

        $creditCardId = $request->input('cardId');
        $csc = intval($request->input('csc'));
        $expireDate = $request->input('expireDate');


        $account = new CreditAccount;
        $card = new CreditCard;
        if ($this->checkPhoneNumber($phonenumber)) {
            $account->phonenumber = $phonenumber;
        } else {
            return response()->json(['code' => '003', 'message' => 'The format of phone is not correct, please check again']);
        }

        if ($this->checkCreditCardNumber($creditCardId)) {
            $card->cardId = $creditCardId;
        } else {
            return response()->json(['code' => '003', 'message' => 'The format of credit card number is not correct, please check again']);
        }

        $account->holdername = $holdername;
        $account->address = $address;
        $account->spendlinglimit = $spendlinglimit;
        $account->balance = $balance;
        $account->save();

        $accountid = $account->accountid;
        $card->csc = $csc;
        $card->expireDate = $expireDate;
        $card->accountid = $accountid;
        $card->save();

        return response()->json(['code' => '001']);
    }

    public function addCard(Request $request) {
        $cardId = $request->input('cardId');
        $csc = $request->input('csc');
        $expireDate = $request->input('expireDate');
        $accountid = $request->input('accountid');

        if (CreditCard::find($cardId)) {
            return response()->json(['code' => '002', 'message' => 'The card with this card number has already existed.']);
        }

        $card = new CreditCard;
        $card->cardId = $cardId;
        $card->csc = $csc;
        $card->expireDate = $expireDate;
        $card->accountid = $accountid;

        $card->save();
        return response()->json(['code' => '001']);
    }

    public function updateAccount(Request $request) {
        $accountid = $request->input('accountid');
        $holdername = $request->input('holdername');
        $address = $request->input('address');
        $spendlinglimit = floatval($request->input('spendlinglimit'));
        $balance = floatval($request->input('balance'));

        $account = CreditAccount::find($accountid);
        $account->holdername = $holdername;
        $account->address = $address;
        $account->spendlinglimit = $spendlinglimit;
        $account->balance = $balance;

        $account->save();
        return response()->json(['code' => '001']);

    }

    public function deleteAccount(Request $request) {
        $accountid = $request->input('accountid');
        $account = CreditAccount::find($accountid);
        if ($account->balance != 0) {
            return response()->json(['code' => '002', 'message' => 'The account has not deleted because the balance is not 0']);
        }
        $cards = $account->cards;
        foreach ($cards as $card) {
            $card->delete();
        }
        $account->delete();
        return response()->json(['code' => '001']);
    }

    public function deleteCard(Request $request) {
        $cardId = $request->input('cardId');
        $accountid = $request->input('accountid');
        $card = CreditCard::find($cardId);
        $account = CreditAccount::find($accountid);
        if ($account->cards->count() == 1) {
            if ($account->balance == 0) {
                $account->delete();
                $card->delete();
                return response()->json(['code' => '001', 'message' => 'The account has also been deleted because there\'s no card associated with it']);
            } else {
                return response()->json(['code' => '002', 'message' => 'The card has not been deleted because there\'s only one card associates with this account and the balance of this account is not 0']);
            }
        }
        $card->delete();
        return response()->json(['code' => '001', 'message' => 'The card has been deleted']);
    }

    public function updateCard(Request $request) {
        $cardId = $request->input('cardId');
        $csc = intval($request->input('csc'));
        $expireDate = $request->input('expireDate');
        $accountid = $request->input('accountid');

        $card = CreditCard::find($cardId);
        $card->csc = $csc;
        $card->expireDate = $expireDate;
        $card->accountid = $accountid;

        $card->save();
        return response()->json(['code' => '001']);
    }

    public function addCreditCard(Request $request) {
        $cardId = $request->input('cardId');
        $csc = $request->input('csc');
        $expireDate = $request->input('expireDate');
        $accountid = $request->input('accountid');

        $card = new CreditCard;
        if ($this->checkCreditCardNumber($cardId)) {
            $card->cardId = $cardId;
        } else {
            return response()->json(['code' => '003', 'message' => 'The format of credit card number is not correct, please check again']);
        }

        $card->csc = $csc;
        $card->expireDate = $expireDate;
        $card->accountid = $accountid;
        $card->save();

        return response()->json(['code' => '001']);
    }

    public function checkPhoneNumber($number) {
        if (preg_match('/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/',
             $number)) {
            return true;
        }
        return false;
    }

    public function checkCreditCardNumber($number) {
        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number=preg_replace('/\D/', '', $number);

        // Set the string length and parity
        $number_length=strlen($number);
        $parity=$number_length % 2;

        // Loop through each digit and do the maths
        $total=0;
        for ($i=0; $i<$number_length; $i++) {
            $digit=$number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit*=2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit-=9;
                }
            }
            // Total up the digits
            $total+=$digit;
        }

        // If the total mod 10 equals 0, the number is valid
        return ($total % 10 == 0) ? TRUE : FALSE;
    }

    public function cardWithAccount(Request $request) {
        $accountid = $request->input('id');
        $cards = CreditCard::where('accountid', '=', $accountid)->get();

        return response()->json(['code' => '001', 'data' => $cards, 'id' => $accountid]);
    }
}
