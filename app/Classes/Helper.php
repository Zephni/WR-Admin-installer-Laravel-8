<?php
    namespace App\Classes;

use Carbon\Carbon;

class Helper
    {
        public static function properize($string)
        {
            return $string.'\''.($string[strlen($string) - 1] != 's' ? 's' : '');
        }

        public static function checkCookieConsent()
        {
            return (isset($_COOKIE['laravel_cookie_consent']) && $_COOKIE['laravel_cookie_consent'] == 1);
        }

        public static function getDate()
        {
            return $_GET['forcedate'] ?? Carbon::now();
        }

        public static function reCaptchaCheck($postedRecaptchaResponse)
        {
            $post_data = http_build_query(
                array(
                    'secret' => '', /* NEED TO ADD THIS PER APPLICATION */
                    'response' => $postedRecaptchaResponse, // eg. $_POST['g-recaptcha-response']
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                )
            );
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $post_data
                )
            );
            $context  = stream_context_create($opts);
            $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
            $result = json_decode($response);
            
            return $result->success;
        }
    }
?>