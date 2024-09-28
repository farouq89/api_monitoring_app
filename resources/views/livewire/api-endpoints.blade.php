<div>
    <div class="container">
        <!-- Display Success Message -->
        @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <!-- Button to Open the Modal -->

        <!-- Table -->
        <div>
            <!-- Table -->
            <table id="apiEndpointsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>URL</th>
                        <th>Method</th>
                        <th>Description</th>
                        <th>Payload Format</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($endpoints as $endpoint)
                    <tr>
                        <td>{{ $endpoint->id }}</td>
                        <td>{{ $endpoint->url }}</td>
                        <td>{{ $endpoint->method }}</td>
                        <td>{{ $endpoint->description }}</td>
                        <td>{{ $endpoint->payload_format }}</td>
                        <td>
                            @if($endpoint->status)
                            <!-- Green animated circle icon for enabled status -->
                            <a href="javascript:void(0)" wire:click="openStatusModal({{ $endpoint->id }})">
                                <i class="fas fa-circle text-success animate-pulse" title="Enabled"></i>
                            </a>
                            @else
                            <!-- Red circle icon for disabled status -->
                            <a href="javascript:void(0)" wire:click="openStatusModal({{ $endpoint->id }})">
                                <i class="fas fa-circle text-danger" title="Disabled"></i>
                            </a>
                            @endif
                        </td>

                        <td>
                            <!-- Actions like edit, delete -->
                            <button wire:click="edit({{ $endpoint->id }})" class="btn btn-sm btn-primary">Edit</button>
                            <button wire:click="delete({{ $endpoint->id }})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal for Add/Edit -->

        <div class="modal fade" id="kt_modal_add_endpoint" tabindex="-1" aria-hidden="true" wire:ignore.self>

            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_endpoint_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">{{ $isEditMode ? 'Edit API Endpoint' : 'Add API Endpoint' }}</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            {!! getIcon('cross','fs-1') !!}
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->

                    <!--begin::Modal body-->
                    <div class="modal-body px-5 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_add_endpoint_form" class="form" wire:submit.prevent="save" enctype="multipart/form-data">
                            <input type="hidden" wire:model="endpointId" name="endpoint_id" />

                            <!--begin::Scroll-->
                            <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_endpoint_scroll" data-kt-scroll="true" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">

                                <!--begin::Input group - URL-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fw-semibold fs-6 mb-2">Endpoint URL</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" wire:model.defer="url" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="https://api.example.com/endpoint" />
                                    <!--end::Input-->
                                    @error('url') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group - Method-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fw-semibold fs-6 mb-2">Method</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select form-select-solid" wire:model.defer="method" aria-label="Select HTTP method">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                        <option value="DELETE">DELETE</option>
                                    </select>
                                    <!--end::Input-->
                                    @error('method') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group - Description-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fw-semibold fs-6 mb-2">Description</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea wire:model.defer="description" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Describe the API endpoint..."></textarea>
                                    <!--end::Input-->
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group - Headers (JSON)-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fw-semibold fs-6 mb-2">Headers (JSON)</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea wire:model.defer="headers" class="form-control form-control-solid mb-3 mb-lg-0" placeholder='{"Authorization": "Bearer token"}'></textarea>
                                    <!--end::Input-->
                                    @error('headers') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group - Payload Format-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fw-semibold fs-6 mb-2">Payload Format</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select wire:model.defer="payload_format" class="form-select form-select-solid">
                                        <option value="JSON">JSON</option>
                                        <option value="XML">XML</option>
                                    </select>
                                    <!--end::Input-->
                                    @error('payload_format') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group - Parameters (JSON)-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fw-semibold fs-6 mb-2">Parameters (JSON)</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea wire:model.defer="parameters" class="form-control form-control-solid mb-3 mb-lg-0" placeholder='{"id": 123, "name": "example"}'></textarea>
                                    <!--end::Input-->
                                    @error('parameters') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group - Status-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fw-semibold fs-6 mb-2">Status</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="statusToggle" wire:model.defer="status" value="1" />
                                        <label class="form-check-label" for="statusToggle">Enabled</label>
                                    </div>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Scroll-->

                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label" wire:loading.remove>Submit</span>
                                    <span class="indicator-progress" wire:loading wire:target="save">
                                        Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>


        <!-- Modal to change the status -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Change API Endpoint Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <!-- API details -->
                        <div class="mb-4">
                            <h6><strong>API Name:</strong> {{ $selectedApiEndpoint->name ?? 'N/A' }}</h6>
                            <p><strong>Current Status:</strong>
                                @if($selectedApiEndpoint && $selectedApiEndpoint->status)
                                <span class="text-success">Enabled</span>
                                @else
                                <span class="text-danger">Disabled</span>
                                @endif
                            </p>
                            <p><strong>Description:</strong> {{ $selectedApiEndpoint->description ?? 'No description available' }}</p>
                        </div>
                        <!-- Confirmation message -->
                        <p>Are you sure you want to change the status of this API endpoint?</p>
                        <div class="d-flex justify-content-center">
                            <!-- Buttons to confirm status change -->
                            <button class="btn btn-success" wire:click="changeStatus({{ $selectedEndpointId }}, true)">Enable</button>
                            <button class="btn btn-danger ms-3" wire:click="changeStatus({{ $selectedEndpointId }}, false)">Disable</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

</div>