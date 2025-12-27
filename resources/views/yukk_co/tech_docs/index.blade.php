@extends('layouts.master')

@section('header')
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>PG Tech Docs</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Transaction Payment Gateway List")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tech Docs</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <span>You may preview / download the latest tech docs here</span>
                    <form action="/yukk_co/pg/tech-docs/download">
                        <button class="btn btn-primary">
                            <i class="icon-download"></i> &nbsp;
                            Download
                        </button>
                    </form>
                </div>
                <div class="col-6">
                    <span>Upload the newest docs here</span>
                    <form action="/yukk_co/pg/tech-docs" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="123">
                        <input type="file" required name="file" accept="application/pdf" class="form-control">
                        <button class="btn btn-primary mt-2" type="submit">
                            <i class="icon-cloud-upload"></i> &nbsp;
                            Upload
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    </script>
@endsection