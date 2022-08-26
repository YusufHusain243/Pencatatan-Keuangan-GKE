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
        <form action="/kode/{{ $kode->id }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="jenis_kode">Jenis Kode</label>
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
                            <label for="no_kode">No Kode</label>
                            <input type="number" class="form-control @error('no_kode') is-invalid @enderror" id="no_kode"
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
                            <label for="nama_kode">Nama Kode</label>
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
