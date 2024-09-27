<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\ApiDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Services\MonitorApiService;
use Illuminate\Http\Request;

class ApiManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /*  public function index(ApiDataTable $dataTable)
    {
        return $dataTable->render('pages/apps.user-management.users.list');
    } */

    public function index(Request $request)
    {
        //Get the logged in user
        $user = User::find(auth()->id());

        /*
         * service class that interact with the MonitorApi model.
         * refer app/Models/Services/MonitorApiService.php
         */
        $apiService = new MonitorApiService();
        //Get filtered apis using getFilteredElements() function in  MonitorApiService
        $apis = $apiService->getFilteredElements($user, $request)
            ->paginate(10);

        $param = [
            'apis' => $apis,
            'request' => $request,
        ];
        return view('pages.apps.api-management.index', $param);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('pages/apps.user-management.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
