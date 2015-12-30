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

    /**
     * @param $devices
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
        } catch(SoapFault $E) {

            dd($E);
            return $E;
        }
    }

    /**
     * @param $devicePoolName
     * @throws \App\Exceptions\SoapException
     * @return \Exception|\SoapFault
     */
    public function getDPool($devicePoolName)
    {
        try {
            return $this->getDevicePool([
                'name' => $devicePoolName
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }
    }

    /**
     * @param $cssName
     * @throws \App\Exceptions\SoapException
     * @return \Exception|\SoapFault
     */
    public function getCallingSearchSpace($cssName)
    {
        try {
            return $this->getCss([
                'name' => $cssName
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }
    }

    /**
     * @param $partitionName
     * @throws \App\Exceptions\SoapException
     * @return \Exception|\SoapFault
     */
    public function getPartition($partitionName)
    {
        try {
            return $this->getRoutePartition([
                'name' => $partitionName
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }
    }

    /**
     * @param $timeScheduleName
     * @throws \App\Exceptions\SoapException
     * @return \Exception|\SoapFault
     */
    public function getTimeSch($timeScheduleName)
    {
        try {
            return $this->getTimeSchedule([
                'name' => $timeScheduleName
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }
    }

    /**
     * @throws \App\Exceptions\SoapException
     * @return \Exception|\SoapFault
     */
    public function getCssList()
    {
         try {
            return $this->listCss([
                'returnedTags' => [
                    'name' => ''
                ],
                'searchCriteria' => [
                    'name' => '%'
                ]
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }   
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\SoapException
     */
    public function getPtList()
    {
         try {
            return $this->listRoutePartition([
                'returnedTags' => [
                    'name' => '',
                    'index' => ''
                ],
                'searchCriteria' => [
                    'name' => '%'
                ]
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }   
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\SoapException
     */
    public function getTimePeriodList()
    {
         try {
            return $this->listTimePeriod([
                'returnedTags' => [
                    'name' => '',
                ],
                'searchCriteria' => [
                    'name' => '%'
                ]
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e);
        }   
    }

    /**
     * @param $listResponse
     * @param $listObjectType
     * @return array
     */
    public function processListRequest($listResponse, $listObjectType)
    {
        if(isset($listResponse->return->{$listObjectType}))
        {
            if(!is_array($listResponse->return->{$listObjectType}))
            {
                $configList[] = $listResponse->return->{$listObjectType}->name;
            } else
            {
                foreach($listResponse->return->{$listObjectType} as $key => $value)
                {
                    $configList[] = $value->name;
                }
            }
        }
        return $configList;
    }

    public function addCssViaSourceObject($cssObj)
    {
        try {
            return $this->addCss([
                'css' => $cssObj
            ]);
        } catch(SoapFault $e) {
            throw new SoapException($e->getMessage());
        }
    }
}