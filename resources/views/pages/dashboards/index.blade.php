<x-default-layout>

    @section('title')
    Dashboard
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
    @endsection

    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">

        <!--begin::Row-->
        <div class="row g-6 g-xl-9">
            <!--begin::Col-->
            <div class="col-lg-6 col-xl-3">
                <!--begin::Card-->
                <div class="card card-flush h-md-100">
                    <!--begin::Card header-->
                    <div class="card-header py-5">
                        <div class="card-title">
                            <h3 class="fw-bold">API Statistics</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <!-- Total APIs -->
                            <div class="d-flex align-items-center mb-7">
                                <span class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="fas fa-server fs-2 text-primary"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-6">Total APIs</span>
                                    <span class="fw-bold fs-3">{{ $totalApis }}</span>
                                </div>
                            </div>
                            <!-- Active APIs -->
                            <div class="d-flex align-items-center mb-7">
                                <span class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="fas fa-check-circle fs-2 text-success"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-6">Active APIs</span>
                                    <span class="fw-bold fs-3 text-success">{{ $activeApis }}</span>
                                </div>
                            </div>
                            <!-- Inactive APIs -->
                            <div class="d-flex align-items-center">
                                <span class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-danger">
                                        <i class="fas fa-times-circle fs-2 text-danger"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-6">Inactive APIs</span>
                                    <span class="fw-bold fs-3 text-danger">{{ $inactiveApis }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-lg-6 col-xl-3">
                <!--begin::Card-->
                <div class="card card-flush h-md-100">
                    <!--begin::Card header-->
                    <div class="card-header py-5">
                        <div class="card-title">
                            <h3 class="fw-bold">User Statistics</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <!-- Total Users -->
                            <div class="d-flex align-items-center mb-7">
                                <span class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-info">
                                        <i class="fas fa-users fs-2 text-info"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-6">Total Users</span>
                                    <span class="fw-bold fs-3">{{ $totalUsers }}</span>
                                </div>
                            </div>
                            <!-- Active Users -->
                            <div class="d-flex align-items-center mb-7">
                                <span class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="fas fa-user-check fs-2 text-success"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-6">Active Users</span>
                                    <span class="fw-bold fs-3 text-success">{{ $activeUsers }}</span>
                                </div>
                            </div>
                            <!-- Inactive Users -->
                            <div class="d-flex align-items-center">
                                <span class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-danger">
                                        <i class="fas fa-user-times fs-2 text-danger"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-6">Inactive Users</span>
                                    <span class="fw-bold fs-3 text-danger">{{ $inactiveUsers }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->

        </div>
        <!--end::Row-->

        <!--begin::Row (Optionally add more cards or statistics here)-->
        <div class="row g-6 g-xl-9 mt-6">
            <!-- Example of another card or chart -->
            <!-- You can use Metronic's chart component to show more data visualizations here -->
        </div>
        <!--end::Row-->


    </div>
</x-default-layout>