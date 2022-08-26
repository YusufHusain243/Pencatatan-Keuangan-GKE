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
        <form action="/sub-kode/{{ $sub_kode->id }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_kode">No Kode</label>
                            <select class="form-control @error('no_kode') is-invalid @enderror" id="no_kode"
                                name="no_kode" required>
                                <option value="">Pilih No Kode</option>
                                @foreach ($kodes as $kode)
                                    <option value="{{ $kode->id }}"
                                        {{ $sub_kode->id_kode === $kode->id ? 'selected' : '' }}>
                                        @if ($kode->jenis_kode == 'Penerimaan')
                                            4.{{ $kode->no_kode }}
                                        @else
                                            5.{{ $kode->no_kode }}
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
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_sub_kode">No Sub Kode</label>
                            <input type="number" class="form-control @error('no_sub_kode') is-invalid @enderror"
                                id="no_sub_kode" placeholder="Masukkan No Sub Kode" name="no_sub_kode"
                                value="{{ substr($sub_kode->no_sub_kode, 0) }}" required>
                            @error('no_sub_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="nama_sub_kode">Nama Sub Kode</label>
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
