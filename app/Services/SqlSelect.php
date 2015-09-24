<?php
namespace App\Services;

/**
 * Class SqlSelect
 * @package App\Services
 */
class SqlSelect
{

    public function executeQuery($sql)
    {

        $axl = new AxlSoap();

        /*
         * Send Query to CUCM and
         * return the results.
         */
        $result = $axl->executeSQLQuery($sql);

        if (isset($result->faultstring)) return $result;

        if (!isset($result->return->row)) return '';

        if (is_array($result->return->row)) {

            return $result->return->row;

        } else {

            $return = [];
            $return[0] = $result->return->row;
            return $return;

        }
    }

    public function getHeaders($data)
    {

        return array_keys((array)$data[0]);

    }
}