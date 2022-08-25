@extends('../main')

@section('page', 'Akun Bank')

@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Akun Bank</h3>
        </div>
        <form>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="nama_bank">Nama Bank</label>
                            <input type="text" class="form-control" id="nama_bank" placeholder="Masukkan Nama Bank">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_rek">No Rekening</label>
                            <input type="number" class="form-control" id="no_rek" placeholder="Masukkan No Rekening">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="saldo">Saldo</label>
                            <input type="number" class="form-control" id="saldo" placeholder="Masukkan Saldo">
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
                        <th>Saldo</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>001</td>
                        <td>001</td>
                        <td>xxxx</td>
                        <td>20/10/2022</td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-primary" href=""><i class="fas fa-edit"></i></a>
                                <form action="" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus menu ini?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
