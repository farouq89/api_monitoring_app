<?php

namespace App\Models\Services;
use Carbon\Carbon;

use App\Models\MonitorServer;
use App\Models\MonitorLog;
use App\Models\User;
use App\Helpers\Uuid;
use App\Helpers\Logger;
use App\Services\ServerStatus;

class MonitorServerService
{
    /*
     * Getting filtered servers
     */
    public function getFilteredElements($user, $request)
    {

        $query = MonitorServer::query()->where('user_id', $user->id);
        $query->orderBy('created_at', 'DESC');
        //Filter servers by by search keywords
        if ($request->search) {
            $query = $query->where('server_address', 'like', '%' . $request->search . '%');
        }

        $servers = $query;
        return $servers;
    }

    /*
     * Adding new server
     */
    public function addServer($request, $user)
    {
        /*
         * service class that interact with the MonitorServer.
         * refer app/Services/ServerStatus.php
         */
        $serverStatus = new ServerStatus();
        //create an object for MonitorLog
        $log = new MonitorLog();
        //create an object for MonitorServer.
        $monitorServer = new MonitorServer();
        $monitorServer->uuid = Uuid::getUuid();
        $monitorServer->user_id = $user->id;
        $monitorServer->is_active = 1;
        $monitorServer->server_address = $request->server_address;
        $monitorServer->port = $request->port;
        $monitorServer->interval = $request->interval;
        $monitorServer->server_email = $request->server_email;
        //case were the server is down
        if ($request->skip_test_connection) {
            $monitorServer->test_result = 0;
            $monitorServer->previous_server_status = 'down';
            $monitorServer->avg_response_time = 0;
            $log->is_up = 0;
        } else {
            //case were the server is up
            $monitorServer->test_result = 1;
            $monitorServer->previous_server_status = 'up';
            $log->is_up = 1;
            $startTime = intval(microtime(true) * 1000);
            //Checking server status using the isUp() function in ServerStatus
            if (!$serverStatus->isUp($request->server_address, $request->port)) {
                return false;
            }
            $endTime = intval(microtime(true) * 1000);
            $monitorServer->avg_response_time = ($endTime - $startTime);
        }
        $monitorServer->save();
        $log->uuid  = Uuid::getUuid();
        $log->rel_id  = $monitorServer->uuid;
        $log->response_time = $monitorServer->avg_response_time;
        $log->previous_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->current_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->save();
        return true;
    }

    /*
     * Updating a server
     */
    public function updateServer($request, $uuid)
    {
        /*
         * service class that interact with the MonitorServer.
         * refer app/Services/ServerStatus.php
         */
        $serverStatus = new ServerStatus();
        //create an object for MonitorLog
        $log = new MonitorLog();
        $server = MonitorServer::find($uuid);
        $updateArray = [];
        $updateArray['server_address'] = $request->server_address;
        $updateArray['port'] = $request->port;
        $updateArray['interval'] = $request->interval;
        $updateArray['server_email'] = $request->server_email;
        //case were the server is down
        if ($request->skip_test_connection) {
            $updateArray['test_result'] = 0;
            $updateArray['previous_server_status'] = 'down';
            $updateArray['avg_response_time'] = 0;
            $log->is_up = 0;
        } else {
            //case were the server is up
            $updateArray['test_result'] = 1;
            $updateArray['previous_server_status'] = 'up';
            $log->is_up = 1;
            $startTime = intval(microtime(true) * 1000);
            //Checking server status using the isUp() function in ServerStatus
            if (!$serverStatus->isUp($request->server_address, $request->port)) {
                return false;
            }
            $endTime = intval(microtime(true) * 1000);
            $updateArray['avg_response_time'] = ($endTime - $startTime);
        }

        $server->update($updateArray);
        $log->uuid  = Uuid::getUuid();
        $log->rel_id  = $server->uuid;
        $log->response_time = $server->avg_response_time;
        $log->previous_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->current_time = Carbon::now()->format('Y-m-d H:i:s');
        $log->save();
        return true;
    }

    /*
     * Calculation of average response time of a Server
     */
    public function getAverageResponseTime($relId)
    {
        //Get logs of corresponding server.
        $value =  MonitorLog::where('rel_id', $relId)
            ->where('is_up', 1)
            ->avg('response_time');

        if (empty($value)) {
            return 0;
        }
        return $value;
    }
  
}
