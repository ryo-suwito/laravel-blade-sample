@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Bulk Add")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Bulk Add")</span>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="import-tab" data-toggle="tab" href="#import" role="tab" aria-selected="true">1. Import CSV</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="preview-tab" data-toggle="tab" href="#preview" role="tab" aria-selected="false" >2. Preview</a>
                    </li>
                </ul>
                <div class="tab-content" id="tabContent">
                    <div class="tab-pane fade show active" id="import" role="tabpanel" aria-labelledby="import-tab">
                        <form action="/file-upload" class="form-control dropzone justify-content-center" id="dropzone">
                            <div class="fallback mt-5">
                                <input name="file" type="file" multiple />
                                <h6>Download template <a href="#" class="text-blue">here</a></h6>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="preview" role="tabpanel" aria-labelledby="preview-tab">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="passed-tab" data-toggle="tab" href="#passed" role="tab" aria-selected="true">@lang("cms.PASSED")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="not-passed-tab" data-toggle="tab" href="#not-passed" role="tab" aria-selected="false">@lang("cms.NOT PASSED")</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="tabContent">
                            <div class="tab-pane fade show active" id="passed" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                        <tr>
                                            <th>@lang("cms.Email")</th>
                                            <th>@lang("cms.Full Name")</th>
                                            <th>@lang("cms.Phone")</th>
                                            <th>@lang("cms.Gender")</th>
                                            <th>@lang("cms.Description")</th>
                                            <th>@lang("cms.Role(s)")</th>
                                            <th>@lang("cms.Partner(s)")</th>
                                            <th>@lang("cms.Branch(es)")</th>
                                            <th>@lang("cms.Beneficiary(es)")</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>kevinpratama175@gmail.com</td>
                                            <td>Kevin Pratama</td>
                                            <td>087881033627</td>
                                            <td>Male</td>
                                            <td>Hello World!</td>
                                            <td>YUKK CO</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="not-passed" role="tabpanel" aria-labelledby="profile-tab">
                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                        <tr>
                                            <th>@lang("cms.Email")</th>
                                            <th>@lang("cms.Full Name")</th>
                                            <th>@lang("cms.Phone")</th>
                                            <th>@lang("cms.Gender")</th>
                                            <th>@lang("cms.Description")</th>
                                            <th>@lang("cms.Role(s)")</th>
                                            <th>@lang("cms.Partner(s)")</th>
                                            <th>@lang("cms.Branch(es)")</th>
                                            <th>@lang("cms.Beneficiary(es)")</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ijah.yellow@gmail.com</td>
                                            <td>Ijah</td>
                                            <td>087222111333</td>
                                            <td>FEMALE</td>
                                            <td>Hello World!</td>
                                            <td>Super Admin</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('post_scripts')
<script defer>
    $(document).ready(function() {
        $(".dataTable").DataTable({
            "paging": true,
            "ordering": true,
            "info": false,
            "searching": true,
        });

        $(".pagination .page-item.active").click(function(e) {
            e.preventDefault();
        });

        var $sections = $('.form-section');

        function navigateTo(index){
            $sections.removeClass("current").eq(index).addClass("current");
            $('.form-navigation .previous').toggle(index);
            var atTheEnd = index >= $sections.length -1;
            $('.form-navigation .next').toggle(!atTheEnd);
            $('.form-navigation [type=submit]').toggle(atTheEnd);
        }

        function curIndex(){
            return $sections.index($sections.filter('.current'));
        }

        $('.form-navigation .previous').click(function (){
           navigateTo(curIndex()-1);
        });

        $('.form-navigation .next').click(function (){
           $('.contact-form').parsley().whenValidate({
               group: 'block-' + curIndex()
           }).done(function (){
               navigateTo(curIndex()+1)
           })
        });

        $sections.each(function (index, section){
           $(section).find(':input').attr('data-parsley-group','block-'+index)
        });

        navigateTo(0);
    });
</script>
@endsection
