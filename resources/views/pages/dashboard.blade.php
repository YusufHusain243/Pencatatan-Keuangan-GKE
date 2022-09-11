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
                <div class="small-box-footer"></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Saldo Bank</h3>
                    <p>{{ $saldo_bank }}</p>
                </div>
                <div class="small-box-footer"></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Saldo Akhir</h3>
                    <p>{{ $saldo_akhir }}</p>
                </div>
                <div class="small-box-footer"></div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Bulanan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="grafikBulanan"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Tahunan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="grafikTahunan"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Prediksi</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="grafikPrediksi"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery -->
    <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('/plugins/chart.js/Chart.min.js') }}"></script>

    <script>
        $(function() {
            var grafik = ['#grafikBulanan', '#grafikTahunan', '#grafikPrediksi']

            for (let i = 0; i < grafik.length; i++) {
                if (grafik[i] == '#grafikBulanan') {
                    var areaChartData = {
                        labels: [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember',
                        ],
                        datasets: [{
                                label: 'Penerimaan',
                                backgroundColor: 'rgb(60, 179, 113)',
                                data: {!! $data_bulan_penerimaan !!},
                            },
                            {
                                label: 'Pengeluaran',
                                backgroundColor: 'rgb(255, 75, 67)',
                                data: {!! $data_bulan_pengeluaran !!},
                            },
                        ],
                    }
                }
                if (grafik[i] == '#grafikTahunan') {
                    var areaChartData = {
                        labels: {!! $data_tahun !!},
                        datasets: [{
                                label: 'Penerimaan',
                                backgroundColor: 'rgb(60, 179, 113)',
                                data: {!! $data_tahun_penerimaan !!}
                            },
                            {
                                label: 'Pengeluaran',
                                backgroundColor: 'rgb(255, 75, 67)',
                                data: {!! $data_tahun_pengeluaran !!}
                            },
                        ],
                    }
                }
                if (grafik[i] == '#grafikPrediksi') {
                    var areaChartData = {
                        labels: [
                            '2019',
                            '2020',
                            '2021',
                            '2022',
                        ],
                        datasets: [{
                                label: 'Penerimaan',
                                backgroundColor: 'rgb(60, 179, 113)',
                                data: [28, 48, 40, 19]
                            },
                            {
                                label: 'Pengeluaran',
                                backgroundColor: 'rgb(255, 75, 67)',
                                data: [65, 59, 80, 81]
                            },
                        ],
                    }
                }
                var barChartCanvas = $(grafik[i]).get(0).getContext('2d')
                var barChartData = $.extend(true, {}, areaChartData)
                var temp0 = areaChartData.datasets[0]
                var temp1 = areaChartData.datasets[1]
                barChartData.datasets[0] = temp1
                barChartData.datasets[1] = temp0

                var barChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    datasetFill: false
                }

                new Chart(barChartCanvas, {
                    type: 'bar',
                    data: barChartData,
                    options: barChartOptions
                })
            }
        })
    </script>
@endsection
