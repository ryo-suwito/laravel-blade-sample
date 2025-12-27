@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>DTTOT Detail</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('cms.yukk_co.dttot.list') }}" class="breadcrumb-item">DTTOT</a>
                    <a href="#" class="breadcrumb-item active">Detail</a>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>DTTOT Detail</h2>
        </div>
        <div class="card-body">
            <form id="form" action="#" class="row" method="POST">
                @csrf
                @method('put')
                <div class="col-sm-12 col-lg-6">
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="">Name</label>
                            @if (isset($user['names']) && is_array($user['names']))
                                @foreach ($user['names'] as $alias)
                                    <input type="text" name="aliases[]" value="{{ @$alias }}" class="form-control" style="border-top:1px solid #333">
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group col-12">
                            <label for="">Type</label>
                            <input type="text" readonly name="password" value="{{ @$user['type'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Description</label>
                            <textarea name="description" readonly class="form-control" id="" cols="30" rows="10">{{ @$user['description'] }}</textarea>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Densus Code</label>
                            <input type="text" readonly name="densus_code" value="{{ @$user['densus_code'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Latest Batch</label>
                            <input type="text" readonly name="batch" value="{{ @$user['batch'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Place of Birth</label>
                            <input type="text" readonly name="place_of_birth" value="{{ @$user['place_of_birth'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Date of Birth</label>
                            <input type="text" readonly name="date_of_birth" value="{{ @$user['date_of_birth'] }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="row">
                        <div class="form-group col-4" style="margin-bottom: 0;">
                            <label for="">Identity Type</label>
                        </div>
                        <div class="form-group col-8" style="margin-bottom: 0;">
                            <label for="">Identity Value</label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        @if(isset($user['identities']))
                        @foreach ($user['identities'] as $identity_type)
                            @foreach ($identity_type as $index => $identity)
                                @if ($identity['identity_type'] == 'ALIAS')
                                    @continue
                                @endif
                                <div class="form-group col-4"  style="margin-bottom: 0;">
                                            <select name="identities_type[]" class="form-control" id="identity{{ $identity['id'] }}">
                                                <option value="{{ $identity['identity_type'] }}" selected>{{ $identity['identity_type'] }}</option>
                                                <option value="KTP">KTP</option>
                                                <option value="SIM">SIM</option>
                                                <option value="Passport">Passport</option>
                                            </select>
                                </div>
                                <div class="form-group col-8"   style="margin-bottom: 0;">
                                        <input name="identities_value[]" type="text" id="identityValue{{ $identity['id'] }}" value="{{ @$identity['identity_value'] }}" class="form-control">
                                </div>
                            @endforeach
                        @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="">Change Log</label>
                            <!-- reverse changelogs array orders -->
                            @if (isset($user['changelogs']) && is_array($user['changelogs']))
                                @foreach ($user['changelogs'] as $change_log)
                                    @if ($change_log['status'] == 'PENDING')
                                        <p>{{  $change_log['created_at'] ?
                                            date('d M Y H:i:s', strtotime($change_log['created_at'])) : ''
                                        }} - {{ $change_log['actor'] }} melakukan {{ $change_log['action'] }}</p>
                                    @else 
                                        @if ($change_log['is_final'])
                                            <p>{{ $change_log['created_at'] ?
                                                date('d M Y H:i:s', strtotime($change_log['created_at'])) : ''
                                            }} - DTTOT status untuk request {{ $change_log['action'] }} menjadi {{ 
                                                $change_log['status'] == 'APPROVED' ? 'Approved' : 'Rejected'
                                            }}</p>
                                        @else
                                            <p>{{  $change_log['created_at'] ?
                                                date('d M Y H:i:s', strtotime($change_log['created_at'])) : ''
                                            }} - {{ $change_log['actor'] }} melakukan {{ 
                                                $change_log['status'] == 'APPROVED' ? 'Approved' : 'Rejected'
                                            }}</p>
                                        @endif
                                    @endif
                                @endforeach
                            @else 
                                <p>No data</p>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-12" style="text-align: center">
                    <a href="{{ route('cms.yukk_co.dttot.list') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('post_scripts')
    <script>
    </script>
@endsection
