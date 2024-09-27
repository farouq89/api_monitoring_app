<?php

namespace App\Models\Services;
use Carbon\Carbon;

use App\Models\MonitorWebpage;
use App\Models\MonitorWebpageLog;
use App\Models\User;
use App\Helpers\Uuid;
use App\Helpers\Logger;
use App\Services\WebpageStatus;

class MonitorWebpageService
{
    /*
     * Getting filtered webpages
     */
    public function getFilteredElements($user, $request)
    {

        $query = MonitorWebpage::query()->where('user_id', $user->id);
        $query->orderBy('created_at', 'DESC');
        //Filter webpages by by search keywords
        if ($request->search) {
            $query = $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('url', 'like', '%' . $request->search . '%');
        }

        $webpages = $query;
        return $webpages;
    }

    /*
     * Adding new webpage
     */
    public function addWebpage($request, $user){
        /*
         * service class that interact with the MonitorWebpage.
         * refer app/Services/WebpageStatus.php
         */
        $webpageStatus = new WebpageStatus();
        //create an object for MonitorWebpageLog
        $log = new MonitorWebpageLog();
        //create an object for MonitorWebpage.
        $webpage = new MonitorWebpage();
        $webpage->uuid = Uuid::getUuid();
        $webpage->user_id = $user->id;
        $webpage->name = $request->name;
        $webpage->url = $request->url;
        $webpage->response_code = $request->response_code;
        $webpage->interval = $request->interval;
        $webpage->email = $request->email;
        $startTime = intval(microtime(true) * 1000);
        //case were the webpage is down
        if ($request->skip_test_connection) {
            $webpage->previous_webpage_status = 'down';
            $webpage->avg_response_time = 0;
            $log->is_up = 0;
        } else {
            //case were the webpage is up
            $webpage->previous_webpage_status = 'up';
            $log->is_up = 1;
            //Checking webpage status using the isUp() function in WebpageStatus
            if (!$webpageStatus->isUp($request->url, $request->response_code)) {
                return false;
            }

            $endTime = intval(microtime(true) * 1000);
            $webpage->avg_response_time = ($endTime - $startTime);
        }
        $webpage->save();
        $log->uuid  = Uuid::getUuid();
        $log->rel_id  = $webpage->uuid;
        $log->response_time = $webpage->avg_response_time;
        $log->previous_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->current_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->save();
        return true;
    }

    /*
     * Updating a webpage
     */
    public function updateWebpage($request, $uuid){
        /*
         * service class that interact with the MonitorWebpage.
         * refer app/Services/WebpageStatus.php
         */
        $webpageStatus = new WebpageStatus();
        //create an object for MonitorWebpageLog
        $log = new MonitorWebpageLog();
        $webpage = MonitorWebpage::find($uuid);
        $updateArray = [];
        $updateArray['name'] = $request->name;
        $updateArray['url'] = $request->url;
        $updateArray['response_code'] = $request->response_code;
        $updateArray['interval'] = $request->interval;
        $updateArray['email'] = $request->email;
        $startTime = intval(microtime(true) * 1000);
        //case were the webpage is down
        if ($request->skip_test_connection) {
            $updateArray['previous_webpage_status'] = 'down';
            $updateArray['avg_response_time'] = 0;
            $log->is_up = 0;
        } else {
            //case were the webpage is up
            $updateArray['previous_webpage_status'] = 'up';
            $log->is_up = 1;

            //Checking webpage status using the isUp() function in WebpageStatus
            if (!$webpageStatus->isUp($request->url, $request->response_code)) {
                return false;
            }

            $endTime = intval(microtime(true) * 1000);
            $updateArray['avg_response_time'] = ($endTime - $startTime);
        }
        $webpage->update($updateArray);
        $log->uuid  = Uuid::getUuid();
        $log->rel_id  = $webpage->uuid;
        $log->response_time = $webpage->avg_response_time;
        $log->previous_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->current_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->save();
        return true;
    }

    /*
     * Calculation of average response time of a webpage
     */
    public function getAverageResponseTime($relId)
    {
        //Get logs of corresponding webpage.
        $value =  MonitorWebpageLog::where('rel_id', $relId)
            ->where('is_up', 1)
            ->avg('response_time');

        if (empty($value)) {
            return 0;
        }
        return $value;
    }
}
