<?php

namespace App\Livewire;

use App\Models\ApiEndpoint;
use Livewire\Component;

class ApiEndpoints extends Component
{
    public $url, $method, $description, $headers, $payload_format, $parameters;
    public $endpointId;
    public $isModalOpen = 0;
    public $isEditMode = false;
    public $selectedEndpointId;
    public $selectedApiEndpoint;
    public $status = 1; // Default is enabled (1)
    protected $rules = [
        'url' => 'required|string',
        'method' => 'required|in:GET,POST,PUT,DELETE',
        'description' => 'nullable|string',
        'headers' => 'nullable',
        'payload_format' => 'nullable|in:JSON,XML',
        'parameters' => 'nullable',
        'status' => 'required|boolean',
    ];

    public function render()
    {
        return view('livewire.api-endpoints', [
            'endpoints' => ApiEndpoint::all(),
        ]);
    }



    public function create()
    {
        $this->resetFields(); // Reset all fields before showing create form
        $this->isEditMode = false;
        //$this->emit('openModal'); // Trigger event to open modal
        $this->dispatch('openModal'); // Emit event to open the modal
        $this->openModal();
    }


    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetFields()
    {
        $this->url = '';
        $this->method = 'GET';
        $this->description = '';
        $this->headers = '{}';
        $this->payload_format = 'JSON';
        $this->parameters = '{}';
        $this->status = true;
        $this->endpointId = null;
    }

    public function save()
    {
        $this->validate();

        ApiEndpoint::updateOrCreate(['id' => $this->endpointId], [
            'url' => $this->url,
            'method' => $this->method,
            'description' => $this->description,
            'headers' => json_decode($this->headers, true), // Decode JSON before saving
            'payload_format' => $this->payload_format,
            'parameters' => json_decode($this->parameters, true), // Decode JSON before saving
            'status' => $this->status ? 1 : 0, // Save as 1 for enabled, 0 for disabled
        ]);
        session()->flash('message', $this->endpointId ? 'Endpoint Updated Successfully.' : 'Endpoint Created Successfully.');

        $this->resetFields();
        $this->dispatch('closeModal');
        //$this->emit('closeModal'); // Trigger event to open modal
        $this->closeModal();
    }

    public function edit(ApiEndpoint $endpoint)
    {
        $this->endpointId = $endpoint->id;
        $this->url = $endpoint->url;
        $this->method = $endpoint->method;
        $this->description = $endpoint->description;
        $this->headers = json_encode($endpoint->headers);
        $this->payload_format = $endpoint->payload_format;
        $this->parameters = json_encode($endpoint->parameters);
        $this->status = $endpoint->status;

        $this->isEditMode = true;
        $this->dispatch('openModal'); // Trigger event to open modal
        $this->openModal();
        //$this->dispatchBrowserEvent('openModal');
    }

    public function delete($id)
    {
        ApiEndpoint::find($id)->delete();
        session()->flash('message', 'Endpoint Deleted Successfully.');
    }

    // Method to open the modal and set the selected endpoint details
    public function openStatusModal($id)
    {
        $this->selectedEndpointId = $id;
        $this->selectedApiEndpoint = ApiEndpoint::find($id);  // Load the API endpoint details
        $this->dispatch('show-status-modal'); // Trigger modal via JavaScript
    }

    // Method to change status
    public function changeStatus($id, $status)
    {
        $endpoint = ApiEndpoint::find($id);
        $endpoint->status = $status;
        $endpoint->save();

        // Close the modal after status update
        $this->dispatch('hide-status-modal');
    }
}
