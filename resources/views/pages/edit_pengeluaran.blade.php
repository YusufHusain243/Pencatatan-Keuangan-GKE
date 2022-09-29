@extends('../main')

@section('page', 'Edit Pengeluaran')

@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Catat Pengeluaran</h3>
        </div>
        <form action="/pengeluaran/{{ Crypt::encrypt($dana->id) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="kode_anggaran">Kode Anggaran <code>*</code></label>
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
                            <label for="sub_kode_anggaran">Sub Kode Anggaran <code>*</code></label>
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
                            <label for="sub_sub_kode_anggaran">Sub Sub-Kode Anggaran <code>*</code></label>
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
                            <label for="tanggal">Tanggal <code>*</code></label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="{{ $dana->tanggal }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="keterangan">Keterangan <code>*</code></label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                value="{{ $dana->keterangan }}" placeholder="Masukkan Keterangan" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nominal">Nominal <code>*</code></label>
                            <input type="text" class="form-control" id="nominal" name="nominal"
                                value="{{ $dana->nominal }}" placeholder="Masukkan Nominal" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="jenis_transaksi">Jenis Transaksi <code>*</code></label>
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
                                    <label for="akun_bank">Pilih Akun Bank <code>*</code></label>
                                    <select class="form-control" id="akun_bank" name="akun_bank">
                                        @foreach ($akun_bank as $bank)
                                            @php
                                                $detailBankId = isset($dana->danaToDetailBank) ? $dana->danaToDetailBank->id_bank : '';
                                            @endphp
                                            @if ($dana->detailBankId == $bank->id)
                                                <option value="{{ $bank->id }}" selected>{{ $bank->nama_bank }}
                                                </option>
                                            @else
                                                <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                            @endif
                                        @endforeach
                                    </select>
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group" id="containerPreview">
                                    <label for="bukti_transfer">Preview </label>
                                    <div>
                                        <img src="/storage/images/{{ $dana->bukti_transfer }}" style="display: none"
                                            id="preview" width="200" height="200" alt="">
                                    </div>
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
                                            @php
                                                $detailBankId = isset($dana->danaToDetailBank) ? $dana->danaToDetailBank->id_bank : '';
                                            @endphp
                                            @if ($dana->detailBankId == $bank->id)
                                                <option value="{{ $bank->id }}" selected>{{ $bank->nama_bank }}
                                                </option>
                                            @else
                                                <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bukti_transfer">Bukti Transfer </label>
                                    <input type="file"
                                        class="form-control @error('bukti_transfer') is-invalid @enderror"
                                        id="bukti_transfer" name="bukti_transfer" value="{{ $dana->bukti_transfer }}"
                                        placeholder="Masukkan Bukti Transfer">
                                    @error('bukti_transfer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
            $('#nominal').val(formatRupiah($('#nominal').val(), 'Rp. '));
            $('#nominal').on('input', function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
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
                    var PATTERN = new RegExp(newStr + '.*\\(*[^<]*'),
                        filtered = myArray.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOption('#sub_kode_anggaran', filtered)
                } else if (kodeAwal == 5) {
                    $("#kode_anggaran option").remove();
                    var PATTERN = new RegExp(newStr + '.*\\(*[^<]*'),
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
                let subKodeAwal = subKodeSelected.slice(0, 5);
                let newStr = subKodeAwal.replace(/\./gi, '\\.');
                var subKodeAnggaranSelected = $('#sub_kode_anggaran option:selected').text();
                subKodeAnggaranSelected = subKodeAnggaranSelected.replace(/\s/g, '');
                subKodeAnggaranSelected = subKodeAnggaranSelected.slice(0, 5);
                if (subKodeAwal == subKodeAnggaranSelected) {
                    $("#sub_sub_kode_anggaran option").remove();
                    var PATTERN = new RegExp(newStr + '.*\\(*[^<]*'),
                        filtered = myArraySub.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOptionSub('#sub_sub_kode_anggaran', filtered)
                } else if (subKodeAwal == subKodeAnggaranSelected) {
                    $("#sub_sub_kode_anggaran option").remove();
                    var PATTERN = new RegExp(newStr + '.*\\(*[^<]*'),
                        filtered = myArraySub.filter(function(str) {
                            return PATTERN.test(str);
                        });
                    makeOptionSub('#sub_sub_kode_anggaran', filtered)
                }
            });

            let preview = "{{ $dana->bukti_transfer }}";
                if (preview != '') {
                    $('#containerPreview').removeAttr('style');
                    $('#containerPreview').attr('style', 'display:block');
                    $('#preview').removeAttr('style');
                    $('#preview').attr('style', 'display:block');
                }

            $('#bukti_transfer').change(function() {
                let bukti_transfer = $('#bukti_transfer');
                if (!bukti_transfer.files) {
                    $('#containerPreview').attr('style', 'display:none');
                    $('#preview').attr('style', 'display:none');
                }

                const file = this.files[0];
                if (file) {
                    $('#containerPreview').attr('style', 'display:block');
                    $('#preview').attr('style', 'display:block');
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
