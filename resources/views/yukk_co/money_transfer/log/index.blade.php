<x-app-layout>
    @if (Route::currentRouteName() == 'money_transfer.transfer_proxies.logs')
        <x-page.header 
            :title="$type == 'error' ? __('cms.Transfer Proxy Error Log') : __('cms.Transfer Proxy Log')">
            <x-slot name="breadcrumb">
                <x-breadcrumb.home />
                <x-breadcrumb.link :link="route('money_transfer.transfer_proxies.show', ['code' => $code])" :text="__('cms.Proxy')"/>
                <x-breadcrumb.active>
                    @php
                        echo $type == 'error' ? __('cms.Transfer Proxy Error Log') : __('cms.Transfer Proxy Log');
                    @endphp
                </x-breadcrumb.active>
            </x-slot>
        </x-page.header>
    @else
        <x-page.header 
            :title="$type == 'error' ? __('cms.Transaction Item Error Log') : __('cms.Transaction Item Log')">
            <x-slot name="breadcrumb">
                <x-breadcrumb.home />
                <x-breadcrumb.link :link="route('money_transfer.transactions.items.show', ['code' => $code])" :text="__('cms.Transaction Item')"/>
                <x-breadcrumb.active>
                @php
                    echo $type == 'error' ? __('cms.Transaction Item Error Log') : __('cms.Transaction Item Log');
                @endphp
                </x-breadcrumb.active>
            </x-slot>
        </x-page.header>
    @endif

    <x-page.content>
        <p>Code: {{ $code }}</p>

        <div class="accordion" id="accordionLog">
            @foreach ($logs as $log)
            <div class="card">
                <div class="card-header" id="{{ $log['_id'] }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse-{{ $log['_id'] }}" aria-expanded="false" aria-controls="collapse-{{ $log['_id'] }}">
                    {{ $log['_source']['message'] .' '. $log['_source']['datetime'] }}
                    </button>
                </h2>
                </div>

                <div id="collapse-{{ $log['_id'] }}" class="collapse" aria-labelledby="{{ $log['_id'] }}" data-parent="#accordionLog">
                <div class="card-body position-relative">
                    <button class="btn btn-outline-primary btn-sm position-absolute pt-10" style="right: 40px; top: 15px;" id="btn-copy" data-id="{{ $log['_id'] }}">copy</button>
                    <textarea id="log-{{ $log['_id'] }}" class="form-control json" cols="30" rows="10" readonly >{{ json_encode($log['_source']['context']) }}</textarea>
                </div>
                </div>
            </div>
            @endforeach
            @if (count($logs) == 0)
            <div class="card">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-center" type="button">
                        Log is not found
                    </button>
                </h2>
            </div>
            @endif
        </div>
    </x-page.content>

    @push('styles')
        <style>
            .accordion .card {
                background-color: #1d1e21;
            }

            .accordion .card .card-body {
                background-color: #43464e;
                padding-top: 10px !important;
            }

            .card-header .btn-link{
                color: #e80ecb !important;
            }
        </style>
    @endpush

    @swal

    @push('scripts')
        <script>
            $('.json').each(function(i) {
                $(this).val(JSON.stringify(JSON.parse($(this).val()), null, 2));
            });

            $('#btn-copy').click(function(e) {
                let textToCopy = $('#log-'+$(this).data('id')).val();

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy);
                } else {
                    // Use the 'out of viewport hidden text area' trick
                    const textArea = document.createElement("textarea");
                    textArea.value = textToCopy;
                        
                    // Move textarea out of the viewport so it's not visible
                    textArea.style.position = "absolute";
                    textArea.style.left = "-999999px";
                        
                    document.body.prepend(textArea);
                    textArea.select();

                    try {
                        document.execCommand('copy');
                    } catch (error) {
                        console.error(error);
                    } finally {
                        textArea.remove();
                    }
                }

                Swal.fire({
                    'text': "Success copy to clipboard!",
                    'icon': 'success',
                    'toast': true,
                    'timer': 3000,
                    'showConfirmButton': false,
                    'position': 'top-right',
                });
            });
        </script>
    @endpush
</x-app-layout>