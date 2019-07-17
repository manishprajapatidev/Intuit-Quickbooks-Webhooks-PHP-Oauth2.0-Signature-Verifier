<?php

/**
 * Intuit-Quickbooks-Webhooks-PHP-Oauth2.0-Signature-Verifier
 *
 * @Manish Prajapati - @manprajapat
 */

// After you are done, click the 'Show Token' button, Replace by xxx-xxx-xxxx-xxxx-xxxxxxxx
$verifier_token = "xxx-xxx-xxxx-xxxx-xxxxxxxx";
$isValid = isValidQbWebhookSignature($verifier_token);

if($isValid) {
    $QBORealmID = 'XYZ'; //get QBORealmID from your Account/ Playground
    $dataPosted = $_REQUEST;
    $key = array_search($QBORealmID, array_column($dataPosted['eventNotifications'], 'realmId'));
    $eventObj = $dataPosted['eventNotifications'][$key];
    if (count($eventObj['dataChangeEvent']['entities'])) {

        // Do you work now, Signature is valid
    }
} else {
    // Signature is not valid
}

function isValidQbWebhookSignature($webhook_token)
{
    if (isset($_SERVER['HTTP_INTUIT_SIGNATURE']) && !empty($_SERVER['HTTP_INTUIT_SIGNATURE'])) {
        $payLoad = file_get_contents("php://input");
        if (isValidJSON($payLoad)) {
            $payloadHash = hash_hmac('sha256', $payLoad, $webhook_token);
            $singatureHash = bin2hex(base64_decode($_SERVER['HTTP_INTUIT_SIGNATURE']));
            return $payloadHash == $singatureHash;
        }
    }
    return false;
}

function isValidJSON($string) {

    if (!isset($string) || trim($string) === '') {
        return false;
    }
    @json_decode($string);
    if (json_last_error() != JSON_ERROR_NONE) {
        return false;
    }
    return true;
}