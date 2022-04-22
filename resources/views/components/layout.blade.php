<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<x-layout.head/>
<body>
<x-layout.nav/>



<!-- Page content-->
<div class="container mt-5">
    <livewire:toasts />
    {{ $slot }}
</div>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
