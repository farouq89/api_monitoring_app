<?php

namespace App\Models\Services;
use Carbon\Carbon;

use App\Models\ServerIncident;
use App\Models\User;
use App\Helpers\Uuid;
use App\Helpers\Logger;
use App\Helpers\Random;

class IncidentService
{
    /*
     * Getting filtered incidents
     */
    public function getFilteredIncidents($user, $request, $query)
    {

        $query->orderBy('updated_at', 'DESC');
        //Filter incidents by server/webpage/Api
        if ($request->rel_id) {
            $query = $query->where('rel_id', $request->rel_id);
        }
        //Filter incidents by status
        if ($request->status == "false") {
            $query = $query->where('status', false);
        } elseif ($request->status == "true") {
            $query = $query->where('status', true);
        }
        $incidents = $query;
        return $incidents;
    }

    /*
     * Add incidet to incidents table
     */
    public function addIncident($request, $incident){

        $incident->uuid = Uuid::getUuid();
        $incident->rel_id = $request->rel_id;
        $incident->incident_id = Random::getIncidentNumber();
        $incident->subject = $request->subject;
        $incident->description = $request->description;
        $incident->save();
    }

    /*
     * updation of incident
     */
    public function updateIncident($request, $incident ){

        $updateArray = [];
        $updateArray['subject'] = $request->subject;
        $updateArray['description'] = $request->description;
        $incident->update($updateArray);
    }

    /*
     * Adding incident follow up details
     */
    public function updateIncidentDetails($request, $incident, $incidentDetails){

        $incidentDetails->uuid = Uuid::getUuid();
        $incidentDetails->incident_id = $incident->uuid;
        $incidentDetails->status = $request->status;
        $incidentDetails->description = $request->updated_description;

        if ($request->status == 'resolved'){
            $incident->status = true;
            $incident->save();
        }
        $incidentDetails->save();
    }
}
