<?php

namespace App\Helpers;

use DateTime;
use Illuminate\Support\Facades\Http;


class StellarHelper{

    private static $base_url = 'https://horizon.stellar.org';

    public static function checkPayment($account_id, $amount, $last_minutes=10){
        $operations = self::getOperations('desc', 50);
        $payment_status = false;
        foreach($operations as $operation){
            if($operation['source_account'] == $account_id && $operation['amount'] == $amount){
                $operation_time = new DateTime($operation['created_at']);
                $allowed_time = time() - 60 * $last_minutes;
                if($operation_time->getTimestamp() > $allowed_time){
                    $payment_status = true;
                    break;
                }
            }
        }
        return $payment_status;
    }

    public static function getOperations($order='desc', $limit=20){
        $resp = Http::acceptJson()->get(self::$base_url .  "/accounts/". config('stellar.payment.address') ."/operations?order=$order&limit=" . $limit + 1);
        if($resp->failed()){
            return false;
        }
        $resp_body = $resp->json()['_embedded']['records'];
        $result = [];
        foreach ($resp_body as &$value) {
            if($value['type']=='payment' && $value['source_account'] != config('stellar.payment.address')){
                unset($value['_links'],$value['id'],$value['paging_token'],$value['type_i']);
                $result[] = $value;
            }
        }
        return $result;
    }
}
