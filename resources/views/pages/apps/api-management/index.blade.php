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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_api">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add API
                    </button>
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:api.add-api-modal></livewire:api.add-api-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                @if (!count($apis))
                <div class="card-body">
                    <div class="empty-state" data-height="400">

                        <h2>{{ __('No API added yet under') }} !!</h2>
                        <p class="lead">
                            {{ __('Sorry we cant find any data, to get rid of this message, make at least 1 entry') }}.
                        </p>
                        <a href=""
                            class="btn btn-purple mt-4">{{ __('Create new One') }}</a>
                    </div>
                </div>

                @else
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr class="text-center text-capitalize">
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Method') }}</th>
                                <th>{{ __('Expected response code') }}</th>
                                <th>{{ __('Response Time') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>
                                    {{ __('Details') }}
                                </th>

                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($apis as $api)
                            <tr class="text-center">
                                <td class="text-left">{{ $api->name }}</td>
                                <td>{{ $api->methode }}</td>
                                <td>{{ $api->expected_response_code }}</td>
                                <td>
                                    {{ $api->avg_response_time }} ms
                                </td>
                                <td>
                                    @if ($api->previous_api_status == 'up')
                                    <i class="fa fa-circle fa-2x text-success-dark" title="{{ __('Up') }}"></i>
                                    @else
                                    <i class="fa fa-circle fa-2x text-danger" title="{{ __('Down') }}"></i>
                                    @endif
                                </td>
                                <td><a href=""
                                        class="btn bg-transparent">
                                        <i class="fas fa-eye text-primary"></i></a></td>

                                <td class="justify-content-center form-inline">
                                    <a href=""
                                        class="btn btn-sm bg-transparent"><i class="far fa-edit text-primary"
                                            aria-hidden="true" title="{{ __('Edit') }}"></i></a>

                                    @if (env('APP_ENV') != 'demo')
                                    <form action="" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-sm bg-transparent"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash text-danger" aria-hidden="true"
                                                title="{{ __('Delete') }}"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    {{ $apis->appends($request->all())->links("pagination::bootstrap-4") }}
                </div>
                @endif
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')

    <script>
        document.getElementById('mySearchInput').addEventListener('keyup', function() {
            window.LaravelDataTables['users-table'].search(this.value).draw();
        });
        document.addEventListener('livewire:init', function() {
            Livewire.on('success', function() {
                $('#kt_modal_add_api').modal('hide');
                window.LaravelDataTables['users-table'].ajax.reload();
            });
        });
        $('#kt_modal_add_api').on('hidden.bs.modal', function() {
            Livewire.dispatch('new_user');
        });
    </script>
    @endpush


</x-default-layout>