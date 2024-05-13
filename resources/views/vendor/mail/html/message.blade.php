@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => env('APP_FRONT_URL')])
Centro de Vinculación Andares
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} Centro de Vinculación Andares. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
