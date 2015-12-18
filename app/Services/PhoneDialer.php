<?php
/**
 * Created by PhpStorm.
 * User: sloan58
 * Date: 6/27/15
 * Time: 11:04 AM
 */

namespace App\Services;


use App\Eraser;
use App\Exceptions\SoapException;
use Sabre\Xml\Reader;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Exceptions\PhoneDialerException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class PhoneDialer {

    /**
     * @var Client
     */
    private $client;

    /**
     * @var cluster
     */
    private $cluster;

    /**
     * @var phoneIp
     */
    private $phoneIp;

    /**
     * @param $phoneIP
     * @throws SoapException
     */
    function __construct($phoneIP)
    {
        if(!\Auth::user()->cluster) {
            throw new SoapException("You have no Active Cluster Selected");
        }

        $this->phoneIP = $phoneIP;

        $this->cluster = \Auth::user()->cluster;

        $this->client = new Client([
            'base_uri' => 'http://' . $this->phoneIP,
            'verify' => false,
            'connect_timeout' => 2,
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

    public function dial(Eraser $tle,$keys)
    {
        $mac = $tle->phone->mac;
        $ip = $this->phoneIP;

        foreach ($keys as $k)
        {
            if ( $k == "Key:Sleep")
            {
                sleep(2);
                continue;
            }

            $xml = 'XML=<CiscoIPPhoneExecute><ExecuteItem Priority="0" URL="' . $k . '"/></CiscoIPPhoneExecute>';

            try {

                 $response = $this->client->post('http://' . $tle->ip_address . '/CGI/Execute',['body' => $xml]);

                //Temp workaround for USC NAT
//                $response = $this->client->post('http://10.134.174.78/CGI/Execute',['body' => $xml]);

            } catch (RequestException $e) {

                if($e instanceof ClientException)
                {
                    //Unauthorized
                    $tle->failure_reason = "Authentication Exception";
                    $tle->save();
                    throw new PhoneDialerException("$mac @ $ip Authentication Exception");
                }
                elseif($e instanceof ConnectException)
                {
                    //Can't Connect
                    $tle->failure_reason = "Connection Exception";
                    $tle->save();
                    throw new PhoneDialerException("$mac @ $ip Connection Exception");
                }
                else
                {
                    //Other exception
                    $tle->failure_reason = "Unknown Exception";
                    $tle->save();
                    throw new PhoneDialerException("$mac @ $ip $e->getMessage()");
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

                $tle->failure_reason = $errorType;
                $tle->result = "Fail";
                $tle->save();
                throw new PhoneDialerException("$mac @ $ip $errorType");
            }

        }
        return true;
    }

}