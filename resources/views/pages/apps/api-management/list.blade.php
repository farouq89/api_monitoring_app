<x-default-layout>

    @section('title')
    APIs
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('api-management.list') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Api" id="mySearchInput" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add user-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_endpoint">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add API
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->

                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <livewire:api-endpoints></livewire:api-endpoints>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
    <script>
        window.addEventListener('openModal', event => {
            $('#kt_modal_add_endpoint').modal('show');
        });

        window.addEventListener('closeModal', event => {
            $('#kt_modal_add_endpoint').modal('hide');
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function() {
            $('#apiEndpointsTable').DataTable({
                "pageLength": 10,
                "ordering": true,
                "searching": true,
                "lengthChange": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        // Reinitialize DataTables after Livewire update
        Livewire.hook('message.processed', (message, component) => {
            $('#apiEndpointsTable').DataTable().destroy(); // Destroy the old DataTable instance
            $('#apiEndpointsTable').DataTable(); // Reinitialize DataTable after Livewire updates the DOM
        });

        $('#apiEndpointsTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "lengthChange": true,
            "autoWidth": false,
            "responsive": true,
            "dom": '<"top"f>rt<"bottom"ilp><"clear">',
            "language": {
                "paginate": {
                    "previous": "Prev",
                    "next": "Next"
                }
            }
        });
    </script>

    <script>
        window.addEventListener('show-status-modal', event => {
            $('#statusModal').modal('show');
        });

        window.addEventListener('hide-status-modal', event => {
            $('#statusModal').modal('hide');
        });
    </script>

    <script>
        let headerIndex = 1; // Start from 1 since the first row is hard-coded

        function addHeaderRow() {
            const container = document.getElementById('headers-container');

            // Create new row for the header key-value
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-3', 'header-row');
            newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" list="headerKeys" placeholder="Enter header key" name="headers[${headerIndex}][key]" wire:model="headers.${headerIndex}.key">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" list="headerValues" placeholder="Enter header value" name="headers[${headerIndex}][value]" wire:model="headers.${headerIndex}.value">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <!-- Remove Button -->
                <button type="button" class="btn btn-sm btn-danger remove-header-btn" onclick="removeHeaderRow(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        `;

            // Append the new row
            container.appendChild(newRow);

            // Increment the index for the next header
            headerIndex++;
        }

        function removeHeaderRow(button) {
            // Remove the row that contains the button
            const row = button.closest('.header-row');
            row.remove();
        }
    </script>



    @endpush


</x-default-layout>