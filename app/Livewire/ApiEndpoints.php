<?php

namespace App\Livewire;

use App\Models\ApiEndpoint;
use Livewire\Component;

class ApiEndpoints extends Component
{
    public $url, $method, $description, $payload_format;
    public $endpointId;
    public $isModalOpen = 0;
    public $isEditMode = false;
    public $selectedEndpointId;
    public $selectedApiEndpoint;
    public $status = 1; // Default is enabled (1)
    public $headers = [];
    public $parameters = [];


    // Validation rules
    protected $rules = [
        'url' => 'required|string',
        'method' => 'required|in:GET,POST,PUT,DELETE',
        'description' => 'nullable|string',
        'headers' => 'nullable|array',
        'headers.*.key' => 'nullable|string',
        'headers.*.value' => 'nullable|string',
        'payload_format' => 'nullable|in:JSON,XML',
        'parameters' => 'nullable|array',
        'status' => 'required|boolean',
    ];

    // Add a new header field dynamically
    public function addHeader()
    {
        $this->headers[] = ['key' => '', 'value' => ''];
    }

    // Remove a header field
    public function removeHeader($index)
    {
        unset($this->headers[$index]);
        $this->headers = array_values($this->headers); // Reindex array
    }

    public function addParameter()
    {
        $this->parameters[] = ['key' => '', 'value' => ''];
    }

    public function removeParameter($index)
    {
        unset($this->parameters[$index]);
        $this->parameters = array_values($this->parameters); // Reindex array
    }
    // Render method
    public function render()
    {
        return view('livewire.api-endpoints', [
            'endpoints' => ApiEndpoint::all(),
        ]);
    }

    // Open create form
    public function create()
    {
        $this->resetFields(); // Reset all fields before showing create form
        $this->isEditMode = false;
        $this->openModal();
    }

    // Open modal
    public function openModal()
    {
        $this->isModalOpen = true;
        $this->dispatch('openModal'); // Trigger event to open the modal
    }

    // Close modal
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->dispatch('closeModal'); // Trigger event to close the modal
    }

    // Reset form fields
    private function resetFields()
    {
        $this->url = '';
        $this->method = 'GET';
        $this->description = '';
        $this->headers = [];
        $this->payload_format = 'JSON';
        $this->parameters = '{}';
        $this->status = true;
        $this->endpointId = null;
    }

    // Save or update API endpoint
    public function save()
    {
        // Validate request
        $this->validate();

        // Save the API endpoint
        ApiEndpoint::updateOrCreate(
            ['id' => $this->endpointId],
            [
                'url' => $this->url,
                'method' => $this->method,
                'description' => $this->description,
                'headers' => json_encode($this->headers), // Save headers as JSON
                'payload_format' => $this->payload_format,
                'parameters' => json_encode($this->parameters),  // Convert parameters array to JSON
                'status' => $this->status ? 1 : 0, // Save as 1 for enabled, 0 for disabled
            ]
        );

        // Flash success message
        session()->flash('message', $this->endpointId ? 'Endpoint Updated Successfully.' : 'Endpoint Created Successfully.');

        // Reset fields and close modal
        $this->resetFields();
        $this->closeModal();
    }

    // Edit API endpoint
    public function edit(ApiEndpoint $endpoint)
    {
        $this->endpointId = $endpoint->id;
        $this->url = $endpoint->url;
        $this->method = $endpoint->method;
        $this->description = $endpoint->description;
        $this->headers = json_decode($endpoint->headers, true) ?? []; // Properly decode headers JSON
        $this->payload_format = $endpoint->payload_format;
        $this->parameters = json_decode($endpoint->parameters, true) ?? []; // Properly decode headers JSON
        $this->status = $endpoint->status;

        $this->isEditMode = true;
        $this->dispatch('openModal');
        $this->openModal();
    }


    // Delete API endpoint
    public function delete($id)
    {
        ApiEndpoint::find($id)->delete();
        session()->flash('message', 'Endpoint Deleted Successfully.');
    }

    // Open modal to change status
    public function openStatusModal($id)
    {
        $this->selectedEndpointId = $id;
        $this->selectedApiEndpoint = ApiEndpoint::find($id);  // Load API endpoint details
        $this->dispatch('show-status-modal'); // Trigger modal via JavaScript
    }

    // Change status of the endpoint
    public function changeStatus($id, $status)
    {
        $endpoint = ApiEndpoint::find($id);
        $endpoint->status = $status;
        $endpoint->save();

        // Close the status modal after updating
        $this->dispatch('hide-status-modal');
    }
}
