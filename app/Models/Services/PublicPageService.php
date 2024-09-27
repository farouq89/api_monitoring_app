<?php

namespace App\Models\Services;
use Carbon\Carbon;

use App\Models\User;
use App\Helpers\Uuid;
use App\Helpers\Logger;

class PublicPageService
{
    /*
     * Getting filtered public pages of APIs and Webpages
     */
    public function getFilteredElements($user, $request, $query)
    {
        $query->orderBy('created_at', 'DESC');
        //Filter public pages by by search keywords
        if ($request->search) {
            $search = $request->search;
            $query = $query->whereHas('monitors', function ($items) use ($search){
                $items->where('name', 'like', '%' . $search . '%');
            })->orWhere('name', 'like', '%' . $request->search . '%');
        }

        $pages = $query;
        return $pages;
    }
    
    /*
     * Getting filtered public pages of servers
     */
    public function getFilteredServerElements($user, $request, $query)
    {
        $query->orderBy('created_at', 'DESC');
        //Filter public pages by by search keywords
        if ($request->search) {
            $search = $request->search;
            $query = $query->whereHas('monitors', function ($items) use ($search){
                $items->where('server_address', 'like', '%' . $search . '%');
            })->orWhere('name', 'like', '%' . $request->search . '%');
        }

        $pages = $query;
        return $pages;
    }

    /*
     * Adding public page
     */
    public function addPage($request, $publicPage){

        $publicPage->rel_id = $request->rel_id;
        $publicPage->name = $request->name;
        if ($request->status == "enable") {
            $publicPage->status = true;
        } else {
            $publicPage->status = false;
        }
        $publicPage->save();
    }

    /*
     * Updating public page
     */
    public function updatePage($request, $publicPage){

        $updateArray = [];
        $updateArray['name'] = $request->name;
        if ($request->status == "enable") {
            $updateArray['status'] = true;
        } else {
            $updateArray['status'] = false;
        }
        $publicPage->update($updateArray);
    }

    /*
     * Getting details to be shown in public pages
     */
    public function showPage($logs){

        //Get the user
        $user = User::find(auth()->id());

        //Get count of logs
        $totalLogs = $logs->count();
        //Get count of up logs
        $upCount = $logs->where('is_up', true)
            ->count();
        //Get count of down logs
        $downCount = $logs->where('is_up', false)
            ->count();
        //Get avg response time
        $avgResponse = $logs->where('is_up', 1)
            ->avg('response_time');
        $avgResponseTime = round($avgResponse, 2);
        
        //Calculate upTime and downTime percentage
        if ($totalLogs !== 0){
            $upTime = round((100*$upCount/$totalLogs), 2);
            $downTime = round((100*$downCount/$totalLogs), 2);
        }else{
            $upTime = 0;
            $downTime = 0;
        }

        /*
         * Getting Last week detals
         */

        //Get date of 7 days ego from now
        $date = Carbon::now()->subDays(7)->startOfDay()->format('Y-m-d H:i:s');
        //Get last 7 days logs
        $weekLogs = $logs->where('created_at', '>=', $date)->count();
        //Get last 7 days up logs
        $weekUpLogs = $logs->where('created_at', '>=', $date)
            ->where('is_up', true)
            ->count();
        //Calculate last 7 day up percentage
        if ($weekLogs !== 0){
            $weekStatus = (100*$weekUpLogs)/$weekLogs;
        }else{
            $weekStatus = 0;
        }  
        $weekStatus = round($weekStatus, 2);
        //Get each day status of last 7 days
        $reports = [];
        for ($i=0; $i<7; $i++){
            //Get the date in the format of dd-Month (eg: 02-Jun)
            $dateList = Carbon::now()->subDays($i)->startOfDay()->format('Y-m-d');
            //Get the start of the day
            $startDate = Carbon::now()->subDays($i)->startOfDay()->format('Y-m-d H:i:s');
            //Get the end of the day
            $endDate = Carbon::now()->subDays($i)->endOfDay()->format('Y-m-d H:i:s');
            //Get that day logs
            $weekDayLogs = $logs->whereBetween('created_at', [$startDate, $endDate])->count();
            //get that day up logs
            $weekDayUpCount = $logs->whereBetween('created_at', [$startDate, $endDate])
                ->where('is_up', true)
                ->count();

            //Get that day status
            if ($weekDayLogs !== 0){
                $dayStatus = (100*$weekDayUpCount)/$weekDayLogs;
            }else{
                $dayStatus = 0;
            }
            $dayStatus = round($dayStatus, 2);
            $reports[$i]['date'] = $dateList;
            $reports[$i]['status'] = $dayStatus;

        }

        $response = [];
        $response['weekStatus'] = $weekStatus;
        $response['reports'] = $reports;
        $response['avgResponseTime'] = $avgResponseTime;
        $response['upTime'] = $upTime;
        $response['downTime'] = $downTime;
        return $response;

    }
}
