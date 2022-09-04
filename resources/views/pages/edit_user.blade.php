@extends('../main')

@section('page', 'Edit User')

@section('container')
    @if (session()->has('UserSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('UserSuccess') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif
    @if (session()->has('UserError'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('UserError') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                <span>
                    <i class="mdi mdi-close"></i>
                </span>
            </button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit User</h3>
        </div>
        <form method="POST" action="/user/{{ $user->id }}">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="username">Username <code>*</code></label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                        name="username" placeholder="Masukkan Username" value="{{ $user->username }}" required>
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="new_password">Password Baru <code>*</code></label>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password"
                        name="new_password" placeholder="Masukkan Password Baru">
                    @error('new_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
