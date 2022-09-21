@extends('../main')

@section('page', 'Edit Akun Bank')

@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Akun Bank</h3>
        </div>
        <form action="/akun-bank/{{ Crypt::encrypt($akun_bank->id) }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nama_bank">Nama Bank <code>*</code></label>
                            <input type="text" class="form-control @error('nama_bank') is-invalid @enderror"
                                id="nama_bank" name="nama_bank" placeholder="Masukkan Nama Bank"
                                value="{{ $akun_bank->nama_bank }}" required>
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
                                id="no_rekening" name="no_rekening" placeholder="Masukkan No Rekening"
                                value="{{ $akun_bank->no_rekening }}" required>
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
@endsection

@push('after-script')
    <script>
        $(document).ready(function() {
            $('#no_rekening').inputmask('9{10,16}');
        });
    </script>
@endpush
