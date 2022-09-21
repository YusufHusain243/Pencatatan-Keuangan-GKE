@extends('../main')

@section('page', 'Prediksi Pengeluaran')

@section('container')
    @if (session()->has('ForecastingError'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('ForecastingError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Pengeluaran</th>
                        <th>X</th>
                        <th>Y</th>
                        <th>XX</th>
                        <th>XY</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data_forecastings) > 0)
                        <?php
                        $i = 1;
                        $x = 0;
                        $y = 0;
                        $xx = 0;
                        $xy = 0;
                        ?>
                        @foreach ($data_forecastings as $data_forecasting)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data_forecasting->tahun }}</td>
                                <td>{{ $data_forecasting->pengeluaran }}</td>
                                <td>{{ $i }}</td>
                                <td>{{ $data_forecasting->pengeluaran }}</td>
                                <td>{{ $i * $i }}</td>
                                <td>{{ $i * $data_forecasting->pengeluaran }}</td>
                            </tr>
                            <?php
                            $x += $i;
                            $y += $data_forecasting->pengeluaran;
                            $xx += $i * $i;
                            $xy += $i * $data_forecasting->pengeluaran;
                            $i++;
                            ?>
                        @endforeach
                        <tr>
                            <td class="text-center font-weight-bold" colspan="3">Jumlah</td>
                            <td class="font-weight-bold">{{ $x }}</td>
                            <td class="font-weight-bold">{{ $y }}</td>
                            <td class="font-weight-bold">{{ $xx }}</td>
                            <td class="font-weight-bold">{{ $xy }}</td>
                        </tr>
                        <tr>
                            <?php
                            $avg_x = round($x / ($i - 1), 2);
                            $avg_y = round($y / ($i - 1), 2);
                            $avg_xx = round($xx / ($i - 1), 2);
                            $avg_xy = round($xy / ($i - 1), 2);
                            ?>
                            <td class="text-center font-weight-bold" colspan="3">Rata Rata</td>
                            <td class="font-weight-bold">{{ $avg_x }}</td>
                            <td class="font-weight-bold">{{ $avg_y }}</td>
                            <td class="font-weight-bold">{{ $avg_xx }}</td>
                            <td class="font-weight-bold">{{ $avg_xy }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Data Kosong</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <br>
            @if (count($data_forecastings) > 0)
                <form action="/forecasting-pengeluaran/prediksi" method="post">
                    @csrf
                    <input type="hidden" name="jenis"
                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt('pengeluaran') ?? '' }}">
                    <input type="hidden" name="x"
                        value="{{ isset($x) ? \Illuminate\Support\Facades\Crypt::encrypt($x) : '' }}">
                    <input type="hidden" name="y"
                        value="{{ isset($y) ? \Illuminate\Support\Facades\Crypt::encrypt($y) : '' }}">
                    <input type="hidden" name="xx"
                        value="{{ isset($xx) ? \Illuminate\Support\Facades\Crypt::encrypt($xx) : '' }}">
                    <input type="hidden" name="xy"
                        value="{{ isset($xy) ? \Illuminate\Support\Facades\Crypt::encrypt($xy) : '' }}">
                    <input type="hidden" name="avg_x"
                        value="{{ isset($avg_x) ? \Illuminate\Support\Facades\Crypt::encrypt($avg_x) : '' }}">
                    <input type="hidden" name="avg_y"
                        value="{{ isset($avg_y) ? \Illuminate\Support\Facades\Crypt::encrypt($avg_y) : '' }}">
                    @php
                        $n = $i - 1;
                    @endphp
                    <input type="hidden" name="n"
                        value="{{ isset($n) ? \Illuminate\Support\Facades\Crypt::encrypt($n) : '' }}">
                    <input type="hidden" name="year"
                        value="{{ isset($data_forecastings) ? \Illuminate\Support\Facades\Crypt::encrypt($data_forecastings) : '' }}">
                    <button type="submit" class="btn btn-sm btn-primary text-bold">Prediksi</button>
                </form>
            @endif
        </div>
    </div>

    @if (isset($result_data))
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Hasil Prediksi</h3>
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
                <span>Rumus Regresi Linear : Y = {{ $a }} + {{ $b }} * X</span>
                <ul>
                    @foreach ($persen_arr as $item)
                        @if ($item['name'] == 'Tidak Ada Perubahan')
                            <li>PREDIKSI PERSENTASE PENERIMAAN PADA TAHUN {{ $item['tahun'] }} ADALAH
                                {{ strtoupper($item['name']) }}</li>
                        @else
                            <li>PREDIKSI PERSENTASE PENERIMAAN PADA TAHUN {{ $item['tahun'] }} ADALAH {{ $item['name'] }}
                                SEBESAR {{ $item['persen'] }} %</li>
                        @endif
                    @endforeach
                </ul>
                <div class="chart">
                    <canvas id="hasil_prediksi"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    @endif
@endsection

@if (isset($result_data))
    @push('after-script')
        <!-- ChartJS -->
        <script src="{{ asset('/plugins/chart.js/Chart.min.js') }}"></script>
        <script>
            var ctx = document.getElementById("hasil_prediksi");
            var scatterChart = new Chart(ctx, {
                type: 'scatter',
                data: {
                    datasets: [{
                            label: 'prediction(Y)',
                            data: {!! $result_data_prediction !!},
                            backgroundColor: '#118ab2',
                        },
                        {
                            label: 'Y',
                            data: {!! $result_data !!},
                            backgroundColor: '#06d6a0',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            display: true,
                            ticks: {
                                stepSize: 1,
                            }
                        }],
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return tooltipItem.yLabel;
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endif
