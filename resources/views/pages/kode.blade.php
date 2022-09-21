@extends('../main')

@section('page', 'Daftar Kode')

@section('container')
    @if (session()->has('KodeSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('KodeSuccess') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    @if (session()->has('KodeError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('KodeError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Daftar Kode</h3>
        </div>
        <form action="/kode" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
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
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_kode">No Kode <code>*</code></label>
                            <input type="text" class="form-control @error('no_kode') is-invalid @enderror" id="no_kode"
                                name="no_kode" placeholder="Masukkan No Kode" required>
                            @error('no_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="nama_kode">Nama Kode <code>*</code></label>
                            <input type="text" class="form-control @error('nama_kode') is-invalid @enderror"
                                id="nama_kode" name="nama_kode" placeholder="Masukkan Nama Kode" required>
                            @error('nama_kode')
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
                <a class="dropdown-item" href="/kode/all">Semua</a>
                <a class="dropdown-item" href="/kode/penerimaan">Penerimaan</a>
                <a class="dropdown-item" href="/kode/pengeluaran">Pengeluaran</a>
            </div>
            <table id="example1" class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Kode</th>
                        <th>No Kode</th>
                        <th>Nama Kode</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kodes as $kode)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kode->jenis_kode }}</td>
                            <td>
                                @if ($kode->jenis_kode == 'Penerimaan')
                                    4.{{ $kode->no_kode }}
                                @else
                                    5.{{ $kode->no_kode }}
                                @endif
                            </td>
                            <td>{{ $kode->nama_kode }}</td>
                            <td>{{ $kode->updated_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-primary" href="/edit/kode/{{ Crypt::encrypt($kode->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/kode/{{ Crypt::encrypt($kode->id) }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus kode ini?');">
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
            $('#jenis_kode').change(function(e) {
                $('#no_kode').val('');
                if (e.target.value == 'Penerimaan') {
                    $('#no_kode').inputmask('4.9{1,}');
                } else if (e.target.value == 'Pengeluaran') {
                    $('#no_kode').inputmask('5.9{1,}');
                }
            });

            $('#no_kode').change(function(e) {
                var kode = $(this).val();
                kode = kode.split('.');
                if (kode[1] == 0 || kode[1] == 00 || kode[1] == 000) {
                    alert('kode tidak boleh 0');
                    $(this).val('')
                    if (kode[0] == 4) {
                        $(this).inputmask('4.9{1,}');
                    } else if (kode[0] == 5) {
                        $(this).inputmask('5.9{1,}');
                    }
                }
            })
        })
    </script>
@endpush
