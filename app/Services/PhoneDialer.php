<?php
/**
 * Created by PhpStorm.
 * User: sloan58
 * Date: 6/27/15
 * Time: 11:04 AM
 */

namespace App\Services;


use App\Eraser;
use Sabre\Xml\Reader;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class PhoneDialer {

    /**
     * @var Client
     */
    public $client;

    /**
     * @var cluster
     */
    private $cluster;
    
    /**
     * @param $phoneIP
     */
    function __construct($phoneIP)
    {
        if(!\Auth::user()->cluster) {
            throw new SoapException("You have no Active Cluster Selected");
        }

        $this->cluster = \Auth::user()->cluster;

        $this->client = new Client([
            'base_uri' => 'http://' . $phoneIP,
            'verify' => false,
            'headers' => [
                'Accept' => 'application/xml',
                'Content-Type' => 'application/xml'
            ],
            'auth' => [
                    $this->cluster->username, $this->cluster->password
                ],
        ]);

        $this->reader = new Reader;
    }

    // public function dial(Eraser $itl,$keys)
    public function dial($keys)
    {

        foreach ($keys as $k)
        {
            if ( $k == "Key:Sleep")
            {
                sleep(2);
                continue;
            }

            $xml = 'XML=<CiscoIPPhoneExecute><ExecuteItem Priority="0" URL="' . $k . '"/></CiscoIPPhoneExecute>';

            try {

                // $response = $this->client->post('http://' . $itl->ip_address . '/CGI/Execute',['body' => $xml]);

                //Temp workaround for USC NAT
                $response = $this->client->post('http://10.134.174.78/CGI/Execute',['body' => $xml]);

            } catch (RequestException $e) {

                dd($e);

                if($e instanceof ClientException)
                {
                    //Unauthorized
                    Log::error('Authentication Exception', [$e]);
                    // $itl->failure_reason = "Authentication Exception";
                    // $itl->save();
                }
                elseif($e instanceof ConnectException)
                {
                    //Can't Connect
                    Log::error('Connection Exception', [$e]);
                    // $itl->failure_reason = "Connection Exception";
                    // $itl->save();
                }
                else
                {
                    //Other exception
                    Log::error('Unknown Error', [$e]);
                    // $itl->failure_reason = "Unknown Exception";
                    // $itl->save();
                }

                return false;
            }

            /*
             * Check our response code and flip
             * $return to false if non zero
             */
            $this->reader->xml($response->getBody()->getContents());
            $response = $this->reader->parse();

            if(isset($response['CiscoIPPhoneResponse']))
            {
                Log::info('dial(),response', [$response]);

            }
            elseif(isset($response['name']) &&  $response['name'] == '{}CiscoIPPhoneError')
            {
                //Log an Error
                switch($response['attributes']['Number'])
                {
                    case 4:
                        $errorType = 'Authentication Exception';
                        break;
                    case 6:
                        $errorType = 'Invalid URL Exception';
                        break;
                    default:
                        $errorType = 'Unknown Exception';
                        break;
                }

                Log::error($errorType, [$response]);
                // $itl->failure_reason = $errorType;
                // $itl->result = "Fail";
                // $itl->save();
                return false;
            }

        }
        return true;
    }

}