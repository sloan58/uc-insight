<?php

namespace App\Http\Controllers;

use Redis;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AxlSourceCluster;
use App\Services\AxlDestinationCluster;
use App\Http\Controllers\Controller;

class DevicePoolMigrationController extends Controller
{
	public function getDevicePool()
	{

		$axlSourceCluster = new AxlSourceCluster;
		$axlDestinationCluster = new AxlDestinationCluster;
	
		$dpName = 'AK-ANCH-4TH_DP';
		/*
		Get device pool to gather data and make sure
		it exists on the source cluster.
		 */
    	$devicePool = $axlSourceCluster->getDPool($dpName);

    	/*
    	Get a list of configuration items we'll be checking on the
    	destination cluster.  Keeping the local copy via 'List' method
    	will reduce the number of API calls needed.
    	 */
    	$result = $axlDestinationCluster->getCssList();
    	foreach($result->return->css as $key => $value)
    	{
    		$destinationClusterCssList[] = $value->name;
    	}

    	$result = $axlDestinationCluster->getPtList();
    	foreach($result->return->routePartition as $key => $value)
    	{
    		$destinationClusterPtList[] = $value->name;
    	}

    	$result = $axlDestinationCluster->getTimePeriodList();
    	foreach($result->return->timePeriod as $key => $value)
    	{
    		$destinationClusterTimePeriodList[] = $value->name;
    	}

		/*
		Gather all CSS columns from the device pool table
		 */
		$result = $axlSourceCluster->executeQuery("SELECT * FROM syscolumns where tabid = (select tabid from systables where tabname = 'devicepool') AND colname like 'fkcallingsearchspace%'");
		$devicePoolCssColumnList = $result->return->row;
		
		/*
		Iterate over each CSS column to see if it's
		populated for the device pool settings.  
		 */
		$devicePoolPopulatedCssList = [];
		foreach($devicePoolCssColumnList as $css)
		{

			/*
			Query CSS column in source cluster to see if it's populated
			 */
			$result = $axlSourceCluster->executeQuery('SELECT c.name FROM callingsearchspace c INNER JOIN devicepool dp on dp.' . $css->colname . ' = c.pkid WHERE dp.name = "' . $dpName . '"');

			if(isset($result->return->row))
			{
				/*
				If it's populated, add it to the list.
				 */
				$devicePoolPopulatedCssList[] = $result->return->row->name;
			}
		}

		/*
		Create an array of CSS's that don't exist in the destination cluster
		 */
		$toCreateCssList = array_diff($devicePoolPopulatedCssList, $destinationClusterCssList);

		/*
		Track CSS's that already exist in the destination cluster.
		 */
		$alreadyCreatedCssList = array_intersect($devicePoolPopulatedCssList, $destinationClusterCssList);

		/*
		Add each CSS that does exist to the 'completed' set
		 */
		foreach($alreadyCreatedCssList as $css)
		{
			// Add CSS name to 'cssAlreadyConfigured' Redis set
			Redis::sadd('cssAlreadyConfigured', $css);
		}

		/*
		Loops through our 'toCreate' list of CSS's
		 */
		foreach($toCreateCssList as $css)
		{
			/*
			Get CSS details from the Source Cluster
			 */
			$result = $axlSourceCluster->getCallingSearchSpace($css);
			// $result = $axlSourceCluster->getCallingSearchSpace('Marty-TEST');

			foreach($result->return->css->members as $partition)
			{
				$partitionsArray[] = $partition->routePartitionName->_;
			}

			if(!isset($partitionsArray))
			{
				//No PT's in the CSS.  We can just add it here.
				continue;
			}

			/*
			The CSS has partitions, let's add them 
			as needed.  First, put all PT's that don't exist 
			in the destination cluster into $toCreatePartitionList
			 */
			$toCreatePartitionList = array_diff($partitionsArray, $destinationClusterPtList);

			/*
			Track Partitions that already exist in the destination cluster.
		 	*/
			$alreadyCreatedPartitionList = array_intersect($partitionsArray, $destinationClusterPtList);
			foreach($alreadyCreatedPartitionList as $partition)
			{
				// Add Parttion name to 'ptAlreadyConfigured' Redis set
				Redis::sadd('ptAlreadyConfigured', $partition);
			}

			/*
			Loops through our 'toCreate' list of CSS's
		 	*/
		 	foreach($toCreatePartitionList as $partition)
		 	{
		 		/*
				Get Partition details from the Source Cluster
			 	*/
				$result = $axlSourceCluster->getPartition($partition);

				/*
				Get the Time Schedule attached to the partition
				 */
				$partitionTimeSchedule = $result->return->routePartition->timeScheduleIdName->_;

				/*
				If the time schedule object was empty,
				create the partition.  There are no further dependencies.
				 */
				if($partitionTimeSchedule == '')
				{
					// No time schedule attached.  Go ahead and create the PT.
					continue;
				}

				$result = $axlSourceCluster->getTimeSch($partitionTimeSchedule);
				$timePeriods = $result->return->timeSchedule->members;

				/*
				If the time period object was empty,
				create the time schedule.  There are no further dependencies.
				 */
				if(!isset($timePeriods->member))
				{
					// No time periods attached.  Go ahead and create the time schedule.
					continue;
				}

				foreach($timePeriods->member as $timePeriod)
				{
					$timePeriodsArray[] = $timePeriod->timePeriodName->_;
				}

				$toCreateTimePeriodList = array_diff($timePeriodsArray, $destinationClusterTimePeriodList);

				$alreadyCreatedTimePeriodList = array_intersect($timePeriodsArray, $destinationClusterTimePeriodList);

				foreach($alreadyCreatedTimePeriodList as $timePeriod)
				{
					// Add Time Period name to 'timPeriodAlreadyConfigured' Redis set
					Redis::sadd('timPeriodAlreadyConfigured', $timePeriod);
				}
		 	}
		}
		dd([$toCreatePartitionList,$toCreateCssList]);
	}
}
