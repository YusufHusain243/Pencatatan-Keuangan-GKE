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
        <form action="/sub-sub-kode/{{ Crypt::encrypt($sub_sub_kode->id) }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="jenis_kode">Jenis Kode <code>*</code></label>
                            <select class="form-control @error('jenis_kode') is-invalid @enderror" id="jenis_kode"
                                name="jenis_kode" onchange="getSubKode(event)" required>
                                <option value="">Pilih Jenis Kode</option>
                                <option value="Penerimaan"
                                    {{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan' ? 'selected' : '' }}>
                                    Penerimaan</option>
                                <option value="Pengeluaran"
                                    {{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Pengeluaran' ? 'selected' : '' }}>
                                    Pengeluaran</option>
                            </select>
                            @error('jenis_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-3" id="sub_kode_anggaran_container">
                        <div class="form-group">
                            <label for="no_sub_kode">No Sub Kode <code>*</code></label>
                            <select class="form-control @error('no_sub_kode') is-invalid @enderror" id="no_sub_kode"
                                name="no_sub_kode" onchange="maskSubKode(event)" required>
                                <option value="">Pilih No Sub Kode</option>
                                @foreach ($sub_kodes as $sub_kode)
                                    <option value="{{ $sub_kode->id }}"
                                        data-type="{{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}"
                                        data-value="{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}"
                                        {{ $sub_sub_kode->id_sub_kode == $sub_kode->id ? 'selected' : '' }}>
                                        {{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                        ({{ $sub_kode->nama_sub_kode }})
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
                                value="{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}" required>
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
        function getSubKode(e) {
            var container = $('#sub_kode_anggaran_container');
            $.ajax({
                url: '/dropdowns/no-sub-kode-anggaran',
                type: 'POST',
                data: {
                    jenis_kode: e.target.value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    container.html(data);
                }
            })
        }

        function maskSubKode(e) {
            var data_value = e.target[e.target.selectedIndex].getAttribute("data-value").replace(/9/g, '\\9');
            $('#no_sub_sub_kode').val('');
            $('#no_sub_sub_kode').inputmask(
                `${e.target[e.target.selectedIndex].getAttribute("data-type")}.${data_value}.999`, {
                    "placeholder": "0"
                });
        }

        $('#no_sub_sub_kode').focusout(function() {
            var no_sub_sub_kode = $('#no_sub_sub_kode').val().split('.');
            if (no_sub_sub_kode[3] == 000) {
                alert('Nomor Sub Sub Kode tidak boleh diisi ' + no_sub_sub_kode[3]);
            }
        })
    </script>
@endpush
