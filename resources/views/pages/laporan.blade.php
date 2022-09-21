@extends('../main')

@section('page', 'Laporan')

@section('container')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('lihat_laporan') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="daterangepicker">Tanggal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" name="daterangepicker" class="form-control float-right" id="daterangepicker">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block">Lihat Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        //Date range picker
        $('#daterangepicker').daterangepicker()
    </script>
@endpush
