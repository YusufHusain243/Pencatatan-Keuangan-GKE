@extends('../main')

@section('page', 'Edit Pengeluaran')

@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Catat Pengeluaran</h3>
        </div>
        <form action="/pengeluaran/{{ $dana->id }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="kode_anggaran">Kode Anggaran</label>
                            <select class="form-control" id="kode_anggaran" name="kode_anggaran" required>
                                @foreach ($kodes as $kode)
                                    <option value="{{ $kode->id }}"
                                        {{ $dana->id_kode === $kode->id ? 'selected' : '' }}>
                                        5.{{ $kode->no_kode }} ({{ $kode->nama_kode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="sub_kode_anggaran">Sub Kode Anggaran</label>
                            <select class="form-control" id="sub_kode_anggaran" name="sub_kode_anggaran" required>
                                <option value="">Pilih Sub Kode Anggaran</option>
                                @foreach ($sub_kodes as $sub_kode)
                                    <option value="{{ $sub_kode->id }}"
                                        {{ $dana->id_sub_kode === $sub_kode->id ? 'selected' : '' }}>
                                        5.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                        ({{ $sub_kode->nama_sub_kode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="sub_sub_kode_anggaran">Sub Sub-Kode Anggaran</label>
                            <select class="form-control" id="sub_sub_kode_anggaran" name="sub_sub_kode_anggaran" required>
                                <option value="">Pilih Kode Anggaran</option>
                                @foreach ($sub_sub_kodes as $sub_sub_kode)
                                    <option value="{{ $sub_sub_kode->id }}"
                                        {{ $dana->id_sub_sub_kode === $sub_sub_kode->id ? 'selected' : '' }}>
                                        5.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        ({{ $sub_sub_kode->nama_sub_sub_kode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="{{ $dana->tanggal }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                value="{{ $dana->keterangan }}" placeholder="Masukkan Keterangan" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal"
                                value="{{ $dana->nominal }}" placeholder="Masukkan Nominal" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="jenis_transaksi">Jenis Transaksi</label>
                            <select class="form-control" id="jenis_transaksi" name="jenis_transaksi" onchange="val()"
                                required>
                                <option value="Tunai/Cash" {{ $dana->transaksi === 'Tunai/Cash' ? 'selected' : '' }}>
                                    Tunai/Cash</option>
                                <option value="Transfer Bank" {{ $dana->transaksi === 'Transfer Bank' ? 'selected' : '' }}>
                                    Transfer Bank</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if ($dana->transaksi == 'Transfer Bank')
                    <div id="pilih_bank" style="display: block;">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="akun_bank">Pilih Akun Bank</label>
                                    <select class="form-control" id="akun_bank" name="akun_bank">
                                        @foreach ($akun_bank as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div id="pilih_bank" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="akun_bank">Pilih Akun Bank</label>
                                    <select class="form-control" id="akun_bank" name="akun_bank">
                                        @foreach ($akun_bank as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
<script>
    function val() {
        x = document.getElementById("jenis_transaksi").value;
        console.log(x);
        if (x == "Transfer Bank") {
            document.getElementById("pilih_bank").style.display = "block";
        } else {
            document.getElementById("pilih_bank").style.display = "none";
        }
    }
</script>
