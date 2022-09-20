@extends('../main')

@section('page', 'Laporan')

@section('container')
    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <a href="{{ asset('/cetak_laporan') }}" class="btn btn-primary">Cetak</a>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center py-0 px-2">A.</th>
                        <th class="py-0 px-2">PENERIMAAN</th>
                        <th class="py-0 px-2"></th>
                        <th class="py-0 px-2"></th>
                    </tr>
                    <tr class="text-center">
                        <th class="py-0 px-2" style="vertical-align: middle">Kode Anggaran</th>
                        <th class="py-0 px-2" style="vertical-align: middle">URAIAN</th>
                        <th class="py-0 px-2" style="vertical-align: middle">NAMA/ALAMAT</th>
                        <th class="py-0 px-2" style="vertical-align: middle">JUMLAH</th>
                    </tr>
                    <tr>
                        <th class="py-0 px-2 text-center" style="vertical-align: middle">4</th>
                        <th class="py-0 px-2" style="vertical-align: middle">PENDAPATAN</th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                    </tr>
                    @foreach ($kodes as $kode)
                        <tr>
                            <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                @if ($kode->jenis_kode == 'Penerimaan')
                                    4.{{ $kode->no_kode }}
                                @else
                                    5.{{ $kode->no_kode }}
                                @endif
                            </th>
                            <th class="py-0 px-2" style="vertical-align: middle">{{ $kode->nama_kode }}</th>
                            <th class="py-0 px-2" style="vertical-align: middle"></th>
                            <th class="py-0 px-2" style="vertical-align: middle"></th>
                        </tr>
                        @foreach ($sub_kodes as $sub_kode)
                            <tr>
                                <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                    @if ($sub_kode->subKodeToKode->jenis_kode == 'Penerimaan')
                                        4.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                    @else
                                        5.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                    @endif
                                </th>
                                <th class="py-0 px-2" style="vertical-align: middle">{{ $sub_kode->nama_sub_kode }}</th>
                                <th class="py-0 px-2" style="vertical-align: middle"></th>
                                <th class="py-0 px-2" style="vertical-align: middle"></th>
                            </tr>
                </thead>
                <tbody>
                    @foreach ($danas as $dana)
                        <tr>
                            <td class="text-center py-0 px-2">
                                @if ($dana->danaToSubSubKode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan')
                                    4.{{ $dana->danaToSubSubKode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $dana->danaToSubSubKode->subSubKodeToSubKode->no_sub_kode }}.{{ $dana->danaToSubSubKode->no_sub_sub_kode }}
                                @else
                                    5.{{ $dana->danaToSubSubKode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $dana->danaToSubSubKode->subSubKodeToSubKode->no_sub_kode }}.{{ $dana->danaToSubSubKode->no_sub_sub_kode }}
                                @endif
                            </td>
                            <td class="py-0 px-2">
                                @php
                                    $kode_persembahan_syukur = $dana->danaToSubSubKode->subSubKodeToSubKode->subKodeToKode->no_kode . '.' . $dana->danaToSubSubKode->subSubKodeToSubKode->no_sub_kode;
                                @endphp
                                <span class="mr-3">-</span><span
                                    class="mr-3">P.S</span>{{ $dana->danaToSubSubKode->nama_sub_sub_kode }}
                            </td>
                            <td class="py-0 px-2">
                                @if ($kode_persembahan_syukur == '1.2')
                                    <span class="mr-2">-</span>{{ $dana->keterangan }}
                                @else
                                    {{ $dana->keterangan }}
                                @endif
                            </td>
                            <td class="py-0 px-2">Rp.<span class="float-right">Rp. @currency($dana->nominal)</span></td>
                        </tr>
                    @endforeach
                </tbody>
                @endforeach
                @endforeach
            </table>
        </div>
    </div>
@endsection

@push('after-style')
    <style>
        table {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 14pt;
        }
    </style>
@endpush
