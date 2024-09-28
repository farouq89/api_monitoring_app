<?php

namespace App\Livewire;

use App\Models\ApiEndpoint;
use Livewire\Component;

class ApiEndpoints extends Component
{
    public $url, $method, $description, $payload_format, $parameters, $status;
    public $endpointId;
    public $isEditMode = false;
    public $headers = [];

    protected $rules = [
        'url' => 'required|string',
        'method' => 'required|in:GET,POST,PUT,DELETE',
        'description' => 'nullable|string',
        'headers' => 'nullable|array',
        'payload_format' => 'nullable|in:JSON,XML',
        'parameters' => 'nullable|array',
        'status' => 'required|boolean',
    ];

    public function create()
    {
        $this->resetFields(); // Reset all fields before showing create form
        $this->isEditMode = false;
        $this->dispatchBrowserEvent('openModal');
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
        $this->dispatchBrowserEvent('openModal');
    }

    public function save()
    {
        $this->validate();

        ApiEndpoint::updateOrCreate(['id' => $this->endpointId], [
            'url' => $this->url,
            'method' => $this->method,
            'description' => $this->description,
            'headers' => json_encode($this->headers),  // Or implode(', ', $this->headers)
            'payload_format' => $this->payload_format,
            'parameters' => json_decode($this->parameters, true), // Decode JSON before saving
            'status' => $this->status,
        ]);
        session()->flash('message', $this->endpointId ? 'Endpoint Updated Successfully.' : 'Endpoint Created Successfully.');

        $this->resetFields();
        $this->dispatchBrowserEvent('closeModal');
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

    public function render()
    {
        return view('livewire.api-endpoints', [
            'endpoints' => ApiEndpoint::all(),
        ]);
    }
}
