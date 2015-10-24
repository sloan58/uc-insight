<?php
namespace App\Services;

use SoapFault;
use SoapClient;
use App\Cluster;
use App\Exceptions\SoapException;

class RisSoap {

    /**
     * @var resource
     */
    protected $client;

    public function __construct()
    {
        $this->cluster = Cluster::where('active', true)->first();

        $this->client = new SoapClient(storage_path() . '/app/sxml/RISAPI.wsdl',
            [
                'trace' => true,
                'exceptions' => true,
                'location' => 'https://' . $this->cluster->ip . ':8443/realtimeservice/services/RisPort',
                'login' => $this->cluster->username,
                'password' => $this->cluster->password,
                'stream_context' => $this->cluster->verify_peer ?: stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))
            ]
        );
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
     * @return bool|\Exception|SoapFault
     * @throws SoapException
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
        } catch (SoapFault $e) {

            /*
             * Loop if RISPort error for exceeding maximum calls in 1 minute
             * The typo in the error message is not mine, it is courtesy of Cisco :-(
             */
            if (preg_match('/^AxisFault: Exceeded allowed rate for Reatime information/',$e->faultstring))
            {
                sleep(30);
                $this->getDeviceIp($phoneArray);
            }

            // It's a real error, let's bail!
            throw new SoapException($e->faultstring);
        }

        return $response["SelectCmDeviceResult"];
    }
}