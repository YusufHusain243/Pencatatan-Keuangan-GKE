@extends('../main')

@section('page', 'Catat Pengeluaran')

@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Catat Pengeluaran</h3>
        </div>
        <form>
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal">
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" placeholder="Masukkan Keterangan">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <div class="form-group">
                            <label for="kode_anggaran">Kode Anggaran</label>
                            <select class="form-control" id="kode_anggaran" required>
                                <option value="">Pilih Kode Anggaran</option>
                                <option value="">4.1.1</option>
                                <option value="">4.2.1</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <label for="jenis_transaksi">Jenis Transaksi</label>
                            <select class="form-control" id="jenis_transaksi" required>
                                <option value="">Pilih Jenis Transaksi</option>
                                <option value="">Tunai/Cash</option>
                                <option value="">BCA/BPD</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="number" class="form-control" id="nominal" placeholder="Masukkan Nominal">
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
                        <th>Tanggal</th>
                        <th>Kode Anggaran</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Transaksi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>1</td>
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
