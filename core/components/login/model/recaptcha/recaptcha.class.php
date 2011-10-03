<?php
/**
 * reCaptcha integration
 *
 * Copyright 2010 by Shaun McCormick <shaun@modxcms.com>
 *
 * Register is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Register is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Register; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * reCaptcha modX service class.
 *
 * Based off of recaptchalib.php by Mike Crawford and Ben Maurer. Changes include converting to OOP and making a class.
 *
 * @package login
 * @subpackage recaptcha
 */
if (!class_exists('reCaptcha')) {
class reCaptcha {
    const API_SERVER = 'http://www.google.com/recaptcha/api/';
    const API_SECURE_SERVER = 'https://www.google.com/recaptcha/api/';
    const VERIFY_SERVER = 'www.google.com';
    const OPT_PRIVATE_KEY = 'privateKey';
    const OPT_PUBLIC_KEY = 'publicKey';
    const OPT_USE_SSL = 'use_ssl';

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            reCaptcha::OPT_PRIVATE_KEY => $this->modx->getOption('recaptcha.private_key',$config,''),
            reCaptcha::OPT_PUBLIC_KEY => $this->modx->getOption('recaptcha.public_key',$config,''),
            reCaptcha::OPT_USE_SSL => $this->modx->getOption('recaptcha.use_ssl',$config,false),
        ),$config);
    }

    /**
     * Encodes the given data into a query string format
     * @param $data - array of string elements to be encoded
     * @return string - encoded request
     */
    protected function qsencode($data) {
        $req = '';
        foreach ($data as $key => $value) {
            $req .= $key . '=' . urlencode( stripslashes($value) ) . '&';
        }

        // Cut the last '&'
        $req=substr($req,0,strlen($req)-1);
        return $req;
    }

    /**
     * Submits an HTTP POST to a reCAPTCHA server
     * @param $host
     * @param $path
     * @param array $data
     * @param int $port
     * @return string
     */
    protected function httpPost($host, $path, array $data = array(), $port = 80) {
        $data['privatekey'] = $this->config[reCaptcha::OPT_PRIVATE_KEY];
        $req = $this->qsencode($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if(false == ($fs = @fsockopen($host, $port, $errno, $errstr, 10))) {
            return 'Could not open socket';
        }

        fwrite($fs, $http_request);
        while (!feof($fs)) {
            $response .= fgets($fs, 1160); // One TCP-IP packet
        }
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }

    /**
     * Gets the challenge HTML (javascript and non-javascript version).
     * This is called from the browser, and the resulting reCAPTCHA HTML widget
     * is embedded within the HTML form it was called from.
     *
     * @param string $theme
     * @param int $width
     * @param int $height
     * @param null $error
     * @return string The HTML to be embedded in the user's form.
     */
    public function getHtml($theme = 'clean',$width = 500,$height = 300,$error = null) {
        if (empty($this->config[reCaptcha::OPT_PUBLIC_KEY])) {
            return $this->error($this->modx->lexicon('recaptcha.no_api_key'));
        }

        /* use ssl or not */
        $server = !empty($this->config[reCaptcha::OPT_USE_SSL]) ? reCaptcha::API_SECURE_SERVER : reCaptcha::API_SERVER;

        $errorpart = '';
        if ($error) {
           $errorpart = "&amp;error=" . $error;
        }
        $opt = array(
            'theme' => $theme,
            'width' => $width,
            'height' => $height,
            'lang' => $this->modx->getOption('cultureKey',null,'en'),
        );
        return '<script type="text/javascript">var RecaptchaOptions = '.$this->modx->toJSON($opt).';</script><script type="text/javascript" src="'. $server . 'challenge?k=' . $this->config[reCaptcha::OPT_PUBLIC_KEY] . $errorpart . '"></script>
        <noscript>
                <iframe src="'. $server . 'noscript?k=' . $this->config[reCaptcha::OPT_PUBLIC_KEY] . $errorpart . '" height="'.$height.'" width="'.$width.'" frameborder="0" style="width: '.$width.'px; height: '.$height.'px;"></iframe><br />
                <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
        </noscript>';
    }

    protected function error($message = '') {
        $response = new reCaptchaResponse();
        $response->is_valid = false;
        $response->error = $message;
        return $message;
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     * @param $remoteIp
     * @param $challenge
     * @param $responseField
     * @param array $extraParams
     * @return ReCaptchaResponse
     */
    public function checkAnswer ($remoteIp, $challenge, $responseField, $extraParams = array()) {
        if (empty($this->config[reCaptcha::OPT_PRIVATE_KEY])) {
            return $this->error($this->modx->lexicon('recaptcha.no_api_key'));
        }

        if (empty($remoteIp)) {
            return $this->error($this->modx->lexicon('recaptcha.no_remote_ip'));
        }

        //discard spam submissions
        if (empty($challenge) || empty($responseField)) {
            return $this->error($this->modx->lexicon('recaptcha.empty_answer'));
        }

        $response = $this->httpPost(reCaptcha::VERIFY_SERVER,"/recaptcha/api/verify",array (
            'remoteip' => $remoteIp,
            'challenge' => $challenge,
            'response' => $responseField,
        ) + $extraParams);

        $answers = explode("\n",$response[1]);
        $response = new reCaptchaResponse();

        if (trim($answers[0]) == 'true') {
            $response->is_valid = true;
        } else {
            $response->is_valid = false;
            $response->error = $answers [1];
        }
        return $response;
    }

    /**
     * gets a URL where the user can sign up for reCAPTCHA. If your application
     * has a configuration page where you enter a key, you should provide a link
     * using this function.
     * @param null $domain
     * @param null $appname
     * @return string
     */
    public function getSignupUrl ($domain = null, $appname = null) {
        return "http://www.google.com/recaptcha/api/getkey?" .  $this->qsencode(array ('domain' => $domain, 'app' => $appname));
    }

    protected function aesPad($val) {
        $block_size = 16;
        $numpad = $block_size - (strlen ($val) % $block_size);
        return str_pad($val, strlen ($val) + $numpad, chr($numpad));
    }

    /* Mailhide related code */
    protected function aesEncrypt($val,$ky) {
        if (!function_exists("mcrypt_encrypt")) {
            return $this->error($this->modx->lexicon('recaptcha.mailhide_no_mcrypt'));
        }
        $mode=MCRYPT_MODE_CBC;
        $enc=MCRYPT_RIJNDAEL_128;
        $val= $this->aesPad($val);
        return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
    }


    protected function mailhideUrlbase64 ($x) {
        return strtr(base64_encode ($x), '+/', '-_');
    }

    /* gets the reCAPTCHA Mailhide url for a given email, public key and private key */
    public function mailhideUrl($email) {
        if (empty($this->config[reCaptcha::OPT_PUBLIC_KEY]) || empty($this->config[reCaptcha::OPT_PRIVATE_KEY])) {
            return $this->error($this->modx->lexicon('recaptcha.mailhide_no_api_key'));
        }

        $ky = pack('H*',$this->config[reCaptcha::OPT_PRIVATE_KEY]);
        $cryptmail = $this->aesEncrypt($email, $ky);
        return 'http://mailhide.recaptcha.net/d?k='
            . $this->config[reCaptcha::OPT_PUBLIC_KEY]
            . '&c=' . $this->mailhideUrlbase64($cryptmail);
    }

    /**
     * gets the parts of the email to expose to the user.
     * eg, given johndoe@example,com return ["john", "example.com"].
     * the email is then displayed as john...@example.com
     *
     * @param $email
     * @return array
     */
    public function mailhideEmailParts($email) {
        $arr = preg_split("/@/", $email);

        if (strlen($arr[0]) <= 4) {
            $arr[0] = substr($arr[0], 0, 1);
        } else if (strlen ($arr[0]) <= 6) {
            $arr[0] = substr($arr[0], 0, 3);
        } else {
            $arr[0] = substr($arr[0], 0, 4);
        }
        return $arr;
    }

    /**
     * Gets html to display an email address given a public an private key.
     * to get a key, go to:
     *
     * http://mailhide.recaptcha.net/apikey
     *
     * @param $email
     * @return string
     */
    public function mailhideHtml($email) {
        $emailparts = $this->mailhideEmailParts($email);
        $url = $this->mailhideUrl($email);

        return htmlentities($emailparts[0]) . "<a href='" . htmlentities ($url) .
            "' onclick=\"window.open('" . htmlentities ($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities ($emailparts [1]);
    }


}

/**
 * A reCaptchaResponse is returned from reCaptcha::check_answer()
 */
class reCaptchaResponse {
    public $is_valid;
    public $error;
}
}