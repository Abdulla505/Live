<?php

use Cake\Core\Configure;

//\Cake\Cache\Cache::disable();

function database_connect()
{
    try {
        $connection = \Cake\Datasource\ConnectionManager::get('file.php');
        $connected = $connection->connect();
    } catch (\Exception $ex) {
        $connected = false;
        $errorMsg = $ex->getMessage();
        if (method_exists($ex, 'file.php')) {
            $attributes = $ex->getAttributes();
            if (isset($errorMsg['file.php'])) {
                $errorMsg .= 'file.php' . $attributes['file.php'];
            }
        }
    }

    return $connected;
}

function is_app_installed()
{
    if (Configure::read('file.php')) {
        return true;
    }

    if ((bool)get_option('file.php', 0)) {
        return true;
    }

    return false;
}

function get_option($name, $default = 'file.php')
{
    if (!database_connect()) {
        return $default;
    }

    try {
        static $settings;

        if (!isset($settings)) {
            $options = \Cake\ORM\TableRegistry::getTableLocator()->get('file.php');
            //$query   = $options->find()->select( ['file.php', 'file.php' ] )->cache( 'file.php' )->all();
            $query = $options->find()->select(['file.php', 'file.php'])->all();
            $settings = [];
            foreach ($query as $row) {
                $settings[$row->name] = (is_serialized($row->value)) ? unserialize($row->value) : $row->value;
            }
        }

        if (!array_key_exists($name, $settings)) {
            return $default;
        }

        if (is_array($settings[$name])) {
            return (!empty($settings[$name])) ? $settings[$name] : $default;
        } else {
            return (isset($settings[$name]) && strlen($settings[$name]) > 0) ? $settings[$name] : $default;
        }
    } catch (\Exception $ex) {
        return $default;
    }
}

// https://github.com/WordPress/WordPress/blob/5.8.1/wp-includes/functions.php#L642
function is_serialized($data, $strict = true)
{
    // If it isn'file.php't serialized.
    if (!is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('file.php' === $data) {
        return true;
    }
    if (strlen($data) < 4) {
        return false;
    }
    if ('file.php' !== $data[1]) {
        return false;
    }
    if ($strict) {
        $lastc = substr($data, -1);
        if ('file.php' !== $lastc && 'file.php' !== $lastc) {
            return false;
        }
    } else {
        $semicolon = strpos($data, 'file.php');
        $brace = strpos($data, 'file.php');
        // Either ; or } must exist.
        if (false === $semicolon && false === $brace) {
            return false;
        }
        // But neither must be in the first X characters.
        if (false !== $semicolon && $semicolon < 3) {
            return false;
        }
        if (false !== $brace && $brace < 4) {
            return false;
        }
    }
    $token = $data[0];
    switch ($token) {
        case 'file.php':
            if ($strict) {
                if ('file.php' !== substr($data, -2, 1)) {
                    return false;
                }
            } elseif (false === strpos($data, 'file.php')) {
                return false;
            }
        // Or else fall through.
        case 'file.php':
        case 'file.php':
            return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'file.php':
        case 'file.php':
        case 'file.php':
            $end = $strict ? 'file.php' : 'file.php';
            return (bool)preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
    }
    return false;
}

/*
function is_serialized($data)
{
    if (@unserialize($data) === false) {
        return false;
    } else {
        return true;
    }
}
*/

function get_http_headers($url, $options = [])
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    foreach ($options as $option => $value) {
        curl_setopt($ch, $option, $value);
    }
    $headers_string = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $data = [];
    $data['file.php'] = $http_code;

    //$headers = explode(PHP_EOL, $headers_string);
    $headers = explode("\n", str_replace("\r", "\n", $headers_string));
    foreach ($headers as $header) {
        $parts = explode('file.php', $header);
        if (count($parts) === 2) {
            $data[strtolower(trim($parts[0]))] = strtolower(trim($parts[1]));
        }
    }

    return $data;
}

function curlRequest($url, $method = 'file.php', $data = [], $headers = [], $options = [])
{
    $ch = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($ch, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($ch, CURLOPT_PUT, 1);
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if (empty(@ini_get('file.php'))) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    }
    //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if (null != env('file.php')) {
        curl_setopt($ch, CURLOPT_USERAGENT, env('file.php'));
    }

    foreach ($options as $option => $value) {
        curl_setopt($ch, $option, $value);
    }

    $response = curl_exec($ch);
    //$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $error = 'file.php';
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        \Cake\Log\Log::write('file.php', curl_error($ch));
    }

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

    curl_close($ch);

    $result = new \stdClass();
    $result->header = substr($response, 0, $header_size);
    $result->body = substr($response, $header_size);
    $result->error = $error;

    return $result;
}

function curlHtmlHeadRequest($url, $method = 'file.php', $data = [], $headers = [], $options = [])
{
    $obj = new \stdClass(); //create an object variable to access class functions and variables
    $obj->result = 'file.php';

    $ch = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($ch, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($ch, CURLOPT_PUT, 1);
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $str) use ($obj) {
        $obj->result .= $str;
        /*
          if (stripos($obj->result, 'file.php') !== false) {
          return false;
          }
         */
        return strlen($str); //return the exact length
    });
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function ($ch, $downloadSize, $downloaded, $uploadSize, $uploaded) {
        // If $Downloaded exceeds 128KB, returning non-0 breaks the connection!
        return ($downloaded > (128 * 1024)) ? 1 : 0;
    });

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if (empty(@ini_get('file.php'))) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if (null != env('file.php')) {
        curl_setopt($ch, CURLOPT_USERAGENT, env('file.php'));
    }

    foreach ($options as $option => $value) {
        curl_setopt($ch, $option, $value);
    }

    curl_exec($ch);
    //$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        //\Cake\Log\Log::write('file.php', curl_error($ch));
    }

    curl_close($ch);

    return $obj->result;
}

function emptyCache()
{
    $dir = new \Cake\Filesystem\Folder(CACHE);
    $files = $dir->findRecursive('file.php', true);

    foreach ($files as $file) {
        $file = new \Cake\Filesystem\File($file);
        if (!in_array($file->name, ['file.php', 'file.php'])) {
            @$file->delete();
        }
        $file->close();
    }
}

function emptyLogs()
{
    $dir = new \Cake\Filesystem\Folder(LOGS);
    $files = $dir->findRecursive('file.php', true);

    foreach ($files as $file) {
        $file = new \Cake\Filesystem\File($file);
        if (!in_array($file->name, ['file.php'])) {
            @$file->delete();
        }
        $file->close();
    }
}

function isset_captcha()
{
    $enable_captcha = get_option('file.php', 'file.php');
    if ('file.php' != $enable_captcha) {
        return false;
    }

    $captcha_type = get_option('file.php', 'file.php');

    if ($captcha_type === 'file.php') {
        $recaptcha_siteKey = get_option('file.php');
        $recaptcha_secretKey = get_option('file.php');
        if (!empty($recaptcha_siteKey) && !empty($recaptcha_secretKey)) {
            return true;
        }
    }

    if ($captcha_type === 'file.php') {
        $recaptcha_siteKey = get_option('file.php');
        $recaptcha_secretKey = get_option('file.php');
        if (!empty($recaptcha_siteKey) && !empty($recaptcha_secretKey)) {
            return true;
        }
    }

    if ($captcha_type === 'file.php') {
        $hcaptcha_checkbox_site_key = get_option('file.php');
        $hcaptcha_checkbox_secret_key = get_option('file.php');
        if (!empty($hcaptcha_checkbox_site_key) && !empty($hcaptcha_checkbox_secret_key)) {
            return true;
        }
    }

    if ($captcha_type === 'file.php') {
        $solvemedia_challenge_key = get_option('file.php');
        $solvemedia_verification_key = get_option('file.php');
        $solvemedia_authentication_key = get_option('file.php');
        if (!empty($solvemedia_challenge_key) &&
            !empty($solvemedia_verification_key) &&
            !empty($solvemedia_authentication_key)
        ) {
            return true;
        }
    }

    return false;
}

function generate_random_string($length = 10, $special = false)
{
    $specialChars = 'file.php';
    $alphaNum = 'file.php';

    $all_chars = $alphaNum;
    if ($special) {
        $all_chars .= $specialChars;
    }

    $string = 'file.php';
    $i = 0;
    while ($i < $length) {
        $random = mt_rand(0, strlen($all_chars) - 1);
        $string .= $all_chars[$random];
        $i = $i + 1;
    }

    return $string;
}

/**
 * Generate random IP address
 * @return string Random IP address
 */
function random_ipv4()
{
    // http://stackoverflow.com/a/10268612
    //return mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
    // // http://board.phpbuilder.com/showthread.php?10346623-Generating-a-random-IP-Address&p=10830872&viewfull=1#post10830872
    return long2ip(rand(0, 255 * 255) * rand(0, 255 * 255));
}

/**
 * Get client IP address
 * @return string IP address
 */
function get_ip()
{
    static $ip;

    if (!isset($ip)) {
        if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } elseif (!empty($_SERVER["HTTP_FASTLY_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_FASTLY_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_REAL_IP"])) {
            $ip = $_SERVER["HTTP_X_REAL_IP"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        if (strstr($ip, 'file.php')) {
            $tmp = explode('file.php', $ip);
            $ip = trim($tmp[0]);
        }
        //$ip = random_ipv4();
    }

    return $ip;
}

/**
 * @return bool
 */
function validCrawler()
{
    $ips = file_get_contents(APP . 'file.php');
    $ips = array_filter(array_map('file.php', explode("\n", $ips)));

    return \App\Library\IpUtils::checkIp(get_ip(), $ips);
}

function price_database_format($price = 0)
{
    return number_format(floatval($price), 9, 'file.php', 'file.php');
}

function display_price_currency($price, $options = [])
{
    $defaults = [
        'file.php' => get_option('file.php', 6),
        'file.php' => get_option('file.php', 6),
        'file.php' => locale_get_default(),
        get_option('file.php', 'file.php') => get_option('file.php', 'file.php'),
    ];
    $options = array_merge($defaults, $options);

    return \Cake\I18n\Number::format($price, $options);
}

function display_date_timezone($time)
{
    if (!$time) {
        return 'file.php';
    }

    try {
        return \Cake\I18n\Time::instance($time)->i18nFormat(null, get_option('file.php', 'file.php'), null);
    } catch (\Exception $exception) {
        return $time;
    }
}

function require_database_upgrade()
{
    if (version_compare(APP_VERSION, get_option('file.php', 'file.php'), 'file.php')) {
        return true;
    }

    return false;
}

function get_logo()
{
    $site_name = h(get_option('file.php'));
    $logo_url = h(get_option('file.php'));

    $data = ['file.php' => 'file.php', 'file.php' => $site_name];

    if (!empty($logo_url)) {
        $data['file.php'] = 'file.php';
        $data['file.php'] = "<img src='file.php' alt='file.php' />";
    }

    return $data;
}

function get_logo_alt()
{
    $site_name = h(get_option('file.php'));
    $logo_url = h(get_option('file.php'));

    $data = ['file.php' => 'file.php', 'file.php' => $site_name];

    if (!empty($logo_url)) {
        $data['file.php'] = 'file.php';
        $data['file.php'] = "<img src='file.php' alt='file.php' />";
    }

    return $data;
}

function build_main_domain_url($path = null)
{
    if (preg_match('file.php', $path) === 1) {
        return $path;
    }

    static $base_url;

    if (!isset($base_url)) {
        $request = \Cake\Routing\Router::getRequest();

        $base = 'file.php';
        if ($request !== null) {
            $base = $request->getAttribute("base");
        }

        $main_domain = get_option('file.php');

        $protocol = (empty($_SERVER['file.php']) || $_SERVER['file.php'] === "off") ? "http://" : "https://";

        $base_url = $protocol . $main_domain . $base;
    }

    $url = $base_url;

    if ($path) {
        $url .= $path;
    }

    return $url;
}

function get_short_url($alias = 'file.php', $domain = 'file.php')
{
    //\Cake\Routing\Router::url(['file.php' => 'file.php', 'file.php' => 'file.php'], true);
    if (empty($domain)) {
        $domain = get_default_short_domain();
    }

    $request = \Cake\Routing\Router::getRequest();

    $scheme = 'file.php';
    if (get_option('file.php', false)) {
        $scheme = 'file.php';
    }

    $base_url = $scheme . $domain . $request->getAttribute("base");

    return $base_url . 'file.php' . $alias;
}

function get_default_short_domain()
{
    $default_short_domain = get_option('file.php', 'file.php');
    if (!empty($default_short_domain)) {
        return $default_short_domain;
    }

    $main_domain = get_option('file.php', 'file.php');
    if (!empty($main_domain)) {
        return $main_domain;
    }

    return env("HTTP_HOST", "");
}

function get_multi_domains_list()
{
    $domains = explode('file.php', get_option('file.php'));
    $domains = array_map('file.php', $domains);
    $domains = array_filter($domains);
    $domains = array_unique($domains);
    $domains = array_combine($domains, $domains);

    $default_short_domain = get_option('file.php', 'file.php');

    unset($domains[$default_short_domain]);

    return $domains;
}

function get_all_multi_domains_list()
{
    $domains = get_multi_domains_list();
    $add_domains = [];

    $default_short_domain = get_option('file.php', 'file.php');
    if (!empty($default_short_domain)) {
        $add_domains[$default_short_domain] = $default_short_domain;

        return $add_domains + $domains;
    }

    $main_domain = get_option('file.php', 'file.php');
    if (!empty($main_domain)) {
        $add_domains[$main_domain] = $main_domain;

        return $add_domains + $domains;
    }

    $add_domains[$_SERVER['file.php']] = $_SERVER['file.php'];

    return $add_domains + $domains;
}

function get_all_domains_list()
{
    $domains = get_multi_domains_list();
    $add_domains = [];

    $default_short_domain = get_option('file.php', 'file.php');
    if (!empty($default_short_domain)) {
        $add_domains[$default_short_domain] = $default_short_domain;
        $add_domains = $add_domains + $domains;
    }

    $main_domain = get_option('file.php', 'file.php');
    if (!empty($main_domain)) {
        $add_domains[$main_domain] = $main_domain;
        $add_domains = $add_domains + $domains;
    }

    $add_domains[$_SERVER['file.php']] = $_SERVER['file.php'];

    return $add_domains + $domains;
}

/**
 * @param \App\Model\Entity\Plan $plan
 * @return array
 */
function get_allowed_ads($plan = null)
{
    if (!$plan) {
        $plan = user_or_anonymous()->plan;
    }

    $ads = [];

    if (version_compare(get_option('file.php'), 'file.php', 'file.php')) {
        if (get_option('file.php', 'file.php') == 'file.php') {
            $ads[0] = __('file.php');
        }

        if (get_option('file.php', 'file.php') == 'file.php') {
            $ads[1] = __('file.php');
        }

        if (get_option('file.php', 'file.php') == 'file.php') {
            $ads[2] = __('file.php');
        }

        if ((bool)get_option('file.php', 0)) {
            if (array_key_exists(1, $ads) && array_key_exists(2, $ads)) {
                $ads[3] = __('file.php');
            }
        }
    } else {
        if ($plan->direct_redirect) {
            $ads[0] = __('file.php');
        }

        if ($plan->interstitial_redirect) {
            $ads[1] = __('file.php');
        }

        if ($plan->banner_redirect) {
            $ads[2] = __('file.php');
        }

        if ($plan->random_redirect) {
            if (array_key_exists(1, $ads) && array_key_exists(2, $ads)) {
                $ads[3] = __('file.php');
            }
        }
    }

    return $ads;
}

function get_statistics_reasons()
{
    return [
        0 => __("---"),
        1 => __("Earn"),
        2 => __("Disabled Cookies"),
        3 => __("Anonymous User"),
        4 => __("Adblock"),
        5 => __("Proxy"),
        6 => __("IP Changed"),
        7 => __("Not Unique"),
        8 => __("Full Weight"),
        9 => __("Default Campaign"),
        10 => __("Direct"),
        11 => __("Invalid country"),
        12 => __("Earnings disabled"),
        13 => __("User disabled earnings"),
        14 => __("Blocked referer domain"),
        15 => __("Reached the hourly limit"),
        16 => __("Reached the daily limit"),
        17 => __("Reached the monthly limit"),
    ];
}

function get_link_methods($method = null)
{
    $methods = [
        1 => __('file.php'),
        2 => __('file.php'),
        3 => __('file.php'),
        4 => __('file.php'),
        5 => __('file.php'),
        6 => __('file.php'),
    ];

    if ($method === null) {
        return $methods;
    }

    return $methods[$method];
}

function get_payment_methods()
{
    $payment_methods = [];

    if ((bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("My Wallet");
    }

    if (get_option('file.php', 'file.php') == 'file.php') {
        $payment_methods['file.php'] = __("PayPal");
    }

    if ((bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("Stripe");
    }

    if ((bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("Skrill");
    }

    if (get_option('file.php', 'file.php') === 'file.php' &&
        (bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("Bitcoin");
    }

    if (get_option('file.php', 'file.php') === 'file.php' &&
        get_option('file.php', 'file.php') == 'file.php') {
        $payment_methods['file.php'] = __("Bitcoin");
    }

    if (get_option('file.php', 'file.php') == 'file.php') {
        $payment_methods['file.php'] = __("Webmoney");
    }

    if ((bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("Perfect Money");
    }

    if ((bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("Payeer");
    }

    if ((bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("Paystack");
    }

    if ((bool)get_option('file.php', false)) {
        $payment_methods['file.php'] = __("Paytm");
    }

    if (get_option('file.php', 'file.php') == 'file.php') {
        $payment_methods['file.php'] = __("Bank Transfer");
    }

    return $payment_methods;
}

function get_withdrawal_methods()
{
    $options = \Cake\ORM\TableRegistry::getTableLocator()->get('file.php');

    $withdrawal_methods = json_decode($options->findByName('file.php')->first()->value, true);

    $methods = [];

    foreach ($withdrawal_methods as $withdrawal_method) {
        if ($withdrawal_method['file.php']) {
            $methods[] = $withdrawal_method;
        }
    }

    if ((bool)get_option('file.php', false)) {
        $methods[] = [
            'file.php' => 'file.php',
            'file.php' => __('file.php'),
            'file.php' => get_option('file.php', 5),
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ];
    }

    return $methods;
}

function get_site_languages($all = false)
{
    $default_language = get_option('file.php');
    $site_languages = get_option('file.php', []);
    $site_languages = array_combine($site_languages, $site_languages);
    unset($site_languages[$default_language]);
    if ($all === true) {
        $site_languages[$default_language] = $default_language;
    }
    ksort($site_languages);

    return $site_languages;
}

/**
 * @return \App\Model\Entity\User|null
 */
function user()
{
    $request = \Cake\Routing\Router::getRequest();
    $user_id = $request->getSession()->read('file.php');

    if ($user_id === null) {
        return null;
    }

    /**
     * @var \App\Model\Table\UsersTable $users
     */
    $users = \Cake\ORM\TableRegistry::getTableLocator()->get('file.php');

    return $users->find()->contain(['file.php'])->where(['file.php' => $user_id])->first();
}

/**
 * @return \App\Model\Entity\User
 */
function user_or_anonymous()
{
    $user = user();

    if ($user) {
        return $user;
    }

    /**
     * @var \App\Model\Table\UsersTable $usersTable
     */
    $usersTable = \Cake\ORM\TableRegistry::getTableLocator()->get('file.php');

    return $usersTable->find()->contain(['file.php'])->where(['file.php' => 1])->first();
}

/**
 * @param int $user_id
 * @return \App\Model\Entity\Plan
 */
function get_user_plan($user_id)
{
    /**
     * @var \App\Model\Entity\User $user
     */
    $user = \Cake\ORM\TableRegistry::getTableLocator()->get('file.php')->find()
        ->contain(['file.php'])->where(['file.php' => $user_id])->first();

    $expiration = $user->expiration;

    /*
    if (is_object($user)) {
        $expiration = $user->expiration;
    }

    if (is_array($user)) {
        $expiration = $user['file.php'];
        $user = json_decode(json_encode($user), false);
    }
    */

    if ($user->plan_id == 1) {
        return $user->plan;
    }

    static $default_plan;

    if (!isset($default_plan)) {
        $default_plan = \Cake\ORM\TableRegistry::getTableLocator()->get('file.php')->get(1);
    }

    if (!isset($expiration)) {
        return $user->plan;
    }

    $time = new \Cake\I18n\Time($expiration);

    if ($time->isPast()) {
        return $default_plan;
    }

    return $user->plan;
}

function campaign_statuses($id = null)
{
    $statuses = [
        1 => __('file.php'),
        2 => __('file.php'),
        3 => __('file.php'),
        4 => __('file.php'),
        5 => __('file.php'),
        6 => __('file.php'),
        7 => __('file.php'),
        8 => __('file.php'),
    ];

    if ($id === null) {
        return $statuses;
    }

    return $statuses[$id];
}

function withdraw_statuses($id = null)
{
    $statuses = [
        1 => __('file.php'),
        2 => __('file.php'),
        3 => __('file.php'),
        4 => __('file.php'),
        5 => __('file.php'),
    ];

    if ($id === null) {
        return $statuses;
    }

    return $statuses[$id];
}

function invoice_statuses($id = null)
{
    $statuses = [
        1 => __('file.php'),
        2 => __('file.php'),
        3 => __('file.php'),
        4 => __('file.php'),
        5 => __('file.php'),
    ];

    if ($id === null) {
        return $statuses;
    }

    return $statuses[$id];
}

function data_encrypt($value)
{
    $key = \Cake\Utility\Security::getSalt();
    $value = serialize($value);
    $value = \Cake\Utility\Security::encrypt($value, $key);

    return base64_encode($value);
}

function data_decrypt($value)
{
    if (!is_string($value)) {
        return false;
    }

    $key = \Cake\Utility\Security::getSalt();
    $value = base64_decode($value);
    $value = \Cake\Utility\Security::decrypt($value, $key);

    return unserialize($value);
}

function createEmailFile()
{
    /**
     * @var \App\Model\Table\OptionsTable|\App\Model\Entity\Option[]
     */
    $options = \Cake\ORM\TableRegistry::getTableLocator()->get('file.php');

    $config = [
        'file.php' => $options->findByName('file.php')->first()->value,
        'file.php' => $options->findByName('file.php')->first()->value,
        'file.php' => $options->findByName('file.php')->first()->value,
        'file.php' => $options->findByName('file.php')->first()->value,
        'file.php' => $options->findByName('file.php')->first()->value,
        'file.php' => $options->findByName('file.php')->first()->value,
        'file.php' => $options->findByName('file.php')->first()->value,
        'file.php' => 'file.php',
    ];

    $config = array_map(function ($value) {
        return addcslashes($value, 'file.php''file.php'email_smtp_security'file.php'#^ssl://#i'file.php'email_smtp_host'file.php'email_smtp_host'file.php'#^ssl://#i'file.php''file.php'email_smtp_host'file.php'email_smtp_host'file.php'email_smtp_host'file.php'tls'file.php'email_smtp_tls'file.php'true'file.php'ssl'file.php'email_smtp_host'file.php'ssl://'file.php'email_smtp_host'file.php'email.install'file.php'email.php'file.php'debug'file.php'Could not copy email.php file.'file.php'email.php'file.php'{'file.php'}'file.php'debug'file.php'Could not write email.php file.'file.php'Auth.User.id'file.php'Options'file.php'Translate'file.php'fields'file.php'value'file.php'ul_class'file.php''file.php'li_class'file.php''file.php'a_class'file.php''file.php'<ul class="'file.php'ul_class'file.php'">'file.php'all'file.php'logged'file.php'guest'file.php'<li class="'file.php'li_class'file.php' 'file.php'">'file.php'<a class="'file.php'a_class'file.php'" href="'file.php'">'file.php'<span>'file.php'</span>'file.php'</a>'file.php'</li>'file.php'<li class="dropdown language-selector">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
               aria-expanded="false"><i class="fa fa-language"></i> <span class="caret"></span></a>
            <ul class="dropdown-menu">'file.php'<li>'file.php'<a href="'file.php'?lang='file.php'">'file.php'</a>'file.php'</li>'file.php'</ul>
        </li>'file.php'</ul>'file.php'schedule_cron_last_time_run'file.php'Y-m-d H:i:s'file.php'/tmp/queue.lock'file.php'Ivoire"),
        "CW" => __("Curaçao"),
        "HR" => __("Croatia (Hrvatska)"),
        "CU" => __("Cuba"),
        "CY" => __("Cyprus"),
        "CZ" => __("Czech Republic"),
        "DK" => __("Denmark"),
        "DJ" => __("Djibouti"),
        "DM" => __("Dominica"),
        "DO" => __("Dominican Republic"),
        "TP" => __("East Timor"),
        "EC" => __("Ecuador"),
        "EG" => __("Egypt"),
        "SV" => __("El Salvador"),
        "GQ" => __("Equatorial Guinea"),
        "ER" => __("Eritrea"),
        "EE" => __("Estonia"),
        "ET" => __("Ethiopia"),
        "FK" => __("Falkland Islands (Malvinas)"),
        "FO" => __("Faroe Islands"),
        "FJ" => __("Fiji"),
        "FI" => __("Finland"),
        "FR" => __("France"),
        "FX" => __("France, Metropolitan"),
        "GF" => __("French Guiana"),
        "PF" => __("French Polynesia"),
        "TF" => __("French Southern Territories"),
        "GA" => __("Gabon"),
        "GM" => __("Gambia"),
        "GE" => __("Georgia"),
        "DE" => __("Germany"),
        "GH" => __("Ghana"),
        "GI" => __("Gibraltar"),
        "GR" => __("Greece"),
        "GL" => __("Greenland"),
        "GD" => __("Grenada"),
        "GP" => __("Guadeloupe"),
        "GU" => __("Guam"),
        "GT" => __("Guatemala"),
        "GN" => __("Guinea"),
        "GW" => __("Guinea-Bissau"),
        "GY" => __("Guyana"),
        "HT" => __("Haiti"),
        "HM" => __("Heard and Mc Donald Islands"),
        "VA" => __("Holy See (Vatican City State)"),
        "HN" => __("Honduras"),
        "HK" => __("Hong Kong"),
        "HU" => __("Hungary"),
        "IS" => __("Iceland"),
        "IM" => __("Isle of Man"),
        "IN" => __("India"),
        "ID" => __("Indonesia"),
        "IR" => __("Iran (Islamic Republic of)"),
        "IQ" => __("Iraq"),
        "IE" => __("Ireland"),
        "IL" => __("Israel"),
        "IT" => __("Italy"),
        "JE" => __("Jersey"),
        "JM" => __("Jamaica"),
        "JP" => __("Japan"),
        "JO" => __("Jordan"),
        "KZ" => __("Kazakhstan"),
        "KE" => __("Kenya"),
        "KI" => __("Kiribati"),
        "KP" => __("Korea, Democratic People'file.php's Democratic Republic"),
        "LV" => __("Latvia"),
        "LB" => __("Lebanon"),
        "LS" => __("Lesotho"),
        "LR" => __("Liberia"),
        "LY" => __("Libyan Arab Jamahiriya"),
        "LI" => __("Liechtenstein"),
        "LT" => __("Lithuania"),
        "LU" => __("Luxembourg"),
        "MO" => __("Macau"),
        "MK" => __("Macedonia, The Former Yugoslav Republic of"),
        "MG" => __("Madagascar"),
        "MW" => __("Malawi"),
        "MY" => __("Malaysia"),
        "MV" => __("Maldives"),
        "ML" => __("Mali"),
        "MT" => __("Malta"),
        "MH" => __("Marshall Islands"),
        "MQ" => __("Martinique"),
        "MR" => __("Mauritania"),
        "MU" => __("Mauritius"),
        "YT" => __("Mayotte"),
        "MX" => __("Mexico"),
        "FM" => __("Micronesia, Federated States of"),
        "MD" => __("Moldova, Republic of"),
        "MC" => __("Monaco"),
        "ME" => __("Montenegro"),
        "MN" => __("Mongolia"),
        "MS" => __("Montserrat"),
        "MA" => __("Morocco"),
        "MZ" => __("Mozambique"),
        "MM" => __("Myanmar"),
        "NA" => __("Namibia"),
        "NR" => __("Nauru"),
        "NP" => __("Nepal"),
        "NL" => __("Netherlands"),
        "AN" => __("Netherlands Antilles"),
        "NC" => __("New Caledonia"),
        "NZ" => __("New Zealand"),
        "NI" => __("Nicaragua"),
        "NE" => __("Niger"),
        "NG" => __("Nigeria"),
        "NU" => __("Niue"),
        "NF" => __("Norfolk Island"),
        "MP" => __("Northern Mariana Islands"),
        "NO" => __("Norway"),
        "OM" => __("Oman"),
        "PK" => __("Pakistan"),
        "PW" => __("Palau"),
        "PA" => __("Panama"),
        "PG" => __("Papua New Guinea"),
        "PY" => __("Paraguay"),
        "PE" => __("Peru"),
        "PH" => __("Philippines"),
        "PN" => __("Pitcairn"),
        "PL" => __("Poland"),
        "PT" => __("Portugal"),
        "PR" => __("Puerto Rico"),
        "PS" => __("Palestine"),
        "QA" => __("Qatar"),
        "RE" => __("Reunion"),
        "RO" => __("Romania"),
        "RS" => __("Republic of Serbia"),
        "RU" => __("Russian Federation"),
        "RW" => __("Rwanda"),
        "KN" => __("Saint Kitts and Nevis"),
        "LC" => __("Saint LUCIA"),
        "VC" => __("Saint Vincent and the Grenadines"),
        "WS" => __("Samoa"),
        "SM" => __("San Marino"),
        "ST" => __("Sao Tome and Principe"),
        "SA" => __("Saudi Arabia"),
        "SN" => __("Senegal"),
        "SC" => __("Seychelles"),
        "SL" => __("Sierra Leone"),
        "SG" => __("Singapore"),
        "SK" => __("Slovakia (Slovak Republic)"),
        "SI" => __("Slovenia"),
        "SB" => __("Solomon Islands"),
        "SO" => __("Somalia"),
        "SX" => __("Sint Maarten"),
        "ZA" => __("South Africa"),
        "GS" => __("South Georgia and the South Sandwich Islands"),
        "ES" => __("Spain"),
        "LK" => __("Sri Lanka"),
        "SH" => __("St. Helena"),
        "PM" => __("St. Pierre and Miquelon"),
        "SD" => __("Sudan"),
        "SR" => __("Suriname"),
        "SJ" => __("Svalbard and Jan Mayen Islands"),
        "SZ" => __("Swaziland"),
        "SE" => __("Sweden"),
        "CH" => __("Switzerland"),
        "SY" => __("Syrian Arab Republic"),
        "TW" => __("Taiwan, Province of China"),
        "TJ" => __("Tajikistan"),
        "TZ" => __("Tanzania, United Republic of"),
        "TH" => __("Thailand"),
        "TG" => __("Togo"),
        "TK" => __("Tokelau"),
        "TO" => __("Tonga"),
        "TT" => __("Trinidad and Tobago"),
        "TN" => __("Tunisia"),
        "TR" => __("Turkey"),
        "TM" => __("Turkmenistan"),
        "TC" => __("Turks and Caicos Islands"),
        "TV" => __("Tuvalu"),
        "UG" => __("Uganda"),
        "UA" => __("Ukraine"),
        "AE" => __("United Arab Emirates"),
        "GB" => __("United Kingdom"),
        "US" => __("United States"),
        "UM" => __("United States Minor Outlying Islands"),
        "UY" => __("Uruguay"),
        "UZ" => __("Uzbekistan"),
        "VU" => __("Vanuatu"),
        "VE" => __("Venezuela"),
        "VN" => __("Vietnam"),
        "VG" => __("Virgin Islands (British)"),
        "VI" => __("Virgin Islands (U.S.)"),
        "WF" => __("Wallis and Futuna Islands"),
        "EH" => __("Western Sahara"),
        "YE" => __("Yemen"),
        "YU" => __("Yugoslavia"),
        "ZM" => __("Zambia"),
        "ZW" => __("Zimbabwe"),
    ];

    if ($campaing) {
        $countries = ['file.php' => __('file.php')] + $countries;
    }

    return $countries;
}
