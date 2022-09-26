@extends('../main')

@section('page', 'Daftar Sub Kode')

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
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
            <h3 class="card-title">Tambah Daftar Sub Kode</h3>
        </div>
        <form action="/sub-kode" id="my_form" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="jenis_kode">Jenis Kode <code>*</code></label>
                            <select class="form-control @error('jenis_kode') is-invalid @enderror" id="jenis_kode"
                                name="jenis_kode" required>
                                <option value="">Pilih Jenis Kode</option>
                                <option value="Penerimaan">Penerimaan</option>
                                <option value="Pengeluaran">Pengeluaran</option>
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
                                @foreach ($kodes as $kode)
                                    <option value="{{ $kode->id }}">
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
                                id="no_sub_kode" placeholder="Masukkan No Sub Kode" name="no_sub_kode" required>
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
                                id="nama_sub_kode" name="nama_sub_kode" placeholder="Masukkan Nama Sub Kode" required>
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

    <div class="card">
        <div class="card-body">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"
                aria-expanded="false">Filter</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="/sub-kode/all">Semua</a>
                <a class="dropdown-item" href="/sub-kode/penerimaan">Penerimaan</a>
                <a class="dropdown-item" href="/sub-kode/pengeluaran">Pengeluaran</a>
            </div>
            <table id="example1" class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Kode</th>
                        <th>No Sub Kode</th>
                        <th>Nama Sub Kode</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sub_kodes as $sub_kode)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($sub_kode->jenis_kode == 'Penerimaan')
                                    4.{{ $sub_kode->no_kode }}
                                @else
                                    5.{{ $sub_kode->no_kode }}
                                @endif
                            </td>
                            <td>
                                @if ($sub_kode->jenis_kode == 'Penerimaan')
                                    4.{{ $sub_kode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                @else
                                    5.{{ $sub_kode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                @endif
                            </td>
                            <td>{{ $sub_kode->nama_sub_kode }}</td>
                            <td>{{ $sub_kode->updated_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-primary" href="/edit/sub-kode/{{ Crypt::encrypt($sub_kode->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/sub-kode/{{ Crypt::encrypt($sub_kode->id) }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus sub kode ini?');">
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
        $(document).ready(function() {
            function makeOption(selector, val) {
                $(selector)
                    .append('<option value="" selected>Pilih No Kode</option>');
                $.each(val, function(i, value) {
                    $(selector)
                        .append('<option value="' + value[0] + '">' + value[1] + '</option>');
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
                    var PATTERN = /5{1}\./,
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#no_kode', filtered)
                }
            });

            $('#no_kode').change(function(e) {
                $('#no_sub_kode').val('');
                var no_kode = $('#no_kode option:selected').text();
                var split = no_kode.split('(');
                var nomor = split[0];
                nomor = nomor.replace(/\s/g, '');

                $('#no_sub_kode').inputmask(`${nomor}.9{1,}`);
            });

            $('#no_sub_kode').change(function(e) {
                var sub_kode = $(this).val();
                sub_kode = sub_kode.split('.');
                if (sub_kode[2] == 0 || sub_kode[2] == 00 || sub_kode[2] == 000) {
                    alert('sub_kode tidak boleh 0');
                    $(this).val('')
                    if (sub_kode[0] == 4) {
                        $(this).inputmask('4.9{1,}');
                    } else if (sub_kode[0] == 5) {
                        $(this).inputmask('5.9{1,}');
                    }
                }
            })
        });
    </script>
@endpush
