<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-5">
    <thead class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
        <tr>
            <th>Department</th>
            <th>Laptop</th>
            <th>System Unit</th>
            <th>Cellphone</th>
            <th>Printer</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody class="fw-semibold">
        @foreach ($report as $dept => $counts)
            <tr>
                <td>{{ strtoupper($dept) }}</td>
                <td>{{ $counts['Laptop'] }}</td>
                <td>{{ $counts['Desktop'] }}</td>
                <td>{{ $counts['Cellphone'] }}</td>
                <td>{{ $counts['Printer'] }}</td>
                <td><strong>{{ $counts['Total'] }}</strong></td>
            </tr>
        @endforeach

        <tr class="fw-bold text-dark border-top">
            <td>TOTAL</td>
            <td>{{ $totals['Laptop'] }}</td>
            <td>{{ $totals['Desktop'] }}</td>
            <td>{{ $totals['Cellphone'] }}</td>
            <td>{{ $totals['Printer'] }}</td>
            <td>{{ $totals['Total'] }}</td>
        </tr>
    </tbody>
</table>

</div>
