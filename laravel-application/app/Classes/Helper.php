<?php
    namespace App\Classes;

use App\Models\Config;
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

        public static function urlAppendKV($url, $kvArray)
        {
            $parsedURL = parse_url($url);
            $finalURL = '';

            if(!array_key_exists('query', $parsedURL))
            {
                $finalURL = $url.'?'.http_build_query($kvArray);
            }
            else
            {
                $curQueryParams = [];
                parse_str($parsedURL['query'], $curQueryParams);
                foreach($kvArray as $k => $v)
                {
                    $curQueryParams[$k] = $v;
                }
                $newQueryString = http_build_query(array_reverse($curQueryParams, true));
                $finalURL = strtok($url, '?').'?'.$newQueryString;
            }

            return $finalURL;
        }
        
        public static function getDBConfig($key)
        {
            return Config::where('_key', $key)->first()->_value;
        }

        public static function setDBConfig($key, $value)
        {
            $config = Config::where('_key', $key)->first();

            if($config == null)
            {
                $config = new Config();
                $config->_key = $key;
            }

            $config->_value = $value;
            return $config->save();
        }

        /* HARDCODED CONFIG
        -----------------------------------*/
        private static $config = [];

        public static function getConfig($name, $key = null)
        {
            $configFilePath = app_path().'/Config/'.$name.'.php';
            
            if(!isset(Helper::$config[$name]))
            {
                if(file_exists($configFilePath))
                {
                    Helper::$config[$name] = include($configFilePath);
                }
                else
                {
                    die('Failed to find "'.$configFilePath.'" config');
                }
            }

            return ($key === null) ? Helper::$config[$name] : Helper::$config[$name][$key];
        }
    }
?>