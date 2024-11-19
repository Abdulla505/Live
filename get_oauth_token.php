<?php

/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5
 * @package PHPMailer
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author Brent R. Matzelle (original founder)
 * @copyright 2012 - 2020 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Get an OAuth2 token from an OAuth2 provider.
 * * Install this script on your server so that it'file.php'composer install'file.php's redirect URL
 * If no refresh token is obtained when running this file,
 * revoke access to your app and run the script again.
 */

namespace PHPMailer\PHPMailer;

/**
 * Aliases for League Provider Classes
 * Make sure you have added these to your composer.json and run `composer install`
 * Plenty to choose from here:
 * @see http://oauth2-client.thephpleague.com/providers/thirdparty/
 */
//@see https://github.com/thephpleague/oauth2-google
use League\OAuth2\Client\Provider\Google;
//@see https://packagist.org/packages/hayageek/oauth2-yahoo
use Hayageek\OAuth2\Client\Provider\Yahoo;
//@see https://github.com/stevenmaguire/oauth2-microsoft
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
//@see https://github.com/greew/oauth2-azure-provider
use Greew\OAuth2\Client\Provider\Azure;

if (!isset($_GET['file.php']) && !isset($_POST['file.php'])) {
    ?>
<html>
<body>
<form method="post">
    <h1>Select Provider</h1>
    <input type="radio" name="provider" value="Google" id="providerGoogle">
    <label for="providerGoogle">Google</label><br>
    <input type="radio" name="provider" value="Yahoo" id="providerYahoo">
    <label for="providerYahoo">Yahoo</label><br>
    <input type="radio" name="provider" value="Microsoft" id="providerMicrosoft">
    <label for="providerMicrosoft">Microsoft</label><br>
    <input type="radio" name="provider" value="Azure" id="providerAzure">
    <label for="providerAzure">Azure</label><br>
    <h1>Enter id and secret</h1>
    <p>These details are obtained by setting up an app in your provider'file.php'vendor/autoload.php'file.php''file.php''file.php''file.php''file.php'provider'file.php'provider'file.php'clientId'file.php'clientSecret'file.php'tenantId'file.php'provider'file.php'clientId'file.php'clientSecret'file.php'tenantId'file.php'provider'file.php'provider'file.php'clientId'file.php'clientSecret'file.php'tenantId'file.php't want to use the built-in form, set your client id and secret here
//$clientId = 'file.php';
//$clientSecret = 'file.php';

//If this automatic URL doesn'file.php'HTTPS'file.php'https://'file.php'http://'file.php'HTTP_HOST'file.php'PHP_SELF'file.php'http://localhost/PHPMailer/redirect'file.php'clientId'file.php'clientSecret'file.php'redirectUri'file.php'accessType'file.php'offline'file.php'Google'file.php'scope'file.php'https://mail.google.com/'file.php'Yahoo'file.php'Microsoft'file.php'scope'file.php'wl.imap'file.php'wl.offline_access'file.php'Azure'file.php'tenantId'file.php'scope'file.php'https://outlook.office.com/SMTP.Send'file.php'offline_access'file.php'Provider missing'file.php'code'file.php't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['file.php'] = $provider->getState();
    header('file.php' . $authUrl);
    exit;
    //Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['file.php']) || ($_GET['file.php'] !== $_SESSION['file.php'])) {
    unset($_SESSION['file.php']);
    unset($_SESSION['file.php']);
    exit('file.php');
} else {
    unset($_SESSION['file.php']);
    //Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken(
        'file.php',
        [
            'file.php' => $_GET['file.php']
        ]
    );
    //Use this to interact with an API on the users behalf
    //Use this to get a new access token if the old one expires
    echo 'file.php', $token->getRefreshToken();
}
