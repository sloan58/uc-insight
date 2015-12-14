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
	
		/*
		Get device pool to gather data and make sure
		it exists on the source cluster.
		 */
    	$devicePool = $axlSourceCluster->getDPool('Karma-HQ-DP');

		/*
		Gather all CSS columns from the device pool table
		 */
		$result = $axlSourceCluster->executeQuery("SELECT * FROM syscolumns where tabid = (select tabid from systables where tabname = 'devicepool') AND colname like 'fkcallingsearchspace%'");
		$dpCssList = $result->return->row;
		
		/*
		Iterate over each CSS column to see if it's
		populated for the device pool settings.  
		 */
		$iterCssList = [];
		foreach($dpCssList as $css)
		{

			$result = $axlSourceCluster->executeQuery('SELECT c.name FROM callingsearchspace c INNER JOIN devicepool dp on dp.' . $css->colname . ' = c.pkid WHERE dp.name = "Karma-HQ-DP"');

			if(isset($result->return->row))
			{
				$iterCssList[] = $result->return->row->name;
			}
		}

		/*
		Iterate over each CSS that was set in the source cluster.
		If it already exists in the destination, mark it complete.
		If it does not exist in the destination cluster, create it!
		 */
		foreach($iterCssList as $css)
		{
			// Check the destination cluster
			// $result = $axlDestinationCluster->executeQuery("SELECT count(*) FROM callingsearchspace WHERE name = '{$css}'");
			$result = $axlDestinationCluster->executeQuery("SELECT count(*) FROM callingsearchspace WHERE name = 'Ham'");

			// CSS exists in destination.
			if($result->return->row->count == "1")
			{
				// Add CSS name to 'cssCompleted' Redis set
				Redis::sadd('cssCompleted', $css);
				continue;
			}

			/*
			Get CSS from Source Cluster
			 */
			$result = $axlSourceCluster->getCallingSearchSpace($css);

			/*
			Extract Partitions from CSS
			 */
			$partitions = explode(':', $result->return->css->clause);

			/*
			Iterate over each Partition to see if it
			exists on the destination cluster
			 */
			foreach($partitions as $partition)
			{
				// Check the destination cluster
				$result = $axlDestinationCluster->executeQuery("SELECT count(*) FROM routepartition WHERE name = '$partition'");
				print_r($result->return->row->count);
			}
		}

	}
}
