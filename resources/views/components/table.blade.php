<div class="table-responsive">
    <table class="table">
        @if(isset($heading))
        <thead>
            <tr>
                {{ $heading }}
            </tr>
        </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
