<?php
namespace Maythiwat;
class WalletAPI {
    public function Request($url, $header = false, $data = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'okhttp/3.8.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        return curl_exec($ch);
    }

    public function GetToken($user, $pass) {
        $url = "https://api-ewm.truemoney.com/api/v1/signin";
        $header = array("Host: api-ewm.truemoney.com", "Content-Type: application/json");
        $data = array("username"=>$user, "password"=>sha1($user.$pass), "type"=>"email");
        return @json_decode($this->Request($url, $header, json_encode($data)), true)['data']['accessToken'];
    }

    public function GetProfile($token) {
        $url = "https://api-ewm.truemoney.com/api/v1/profile/{$token}";
        $header = array("Host: api-ewm.truemoney.com");
        return @json_decode($this->Request($url, $header, false), true)['data'];
    }

    public function FetchActivities($token, $start, $end, $limit = 25) {
        $url = "https://api-ewm.truemoney.com/api/v1/profile/transactions/history/{$token}/?startDate={$start}&endDate={$end}&limit={$limit}";
        $header = array("Host: api-ewm.truemoney.com");
        return @json_decode($this->Request($url, $header, false), true)['data']['activities'];
    }

    public function FetchTxDetail($token, $id) {
        $url = "https://api-ewm.truemoney.com/api/v1/profile/activities/{$id}/detail/{$token}";
        $header = array("Host: api-ewm.truemoney.com");
        return @json_decode($this->Request($url, $header, false), true)['data'];
    }

    public function CashcardTopup($token, $cashcard) {
        $time = time();
        $url = "https://api-ewm.truemoney.com/api/v1/topup/mobile/{$time}/{$token}/cashcard/{$cashcard}";
        $header = array("Host: api-ewm.truemoney.com");
        return @json_decode($this->Request($url, $header, true), true);
    }
}
?>