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
        <form action="/akun-bank" method="POST" id="form">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nama_bank">Nama Bank <code>*</code></label>
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
                            <label for="no_rekening">No Rekening <code>*</code></label>
                            <input type="text" class="form-control @error('no_rekening') is-invalid @enderror"
                                id="no_rekening" name="no_rekening" placeholder="Masukkan No Rekening" minlength="10"
                                required>
                            <small class="form-text text-danger" id="tooltip">No. Rekening setidaknya memiliki 10 karakter</small>
                            @error('no_rekening')
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

@push('after-script')
    <script>
        $(document).ready(function() {
            let isvalid = false;
            $("#tooltip").hide();
            $('#no_rekening').inputmask({
                    mask: "*{10,16}",
                    definitions: {
                        '*': {
                            validator: "[0-9]"
                        }
                    },
                    removeMaskOnSubmit: true,
                    onincomplete: function() {
                        isvalid = false
                    },
                    oncomplete: function() {
                        isvalid = true
                    }
                }),
                $("#form").submit(function(e) {
                    if (!isvalid) {
                        $("#tooltip").show();
                        let tooltip = setInterval(function() {
                            clearInterval(tooltip);
                            $("#tooltip").hide();
                        }, 3000);
                        e.preventDefault();
                        return
                    }
                    $("form").submit();
                });
        });
    </script>
@endpush
