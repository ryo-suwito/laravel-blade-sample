<div>
    @if (! empty($item))
        <a target="_blank" href="{{ $url }}" class="form-control"
            style="color: #4881db;">
                {{ $item }}
        </a>
    @else
        <input type="text" name="{{ $name }}" class="form-control" value="" readonly>
    @endif   
</div>