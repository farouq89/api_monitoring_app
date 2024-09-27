<?php

namespace App\Models\Services;
use Carbon\Carbon;

use App\Models\MonitorApi;
use App\Models\MonitorApiLog;
use App\Models\User;
use App\Helpers\Uuid;
use App\Helpers\Logger;
use App\Services\ApiStatus;

class MonitorApiService
{
    /*
     * Getting filtered APIs
     */
    public function getFilteredElements($user, $request)
    {

        $query = MonitorApi::query()->where('user_id', $user->id);
        $query->orderBy('created_at', 'DESC');
        //Filter APIs by by search keywords
        if ($request->search) {
            $query = $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('url', 'like', '%' . $request->search . '%');
        }

        $apis = $query;
        return $apis;
    }

    /*
     * Adding new Api
     */
    public function addApi($request, $user){
        /*
         * service class that interact with the MonitorApi.
         * refer app/Services/ApiStatus.php
         */
        $apiStatus = new ApiStatus();
        //create an object for MonitorApiLog
        $log = new MonitorApiLog();
        //create an object for MonitorApi.
        $api = new MonitorApi();
        $api->uuid = Uuid::getUuid();
        $api->user_id = $user->id;
        $api->name = $request->name;
        $api->url = $request->url;
        $api->methode = $request->methode;
        $api->json = $request->json;
        $api->expected_response_code = $request->response_code;
        $api->interval = $request->interval;
        $api->email = $request->email;
        //case were the api is down
        if ($request->skip_test_connection) {
            $api->previous_api_status = 'down';
            $api->avg_response_time = 0;
            $log->is_up = 0;
        } else {
            //case were the api is up
            $api->previous_api_status = 'up';
            $startTime = intval(microtime(true) * 1000);
            $log->is_up = 1;
            //Checking Api status using the isUp() function in ApiStatus
            if (!$apiStatus->isUp($request->url, $request->response_code, $request->json, $request->methode)) {
                return false;
            }

            $endTime = intval(microtime(true) * 1000);
            $api->avg_response_time = ($endTime - $startTime);
        }
        $api->save();
        $log->uuid  = Uuid::getUuid();
        $log->rel_id  = $api->uuid;
        $log->response_time = $api->avg_response_time;
        $log->previous_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->current_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->save();
        return true;
    }

    /*
     * Updating an Api
     */
    public function updateApi($request, $uuid){
        /*
         * service class that interact with the MonitorApi.
         * refer app/Services/ApiStatus.php
         */
        $apiStatus = new ApiStatus();
        //create an object for MonitorApiLog
        $log = new MonitorApiLog();
        //Get the API
        $api = MonitorApi::find($uuid);
        $updateArray = [];
        $updateArray['name'] = $request->name;
        $updateArray['url'] = $request->url;
        $updateArray['methode'] = $request->methode;
        $updateArray['json'] = $request->json;
        $updateArray['expected_response_code'] = $request->response_code;
        $updateArray['interval'] = $request->interval;
        $updateArray['email'] = $request->email;
        if ($request->skip_test_connection) {
            $updateArray['previous_api_status'] = 'down';
            $updateArray['avg_response_time'] = 0;
            $log->is_up = 0;
        } else {
            $updateArray['previous_api_status'] = 'up';
            $log->is_up = 1;
            $startTime = intval(microtime(true) * 1000);
             //Checking Api status using the isUp() function in ApiStatus
            if (!$apiStatus->isUp($request->url, $request->response_code, $request->json, $request->methode)) {
                return false;
            }

            $endTime = intval(microtime(true) * 1000);
            $updateArray['avg_response_time'] = ($endTime - $startTime);
        }
        $api->update($updateArray);
        $log->uuid  = Uuid::getUuid();
        $log->rel_id  = $api->uuid;
        $log->response_time = $api->avg_response_time;
        $log->previous_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->current_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->save();
        return true;
    }

    /*
     * Calculation of average response time of an API
     */
    public function getAverageResponseTime($relId)
    {
        //Get logs of corresponding Api.
        $value =  MonitorApiLog::where('rel_id', $relId)
            ->where('is_up', 1)
            ->avg('response_time');

        if (empty($value)) {
            return 0;
        }
        $value = round($value, 2);
        return $value;
    }
}
