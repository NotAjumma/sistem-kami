@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <a href="{{ url('email-template') }}" class="btn btn-sm btn-primary mb-4">List Email Template</a>
        </div>
        <div class="col-xl-12">
            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa">
                        <i class="fa-solid fa-envelope me-2"></i>Add Email Template
                    </div>
                    <div class="tools">
                        <a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <form>
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control">
                                        <div class="form-text">Title should be meaning full like :
                                            registration email, forgot password email.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="5"></textarea>
                                        <div class="form-text">Decribe about this email template. </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xl-6">
                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input class="form-control " type="text" placeholder="Slug"
                                        aria-label="Disabled input example" disabled>
                                    <div class="form-text">slug will use for url. can't edited.</div>
                                </div>
                                <label class="form-label">placeholder</label>
                                <div class="new-scroll">
                                    <div class="d-grid mb-3">
                                        <h6 class="mb-0">User Configuration</h6>
                                        <span>#USERNAME#: Username can display with this placeholder.</span>
                                        <span>#USERNAME#: Username can display with this placeholder.</span>
                                        <span>#LASTNAME#: Lastname can display with this placeholder.</span>
                                        <span>#TELEPHONE#: Contact number can display with this
                                            placeholder.</span>
                                        <span>#PASSWORD#: password can display with this placeholder.</span>
                                        <span>#SITENAME#: Site name can display with this
                                            placeholder.</span>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <h6 class="mb-0">Config Configuration</h6>
                                        <span>#SITENAME#: Site name can display with this
                                            placeholder.</span>
                                        <span>#ADMINEMAIL#: Admin email can display with this
                                            placeholder.</span>
                                        <span>#SUPPORTEMAIL#: Support email can display with this
                                            placeholder.</span>
                                        <span>#SITEADDRESS#: Site address can display with this
                                            placeholder.</span>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <h6>Generate Configuration</h6>
                                        <span>#ACTIVATIONLINK#: Activation link can display with this
                                            placeholder.</span>
                                        <span>#SITELOGO#: Site logo can display with this
                                            placeholder.</span>
                                        <span>#LOGINLINK#: Login link can display with this
                                            placeholder.</span>
                                        <span>#REGESTERLINK#: Registration link can display with this
                                            placeholder.</span>
                                        <span>#REGESTERLINK#: Registration link can display with this
                                            placeholder.</span>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <h6>Contact Configuration</h6>
                                        <span>#NAME#: Contact user name can display with this
                                            placeholder.</span>
                                        <span>#EMAIL#: Contact user email can display with this
                                            placeholder.</span>
                                        <span>#MESSAGE#: Contact user message can display with this
                                            placeholder.</span>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <h6>Subscribe Configuration</h6>
                                        <span>#USERNAME#: Subscribe user email can display with this
                                            placeholder.</span>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <h6>Order Configuration</h6>
                                        <span>#STATUS#: Order Status can display with this
                                            placeholder.</span>
                                        <span>#FIRSTNAME#: User first name can display with this
                                            placeholder.</span>
                                        <span>#LASTNAME#: User last name can display with this
                                            placeholder.</span>
                                        <span>#MESSAGE#: Delivery details or expected deliery date. This
                                            message will deliver to customer.</span>
                                        <span>#ID#: Order number can display with this placeholder.</span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xl-12">
                                <label class="form-label mt-3 mt-xl-0">Email Template</label>
                                <div class="custom-ekeditor mb-3">
                                    <div id="ckeditor"></div>
                                </div>
                                <div class="form-text mb-3">This design will show in recieved email and
                                    place holders will replace with dynamic content.</div>
                            </div>
                            <label class="form-label">Status</label>
                            <ul class="d-flex align-items-center mb-3">
                                <li>
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox"
                                            value="" id="flexCheckDefault"> <label class="form-check-label"
                                            for="flexCheckDefault"></label></div>
                                </li>
                                <li>Active status template will use in email sending only.</li>
                            </ul>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary">Save Email Template</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").on('change', function() {

        readURL(this);
    });
    $('.remove-img').on('click', function() {
        var imageUrl = "images/no-img-avatar.png";
        $('.avatar-preview, #imagePreview').removeAttr('style');
        $('#imagePreview').css('background-image', 'url(' + imageUrl + ')');
    });
</script>
@endpush