@extends('../main')

@section('page', 'Prediksi Penerimaan')

@section('container')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Penerimaan</th>
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
                                <td>{{ $data_forecasting->penerimaan }}</td>
                                <td>{{ $i }}</td>
                                <td>{{ $data_forecasting->penerimaan }}</td>
                                <td>{{ $i * $i }}</td>
                                <td>{{ $i * $data_forecasting->penerimaan }}</td>
                            </tr>
                            <?php
                            $x += $i;
                            $y += $data_forecasting->penerimaan;
                            $xx += $i * $i;
                            $xy += $i * $data_forecasting->penerimaan;
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
            <form action="/forecasting-penerimaan/prediksi" method="post">
                @csrf
                <input type="hidden" name="jenis"
                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt('penerimaan') ?? '' }}">
                <input type="hidden" name="x" value="{{ \Illuminate\Support\Facades\Crypt::encrypt($x) ?? '' }}">
                <input type="hidden" name="y" value="{{ \Illuminate\Support\Facades\Crypt::encrypt($y) ?? '' }}">
                <input type="hidden" name="xx" value="{{ \Illuminate\Support\Facades\Crypt::encrypt($xx) ?? '' }}">
                <input type="hidden" name="xy" value="{{ \Illuminate\Support\Facades\Crypt::encrypt($xy) ?? '' }}">
                <input type="hidden" name="avg_x"
                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($avg_x) ?? '' }}">
                <input type="hidden" name="avg_y"
                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($avg_y) ?? '' }}">
                <input type="hidden" name="n"
                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($i - 1) ?? '' }}">
                <input type="hidden" name="year"
                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($data_forecastings) ?? '' }}">
                <button type="submit" class="btn btn-sm btn-primary text-bold">Prediksi</button>
            </form>
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
                <div class="chart">
                    <canvas id="hasil_prediksi"
                        style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
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
                            backgroundColor: '#2196f3',
                        },
                        {
                            label: 'Y',
                            data: {!! $result_data !!},
                            backgroundColor: '#c23192',
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
                                stepSize: 1
                            }
                        }]
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
