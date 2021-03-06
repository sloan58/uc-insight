<?php
namespace App\Services;

use SoapFault;
use SoapClient;
use App\Cluster;
use App\Exceptions\SoapException;

/**
 * Class AxlSoap
 * @package App\Services
 */
class AxlSoap extends SoapClient {

    /**
     * @var resource
     */
    private $cluster;

    /**
     *
     */
    public function __construct()
    {
        if(!\Auth::user()->cluster) {
            throw new SoapException("You have no Active Cluster Selected");
        }

        $this->cluster = \Auth::user()->cluster;

        parent::__construct(storage_path() . '/app/axl/' . $this->cluster->version . '/AXLAPI.wsdl',
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
     * @param $sql
     * @throws \App\Exceptions\SoapException
     * @return \Exception|SoapFault
     */
    public function executeQuery($sql)
    {
        try {
            return $this->executeSQLQuery([
                'sql' => $sql,
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }
    }

    /**
     * @throws \App\Exceptions\SoapException
     * @internal param $appUserId
     * @return \Exception|\SoapFault
     */
    public function getAxlUser()
    {
        $userType = 'get' . $this->cluster->user_type;

        try {
            return $this->$userType([
                'userid' => $this->cluster->username
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }
    }

    /*
     * @param $devices
     * @throws \App\Exceptions\SoapException
     * @internal param $appUserId
     * @return \Exception|SoapFault
     */
    public function updateAxlUser($devices)
    {
        $userType = 'update' . $this->cluster->user_type;

        try {
            return $this->$userType([
                'userid' => $this->cluster->username,
                'associatedDevices' => [
                    'device' => $devices
                ]
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }
    }

}