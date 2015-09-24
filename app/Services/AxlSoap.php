<?php
namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use SoapClient;
use SoapFault;

/**
 * Class AxlSoap
 * @package App\Services
 */
class AxlSoap {

    /**
     * @var resource
     */
    protected $client;

    /**
     * @param $wsdlPath
     * @param $location
     * @param $user
     * @param $pass
     */
    public function __construct()
    {
        $this->client = new SoapClient(app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            [
                'trace'=> true,
                'exceptions'=> true,
                'location' => env('CUCM_AXL_LOCATION'),
                'login' => env('CUCM_LOGIN'),
                'password' => env('CUCM_PASS')
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
     * @param $userId
     * @return mixed
     */
    public function getPhone($macAddress)
    {
        try {
            return $this->client->getPhone([
                'name' => $macAddress
            ]);
        } catch(SoapFault $E) {

            dd($E);
            return $E;

        }
    }

    /**
     * @param $appUserId
     * @return \Exception|\SoapFault
     */
    public function getAppUser($appUserId)
    {
        try {
            return $this->client->getAppUser([
                'userid' => $appUserId
            ]);
        } catch(SoapFault $E) {

//            dd($E);
            return $E;
        }
    }

    /**
     * @param $appUserId
     * @param $devices
     * @return \Exception|SoapFault
     */
    public function updateAppUser($appUserId,$devices)
    {
        try {
            return $this->client->updateAppUser([
                'userid' => $appUserId,
                'associatedDevices' => [
                    'device' => $devices
                ]
            ]);
        } catch(SoapFault $E) {

            dd($E);
            return $E;
        }
    }

    /**
     * @param $sql
     * @return \Exception|SoapFault
     */
    public function executeSQLQuery($sql)
    {
        try {
            return $this->client->executeSQLQuery([
                'sql' => $sql,
            ]);
        } catch(SoapFault $E) {

            return $E;
        }
    }
}