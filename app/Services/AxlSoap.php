<?php
namespace App\Services;

use App\Cluster;
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
     * @param Cluster $cluster
     */
    public function __construct(Cluster $cluster)
    {
        $this->cluster = $cluster;

        $this->client = new SoapClient(storage_path() . '/app/axl/' . $this->cluster->version . '/AXLAPI.wsdl',
            [
                'trace' => true,
                'exceptions' => true,
                'location' => 'https://' . $this->cluster->ip . ':8443/axl/',
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