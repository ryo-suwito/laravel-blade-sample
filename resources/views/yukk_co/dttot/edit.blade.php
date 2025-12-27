@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>DTTOT</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('cms.yukk_co.dttot.list') }}" class="breadcrumb-item">DTTOT</a>
                    <a href="#" class="breadcrumb-item active">Edit</a>
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
            <h2>DTTOT Edit</h2>
        </div>
        <div class="card-body">
            <form id="form" action="
            {{ route('cms.yukk_co.dttot.update', $user['id']) }}
            " class="row" method="POST">
                @csrf
                @method('put')
                <div class="col-sm-12 col-lg-6">
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="">Name</label>
                            <div id="additionalNameContainer">
                                @if (isset($user['names']))
                                    @foreach ($user['names'] as $index => $alias)
                                    <div style="display:flex; justify-content: space-between; align-items: center; width: 100%;">
                                        <input type="text" name="aliases[]" value="{{ $alias }}" class="form-control" style="border-top:1px solid #333; display: inline-block;"> 
                                        @if ($index != 0)
                                        <span class="removeAlias" onclick="onRemoveAlias(this)">x</span>
                                        @endif
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <!-- add new alias -->
                            <p><a href="#" onclick="onAddNewAlias()" >+ Add New Alias</a></p>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Type</label>
                            <select name="type" class="form-control" id="">
                                <option value="PERSONAL" {{ @$user['type'] == 'PERSONAL' ? 'selected' : '' }}>PERSONAL</option>
                                <option value="CORPORATE" {{ @$user['type'] == 'CORPORATE' ? 'selected' : '' }}>CORPORATE</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control" id="" cols="30" rows="10">{{ @$user['description'] }}</textarea>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Densus Code</label>
                            <input readonly type="text" name="densus_code" value="{{ @$user['densus_code'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Latest Batch</label>
                            <input type="text" readonly name="batch" value="{{ @$user['batch'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Place of Birth</label>
                            <input type="text" name="place_of_birth" value="{{ @$user['place_of_birth'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Date of Birth</label>
                            <input type="text" name="date_of_birth" value="{{ @$user['date_of_birth'] }}" class="form-control">
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
                    <div class="row">
                        <div class="form-group col-12">
                            <div id="additionalIdentityContainer">
                                
                            <?php
                                @$countIdentity = 0;
                                @$typeList = ['KTP', 'SIM', 'PASSPORT', 'PHONE', 'NPWP', 'EMAIL', 'OTHER'];
                            ?>
                            @foreach ($user['identities'] as $identity_type)
                                @foreach ($identity_type as  $index => $identity)
                                    @if ($identity['identity_type'] == 'ALIAS')
                                        @continue
                                    @endif
                                    <div class="row">
                                        <div class="form-group col-4"  style="margin-bottom: 0;">
                                                    <select name="identities_type[]" class="form-control" id="identity{{ $identity['id'] }}">
                                                        <option value="{{ $identity['identity_type'] }}" selected>{{ $identity['identity_type'] }}</option>
                                                        @foreach ($typeList as $type)
                                                            @if ($type == $identity['identity_type'])
                                                                @continue
                                                            @endif
                                                            <option value="{{ $type }}">{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                        </div>
                                        <div class="form-group col-8"  
                                                style="margin-bottom: 0;display:flex; justify-content: space-between; align-items: center;">
                                                
                                                <input name="identities_value[]" type="text" id="identityValue{{ $identity['id'] }}" value="{{ @$identity['identity_value'] }}" class="form-control">
                                                @if ($countIdentity != 0) 
                                                <span class="removeAlias" onclick="onRemoveIdentity(this)">x</span>
                                                @endif
                                        </div>
                                    </div>
                                    <?php
                                        $countIdentity++;
                                    ?>
                                @endforeach
                            @endforeach
                            </div>
                            <!-- add new identity -->
                            <p><a href="#" onclick="onAddNewIdentity()" >+ Add New Identity</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-12" style="text-align: center">
                    <button type="submit" class="btn btn-primary">Edit</button>
                    <a href="{{ route('cms.yukk_co.dttot.list') }}" class="btn btn-dark">Cancel</a>
                </div>
            </form>
        </div>

        <div class="modal fade" id="modal-toggle-active" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirmation-form" class="modal-dialog"
                role="document" method="post" action="{{ route('cms.store.users.toggle.active', $user['id']) }}">
                @csrf
                <input id="modal-confirmation-input" name="id" type="hidden" value="{{ $user['id'] }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure to xxx?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('post_scripts')
    <script>
        var aliasFieldCount = 0;
        var identityFieldCount = 0;

        function onAddNewAlias() {
            // check how many alias input text is in the nameContainer
            aliasFieldCount = checkNameContainer() + 1;
            consolidateInputText();
            // add blank new input text for additionalNameContainer using jquery
            $('#additionalNameContainer').append('<div style="display:flex; justify-content: space-between; align-items: center; width: 100%;"><input type="text" name="aliases[]" value="" class="form-control" style="border-top:1px solid #333; display: inline-block;"> <span class="removeAlias" onclick="onRemoveAlias(this)">x</span></div>');
        }
        function onRemoveAlias(element) {
            // remove the parent element of the element
            aliasFieldCount = checkNameContainer() - 1;
            // get first input text in the nameContainer
            $(element).parent().remove();
            consolidateInputText();
        }
        function onAddNewIdentity() {
            identityFieldCount = checkIdentityContainer() + 1;
            consolidateIdentityInputText();
            // add blank new input text for additionalNameContainer using jquery
            $('#additionalIdentityContainer').append('\
                    <div class="row">\
                        <div class="form-group col-4" style="margin:0">\
                            <select name="identities_type[]" class="form-control" id="">\
                                <option value="KTP">KTP</option>\
                                <option value="SIM">SIM</option>\
                                <option value="PASSPORT">PASSPORT</option>\
                                <option value="PHONE">PHONE</option>\
                                <option value="NPWP">NPWP</option>\
                                <option value="EMAIL">EMAIL</option>\
                                <option value="OTHER">OTHER</option>\
                            </select>\
                        </div>\
                        <div class="form-group col-8" style="display:flex; justify-content: space-between; align-items: center;">\
                            <input type="text" name="identities_value[]" value="" class="form-control">\
                            <span class="removeAlias" onclick="onRemoveIdentity(this)">x</span>\
                        </div>\
                    </div>');
        }
        function onRemoveIdentity(element) {
            identityFieldCount = checkIdentityContainer() - 1;
            // remove the parent element of the element
            $(element).parent().parent().remove();
            consolidateIdentityInputText();
        }
        function checkNameContainer(){
            // check how many input text is in the additionalNameContainer descendant
            var aliasInputTexts = $('#additionalNameContainer input[type=text]');
            // get only the input text which name is aliases[] 
            aliasInputTexts = aliasInputTexts.filter(function(index, element){
                return $(element).attr('name') == 'aliases[]';
            });
            // count the input text
            aliasFieldCount = aliasInputTexts.length;
            return aliasFieldCount;
        }
        function checkIdentityContainer(){
            // check how many input text is in the additionalNameContainer descendant
            var identityInputTexts = $('#additionalIdentityContainer input[type=text]');
            // get only the input text which name is aliases[] 
            identityInputTexts = identityInputTexts.filter(function(index, element){
                return $(element).attr('name') == 'identities_value[]';
            });
            // count the input text
            identityFieldCount = identityInputTexts.length;
            return identityFieldCount;
        }
        function consolidateInputText(){
            console.info('aliasFieldCount', aliasFieldCount);
            // if aliasFieldCount is greater than 1, add span removeAlias to the first input text
            let isHavingRemoveAlias = $('#additionalNameContainer input[type=text]').first().parent().find('span').length > 0;
            if (aliasFieldCount > 1 && !isHavingRemoveAlias) {
                $('#additionalNameContainer input[type=text]').first().parent().append('<span class="removeAlias" onclick="onRemoveAlias(this)">x</span>');
            } else if (aliasFieldCount <= 1){
                $('#additionalNameContainer input[type=text]').first().parent().find('span').remove();
            }
        }
        function consolidateIdentityInputText(){
            console.info('identityFieldCount', identityFieldCount);
            // if aliasFieldCount is greater than 1, add span removeAlias to the first input text
            let isHavingRemoveAlias = $('#additionalIdentityContainer input[type=text]').first().parent().find('span').length > 0;
            if (identityFieldCount > 1 && !isHavingRemoveAlias) {
                $('#additionalIdentityContainer input[type=text]').first().parent().append('<span class="removeAlias" onclick="onRemoveIdentity(this)">x</span>');
            } else if (identityFieldCount <= 1){
                $('#additionalIdentityContainer input[type=text]').first().parent().find('span').remove();
            }

        }
        // on document ready
        $('document').ready(function(){
            // check how many alias input text is in the nameContainer
            aliasFieldCount = checkNameContainer();
            consolidateInputText();
            identityFieldCount = checkIdentityContainer();
            consolidateIdentityInputText();

            // intercept form submit
            $('#form').submit(function(e){
                // get aliases input text
                var aliasInputTexts = $('#additionalNameContainer input[type=text]');
                // make the first input text as the "name" input text and other input text as "aliases" input text
                var nameInputText = aliasInputTexts.first();
                var aliasInputTexts = aliasInputTexts.not(':first');
                // set the attribute name input text to the first input text
                nameInputText.attr('name', 'name');
                // set the attribute name aliases[] to the other input text
                aliasInputTexts.attr('name', 'aliases[]');

                // submit
                return true;
            });
        });
    </script>
    <style>
        .removeAlias {
            display: inline-block;
            width: 20px;
            height: 20px;
            color: white;
            text-align: center;
            cursor: pointer;
        }
    </style>
@endsection
