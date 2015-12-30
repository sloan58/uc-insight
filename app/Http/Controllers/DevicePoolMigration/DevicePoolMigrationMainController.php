<?php

namespace App\Http\Controllers\DevicePoolMigration;

use Redis;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AxlSourceCluster;
use App\Services\AxlDestinationCluster;
use App\Http\Controllers\Controller;

class DevicePoolMigrationMainController extends Controller
{
	public function index()
	{

		$axlSourceCluster = new AxlSourceCluster;
		$axlDestinationCluster = new AxlDestinationCluster;

        $dpName = 'Karma-HQ-DP-DEV';
		/*
		Get device pool to gather data and make sure
		it exists on the source cluster.
		 */
    	$devicePool = $axlSourceCluster->getDPool($dpName);

        /*
         * Create a $cssObj for all CSS related tasks
         */
        $cssObj = new CallingSearchSpaceController();

        /*
         * Get a list of all CSS's in the destination cluster
         */
        $destinationClusterCssList = $cssObj->destinationClusterCssList();

        /*
        Gather all CSS columns from the device pool table
        */
        $devicePoolCssColumnList = $cssObj->getCssColumnList('devicepool');

        $sourceClusterPopulatedCssList = $cssObj->getSourceClusterPopulatedCssList($devicePoolCssColumnList,$dpName);

        /*
        An array of CSS's that don't exist in the destination cluster
         */
        $toCreateCssList = array_diff($sourceClusterPopulatedCssList, $destinationClusterCssList);

        /*
        Track CSS's that already exist in the destination cluster.
        They'll be added to a Redis Set
         */
        $cssObj->alreadyCreatedInDestinationCluster($sourceClusterPopulatedCssList, $destinationClusterCssList, 'css');

        $cssObj->createCss($toCreateCssList);

        /*
         * Create a $ptObj for all Partition related tasks
         */
        $ptObj = new PartitionController();

        /*
         * Get a list of all Partitions in the destination cluster
         */
        $destinationClusterPtList = $ptObj->destinationClusterPtList();

        /*
         * Create a $timePeriodObj for all Time Period related tasks
         */
        $timePeriodObj = new TimePeriodController();

        /*
        * Get a list of all Time Periods in the destination cluster
        */
        $destinationClusterPtList = $timePeriodObj->destinationClusterTimePeriodList();

	}
}
