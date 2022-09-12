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
                        $avg_x = $x / ($i - 1);
                        $avg_y = $y / ($i - 1);
                        $avg_xx = $xx / ($i - 1);
                        $avg_xy = $xy / ($i - 1);
                        ?>
                        <td class="text-center font-weight-bold" colspan="3">Rata Rata</td>
                        <td class="font-weight-bold">{{ $avg_x }}</td>
                        <td class="font-weight-bold">{{ $avg_y }}</td>
                        <td class="font-weight-bold">{{ $avg_xx }}</td>
                        <td class="font-weight-bold">{{ $avg_xy }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <form action="/forecasting-penerimaan/prediksi" method="post">
                @csrf
                <input type="hidden" name="jenis" value="penerimaan">
                <input type="hidden" name="x" value="{{ $x }}">
                <input type="hidden" name="y" value="{{ $y }}">
                <input type="hidden" name="xx" value="{{ $xx }}">
                <input type="hidden" name="xy" value="{{ $xy }}">
                <input type="hidden" name="n" value="{{ $i - 1 }}">
                <span>Prediksi Penerimaan Untuk</span>
                <select name="tahun_prediksi" id="tahun_prediksi">
                    <option value="1" @if (isset($tahun)) {{ $tahun === '1' ? 'selected' : '' }} @endif>
                        1
                    </option>
                    <option value="2" @if (isset($tahun)) {{ $tahun === '2' ? 'selected' : '' }} @endif>
                        2
                    </option>
                    <option value="3" @if (isset($tahun)) {{ $tahun === '3' ? 'selected' : '' }} @endif>
                        3
                    </option>
                    <option value="4" @if (isset($tahun)) {{ $tahun === '4' ? 'selected' : '' }} @endif>
                        4
                    </option>
                    <option value="5" @if (isset($tahun)) {{ $tahun === '5' ? 'selected' : '' }} @endif>
                        5
                    </option>
                    <option value="6" @if (isset($tahun)) {{ $tahun === '6' ? 'selected' : '' }} @endif>
                        6
                    </option>
                    <option value="7" @if (isset($tahun)) {{ $tahun === '7' ? 'selected' : '' }} @endif>
                        7
                    </option>
                    <option value="8" @if (isset($tahun)) {{ $tahun === '8' ? 'selected' : '' }} @endif>
                        8
                    </option>
                    <option value="9" @if (isset($tahun)) {{ $tahun === '9' ? 'selected' : '' }} @endif>
                        9
                    </option>
                    <option value="10"
                        @if (isset($tahun)) {{ $tahun === '10' ? 'selected' : '' }} @endif>
                        10
                    </option>
                </select>
                <span>Tahun Kedepan</span>
                <button type="submit" class="btn btn-sm btn-primary text-bold">Prediksi</button>
                <br>
            </form>
            @if (isset($result))
                <span>Rumus Regresi Linear : y = {{ $a }} + {{ $b }} * {{ $tahun }}</span>
                <br>
                <span> Prediksi Penerimaan Untuk {{ $tahun }} Tahun Kedepan Adalah = {{ $result }}</span>
            @endif
        </div>
    </div>
@endsection
