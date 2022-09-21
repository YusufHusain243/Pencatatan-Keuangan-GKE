@extends('../main')

@section('page', 'Edit Data')

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
            <h3 class="card-title">Edit Data</h3>
        </div>
        <form method="POST" action="/data-forecasting/{{ Crypt::encrypt($forecasting->id) }}">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="tahun">Tahun <code>*</code></label>
                            <input type="text" minlength="4" maxlength="4"
                                class="form-control @error('tahun') is-invalid @enderror" id="datepicker" name="tahun"
                                placeholder="Masukkan Tahun" required autocomplete="off" value="{{ $forecasting->tahun }}">
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
                                id="penerimaan" name="penerimaan" placeholder="Masukkan Penerimaan"
                                value="{{ $forecasting->penerimaan }}" required>
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
                                id="pengeluaran" name="pengeluaran" placeholder="Masukkan Pengeluaran"
                                value="{{ $forecasting->pengeluaran }}" required>
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
@endsection

@push('after-style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
        rel="stylesheet" />
@endpush

@push('after-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
    <script>
        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
        $(function() {
            $("#datepicker").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                orientation: "bottom"
            });
        });
        if ($('#penerimaan').val() != '') {
            $('#penerimaan').val(formatRupiah($('#penerimaan').val(), 'Rp. '))
        }
        if ($('#pengeluaran').val() != '') {
            $('#pengeluaran').val(formatRupiah($('#pengeluaran').val(), 'Rp. '))
        }
        $('#penerimaan').keyup(function(e) {
            $(this).val(formatRupiah(e.target.value, 'Rp. '));
        });
        $('#pengeluaran').keyup(function(e) {
            $(this).val(formatRupiah(e.target.value, 'Rp. '));
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
@endpush
