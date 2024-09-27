<div class="modal fade" id="kt_modal_add_api" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_api_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Add New Api</h2>
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
                <form id="kt_modal_add_api_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="api_id" name="api_id" value="{{ $api_id }}" />
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_api_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_api_header" data-kt-scroll-wrappers="#kt_modal_add_api_scroll" data-kt-scroll-offset="300px">


                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">API Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="name" />
                            <!--end::Input-->
                            <small class="form-text text-muted"><i class="fa fa-exclamation-circle"
                                    aria-hidden="true"></i>
                                {{ __('Here you can enter a name of your choice for the webpage') }}.
                            </small>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">URL</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="url" wire:model="url" name="url" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="https://example.com/" />
                            <!--end::Input-->
                            @error('url')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted"><i class="fa fa-exclamation-circle"
                                    aria-hidden="true"></i>
                                {{ __('Here you can enter the API URL') }}. <br>
                                Eg: {{ __('https://example.com/api/products/3') }}
                            </small>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Expected response code</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model="response_code" name="response_code" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" />
                            <!--end::Input-->
                            @error('response_code')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted"><i class="fa fa-exclamation-circle"
                                    aria-hidden="true"></i>
                                {{ __('Here you need to provide HTTP response status code of the URL, It should be some numbers') }}. <br>
                                Eg: {{ __('200') }}
                            </small>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">JSON data</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <textarea type="text" wire:model="json" name="json" class="form-control form-control-solid mb-3 mb-lg-0"> </textarea>
                            <!--end::Input-->
                            @error('json')
                            <span class="text-danger">{{ $message }}</span> @enderror

                            <small class="form-text text-muted"><i class="fa fa-exclamation-circle"
                                    aria-hidden="true"></i>
                                {{ __('Please paste any json data that should be posted to the API here (optional)') }}. <br>
                            </small>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-5">Method</label>
                            <!--end::Label-->
                            @error('method')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            <!--begin::Roles-->
                            <!--begin::Input row-->
                            <div class="d-flex fv-row">
                                <!--begin::Radio-->
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input class="form-check-input me-3" id="kt_modal_update_role_option_GET" wire:model="method" name="method" type="radio" value="GET" checked="checked" />
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_update_role_option_GET">
                                        <div class="fw-bold text-gray-800">
                                            GET
                                        </div>

                                    </label>
                                    <!--end::Label-->
                                </div>
                                <!--end::Radio-->
                            </div>
                            <!--end::Input row-->
                            <br />
                            <!--begin::Input row-->
                            <div class="d-flex fv-row">
                                <!--begin::Radio-->
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input class="form-check-input me-3" id="kt_modal_update_role_option_POST" wire:model="method" name="method" type="radio" value="POST" checked="" />
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_update_role_option_POST">
                                        <div class="fw-bold text-gray-800">
                                            POST
                                        </div>

                                    </label>
                                    <!--end::Label-->
                                </div>
                                <!--end::Radio-->
                            </div>
                            <!--end::Input row-->
                            <br />
                            <!--begin::Input row-->
                            <div class="d-flex fv-row">
                                <!--begin::Radio-->
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input class="form-check-input me-3" id="kt_modal_update_role_option_PUT" wire:model="method" name="method" type="radio" value="PUT" checked="" />
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_update_role_option_PUT">
                                        <div class="fw-bold text-gray-800">
                                            PUT
                                        </div>

                                    </label>
                                    <!--end::Label-->
                                </div>
                                <!--end::Radio-->
                            </div>
                            <!--end::Input row-->
                            <br />
                            <!--begin::Input row-->
                            <div class="d-flex fv-row">
                                <!--begin::Radio-->
                                <div class="form-check form-check-custom form-check-solid">
                                    <!--begin::Input-->
                                    <input class="form-check-input me-3" id="kt_modal_update_role_option_DELETE" wire:model="method" name="method" type="radio" value="DELETE" checked="" />
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <label class="form-check-label" for="kt_modal_update_role_option_DELETE">
                                        <div class="fw-bold text-gray-800">
                                            DELETE
                                        </div>

                                    </label>
                                    <!--end::Label-->
                                </div>
                                <!--end::Radio-->
                            </div>
                            <!--end::Input row-->

                            <div class='separator separator-dashed my-5'></div>

                            <!--end::Roles-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label" wire:loading.remove>Submit</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
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