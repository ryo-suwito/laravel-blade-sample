<div class="col p-2 mr-1 bg-card">
    <div class="row">
        <div class="col">
            <h5>{{ $title }}</h5>
        </div>
        @if ($typeData == "old" && $type == "UPDATE")
            <div class="col">
                <a href="{{ $viewDetailUrl }}" target="_blank" class="btn btn-secondary float-right">View Detail</a>
            </div>
        @endif
    </div>
    <hr class="divider">
    @foreach ($approval as $key => $item)
        @if(!in_array($key, $hidden))
        <div class="row mb-1">
            <div class="col-md-4 my-auto">
                @if (array_key_exists($key, $relations[$typeData] ?? []))
                    <label>{{ ucwords(str_replace('_', ' ', $relations[$typeData][$key]['label'] ?? '')) }}</label>
                @else
                    <label>{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                @endif
            </div>
            <div class="col">
                @if (array_key_exists($key, $views))
                    @if ($views[$key] == "image")
                        <img src="{{ $item['url'] }}" alt="{{ $key . '_image' }}" class="img">
                    @elseif ($views[$key] == "boolean")
                        @if ($item == 1)
                            <i class="icon-checkmark text-success"></i>
                        @else
                            <i class='icon-cross2 text-danger'></i>
                        @endif
                    @endif
                @else
                    @if (array_key_exists($key, $relations[$typeData] ?? []))
                        <input type="text" class="form-control input" value="{{ $relations[$typeData][$key]['name'] ?? ''}}" readonly>
                    @else
                        <input type="text" class="form-control input" value="{{ $item }}" readonly>
                    @endif
                @endif
            </div>
        </div>
        @endif
    @endforeach    
</div>

@push('styles')
<style>
    .bg-card {
        background-color: #202125;
    }

    .input {
        background-color: #2c2d33 !important;
    }

    .divider {
        border: 1px solid whitesmoke;
    }

    .img {
        max-width: 250px;
        max-height: 350px;
    }
</style>        
@endpush