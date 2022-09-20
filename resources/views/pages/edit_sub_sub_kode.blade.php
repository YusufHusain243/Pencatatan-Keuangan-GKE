@extends('../main')

@section('page', 'Edit Sub Sub-Kode')

@section('container')
    @if (session()->has('SubSubKodeSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('SubSubKodeSuccess') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    @if (session()->has('SubSubKodeError'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('SubSubKodeError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Daftar Sub Sub-Kode</h3>
        </div>
        <form action="/sub-sub-kode/{{ $sub_sub_kode->id }}" method="POST">
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
                                    {{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan' ? ' selected' : '' }}>
                                    Penerimaan
                                </option>
                                <option value="Pengeluaran"
                                    {{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Pengeluaran' ? ' selected' : '' }}>
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
                            <label for="no_sub_kode">No Sub Kode</label>
                            <select class="form-control @error('no_sub_kode') is-invalid @enderror" id="no_sub_kode"
                                name="no_sub_kode" required>
                                <option value="">Pilih No Sub Kode</option>
                                @php
                                    $noSubKodeSelected = 0;
                                @endphp
                                @foreach ($sub_kodes as $sub_kode)
                                    @php
                                        if ($sub_sub_kode->id_sub_kode === $sub_kode->id) {
                                            $noSubKodeSelected = $sub_kode->id;
                                        }
                                    @endphp
                                    <option value="{{ $sub_kode->id }}"
                                        {{ $sub_sub_kode->id_sub_kode === $sub_kode->id ? 'selected' : '' }}>
                                        @if ($sub_kode->subKodeToKode->jenis_kode == 'Penerimaan')
                                            4.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                            ({{ $sub_kode->nama_sub_kode }})
                                        @else
                                            5.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                            ({{ $sub_kode->nama_sub_kode }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('no_sub_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="no_sub_sub_kode">No Sub Sub-Kode</label>
                            <input type="text" class="form-control @error('no_sub_sub_kode') is-invalid @enderror"
                                id="no_sub_sub_kode" name="no_sub_sub_kode" placeholder="Masukkan No Sub Sub-Kode"
                                value="{{ $sub_sub_kode->no_sub_sub_kode }}" required>
                            @error('no_sub_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="nama_sub_sub_kode">Nama Sub Sub-Kode</label>
                            <input type="text" class="form-control @error('nama_sub_sub_kode') is-invalid @enderror"
                                id="nama_sub_sub_kode" name="nama_sub_sub_kode" placeholder="Masukkan Nama Sub Sub-Kode"
                                value="{{ $sub_sub_kode->nama_sub_sub_kode }}">
                            @error('nama_sub_sub_kode')
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
            function makeOption(selector, val) {
                $(selector)
                    .append('<option value="" selected>Pilih No Sub Kode</option>');
                $.each(val, function(i, value) {
                    $(selector)
                        .append('<option value="' + value[0] + '">' + value[1] + '</option>');
                });
            }
            var opts = $('#no_sub_kode option');

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
            var noSubKodeSelected = {{ $noSubKodeSelected }};

            if (jenis_kode_awal == 'Penerimaan') {
                $("#no_sub_kode option").remove();
                var PATTERN = /4\..*\..*\(*[^<]*/,
                    filtered = myArray.filter(function(str) {
                        return PATTERN.test(str);
                    });
                makeOption('#no_sub_kode', filtered)
                $('#no_sub_kode option').val(noSubKodeSelected).attr('selected', true);
            } else if (jenis_kode_awal == 'Pengeluaran') {
                $("#no_sub_kode option").remove();
                var PATTERN = /5\..*\..*\(*[^<]*/,
                    filtered = myArray.filter(function(str) {
                        return PATTERN.test(str);
                    });
                makeOption('#no_sub_kode', filtered)
                $('#no_sub_kode option').val(noSubKodeSelected).attr('selected', true);
            }

            $('#jenis_kode').change(function(e) {
                $('#no_sub_kode').val('');
                $('#no_sub_sub_kode').val('');
                if (e.target.value == 'Penerimaan') {
                    $("#no_sub_kode option").remove();
                    var PATTERN = /4\..*\..*\(*[^<]*/,
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#no_sub_kode', filtered)
                } else if (e.target.value == 'Pengeluaran') {
                    $("#no_sub_kode option").remove();
                    var PATTERN = /5\..*\..*\(*[^<]*/,
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#no_sub_kode', filtered)
                }
            });

            $('#no_sub_kode').change(function(e) {
                $('#no_sub_sub_kode').val('');
                var no_kode = $('#no_sub_kode option:selected').text();
                var split = no_kode.split('(');
                var nomor = split[0];
                nomor = nomor.replace(/\s/g, '');

                $('#no_sub_sub_kode').inputmask(`${nomor}.9{1,}`);
            });

            var no_sub_kode = $('#no_sub_kode option:selected').text();
            var split = no_sub_kode.split('(');
            var nomor = split[0];
            nomor = nomor.replace(/\s/g, '');
            $('#no_sub_sub_kode').inputmask(`${nomor}.9{1,}`);

            $('#no_sub_sub_kode').change(function(e) {
                var sub_sub_kode = $(this).val();
                sub_sub_kode = sub_sub_kode.split('.');
                if (sub_sub_kode[3] == 0 || sub_sub_kode[3] == 00 || sub_sub_kode[3] == 000) {
                    alert('sub_sub_kode tidak boleh 0');
                    $(this).val('')
                    if (sub_sub_kode[0] == 4) {
                        $(this).inputmask('4.9{1,}');
                    } else if (sub_sub_kode[0] == 5) {
                        $(this).inputmask('5.9{1,}');
                    }
                }
            })
        });
    </script>
@endpush
