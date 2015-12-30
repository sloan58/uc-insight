<?php

namespace App\Http\Controllers\DevicePoolMigration;

use App\Services\AxlDestinationCluster;
use App\Services\AxlSourceCluster;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TimePeriodController extends Controller
{
    function __construct()
    {
        /*
         * Create a Source and Destination Cluster Object
         */
        $this->axlSourceCluster = new AxlSourceCluster;
        $this->axlDestinationCluster = new AxlDestinationCluster;
    }

    public function destinationClusterTimePeriodList()
    {
        /*
         * Query the Destination Cluster for a list of Time Period names
         */
        $result = $this->axlDestinationCluster->getTimePeriodList();

        /*
         * Process and return the results, which is an array of Time Period names (or null)
         */
        return $this->axlDestinationCluster->processListRequest($result,'timePeriod');

    }
}
