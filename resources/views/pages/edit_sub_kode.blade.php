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
                                name="jenis_kode" onchange="getKode(event)" required>
                                <option value="">Pilih Jenis Kode</option>
                                <option value="Penerimaan"
                                    {{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? 'selected' : '' }}>Penerimaan
                                </option>
                                <option value="Pengeluaran"
                                    {{ $sub_kode->subKodeToKode->jenis_kode == 'Pengeluaran' ? 'selected' : '' }}>
                                    Pengeluaran</option>
                            </select>
                            @error('jenis_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-3" id="kode_anggaran_container">
                        <div class="form-group">
                            <label for="no_kode">No Kode <code>*</code></label>
                            <select class="form-control @error('no_kode') is-invalid @enderror" id="no_kode"
                                name="no_kode" onchange="maskSubKode(event)" required>
                                <option value="">Pilih No Kode</option>
                                @foreach ($kodes as $kode)
                                    <option value="{{ $kode->id }}"
                                        data-type="{{ $kode->jenis_kode == 'Penerimaan' ? 4 : 5 }}"
                                        data-value="{{ $kode->no_kode }}"
                                        {{ $sub_kode->id_kode == $kode->id ? 'selected' : '' }}>
                                        {{ $kode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $kode->no_kode }}
                                        ({{ $kode->nama_kode }})
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
                                value="{{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}"
                                required>
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
        function getKode(e) {
            var container = $('#kode_anggaran_container');
            $.ajax({
                url: '/dropdowns/kode-anggaran',
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
            $('#no_sub_kode').val('');
            $('#no_sub_kode').inputmask(
                `${e.target[e.target.selectedIndex].getAttribute("data-type")}.${e.target[e.target.selectedIndex].getAttribute("data-value")}.99`, {
                    "placeholder": ""
                });
        }
    </script>
@endpush
