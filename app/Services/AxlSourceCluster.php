<?php
namespace App\Services;

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

        parent::__construct(storage_path() . '/app/axl/10.5/AXLAPI.wsdl',
            [
                'trace' => true,
                'exceptions' => true,
                'location' => 'https://192.168.1.120:8443/axl/',
                'login' => 'UC-Insight-AXL',
                'password' => 'A0usc1@123',
                'stream_context' => stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))
            ]
        );
    }
}