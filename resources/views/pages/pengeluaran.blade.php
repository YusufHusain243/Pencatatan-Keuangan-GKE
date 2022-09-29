@extends('../main')

@section('page', 'Catat Pengeluaran')

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
            <h3 class="card-title">Tambah Catat Pengeluaran</h3>
        </div>
        <form action="/pengeluaran" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="kode_anggaran">Kode Anggaran <code>*</code></label>
                            <select class="form-control @error('kode_anggaran') is-invalid @enderror" id="kode_anggaran"
                                name="kode_anggaran" required>
                                <option value="">Pilih Kode Anggaran</option>
                                @foreach ($kodes as $kode)
                                    <option value="{{ $kode->id }}">
                                        5.{{ $kode->no_kode }} ({{ $kode->nama_kode }})
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
                            <label for="sub_kode_anggaran">Sub Kode Anggaran <code>*</code></label>
                            <select class="form-control @error('sub_kode_anggaran') is-invalid @enderror"
                                id="sub_kode_anggaran" name="sub_kode_anggaran" required>
                                <option value="">Pilih Sub Kode Anggaran</option>
                                @foreach ($sub_kodes as $sub_kode)
                                    <option value="{{ $sub_kode->id }}">
                                        5.{{ $sub_kode->no_kode }}.{{ $sub_kode->no_sub_kode }}
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
                            <label for="sub_sub_kode_anggaran">Sub Sub-Kode Anggaran <code>*</code></label>
                            <select class="form-control @error('sub_sub_kode_anggaran') is-invalid @enderror"
                                id="sub_sub_kode_anggaran" name="sub_sub_kode_anggaran" required>
                                <option value="">Pilih Sub Sub Kode Anggaran</option>
                                @foreach ($sub_sub_kodes as $sub_sub_kode)
                                    <option value="{{ $sub_sub_kode->id }}">
                                        5.{{ $sub_sub_kode->no_kode }}.{{ $sub_sub_kode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
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
                            <label for="tanggal">Tanggal <code>*</code></label>
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
                            <label for="keterangan">Keterangan <code>*</code></label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required>
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
                            <label for="nominal">Nominal <code>*</code></label>
                            <input type="text" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                name="nominal" placeholder="Masukkan Nominal" required>
                            @error('nominal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="jenis_transaksi">Jenis Transaksi <code>*</code></label>
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
                                <label for="akun_bank">Pilih Akun Bank <code>*</code></label>
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
                        <div class="col-6">
                            <div class="form-group">
                                <label for="bukti_transfer">Bukti Transfer </label>
                                <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror"
                                    id="bukti_transfer" name="bukti_transfer" placeholder="Masukkan Bukti Transfer">
                                @error('bukti_transfer')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="form-group" id="containerPreview" style="display: none">
                                    <label for="bukti_transfer">Preview </label>
                                    <div>
                                        <img src="" id="preview" width="200" height="200"
                                            alt="">
                                    </div>
                                </div>
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
                        <th>Bukti Transfer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($danas as $dana)
                        @if ($dana->danaToKode->jenis_kode == 'Pengeluaran')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dana->tanggal }}</td>
                                <td>
                                    5.{{ $dana->danaToKode->no_kode }}
                                </td>
                                <td>Rp. @currency($dana->nominal)</td>
                                <td>{{ $dana->keterangan }}</td>
                                <td>
                                    {{ $dana->transaksi }}
                                </td>
                                <td>
                                    @if ($dana->transaksi == 'Transfer Bank')
                                        @if (strlen($dana->bukti_transfer) > 0)
                                            {{-- <img src="storage/images/{{ $dana->bukti_transfer }}" width="150"> --}}
                                            <a href="http://127.0.0.1:8000/storage/images/{{ $dana->bukti_transfer }}" target="_blank">Lihat
                                                Bukti</a>
                                        @else
                                            ---
                                        @endif
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-primary"
                                            href="/edit/pengeluaran/{{ Crypt::encrypt($dana->id) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/pengeluaran/{{ Crypt::encrypt($dana->id) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus pengeluaran ini?');">
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
@push('after-script')
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

        function val() {
            x = document.getElementById("jenis_transaksi").value;
            if (x == "Transfer Bank") {
                document.getElementById("pilih_bank").style.display = "block";
            } else {
                document.getElementById("pilih_bank").style.display = "none";
            }
        }

        $(document).ready(function() {
            $('#nominal').focusout(function(e) {
                $(this).val(formatRupiah(e.target.value, 'Rp. '));
            });
            $('#nominal').focus(function(e) {
                let text = e.target.value;
                text = text.replace(/\D/g, "");
                $(this).val(text);
            });

            function makeOption(selector, val) {
                $(selector)
                    .append('<option value="" selected>Pilih Sub Kode Anggaran</option>');
                $.each(val, function(i, value) {
                    $(selector)
                        .append('<option value="' + value[0] + '">' + value[1] + '</option>');
                });
            }

            function makeOptionSub(selector, val) {
                $(selector)
                    .append('<option value="" selected>Pilih Sub Sub Kode Anggaran</option>');
                $.each(val, function(i, value) {
                    $(selector)
                        .append('<option value="' + value[0] + '">' + value[1] + '</option>');
                });
            }

            var opts = $('#sub_kode_anggaran option');

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

            var optsSub = $('#sub_sub_kode_anggaran option');

            var myArraySub = [];

            var valsSub = [...optsSub]
                .map((val, index) => {
                    var text = val.textContent;
                    var value = val.value;
                    if (value) {
                        myArraySub[index] = [value, text];
                    }
                    return text;
                });

            $('#kode_anggaran').change(function(e) {
                $('#sub_kode_anggaran').val('');
                $('#sub_sub_kode_anggaran').val('');
                var kodeSelected = $('#kode_anggaran option:selected').text();
                kodeSelected = kodeSelected.replace(/\s/g, '');
                let kodeAwal = kodeSelected.slice(0, 3);
                let newStr = kodeAwal.replace(/\./gi, '\\.');
                if (kodeSelected.charAt(0) == 4) {
                    $("#sub_kode_anggaran option").remove();
                    var PATTERN = new RegExp(newStr + '{1}'),
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#sub_kode_anggaran', filtered)
                } else if (kodeSelected.charAt(0) == 5) {
                    $("#sub_kode_anggaran option").remove();
                    var PATTERN = new RegExp(newStr + '{1}'),
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#sub_kode_anggaran', filtered)
                }
            });

            $('#sub_kode_anggaran').change(function(e) {
                $('#sub_sub_kode_anggaran').val('');
                var subKodeSelected = $('#sub_kode_anggaran option:selected').text();
                subKodeSelected = subKodeSelected.replace(/\s/g, '');
                let subKodeAwal = subKodeSelected.substr(0, subKodeSelected.indexOf('('));;
                let newStr = subKodeAwal.replace(/\./gi, '\\.');
                var subKodeAnggaranSelected = $('#sub_kode_anggaran option:selected').text();
                subKodeAnggaranSelected = subKodeAnggaranSelected.replace(/\s/g, '');
                subKodeAnggaranSelected = subKodeAnggaranSelected.substr(0, subKodeAnggaranSelected.indexOf(
                    '('));
                if (subKodeAwal == subKodeAnggaranSelected) {
                    $("#sub_sub_kode_anggaran option").remove();
                    var PATTERN = new RegExp(newStr + '{1}'),
                        filtered = myArraySub.filter(function(str) {
                            return PATTERN.test(str);
                        });
                        console.log(newStr);
                    makeOptionSub('#sub_sub_kode_anggaran', filtered)
                } else if (subKodeAwal == subKodeAnggaranSelected) {
                    $("#sub_sub_kode_anggaran option").remove();
                    var PATTERN = new RegExp(newStr + '{1}'),
                        filtered = myArraySub.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOptionSub('#sub_sub_kode_anggaran', filtered)
                }
            });

            $('#bukti_transfer').change(function() {
                let bukti_transfer = $('#bukti_transfer');
                if (!bukti_transfer.files) {
                    $('#containerPreview').attr('style', 'display:none');
                }

                const file = this.files[0];
                if (file) {
                    $('#containerPreview').removeAttr("style").hide();
                    $("#containerPreview").show();
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#preview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
