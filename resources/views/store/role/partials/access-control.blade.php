<div id="access-control" class="mt-4">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-auto">
            <h5 class="mb-2 mb-md-0 @error('access_control') text-danger @enderror">@lang('cms.Access Controls')</h5>
        </div>
        <div class="col-12 col-md-3">
            <input class="form-control" placeholder="Search access control" @input="handleSearch" />
        </div>
    </div>

    @error('access_control')
        <p class="text-danger mb-2">{{ $message }}</p>
    @enderror

    <div v-for="group in groups" class="mb-4">
        <div class="access__group_name">
            <h5 class="font-weight-bold h5">@{{ group.name }}</h5>
        </div>
        <div class="row">
            <div v-for="access in group.access_controls" class="col-md-4 mb-2">
                <div class="font-weight-bold mb-1 access__name" style="user-select: none;">@{{ access.name }}</div>
                <div class="px-1 access__action-list">
                    <label v-for="action in access.actions" class="d-flex align-items-center" style="user-select: none; cursor: pointer;">
                        <input v-model="inputAccessControls" type="checkbox" :value="action.value" />
                        <span class="ml-2">
                            @{{ action.name }}
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div>
        <input v-for="value in inputAccessControls" type="hidden" name="access_control[]" :value="value" />
    </div>
</div>

@push('styles')
<style>
    .access__group_name {
        overflow: hidden;
    }

    .access__group_name h5::after {
        background-color: rgba(255, 255, 255, 0.2);
        content: "";
        display: inline-block;
        height: 1px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
 
    .access__group_name h5::after {
        left: 0.5rem;
        margin-right: -100%;
    }
</style>
@endpush
