@extends('../main')

@section('page', 'Edit Sub Kode')

@section('container')
    @if (session()->has('SubKodeSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('SubKodeSuccess') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    @if (session()->has('SubKodeError'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('SubKodeError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Daftar Sub Kode</h3>
        </div>
        <form action="/sub-kode/{{ Crypt::encrypt($sub_kode->id) }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="jenis_kode">Jenis Kode <code>*</code></label>
                            <select class="form-control @error('jenis_kode') is-invalid @enderror" id="jenis_kode"
                                name="jenis_kode" required>
                                <option value="">Pilih Jenis Kode</option>
                                <option value="Penerimaan"
                                    {{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? ' selected' : '' }}>Penerimaan
                                </option>
                                <option value="Pengeluaran"
                                    {{ $sub_kode->subKodeToKode->jenis_kode == 'Pengeluaran' ? ' selected' : '' }}>
                                    Pengeluaran</option>
                            </select>
                            @error('jenis_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="no_kode">No Kode <code>*</code></label>
                            <select class="form-control @error('no_kode') is-invalid @enderror" id="no_kode"
                                name="no_kode" required>
                                <option value="">Pilih No Kode</option>
                                @php
                                    $kodeSelected = 0;
                                @endphp
                                @foreach ($kodes as $kode)
                                    @php
                                        if ($sub_kode->id_kode == $kode->id) {
                                            $kodeSelected = $kode->id;
                                        }
                                    @endphp
                                    <option value="{{ $kode->id }}"
                                        {{ $sub_kode->id_kode === $kode->id ? 'selected' : '' }}>
                                        @if ($kode->jenis_kode == 'Penerimaan')
                                            4.{{ $kode->no_kode }} ({{ $kode->nama_kode }})
                                        @else
                                            5.{{ $kode->no_kode }} ({{ $kode->nama_kode }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('no_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="no_sub_kode">No Sub Kode <code>*</code></label>
                            <input type="text" class="form-control @error('no_sub_kode') is-invalid @enderror"
                                id="no_sub_kode" placeholder="Masukkan No Sub Kode" name="no_sub_kode"
                                value="{{ $sub_kode->no_sub_kode }}" required>
                            @error('no_sub_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="nama_sub_kode">Nama Sub Kode <code>*</code></label>
                            <input type="text" class="form-control @error('nama_sub_kode') is-invalid @enderror"
                                id="nama_sub_kode" name="nama_sub_kode" value="{{ $sub_kode->nama_sub_kode }}"
                                placeholder="Masukkan Nama Sub Kode" required>
                            @error('nama_sub_kode')
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

@push('after-script')
    <script>
        $(document).ready(function() {
            function makeOption(selector, val, selected) {
                $(selector)
                    .append('<option value="" selected>Pilih No Kode</option>');
                $.each(val, function(i, value) {
                    if (value[0] == selected) {
                        $(selector)
                        .append('<option value="' + value[0] + '" selected>' + value[1] + '</option>');
                    } else {
                        $(selector)
                        .append('<option value="' + value[0] + '">' + value[1] + '</option>');
                    }
                });
            }
            var opts = $('#no_kode option');

            var myArray = [];

            var vals = [...opts]
                .map((val, index) => {
                    var text = val.textContent;
                    var value = val.value;
                    if (value) {
                        myArray[index] = [value, text];
                    }
                    return text;
                });

            var jenis_kode_awal = $('#jenis_kode').val();
            var kodeSelected = {{ $kodeSelected }};

            if (jenis_kode_awal == 'Penerimaan') {
                $("#no_kode option").remove();
                var PATTERN = /4{1}\./,
                    filtered = myArray.filter(function(str) {
                        return PATTERN.test(str);
                    });
                makeOption('#no_kode', filtered, kodeSelected)
            } else if (jenis_kode_awal == 'Pengeluaran') {
                $("#no_kode option").remove();
                var PATTERN = /5{1}\./,
                    filtered = myArray.filter(function(str) {
                        return PATTERN.test(str);
                    });
                makeOption('#no_kode', filtered, kodeSelected)
            }

            $('#jenis_kode').change(function(e) {
                $('#no_sub_kode').val('');
                if (e.target.value == 'Penerimaan') {
                    $("#no_kode option").remove();
                    var PATTERN = /4{1}\./,
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#no_kode', filtered)
                } else if (e.target.value == 'Pengeluaran') {
                    $("#no_kode option").remove();
                    var PATTERN = /4{1}\./,
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#no_kode', filtered)
                }
            });

            var no_kode = $('#no_kode option:selected').text();
            var split = no_kode.split('(');
            var nomor = split[0];
            nomor = nomor.replace(/\s/g, '');
            $('#no_sub_kode').inputmask(`${nomor}.99`);

            $('#no_kode').change(function(e) {
                $('#no_sub_kode').val('');
                var no_kode = $('#no_kode option:selected').text();
                var split = no_kode.split('(');
                var nomor = split[0];
                nomor = nomor.replace(/\s/g, '');

                $('#no_sub_kode').inputmask(`${nomor}.99`);
            });

            $('#no_sub_kode').change(function(e) {
                var sub_kode = $(this).val();
                sub_kode = sub_kode.split('.');
                if (sub_kode[2] == 0 || sub_kode[2] == 00 || sub_kode[2] == 000) {
                    alert('sub_kode tidak boleh 0');
                    $(this).val('')
                    if (sub_kode[0] == 4) {
                        $(this).inputmask('4.99');
                    } else if (sub_kode[0] == 5) {
                        $(this).inputmask('4.99');
                    }
                }
            })
        });
    </script>
@endpush
