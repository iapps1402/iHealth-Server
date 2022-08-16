<?php

namespace App\Helpers;

class MyEncryption
{

    public $pubkey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDIvn9C9rOmP8WiP36zd64ZYs5n
NAzL4OWUYOc4CihufTGNuyej+zxChb1ZA3L0eP2C5a94SFvIj32HQVZ95R5gcksf
HNWk1T2JlS30alNwmyEpLGRrZJwJo/wfcuPIcRL381TMt1h/vYdNgBQyLWvLIMLg
e/QytOgcMnSDrv9tkQIDAQAB
-----END PUBLIC KEY-----
';
    public $privkey = '-----BEGIN RSA PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMi+f0L2s6Y/xaI/
frN3rhlizmc0DMvg5ZRg5zgKKG59MY27J6P7PEKFvVkDcvR4/YLlr3hIW8iPfYdB
Vn3lHmBySx8c1aTVPYmVLfRqU3CbISksZGtknAmj/B9y48hxEvfzVMy3WH+9h02A
FDIta8sgwuB79DK06BwydIOu/22RAgMBAAECgYAN4PXFeG71OIV+cSteVxDnWmhw
xC16TwMwXf6+Zh1jfi5V4TGdabpB0yNDKzTgaBkG2sBl7YLC/ACuwDqtm5Cc9NuV
kx/CvBE3h5KeeOlUGzFzWu703MAejS2Vn8yuP5C50LFG+lrWfgHQeAATEdcdMgBR
M2uLuhdFfdiUlsLZUQJBAP59AwlrMM2Q60+Br1dATT3xnlFmz6qf3QSqVggkUptz
3I+1BaZDKptpQZBSM7A58O49GqVJ0aZKCHoIBeSN8j0CQQDJ78JQeRg6TPyx6vte
dOGukf2HLx0SICXE4lL408wm7Q4WjajqrIjIYOpUaNVrKTS0DB4bGdg8RPGzcqAR
GYHlAkEAuh896FaQA9g4cRac4YgytdoPeuNhgB2ZLL9TWnMOQ4kyR5wSPK4k7DLH
88Ba80j9D5B6+2YIwcClgHJ7tNOn1QJAQEQOij/PpxZDQXgwIYJ/JGiP0Ar9bHxQ
qNUCZPA6w5Sj7CePP4hDS8oUKWLnsN//RuGoXyWdfKjQZzSunmKIxQJAO1mNsHNv
f6ZFSvWkUXphplkpbWDefcV1l+maql/M0hlvG8nc5VJEJq9vTJ26Hngq29aA8JZe
2knjFDeB6TnamA==
-----END RSA PRIVATE KEY-----
';

    public function encrypt($data)
    {
        if (openssl_public_encrypt($data, $encrypted, $this->pubkey))
            $data = base64_encode($encrypted);
        else
            throw new \Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');

        return $data;
    }

    public function decrypt($data)
    {
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey))
            $data = $decrypted;
        else
            $data = '';

        return $data;
    }
}
