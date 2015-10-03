<?php
namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use SoapClient;
use SoapFault;

class RisSoap {

    /**
     * @var resource
     */
    protected $client;

    public function __construct($wsdlPath,$location,$user,$pass)
    {
        $this->client = new SoapClient($wsdlPath,
            [
                'trace'=> true,
                'exceptions'=> true,
                'location'=> $location,
                'login'=> $user,
                'password'=> $pass,
            ]
        );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunctions()
    {
        return $this->client->__getFunctions();
    }

    /**
     * @return mixed
     */
    public function getLastRequest()
    {
        return $this->client->__getLastRequest();
    }

    /**
     * @return string
     */
    public function getLastRequestHeaders()
    {
        return $this->client->__getLastRequestHeaders();
    }

    /**
     * @return string
     */
    public function getLastResponseHeaders()
    {
        return $this->client->__getLastResponseHeaders();
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->client->__getLastResponse();
    }

    /**
     * @param $phoneArray
     * @return bool|\Exception|\SoapFault
     */
    public function getDeviceIp($phoneArray)
    {
        try {
            $response = $this->client->SelectCmDevice('',[
                'MaxReturnedDevices'=>'1000',
                'Class'=>'Any',
                'Model'=>'255',
                'Status'=>'Any',
                'NodeName'=>'',
                'SelectBy'=>'Name',
                'SelectItems'=>
                    $phoneArray
            ]);
        } catch (SoapFault $E) {

            // Loop if we get a RISPort error for exceeding maximum calls in 1 minute
            if (preg_match('/^AxisFault: Exceeded allowed rate for Reatime information/',$E->faultstring))
            {
                sleep(30);
                $this->getDeviceIp($phoneArray);
            }
            return $E;
        }


        /*
         * No Errors
         * Process Results
         */

        return $response["SelectCmDeviceResult"];
        $SelectCmDeviceResult = $response["SelectCmDeviceResult"];

        /*
         * Return results if they exist
         * Or return false
         */
        if ($SelectCmDeviceResult->CmNodes) {
            return $SelectCmDeviceResult->CmNodes;
        }
        return false;
    }
}