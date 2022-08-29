@extends('../main')

@section('page', 'Catat Penerimaan')

@section('container')
    @if (session()->has('DanaSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('DanaSuccess') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    @if (session()->has('DanaError'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('DanaError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Catat Penerimaan</h3>
        </div>
        <form action="/penerimaan" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="kode_anggaran">Kode Anggaran</label>
                            <select class="form-control @error('kode_anggaran') is-invalid @enderror" id="kode_anggaran"
                                name="kode_anggaran" required>
                                <option value="">Pilih Kode Anggaran</option>
                                @foreach ($kodes as $kode)
                                    <option value="{{ $kode->id }}">
                                        4.{{ $kode->no_kode }} ({{ $kode->nama_kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_anggaran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="sub_kode_anggaran">Sub Kode Anggaran</label>
                            <select class="form-control @error('sub_kode_anggaran') is-invalid @enderror"
                                id="sub_kode_anggaran" name="sub_kode_anggaran" required>
                                <option value="">Pilih Sub Kode Anggaran</option>
                                @foreach ($sub_kodes as $sub_kode)
                                    <option value="{{ $sub_kode->id }}">
                                        4.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                        ({{ $sub_kode->nama_sub_kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_kode_anggaran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="sub_sub_kode_anggaran">Sub Sub-Kode Anggaran</label>
                            <select class="form-control @error('sub_sub_kode_anggaran') is-invalid @enderror"
                                id="sub_sub_kode_anggaran" name="sub_sub_kode_anggaran" required>
                                <option value="">Pilih Kode Anggaran</option>
                                @foreach ($sub_sub_kodes as $sub_sub_kode)
                                    <option value="{{ $sub_sub_kode->id }}">
                                        4.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        ({{ $sub_sub_kode->nama_sub_sub_kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_sub_kode_anggaran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                                name="tanggal" required>
                            @error('tanggal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" placeholder="Masukkan Keterangan">
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                name="nominal" placeholder="Masukkan Nominal">
                            @error('nominal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="jenis_transaksi">Jenis Transaksi</label>
                            <select class="form-control @error('jenis_transaksi') is-invalid @enderror" id="jenis_transaksi"
                                name="jenis_transaksi" onchange="val()" required>
                                <option value="">Pilih Jenis Transaksi</option>
                                <option value="Tunai/Cash">Tunai/Cash</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                            </select>
                            @error('jenis_transaksi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div id="pilih_bank" style="display: none;">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="akun_bank">Pilih Akun Bank</label>
                                <select class="form-control @error('akun_bank') is-invalid @enderror" id="akun_bank"
                                    name="akun_bank">
                                    <option value="">Pilih Akun Bank</option>
                                    @foreach ($akun_bank as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                    @endforeach
                                </select>
                                @error('akun_bank')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
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
                        <th>Tanggal</th>
                        <th>Kode Anggaran</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Jenis Transaksi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($danas as $dana)
                        @if ($dana->danaToKode->jenis_kode == 'Penerimaan')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dana->tanggal }}</td>
                                <td>
                                    4.{{ $dana->danaToKode->no_kode }}.{{ $dana->danaToSubKode->no_sub_kode }}.{{ $dana->danaToSubSubKode->no_sub_sub_kode }}
                                </td>
                                <td>{{ $dana->nominal }}</td>
                                <td>{{ $dana->keterangan }}</td>
                                <td>
                                    {{ $dana->transaksi }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-primary" href="/edit/penerimaan/{{ $dana->id }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/penerimaan/{{ $dana->id }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus penerimaan ini?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
<script>
    function val() {
        x = document.getElementById("jenis_transaksi").value;
        if (x == "Transfer Bank") {
            document.getElementById("pilih_bank").style.display = "block";
        } else {
            document.getElementById("pilih_bank").style.display = "none";
        }
    }
</script>