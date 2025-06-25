<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-5">
    <thead class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
        <tr>
            <th>Month</th>
            <th>Laptop</th>
            <th>Desktop</th>
            <th>Cellphone</th>
            <th>Printer</th>
            <th>Total Quantity</th>
            <th>Total Value (₱)</th>
        </tr>
    </thead>
    <tbody class="fw-semibold">
        @if(!empty($report))
            @foreach ($report as $month => $data)
                <tr>
                    <td>{{ $month }}</td>
                    <td>{{ $data['Laptop'] }}</td>
                    <td>{{ $data['Desktop'] }}</td>
                    <td>{{ $data['Cellphone'] }}</td>
                    <td>{{ $data['Printer'] }}</td>
                    <td><strong>{{ $data['TotalQty'] }}</strong></td>
                    <td><strong>₱{{ number_format($data['TotalValue'], 2) }}</strong></td>
                </tr>
            @endforeach
            <tr class="fw-bold text-dark border-top">
                <td>Total Value (₱)</td>
                <td>₱{{ number_format($totalValuePerType['Laptop'], 2) }}</td>
                <td>₱{{ number_format($totalValuePerType['Desktop'], 2) }}</td>
                <td>₱{{ number_format($totalValuePerType['Cellphone'], 2) }}</td>
                <td>₱{{ number_format($totalValuePerType['Printer'], 2) }}</td>
                <td></td>
                <td>₱{{ number_format($totalValuePerType['GrandTotalValue'], 2) }}</td>
            </tr>
        @else
            <tr>
                <td colspan="7" class="text-center">No data available.</td>
            </tr>
        @endif
    </tbody>
</table>

</div>
