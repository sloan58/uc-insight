<?php

use App\Services\AxlSoap;
use App\Exceptions\SoapException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @param $userObj
 * @param $deviceList
 * @return array
 */
function createDeviceArray($userObj,$deviceList)
{
    //$device array to be returned
    $devices = [];

    /*
     * Check if UserObj deviceList is not set.
     * (user is not currently associated to any devices)
     */
    if (!isset($userObj->return->appUser->associatedDevices->device))
    {

        /*
         * Add all device from the device list
         * to the devices array
         */
        foreach($deviceList as $d)
        {
            $devices[] = $d;
        }
    }
    /*
     * Check if userObj devices is an array
     * (user is associated to multiple devices)
     */
    elseif (is_array($userObj->return->appUser->associatedDevices->device)) {

        $devices = array_merge($userObj->return->appUser->associatedDevices->device,$deviceList);

        //If the userObj element DOES exist but IS NOT and array (it's a single device)
        /*
         * The userObj devices object exists
         * but is not an array.
         * (it's a single device)
         */
    } else {

        array_push($deviceList,$userObj->return->appUser->associatedDevices->device);
        $devices = $deviceList;
    }

    /*
     * It's possible some devices were
     * already associated to the appUser.
     * Here we make sure the device names
     * are unique.
     */
    $devices = array_unique($devices);

    /*
     * We only want to return the
     * array values.
     * (Can't remember why I had to do this...)
     */
    $devices = array_values($devices);

    return $devices;
}

/**
 * @param $phones
 * @return array
 */
function createRisPhoneArray($phones)
{
    $deviceArray = [];

    foreach ($phones as $i)
    {
        $deviceArray[]['Item'] = $i;
    }
    return $deviceArray;
}

/**
 * @param $risResults
 * @param $phoneArray
 * @return mixed
 */
function processRisResults($risResults,$phoneArray)
{
    $i = 0;

    foreach($phoneArray as $k => $v)
    {
        $deviceAndIp[$i]['DeviceName'] = $v['Item'];

        if(isset($risResults->CmNodes))
        {
            foreach ($risResults->CmNodes as $cmNode)
            {
                if (!isset($cmNode->CmDevices[0])) continue;

                list($deviceAndIp[$i]['IpAddress'],$deviceAndIp[$i]['IsRegistered'],$deviceAndIp[$i]['Description'],$deviceAndIp[$i]['Product']) = searchForIp($cmNode->CmDevices,$deviceAndIp[$i]['DeviceName']);

                if (filter_var($deviceAndIp[$i]['IpAddress'], FILTER_VALIDATE_IP)) break;
            }
        }
        if (!isset($deviceAndIp[$i]['IpAddress']))
        {
            $deviceAndIp[$i]['IpAddress'] = "Unregistered/Unknown";
            $deviceAndIp[$i]['IsRegistered'] = false;
            $deviceAndIp[$i]['Description'] = "Unavailable";
            $deviceAndIp[$i]['Model'] = "Unavailable";
            $deviceAndIp[$i]['Product'] = "Unavailable";
        }
        $i++;
    }
    return $deviceAndIp;
}

/**
 * @param $array
 * @param $value
 * @return bool
 */
function searchForIp($array,$value)
{

    foreach($array as $device)
    {
        if($device->Name == $value && $device->Status == "Registered")
        {
            return [$device->IpAddress,true,$device->Description,$device->Product];
        }
    }
    return false;
}

/**
 * @param $model
 * @param $logger
 * @return array
 */
function setKeys($model,$tleType)
{
    switch(strtolower($tleType)) {

        case 'itl':

            switch ($model) {

                case "Cisco 7905":
                case "Cisco 7911":
                    return [

                        'Init:Applications',
                        'Key:Applications',
                        'Key:KeyPad3',
                        'Key:KeyPad4',
                        'Key:KeyPad5',
                        'Key:KeyPad2',
                        'Key:Soft4',
                        'Key:Soft2',
                        'Key:Sleep',
                        'Key:KeyPadStar',
                        'Key:KeyPadStar',
                        'Key:KeyPadPound',
                        'Key:Sleep',
                        'Key:Soft4',
                        'Key:Soft2',
                        'Init:Applications',

                    ];
                    break;

                case "Cisco 7941":
                case "Cisco 7945":
                case "Cisco 7961":
                case "Cisco 7962":
                case "Cisco 7965":
                    return [

                        'Init:Settings',
                        'Key:Settings',
                        'Key:KeyPad4',
                        'Key:KeyPad5',
                        'Key:KeyPad2',
                        'Key:Soft4',
                        'Key:Sleep',
                        'Key:KeyPadStar',
                        'Key:KeyPadStar',
                        'Key:KeyPadPound',
                        'Key:Sleep',
                        'Key:Soft4',
                        'Init:Services'
                    ];
                    break;
                case "Cisco 7971":
                case "Cisco 7975":
                    return [

                        'Init:Settings',
                        'Key:Settings',
                        'Key:KeyPad4',
                        'Key:KeyPad5',
                        'Key:KeyPad2',
                        'Key:Soft5',
                        'Key:Sleep',
                        'Key:KeyPadStar',
                        'Key:KeyPadStar',
                        'Key:KeyPadPound',
                        'Key:Sleep',
                        'Key:Soft5',
                        'Init:Services'
                    ];
                    break;

                case "Cisco 8961":
                case "Cisco 9951":
                case "Cisco 7937":
                case "Cisco 9971":
                    return [

                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:Applications',
                        'Key:KeyPad4',
                        'Key:KeyPad4',
                        'Key:KeyPad4',
                        'Key:Soft3',
                    ];
                    break;

                default:
                    Log::error("ITL-> No model found for " . $model);
                    return false;
            }
            break;

        case 'ctl':

            switch ($model) {
                case "Cisco 7905": // This sequence for a 7905 actually deletes the ITL.  It's here for testing and should be updated....
                case "Cisco 7911": // This sequence for a 7911 actually deletes the ITL.  It's here for testing and should be updated....
                    return [
                        'Init:Applications',
                        'Key:Applications',
                        'Key:KeyPad3',
                        'Key:KeyPad4',
                        'Key:KeyPad5',
                        'Key:KeyPad2',
                        'Key:Soft4',
                        'Key:Soft2',
                        'Key:Sleep',
                        'Key:KeyPadStar',
                        'Key:KeyPadStar',
                        'Key:KeyPadPound',
                        'Key:Sleep',
                        'Key:Soft4',
                        'Key:Soft2',
                        'Init:Applications',
                    ];
                    break;
                case "Cisco 7941":
                case "Cisco 7945":
                case "Cisco 7961":
                case "Cisco 7965":
                    return [
                        'Init:Settings',
                        'Key:Settings',
                        'Key:KeyPad4',
                        'Key:KeyPad5',
                        'Key:KeyPad1',
                        'Key:Soft4',
                        'Key:Sleep',
                        'Key:KeyPadStar',
                        'Key:KeyPadStar',
                        'Key:KeyPadPound',
                        'Key:Sleep',
                        'Key:Soft4',
                        'Init:Services'
                    ];
                    break;
                case "Cisco 7971":
                case "Cisco 7975":
                    return [
                        'Init:Settings',
                        'Key:Settings',
                        'Key:KeyPad4',
                        'Key:KeyPad5',
                        'Key:KeyPad1',
                        'Key:Soft5',
                        'Key:Sleep',
                        'Key:KeyPadStar',
                        'Key:KeyPadStar',
                        'Key:KeyPadPound',
                        'Key:Sleep',
                        'Key:Soft5',
                        'Init:Services'
                    ];
                    break;
                case "Cisco 8961":
                case "Cisco 9951":
                case "Cisco 7937":
                case "Cisco 9971":
                    return [
                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:NavBack',
                        'Key:Applications',
                        'Key:KeyPad4',
                        'Key:KeyPad4',
                        'Key:KeyPad4',
                        'Key:Soft3',
                    ];
                    break;
                default:
                    Log::error("CTL-> No model found for " . $model);
                    return false;
            }
            break;

    }
}

/*
|--------------------------------------------------------------------------
| Detect Active Route
|--------------------------------------------------------------------------
|
| Compare given route with current route and return output if they match.
| Very useful for navigation, marking if the link is active.
|
*/
function isActiveRoute($route, $output = "active")
{
    if (Route::currentRouteName() == $route) return $output;
}

/*
|--------------------------------------------------------------------------
| Detect Active Routes
|--------------------------------------------------------------------------
|
| Compare given routes with current route and return output if they match.
| Very useful for navigation, marking if the link is active.
|
*/
function areActiveRoutes(Array $routes, $output = "active")
{
    foreach ($routes as $route)
    {
        if (Route::currentRouteName() == $route) return $output;
    }

}

/*
|--------------------------------------------------------------------------
| Check Passwords
|--------------------------------------------------------------------------
|
| Compare passwords to see if they need to be updated in the database.
|
*/
function checkPassword($currentPassword,$toCheckPassword)
{
    if($currentPassword != $toCheckPassword && $toCheckPassword != '')
    {

        return $toCheckPassword;

    } else {

        return $currentPassword;
    }
}

function executeQuery($sql)
{
    $axl = new AxlSoap();

    $result = $axl->executeSQLQuery($sql);
    return checkQueryResult($result);

}

function checkQueryResult($result)
{
    switch($result) {
        case isset($result->faultcode) && $result->faultcode == 'HTTP':
            throw new SoapException('Server Error.  Please check your WSDL version is correct for the active cluster.');
            break;

        case isset($result->faultstring):
            throw new SoapException($result->faultstring);
            break;

        case !isset($result->return->row):
            throw new SoapException('No Results Found');
            break;

        case is_array($result->return->row):
            return $result->return->row;
            break;

        default:
            $return = [];
            $return[0] = $result->return->row;
            return $return;
    }
}

function getHeaders($data)
{
    if(isset($data[0]))
    {
        return array_keys((array)$data[0]);

    } else {

        return false;
    }

}

function messagePhone($guzzle,$deviceData,$count)
{
    $count++;

    if ($count > 2) {

        $deviceData['Firmware'] = 'Web Unresponsive';
        return $deviceData;

    }

    try {

        $stream = \GuzzleHttp\Stream\Stream::factory($guzzle->get('', ['timeout' => 2]));

    } catch (\GuzzleHttp\Exception\RequestException $e) {

        if ($e->hasResponse()) {

//            $logger->debug('(Request)', [$e->getRequest()]);
//            $logger->debug('(Response)', [$e->getResponse()]);
            dd($e->getResponse());
            print "$deviceData[DeviceName],$deviceData[IpAddress],Some weird error.  Check the logs.\n";
            return false;

        } else {

//            $logger->debug('Web Timeout (Request)', [$e->getRequest()]);
            return messagePhone($guzzle, $deviceData, $count);
        }

    }

    $page = $stream->getContents();
    $crawler = new Crawler($page);

    if ($crawler->filter('DIV TABLE TR')->eq(5)->filter('td')->eq(2)->count()) {

        $crawler = $crawler->filter('DIV TABLE TR')->eq(5)->filter('td')->eq(2)->text();

        $deviceData['Firmware'] = $crawler;

    } else {

        $deviceData['Firmware'] = 'Web Parsing Error';

    }

    return $deviceData;
}