@extends('../main')

@section('page', 'Forecasting')

@section('container')
    @if (session()->has('ForecastingSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('ForecastingSuccess') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    @if (session()->has('ForecastingError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('ForecastingError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Add Data</h3>
        </div>
        <form method="POST" action="/data-forecasting">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="tahun">Tahun <code>*</code></label>
                            <input type="text" minlength="4" maxlength="4" class="form-control @error('tahun') is-invalid @enderror" id="datepicker"
                                name="tahun" placeholder="Masukkan Tahun" required autocomplete="off" readonly>
                            @error('tahun')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="penerimaan">Penerimaan <code>*</code></label>
                            <input type="text" class="form-control @error('penerimaan') is-invalid @enderror"
                                id="penerimaan" name="penerimaan" placeholder="Masukkan Penerimaan" required>
                            @error('penerimaan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="pengeluaran">Pengeluaran <code>*</code></label>
                            <input type="text" class="form-control @error('pengeluaran') is-invalid @enderror"
                                id="pengeluaran" name="pengeluaran" placeholder="Masukkan Pengeluaran" required>
                            @error('pengeluaran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Penerimaan</th>
                        <th>Pengeluaran</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($forecastings as $forecasting)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $forecasting->tahun }}</td>
                            <td>{{ $forecasting->penerimaan }}</td>
                            <td>{{ $forecasting->pengeluaran }}</td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-primary" href="edit/data-forecasting/{{ $forecasting->id }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/data-forecasting/{{ $forecasting->id }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus data ini?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        $(function() {
            $('#datepicker').datepicker({
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy',
                onClose: function(dateText, inst) {
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, 1));
                }
            });
            $(".date-picker-year").focus(function() {
                $(".ui-datepicker-month").hide();
            });
        });
        $(function() {
            $("input[name='penerimaan']").on('input', function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
        });
        $(function() {
            $("input[name='pengeluaran']").on('input', function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
        });
    </script>
    <style>
        .ui-datepicker-calendar {
            display: none;
        }

        .ui-datepicker-month {
            display: none;
        }

        .ui-datepicker-next,
        .ui-datepicker-prev {
            display: none;
        }
    </style>
@endpush
