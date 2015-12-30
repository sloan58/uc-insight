<?php

namespace App\Http\Controllers\DevicePoolMigration;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AxlSourceCluster;
use App\Http\Controllers\Controller;
use App\Services\AxlDestinationCluster;


class PartitionController extends Controller
{
    function __construct()
    {
        /*
         * Create a Source and Destination Cluster Object
         */
        $this->axlSourceCluster = new AxlSourceCluster;
        $this->axlDestinationCluster = new AxlDestinationCluster;
    }

    public function destinationClusterPtList()
    {
        /*
         * Query the Destination Cluster for a list of Partition names
         */
        $result = $this->axlDestinationCluster->getPtList();

        /*
         * Process and return the results, which is an array of Partition names (or null)
         */
        return $this->axlDestinationCluster->processListRequest($result,'routePartition');

    }
}
