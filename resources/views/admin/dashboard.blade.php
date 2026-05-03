@extends('layouts.admin') {{-- ያንተ የላይ አውት ስም ይሄ ካልሆነ ቀይረው --}}

@section('content')
<div class="container-fluid px-4 py-5">
    <h2 class="mb-4" style="font-weight: bold; color: #2c3e50;">የአድዋ ባንክ አስተዳደር ዳሽቦርድ</h2>

    <!-- የስታቲስቲክስ ካርዶች -->
    <div class="row g-3 mb-5">
        <!-- ጠቅላላ ደንበኞች -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.8;">ጠቅላላ ደንበኞች</h6>
                            <h2 class="mb-0" style="font-weight: 700;">{{ number_format($totalUsers) }}</h2>
                        </div>
                        <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ጠቅላላ ተቀማጭ ብር -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #27ae60, #219150); color: white; border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.8;">ጠቅላላ ተቀማጭ ብር (Balance)</h6>
                            <h2 class="mb-0" style="font-weight: 700;">{{ number_format($totalBalance, 2) }} <small style="font-size: 1rem;">ETB</small></h2>
                        </div>
                        <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- የዛሬ እንቅስቃሴዎች -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #e67e22, #d35400); color: white; border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.8;">የዛሬ የገንዘብ እንቅስቃሴዎች</h6>
                            <h2 class="mb-0" style="font-weight: 700;">{{ $todayTransactionsCount }}</h2>
                        </div>
                        <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- የቅርብ ጊዜ እንቅስቃሴዎች ሰንጠረዥ (Table) -->
    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0" style="font-weight: 600; color: #2c3e50;">የቅርብ ጊዜ እንቅስቃሴዎች</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">አካውንት ቁጥር</th>
                            <th class="py-3">አይነት</th>
                            <th class="py-3 text-end">መጠን (Amount)</th>
                            <th class="py-3 px-4">ቀን</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $trx)
                        <tr>
                            <td class="px-4" style="font-weight: 500;">{{ $trx->account_number }}</td>
                            <td>
                                @if($trx->type == 'Deposit')
                                    <span class="badge bg-success-soft text-success" style="background: #e8f5e9; padding: 5px 10px; border-radius: 5px;">Deposit</span>
                                @elseif($trx->type == 'Withdraw')
                                    <span class="badge bg-danger-soft text-danger" style="background: #ffebee; padding: 5px 10px; border-radius: 5px;">Withdraw</span>
                                @else
                                    <span class="badge bg-info-soft text-info" style="background: #e1f5fe; padding: 5px 10px; border-radius: 5px;">{{ $trx->type }}</span>
                                @endif
                            </td>
                            <td class="text-end" style="font-weight: 600;">{{ number_format($trx->amount, 2) }} ETB</td>
                            <td class="px-4 text-muted small">{{ $trx->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">ምንም አይነት የቅርብ ጊዜ እንቅስቃሴ የለም።</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3 text-center">
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 20px; padding: 5px 20px;">ሁሉንም ዝርዝሮች እይ</a>
        </div>
    </div>
</div>

<style>
    /* ካርዶቹ ላይ Hover effect እንዲኖራቸው */
    .card { transition: transform 0.3s ease; }
    .card:hover { transform: translateY(-5px); }
</style>
@endsection
