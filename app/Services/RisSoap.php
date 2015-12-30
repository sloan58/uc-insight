<?php
namespace App\Services;

use SoapFault;
use SoapClient;
use App\Cluster;
use App\Exceptions\SoapException;

class RisSoap extends SoapClient{

    /**
     * @var resource
     */
    private $cluster;

    public function __construct()
    {
        if(!\Auth::user()->cluster) {
            throw new SoapException("You have no Active Cluster Selected");
        }
        
        $this->cluster = \Auth::user()->cluster;

        parent::__construct(storage_path() . '/app/sxml/RISAPI.wsdl',
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
     * @param $phoneArray
     * @return bool|\Exception|SoapFault
     * @throws SoapException
     */
    public function getDeviceIp($phoneArray)
    {
        try {
            $response = $this->SelectCmDevice('',[
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