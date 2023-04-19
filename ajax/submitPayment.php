<?php
error_reporting(0);
session_start();
header('Content-type: application/json; charset=utf-8');

date_default_timezone_set('Asia/Bangkok');
$start_date = date('Y-m-d', strtotime('-365 days'));
$end_date = date('Y-m-d', strtotime('1 days'));

require_once(__DIR__ . '/../include/Config.php');
require_once(__DIR__ . '/../include/PDOQuery.php');
require_once(__DIR__ . '/../include/WalletAPI.php');
use Maythiwat\WalletAPI;

$obj = (object)[];
function CreateJsonResponse() {
    global $obj;
    $obj->timestamp = time();
    die(json_encode($obj, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
}

if (empty($_SESSION['username'])) {
    $obj->status = 'error';
    $obj->info = 'โปรดเข้าสู่ระบบ!';
    CreateJsonResponse();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(rtrim($_POST['x-number'])) && !empty(rtrim($_POST['x-method'])) && ctype_digit($_POST['x-number']) == true && strlen($_POST['x-number']) == 14) {
        $method = $_POST['x-method'];
        $number = $_POST['x-number'];
        if ($method === "tw") {
            $q = Query('SELECT tx_id FROM wallet_history where tx_id = :tx', array(':tx'=>$number));
            if ($q->rowCount() > 0) {
                $obj->status = 'error';
                $obj->info = 'หมายเลขอ้างอิงไม่ถูกต้อง';
                CreateJsonResponse();
            }
            
            $tw = new WalletAPI();
            $token = $tw->GetToken($_cfg['wallet']['user'], $_cfg['wallet']['pass']);
            if ($token !== false && $token !== null) {
                $activities = $tw->FetchActivities($token, $start_date, $end_date);
                foreach($activities as $reports) {
                    if($reports['text3En'] == 'creditor') {
                        $txData = $tw->FetchTxDetail($token,$reports['reportID']);
                        $tx['id'] = $txData['section4']['column2']['cell1']['value'];
                        $tx['message'] = $txData['personalMessage']['value'];
                        $tx['fee'] = $txData['section3']['column2']['cell1']['value'];
                        $tx['date'] = $txData['section4']['column1']['cell1']['value'];
                        $tx['sender']['name'] = $txData['section2']['column1']['cell2']['value'];
                        $tx['sender']['phone'] = $txData['ref1'];
                        $tx['amount'] = str_replace(',', '', $txData['section3']['column1']['cell1']['value']);
                        if ($tx['id'] === $number) {
                            break;
                        }else{
                            unset($tx);
                            $tx = false;
                        }
                    }
                }
                if ($tx !== false && $tx !== null) {
                    Query('UPDATE clients SET coins = coins + :amount, paid = paid + :amount WHERE username = :user', array(':user'=>$_SESSION['username'],':amount'=>$tx['amount']));
                    Query('INSERT INTO wallet_history (tx_id, tx_phone, tx_date, payee, ip) VALUES (:tx_id, :phone, :date, :sender, :ip)', array(':tx_id'=>$tx['id'],':sender'=>$tx['sender']['name'],':date'=>date("Y-m-d H:i:s"),':ip'=>$_SERVER['REMOTE_ADDR'],':phone'=>$tx['sender']['phone']));
                    $obj->status = 'success';
                    $obj->info = 'เติมเงินสำเร็จ';
                    CreateJsonResponse();
                }else{
                    $obj->status = 'error';
                    $obj->info = 'หมายเลขอ้างอิงไม่ถูกต้อง';
                    CreateJsonResponse();
                }
            }else{
                $obj->status = 'error';
                $obj->info = 'ไม่สามารถเชื่อมต่อกับ API TrueMoney ได้!';
                CreateJsonResponse();
            }
        }elseif ($method === "tm") {
            $tw = new WalletAPI();
            $token = $tw->GetToken($_cfg['wallet']['user'], $_cfg['wallet']['pass']);
            if ($token !== false && $token !== null) {
                $tm = $tw->CashcardTopup($token, $number);
                @$tx = $tm['transactionId'];
                @$code = $tm['code'];
                @$orgm = $tm['originalMessage'];
                if (isset($tx) && ctype_digit($tx)) {
                    Query('UPDATE clients SET coins = coins + :amount, paid = paid + :amount WHERE username = :user', array(':user'=>$_SESSION['username'],':amount'=>$tm['amount']-$tm['serviceFee']));
                    $obj->status = 'success';
                    $obj->info = 'ทำรายการสำเร็จ';
                    $obj->amount = $tm['amount'];
                    $obj->fee = abs($tm['serviceFee']);
                    $obj->pin = $tm['cashcardPin'];
                    CreateJsonResponse();
                }else{
                    if ($orgm) {
                        if ($orgm == 'access token not found.') {
                            $obj->status = 'error';
                            $obj->error = 'invalid_token';
                            $obj->info = 'ไม่สามารถเข้าสู่ระบบได้';
                            CreateJsonResponse();
                        }elseif ($orgm == "Can't topup wallet") {
                            $obj->status = 'error';
                            $obj->error = 'invalid_card';
                            $obj->info = 'หมายเลขบัตรเงินสดไม่ถูกต้อง';
                            CreateJsonResponse();
                        }else{
                            $obj->status = 'error';
                            $obj->error = 'common_error';
                            $obj->info = 'เกิดข้อผิดพลาด: '.$orgm;
                            CreateJsonResponse();
                        }
                    }else{
                        $obj->status = 'error';
                        $obj->error = 'unknown_error';
                        $obj->info = 'เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้งภายหลัง';
                        CreateJsonResponse();
                    }
                }
            }else{
                $obj->status = 'error';
                $obj->info = 'ไม่สามารถเชื่อมต่อกับ API TrueMoney ได้!';
                CreateJsonResponse();
            }
        }else{
            $obj->status = 'error';
            $obj->info = 'ระบบไม่รองรับการชำระเงินรูปแบบนี้!';
            CreateJsonResponse();
        }
    }else{
        $obj->status = 'error';
        $obj->info = 'โปรดเลือกช่องทางสำหรับการชำระเงิน!';
        CreateJsonResponse();
    }
}
?>