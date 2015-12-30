<?php

namespace App\Http\Controllers\DevicePoolMigration;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\AxlSourceCluster;
use App\Http\Controllers\Controller;
use App\Services\AxlDestinationCluster;

/**
 * Class CallingSearchSpaceController
 * @package App\Http\Controllers\DevicePoolMigration
 */
class CallingSearchSpaceController extends Controller
{

    /**
     *
     */
    function __construct()
    {
        /*
         * Create a Source and Destination Cluster Object
         */
        $this->axlSourceCluster = new AxlSourceCluster;
        $this->axlDestinationCluster = new AxlDestinationCluster;
        $this->ptObj = new PartitionController();
    }

    /**
     * @return array
     */
    public function destinationClusterCssList()
    {
        /*
         * Query the Destination Cluster for a list of CSS names
         */
        $result = $this->axlDestinationCluster->getCssList();

        /*
         * Process and return the results, which is an array of CSS names (or null)
         */
        return $this->axlDestinationCluster->processListRequest($result,'css');

    }

    /**
     * @param $table
     * @return mixed
     */
    public function getCssColumnList($table)
    {
        /*
         * Get all CSS columns for the supplied table
         */
        $result = $this->axlSourceCluster->executeQuery("SELECT * FROM syscolumns where tabid = (select tabid from systables where tabname = '$table') AND colname like 'fkcallingsearchspace%'");
        return $result->return->row;
    }

    /**
     * @param $cssColumnList
     * @param $dpName
     * @return array
     */
    public function getSourceClusterPopulatedCssList($cssColumnList,$dpName)
    {
        /*
		Iterate over each CSS column to see if it's
		populated for the device pool settings.
		 */
        $devicePoolPopulatedCssList = [];
        foreach($cssColumnList as $css)
        {

            /*
            Query CSS column in source cluster to see if it's populated
             */
            $result = $this->axlSourceCluster->executeQuery('SELECT c.name FROM callingsearchspace c INNER JOIN devicepool dp on dp.' . $css->colname . ' = c.pkid WHERE dp.name = "' . $dpName . '"');

            if(isset($result->return->row))
            {
                /*
                If it's populated, add it to the list.
                 */
                $devicePoolPopulatedCssList[] = $result->return->row->name;
            }
        }

        /*
         * return a list of unique CSS names, do not preserve array keys.
         */
        return array_values(array_unique($devicePoolPopulatedCssList));
    }


    /**
     * @param $source
     * @param $destination
     * @param $setType
     * @return array
     */
    public function alreadyCreatedInDestinationCluster($source, $destination, $setType)
    {
        $alreadyCreatedList = array_intersect($source, $destination);

        /*
		Add each item that already exist to the 'already configured' set
		 */
        foreach($alreadyCreatedList as $item)
        {
            Redis::sadd($setType . 'AlreadyConfigured', $item);
        }

        return $alreadyCreatedList;
    }

    /**
     * @param $toCreateCssList
     */
    public function createCss($toCreateCssList)
    {

        /*
         * Get a list of all Partitions already configured in the destination cluster
         */
        $destinationClusterPtList = $this->ptObj->destinationClusterPtList();

        /*
		Loops through our 'toCreate' list of CSS's
		 */
        foreach($toCreateCssList as $css)
        {
            /*
            Get CSS details from the Source Cluster
             */
            $result = $this->axlSourceCluster->getCallingSearchSpace($css);

            foreach($result->return->css->members as $partition)
            {
                $partitionsArray[] = $partition->routePartitionName->_;
            }

            if(!isset($partitionsArray))
            {

//                TODO: Create DB Tables for RollBack
                
                //No PT's in the CSS.  We can just add it here.
                $result = $this->axlDestinationCluster->addCssViaSourceObject($result->return->css);
                $newCss = $result->return;
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
