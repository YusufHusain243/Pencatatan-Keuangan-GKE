@extends('../main')

@section('page', 'Akun Bank')

@section('container')
    @if (session()->has('AkunBankSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('AkunBankSuccess') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    @if (session()->has('AkunBankError'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('AkunBankError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Akun Bank</h3>
        </div>
        <form action="/akun-bank" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nama_bank">Nama Bank</label>
                            <input type="text" class="form-control @error('nama_bank') is-invalid @enderror"
                                id="nama_bank" name="nama_bank" placeholder="Masukkan Nama Bank" required>
                            @error('nama_bank')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="no_rek">No Rekening</label>
                            <input type="number" class="form-control @error('no_rek') is-invalid @enderror" id="no_rek"
                                name="no_rek" placeholder="Masukkan No Rekening" required>
                            @error('no_rek')
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

    <div class="card">
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Bank</th>
                        <th>No Rekening</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($akun_banks as $akun_bank)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $akun_bank->nama_bank }}</td>
                            <td>{{ $akun_bank->no_rekening }}</td>
                            <td>{{ $akun_bank->updated_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-primary" href="/edit/akun-bank/{{ $akun_bank->id }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/akun-bank/{{ $akun_bank->id }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus akun bank ini?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
