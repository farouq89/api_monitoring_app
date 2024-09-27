<?php

namespace App\Livewire\Api;

use App\Models\MonitorApi;
use Livewire\Component;
//use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
//use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddApiModal extends Component
{
    // use WithFileUploads;

    public $api_id;
    public $name;
    public $url;
    public $method;


    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'url' => 'required|url',
        'method' => 'required|string',
    ];

    protected $listeners = [
        'delete_api' => 'deleteApi',
        'update_api' => 'updateApi',
        'new_api' => 'hydrate',
    ];

    public function render()
    {
        /*  $roles = Role::all();

        $roles_description = [
            'administrator' => 'Best for business owners and company administrators',
            'developer' => 'Best for developers or people primarily using the API',
            'analyst' => 'Best for people who need full access to analytics data, but don\'t need to update business settings',
            'support' => 'Best for employees who regularly refund payments and respond to disputes',
            'trial' => 'Best for people who need to preview content data, but don\'t need to make any updates',
        ];

        foreach ($roles as $i => $role) {
            $roles[$i]->description = $roles_description[$role->name] ?? '';
        }
 */
        return view('livewire.api.add-api-modal');
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new user
            $data = [
                'name' => $this->name,
            ];

            if ($this->avatar) {
                $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            } else {
                $data['profile_photo_path'] = null;
            }

            if (!$this->edit_mode) {
                $data['password'] = Hash::make($this->email);
            }

            // Update or Create a new user record in the database
            $data['email'] = $this->email;
            $user = User::find($this->user_id) ?? User::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $user->$k = $v;
                }
                $user->save();
            }

            if ($this->edit_mode) {
                // Assign selected role for user
                $user->syncRoles($this->role);

                // Emit a success event with a message
                $this->dispatch('success', __('Api updated'));
            } else {
                // Assign selected role for user
                $user->assignRole($this->role);

                // Send a password reset link to the user's email
                Password::sendResetLink($user->only('email'));

                // Emit a success event with a message
                $this->dispatch('success', __('New Api created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteApi($id)
    {
        // Prevent deletion of current user
        if ($id == Auth::id()) {
            $this->dispatch('error', 'Api cannot be deleted');
            return;
        }

        // Delete the user record with the specified ID
        User::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Api successfully deleted');
    }

    public function updateApi($id)
    {
        $this->edit_mode = true;

        $user = User::find($id);

        $this->user_id = $user->id;
        $this->saved_avatar = $user->profile_photo_url;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles?->first()->name ?? '';
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
    }
}
