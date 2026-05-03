@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        አድዋ ባንክ - የአስተዳዳሪ ዳሽቦርድ
    </h2>
</div>

<!-- የባንኩ አጠቃላይ መረጃ ካርዶች -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-blue-600 p-6 rounded-lg shadow-md text-white">
        <h3 class="text-lg font-semibold">ጠቅላላ ደንበኞች</h3>
        <p class="text-3xl font-bold">{{ $totalUsers }}</p>
    </div>
    <div class="bg-green-600 p-6 rounded-lg shadow-md text-white">
        <h3 class="text-lg font-semibold">ጠቅላላ የተቀመጠ ገንዘብ</h3>
        <p class="text-3xl font-bold">{{ number_format($totalBalance, 2) }} ETB</p>
    </div>
    <div class="bg-purple-600 p-6 rounded-lg shadow-md text-white">
        <h3 class="text-lg font-semibold">የዛሬ ግብይቶች</h3>
        <p class="text-3xl font-bold">{{ $todayTransactionsCount }}</p>
    </div>
</div>

<!-- የገንዘብ እንቅስቃሴ ግራፍ -->
<div class="bg-white shadow-sm rounded-lg p-6 mb-6 border">
    <h3 class="text-lg font-bold mb-4">የሳምንቱ የገንዘብ እንቅስቃሴ (Deposit vs Withdraw)</h3>
    <div style="height: 300px;">
        <canvas id="transactionChart"></canvas>
    </div>
</div>

<!-- የቅርብ ጊዜ ግብይቶች ዝርዝር -->
<div class="bg-white shadow-sm rounded-lg p-6 border">
    <h3 class="text-lg font-bold mb-4">የቅርብ ጊዜ ግብይቶች</h3>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3 border">አካውንት</th>
                <th class="p-3 border">ዓይነት</th>
                <th class="p-3 border">መጠን (ETB)</th>
                <th class="p-3 border">ቀን</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentTransactions as $trx)
            <tr>
                <td class="p-3 border">{{ $trx->account_number }}</td>
                <td class="p-3 border">
                    <span class="px-2 py-1 rounded text-sm {{ $trx->type == 'Deposit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $trx->type }}
                    </span>
                </td>
                <td class="p-3 border font-bold">{{ number_format($trx->amount, 2) }}</td>
                <td class="p-3 border text-sm text-gray-500">{{ $trx->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('transactionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['ሰኞ', 'ማክሰኞ', 'ረቡዕ', 'ሐሙስ', 'አርብ', 'ቅዳሜ', 'እሁድ'],
            datasets: [{
                label: 'ገቢ (Deposit)',
                data: [5000, 15000, 8000, 25000, 12000, 30000, 20000],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'ወጪ (Withdraw)',
                data: [3000, 10000, 5000, 15000, 8000, 12000, 9000],
                borderColor: '#EF4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
