<?php

namespace App\APIs\Transaction;

use Exception;
use Illuminate\Support\Str;

class Sadad
{
    private static $TERMINAL_ID = '24051437';
    private static $MERCHANT_ID = '000000140331301';
    private static $TERMINAL_KEY = 'HmMrb3RMZu7rN4B0ub2eKFAvWr7oNnYw';
    private $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    private function encrypt_pkcs7($str)
    {
        $key = base64_decode(self::$TERMINAL_KEY);
        $cipherText = OpenSSL_encrypt($str, "DES-EDE3", $key, OPENSSL_RAW_DATA);
        return base64_encode($cipherText);
    }

    private function CallAPI($url, $data = false)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function send($orderId, $price, $returnUrl, $phoneNumber = null)
    {
        if (Request()->ip() == '127.0.0.1' || Str::startsWith(Request()->ip(), '192.168')) //ignoring localhost
        {
            return redirect($returnUrl . '?OrderId=' . $orderId);
        }

        session_start();
        $Amount = $price * 10;
        $LocalDateTime = date("m/d/Y g:i:s a");
        $SignData = $this->encrypt_pkcs7(self::$TERMINAL_ID . ";$orderId;$Amount");
        $data = array(
            'TerminalId' => self::$TERMINAL_ID,
            'MerchantId' => self::$MERCHANT_ID,
            'Amount' => $Amount,
            'SignData' => $SignData,
            'ReturnUrl' => $returnUrl,
            'LocalDateTime' => $LocalDateTime,
            'OrderId' => $orderId);

        if ($phoneNumber != null)
            $data['UserId'] = $phoneNumber;

        $str_data = json_encode($data);
        $res = $this->CallAPI('https://sadad.shaparak.ir/vpg/api/v0/Request/PaymentRequest', $str_data);
        $array = json_decode($res);

        if (!isset($array->ResCode))
            throw new Exception('خطایی به هنگام برقراری ارتباط با درگاه به وجود آمد.');

        if ($array->ResCode == 0) {
            $Token = $array->Token;
            $url = "https://sadad.shaparak.ir/VPG/Purchase?Token=$Token";
            return redirect($url);
        } else
            throw new Exception($array->Description);

    }

    public function verify()
    {
        if (Request()->ip() == '127.0.0.1' || Str::startsWith(Request()->ip(), '192.168')) //ignoring localhost
        {
            try {
                return Gateway::verifyLocalhost($this->transaction);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error in verifying localhost!'
                ]);
            }
        }

        $Token = Request()->token;
        $ResCode = Request()->ResCode;
        if ($ResCode == 0) {
            $verifyData = array(
                'Token' => $Token,
                'SignData' => $this->encrypt_pkcs7($Token)
            );

            $str_data = json_encode($verifyData);
            $res = $this->CallAPI('https://sadad.shaparak.ir/vpg/api/v0/Advice/Verify', $str_data);
            $array = json_decode($res);

        }
        if (isset($array) && $array->ResCode != -1 && $array->ResCode == 0) {
            $this->transaction->update([
                'trace_number' => $array->SystemTraceNo,
                'reference_id' => $array->RetrivalRefNo,
                'message' => $array->Description,
                'success' => true
            ]);
            return $this->transaction;
        }
        throw new Exception('تراکنش نا موفق بود در صورت کسر مبلغ از حساب شما حداکثر پس از 72 ساعت مبلغ به حسابتان برمی گردد.');
    }
}
