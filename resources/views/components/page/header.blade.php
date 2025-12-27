@props([
    'title' => 'Page Title',
])

<x-slot name="header">
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>{{ $title }}</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @if (isset($actions))
                    {{ $actions }}
                @endif
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    @if (isset($breadcrumb))
                        {{ $breadcrumb }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-slot>
