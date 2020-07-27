<?php
// *******************************************************************************************************
// *** listRequests
// *******************************************************************************************************
// ***
// *** It gets a array of requests based on given criterias
// *** it is possible to limit it in order to help display layout
// ***
public static function listRequests($crit = null, $limit = null)
{
    // get instance from DB
    $db = Db::getInstance();

    // request array creation
    $requestList = array();

    $query = 'SELECT * FROM request where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
    $query .= ' ORDER BY request_id';
    if (!is_null($limit))
    {
        $query .= ' LIMIT ' . $limit;
    }
    //echo $query;
    try
    {
        $result = $db->query($query);
    }
    catch(Exception $e)
    {
        return null;
    }

    if ($result->num_rows == 0)
    {
        return array(
            0 => NULL
        );
    }

    // return array with values
    while ($request = $result->fetch_assoc())
    {

        $requestData = new Request();
        $requestData->setRequestId($request['request_id']);
        $requestData->setRequestType($request['req_type']);
        $requestData->setRequestDate($request['req_date']);
        $requestData->setUserIdReq($request['user_id_req']);
        $requestData->setUserIdDonor($request['user_id_donor']);
        $requestData->setStatusRequest($request['status']);

        // retrieve requestItem
        $requestItemList = MdlRequests::listRequestItems(' request_id = ' . $request['request_id']);
        if ($requestItemList == NULL)
        {
            $requestItemList = array();
        }
        $requestData->setRequestItems($requestItemList);

        //The array_push() function inserts one or more elements to the end of an array.
        array_push($requestList, $requestData);

    }

    return $requestList;
}
