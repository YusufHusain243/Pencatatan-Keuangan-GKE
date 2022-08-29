@extends('../main')

@section('page', 'Dashboard')

@section('container')
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Saldo Kas</h3>
                    <p>{{ $saldo_kas }}</p>
                </div>
                <a href="#" class="small-box-footer">
                    <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Saldo Bank</h3>
                    <p>{{ $saldo_bank }}</p>
                </div>
                <a href="#" class="small-box-footer">
                    <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Saldo Akhir</h3>
                    <p>{{ $saldo_akhir }}</p>
                </div>
                <a href="#" class="small-box-footer">
                    <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
