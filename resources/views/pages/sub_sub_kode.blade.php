@extends('../main')

@section('page', 'Daftar Sub Sub-Akun')

@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Daftar Sub Sub-Akun</h3>
        </div>
        <form>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="jenis_akun">No Sub Akun</label>
                            <select class="form-control" id="jenis_akun" required>
                                <option value="">Pilih No Sub Akun</option>
                                <option value="">4.1.1</option>
                                <option value="">4.2.1</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_sub_sub_akun">No Sub Sub-Akun</label>
                            <input type="number" class="form-control" id="no_sub_sub_akun" placeholder="Masukkan No Sub Sub-Akun">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="nama_sub_sub_akun">Nama Sub Sub-Akun</label>
                            <input type="text" class="form-control" id="nama_sub_sub_akun" placeholder="Masukkan Nama Sub Sub-Akun">
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
                        <th>No Sub Sub-Akun</th>
                        <th>Nama Sub Sub-Akun</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
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
