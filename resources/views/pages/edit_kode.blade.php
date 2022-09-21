@extends('../main')

@section('page', 'Edit Kode')

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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
            <h3 class="card-title">Edit Daftar Kode</h3>
        </div>
        <form action="/kode/{{ Crypt::encrypt($kode->id) }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="jenis_kode">Jenis Kode <code>*</code></label>
                            <select class="form-control @error('jenis_kode') is-invalid @enderror" id="jenis_kode"
                                name="jenis_kode" required>
                                <option value="Penerimaan" {{ $kode->jenis_kode === 'Penerimaan' ? 'selected' : '' }}>
                                    Penerimaan</option>
                                <option value="Pengeluaran" {{ $kode->jenis_kode === 'Pengeluaran' ? 'selected' : '' }}>
                                    Pengeluaran</option>
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
                                name="no_kode" placeholder="Masukkan No Kode" value="{{ $kode->no_kode }}" required>
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
                                id="nama_kode" name="nama_kode" placeholder="Masukkan Nama Kode"
                                value="{{ $kode->nama_kode }}" required>
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
@endsection

@push('after-script')
    <script>
        $(document).ready(function() {
            var jenis_kode = $('#jenis_kode').val();
            if (jenis_kode == 'Penerimaan') {
                $('#no_kode').inputmask('4.9{1,}');
            } else if (jenis_kode == 'Pengeluaran') {
                $('#no_kode').inputmask('5.9{1,}');
            }

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
