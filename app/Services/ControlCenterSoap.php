<?php
namespace App\Services;

use SoapFault;
use SoapClient;
use App\Cluster;
use App\Exceptions\SoapException;

class ControlCenterSoap {

    /**
     * @var resource
     */
    protected $client;

    /**
     *
     */
    public function __construct()
    {
        $this->cluster = Cluster::where('active', true)->first();

        $this->client = new SoapClient('https://' . $this->cluster->ip . '/controlcenterservice2/services/ControlCenterServices?wsdl',
            [
                'trace' => true,
                'exceptions' => true,
                'location' => 'https://' . $this->cluster->ip . ':8443/controlcenterservice2/services/ControlCenterServices',
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
     * @return mixed
     * @throws SoapException
     */
    public function getServiceStatus()
    {
        try {

            $status =  $this->client->soapGetServiceStatus([
                'ServiceStatus' => ''
            ]);

        } catch (SoapFault $e) {

            throw new SoapException($e->faultstring);

        }

        return $status->soapGetServiceStatusReturn->ServiceInfoList->item;
    }
}