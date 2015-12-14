<?php
namespace App\Services;

use SoapClient;
use App\Services\AxlSoap;
use App\Exceptions\SoapException;

/**
 * Class AxlSoap
 * @package App\Services
 */
class AxlSourceCluster extends AxlSoap {

    /**
     * @var resource
     */
    private $cluster;

    /**
     *
     */
    public function __construct()
    {

        SoapClient::__construct(storage_path() . '/app/axl/9.1/AXLAPI.wsdl',
            [
                'trace' => true,
                'exceptions' => true,
                'location' => 'https://10.134.175.10:8443/axl/',
                'login' => 'UC-Insight-AXL',
                'password' => 'A0usc1@123',
                'stream_context' => stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))
            ]
        );
    }
}