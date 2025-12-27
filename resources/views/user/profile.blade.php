@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.My Profile")</h4>
            </div>

            @if(isProductionMode())
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <button class="btn btn-primary" data-toggle="modal" data-target="#changePassword">@lang("cms.Change Password")</button>
            </div>
            @endif
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.My Profile")</span>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-6">
                            <input id="email" type="text" class="form-control" value="{{ $profile->result->email }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Full Name")</label>
                        <div class="col-lg-6">
                            <input form="confirmationModal" id="full_name" name="full_name" type="text" class="form-control" value="{{ $profile->result->full_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Phone")</label>
                        <div class="col-lg-6">
                            <input form="confirmationModal" id="phone" name="phone" type="text" class="form-control" value="{{ $profile->result->phone }}" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Gender")</label>
                        <div class="col-lg-6">
                            <select form="confirmationModal" class="form-control dropdown" id="gender" name="gender">
                                <option value="MALE" @if($profile->result->gender == "MALE") selected @endif>MALE</option>
                                <option value="FEMALE" @if($profile->result->gender == "FEMALE") selected @endif>FEMALE</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">@lang("cms.Role Name(s)")</label>
                        <div class="col-lg-6 col-form-label">
                            @foreach($profile->result->user_role_list as $item)
                                {{ $item->role->name }}
                                <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button id="submit" type="button" class="btn btn-primary justify-content-center ml-2 mr-2 mb-2" data-toggle="modal" data-target="#confirm" disabled>Save</button>

        <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confimationLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <form action="{{ route("cms.user.update") }}" method="POST" id="confirmationModal">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="demoModalLabel">Authentication</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">Insert Your Password</div>
                            <input id="password" name="password" type="password" class="form-control mr-1 mt-1" value="">
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary">Change</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(isProductionMode())
        <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <form action="{{ route("cms.password.update") }}" method="POST" id="form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title justify-content-center" id="demoModalLabel">Change Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="text-center mt-1">Insert Your Old Password</div>
                            <div class="input-group mb-3">
                                <input id="old_password" name="old_password" type="password" value="" class="input form-control" placeholder="Input Your Old Password" required="true" aria-label="old_password" aria-describedby="basic-addon1"/>
                                <div class="input-group-append">
                                <span class="input-group-text" onclick="password_show_hide();">
                                  <i class="fas fa-eye" id="show_eye"></i>
                                  <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="text-center mt-1">Insert Your New Password</div>
                            <div class="input-group mb-3">
                                <input id="new_password" name="new_password" type="password" value="" class="input form-control" placeholder="Input Your New Password" required="true" aria-describedby="basic-addon1"/>
                                <div class="input-group-append">
                                <span class="input-group-text" onclick="new_password_show_hide();">
                                  <i class="fas fa-eye" id="show_eye_1"></i>
                                  <i class="fas fa-eye-slash d-none" id="hide_eye_1"></i>
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="text-center mt-1">Confirm Your New Password</div>
                            <div class="input-group mb-3">
                                <input id="_password" name="_password" type="password" value="" class="input form-control" placeholder="Confirm Your New Password" required="true" aria-describedby="basic-addon1"/>
                                <div class="input-group-append">
                                <span class="input-group-text" onclick="confirm_new_password_show_hide();">
                                  <i class="fas fa-eye" id="show_eye_2"></i>
                                  <i class="fas fa-eye-slash d-none" id="hide_eye_2"></i>
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('post_scripts')
<script defer>
    function password_show_hide() {
        var x = document.getElementById("old_password");
        var show_eye = document.getElementById("show_eye");
        var hide_eye = document.getElementById("hide_eye");
        hide_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            x.type = "password";
            show_eye.style.display = "block";
            hide_eye.style.display = "none";
        }
    }

    function new_password_show_hide() {
        var x = document.getElementById("new_password");
        var show_eye = document.getElementById("show_eye_1");
        var hide_eye = document.getElementById("hide_eye_1");
        hide_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            x.type = "password";
            show_eye.style.display = "block";
            hide_eye.style.display = "none";
        }
    }

    function confirm_new_password_show_hide() {
        var x = document.getElementById("_password");
        var show_eye = document.getElementById("show_eye_2");
        var hide_eye = document.getElementById("hide_eye_2");
        hide_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            x.type = "password";
            show_eye.style.display = "block";
            hide_eye.style.display = "none";
        }
    }

    $(document).ready(function() {
        $('#email').change(function() {
            $('#submit').removeAttr('disabled', $('#email').val() != "");
        });
        $('#full_name').change(function() {
            $('#submit').removeAttr('disabled', $('#full_name').val() != "");
        });
        $('#phone').change(function() {
            $('#submit').removeAttr('disabled', $('#phone').val() != "");
        });
        $("select.dropdown").change(function(){
            var gender = $(this).children("option:selected").val();
            if (gender != null){
                $('#submit').removeAttr('disabled');
            }
        });
    });
</script>
@endsection
