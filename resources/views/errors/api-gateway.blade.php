<x-app-layout>
    <x-page.header :title="__('cms.System Error')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
        </x-slot>
    </x-page.header>
    
    <x-page.content :title="__('cms.System Error')">
        <p>We are very sorry but it seems our Service is currently Unavailable. Please try again later or contact our Customer Support.</p>
    
        @if (isset($response) && $response instanceof \Illuminate\Http\Client\Response)
            <div class="alert alert-danger alert-styled-left alert-dismissible mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h5 class="font-weight-semibold">{{ $response->status() }}</h5>
                {{ $response->json('status_message') ?? $response->getReasonPhrase() }}
            </div>
        @endif
    
        @if (isset($alert) && is_array($alert) && @$alert['title'] && @$alert['message'])
            <div class="alert alert-danger alert-styled-left alert-dismissible mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h5 class="font-weight-semibold">{{ $alert['title'] }}</h5>
                {{ $alert['message'] }}
            </div>
        @endif
    </x-page.content>
</x-app-layout>
