<?php
namespace App\Services;

use App\Cluster;
use Illuminate\Support\Facades\Artisan;
use SoapClient;
use SoapFault;

class RisSoap {

    /**
     * @var resource
     */
    protected $client;

    /**
     * @param Cluster $cluster
     */
    public function __construct(Cluster $cluster)
    {
        $this->cluster = $cluster;

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