<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 09-Aug-21
 * Time: 12:16
 */

namespace App\Helpers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class EmailHelper {

    public static function sendEmail($to_email, $to_name, $subject, $body_html, $sender_name = null, $sender_email = null) {
        return self::sendEmailUsingK1ng($to_email, $subject, $body_html, $sender_name, $sender_email);
    }

    public static function sendEmailUsingK1ng($to_email, $subject, $body_html, $sender_name = null, $sender_email = null) {
        $client = new Client(['verify' => false]);

        try {
            $url = env("K1NG_EMAIL_URL", "https://api.k1nguniverse.com/api/v1/send");
            $response = $client->post($url, [
                "form_params" => [
                    "api_key" => env("K1NG_EMAIL_API_KEY"),
                    "api_pass" => env("K1NG_EMAIL_API_PASS"),
                    "module" => "EMAIL",
                    "sub_module" => env("K1NG_EMAIL_SUB_MODULE", "REGULAR"),
                    "sid" => ($sender_email != null ? $sender_email : "info@yukk.co.id"),
                    "from_name" => ($sender_name != null ? $sender_name : "YUKK"),
                    "subject" => $subject,
                    "destination" => $to_email,
                    "content" => $body_html,
                ],
            ]);

        } catch (RequestException $guzzle_exception) {
            if ($guzzle_exception->hasResponse()) {
                $response = $guzzle_exception->getResponse();
            } else {
                // Don't have Response??
                // Timeout ???
                $response = null;
            }
        } catch (GuzzleException $guzzle_exception) {
            // Timeout
            $response = null;
        } catch (\Exception $exception) {
            try {
                $response = $exception->getResponse();
            } catch (\Exception $e) {
                $response = null;
            }
        }

        if ($response) {
            // There is a response
            //  Build response based on the $response
            $result = \GuzzleHttp\json_decode($response->getBody()->getContents());
        } else {
            // There is no response
            //  Build a default Response
            $result = null;
        }

        return $result;
    }

}