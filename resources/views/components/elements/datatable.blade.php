<div class="d-flex flex-column flex-xl-row p-7">
    <div class="flex-lg-row-fluid">
    <div class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div id="{{ $id }}_wrapper">
            <table
                class="table {{ $class }} fs-6 gy-5 no-footer"
                id="{{ $id }}_table"
                {{ $attributes->merge(['class' => 'table']) }}
            >
            </table>
        </div>
    </div>
    </div>
</div>
