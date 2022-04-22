<div class="table-responsive overflow-visible">
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
