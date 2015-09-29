<?php
namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use SoapClient;
use SoapFault;

class ControlCenterSoap {

    /**
     * @var resource
     */
    protected $client;

    public function __construct($node,$user,$pass)
    {
        $this->client = new SoapClient('https://' . $node . '/controlcenterservice2/services/ControlCenterServices?wsdl',
            [
                'trace'=> true,
                'exceptions'=> true,
                'location'=> 'https://' . $node . ':8443/controlcenterservice2/services/ControlCenterServices',
                'login'=> $user,
                'password'=> $pass,
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
     */
    public function getServiceStatus()
    {
        try {

            $status =  $this->client->soapGetServiceStatus([
                'ServiceStatus' => ''
            ]);

        } catch (SoapFault $e) {

            var_dump($e); die;

        }

        return $status->soapGetServiceStatusReturn->ServiceInfoList->item;
    }
}