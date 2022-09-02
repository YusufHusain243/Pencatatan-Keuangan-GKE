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
        <form action="/sub-sub-kode/{{ $sub_sub_kode->id }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_sub_kode">No Sub Kode</label>
                            <select class="form-control @error('no_sub_kode') is-invalid @enderror" id="no_sub_kode"
                                name="no_sub_kode" required>
                                <option value="">Pilih No Sub Kode</option>
                                @foreach ($sub_kodes as $sub_kode)
                                    <option value="{{ $sub_kode->id }}"
                                        {{ $sub_sub_kode->id_sub_kode === $sub_kode->id ? 'selected' : '' }}>
                                        @if ($sub_kode->subKodeToKode->jenis_kode == 'Penerimaan')
                                            4.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                            ({{ $sub_kode->nama_sub_kode }})
                                        @else
                                            5.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                            ({{ $sub_kode->nama_sub_kode }})
                                        @endif
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
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_sub_sub_kode">No Sub Sub-Kode</label>
                            <input type="number" class="form-control @error('no_sub_sub_kode') is-invalid @enderror"
                                id="no_sub_sub_kode" name="no_sub_sub_kode" placeholder="Masukkan No Sub Sub-Kode"
                                value="{{ $sub_sub_kode->no_sub_sub_kode }}" required>
                            @error('no_sub_kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
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
