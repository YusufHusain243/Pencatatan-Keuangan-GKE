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
                        <th class="py-0 px-2" style="vertical-align: middle">KETERANGAN</th>
                        <th class="py-0 px-2" style="vertical-align: middle">JUMLAH</th>
                    </tr>
                    <tr>
                        <th class="py-0 px-2 text-center" style="vertical-align: middle">4</th>
                        <th class="py-0 px-2" style="vertical-align: middle">PENDAPATAN</th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                    </tr>
                    @php
                        $jumlah = 0;
                    @endphp
                    @foreach ($kodes as $kode)
                        <tr>
                            <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                @if ($kode->jenis_kode == 'Penerimaan')
                                    4.{{ $kode->no_kode }}
                                @else
                                    5.{{ $kode->no_kode }}
                                @endif
                            <th class="py-0 px-2" style="vertical-align: middle">{{ $kode->nama_kode }}</th>
                            <th class="py-0 px-2" style="vertical-align: middle"></th>
                            <th class="py-0 px-2" style="vertical-align: middle"></th>
                            </th>
                        </tr>
                        @foreach ($kode->kodeToSubKode as $sub_kode)
                            <tr>
                                @if ($sub_kode->subKodeToKode->no_kode == $kode->no_kode)
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
                                @endif
                            </tr>
                </thead>
                <tbody>
                    @foreach ($sub_kode->subKodeToSubSubKode as $sub_sub_kode)
                        @foreach ($sub_sub_kode->subSubKodeToDana as $key => $dana)
                            @php
                                $jumlah += $dana->nominal;
                            @endphp
                            <tr>
                                <td class="text-center py-0 px-2">
                                    @if ($dana == $sub_sub_kode->subSubKodeToDana[0])
                                        @if ($sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan')
                                            4.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        @else
                                            5.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        @endif
                                    @endif
                                </td>
                                <td class="py-0 px-2">
                                    @php
                                        $kode_persembahan_syukur = $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode . '.' . $sub_sub_kode->subSubKodeToSubKode->no_sub_kode;
                                    @endphp
                                    @if ($kode_persembahan_syukur == '1.2')
                                        <span class="mr-3">-</span><span
                                            class="mr-3">P.S</span>{{ $sub_sub_kode->nama_sub_sub_kode }}
                                    @else
                                        {{ $sub_sub_kode->nama_sub_sub_kode }}
                                    @endif
                                </td>
                                <td class="py-0 px-2">
                                    {{ $dana->keterangan }}
                                </td>
                                <td class="py-0 px-2">Rp.<span class="float-right">@currency($dana->nominal)</span></td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="py-0 px-2 text-right font-weight-bold">JUMLAH</td>
                        <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">@currency($jumlah)</span></td>
                    </tr>
                </tbody>
                @php
                    $jumlah = 0;
                @endphp
                @endforeach
                @endforeach
            </table>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center py-0 px-2">B.</th>
                        <th class="py-0 px-2">BELANJA</th>
                        <th class="py-0 px-2"></th>
                        <th class="py-0 px-2"></th>
                    </tr>
                    <tr class="text-center">
                        <th class="py-0 px-2" style="vertical-align: middle">Kode Anggaran</th>
                        <th class="py-0 px-2" style="vertical-align: middle" colspan="2">URAIAN</th>
                        <th class="py-0 px-2" style="vertical-align: middle">JUMLAH</th>
                    </tr>
                    <tr>
                        <th class="py-0 px-2 text-center" style="vertical-align: middle">4</th>
                        <th class="py-0 px-2" style="vertical-align: middle">PENDAPATAN</th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                    </tr>
                    @php
                        $jumlah = 0;
                    @endphp
                    @foreach ($kodes as $kode)
                        <tr>
                            <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                @if ($kode->jenis_kode == 'Penerimaan')
                                    4.{{ $kode->no_kode }}
                                @else
                                    5.{{ $kode->no_kode }}
                                @endif
                            <th class="py-0 px-2" style="vertical-align: middle">{{ $kode->nama_kode }}</th>
                            <th class="py-0 px-2" style="vertical-align: middle"></th>
                            <th class="py-0 px-2" style="vertical-align: middle"></th>
                            </th>
                        </tr>
                        @foreach ($kode->kodeToSubKode as $sub_kode)
                            <tr>
                                @if ($sub_kode->subKodeToKode->no_kode == $kode->no_kode)
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
                                @endif
                            </tr>
                </thead>
                <tbody>
                    @foreach ($sub_kode->subKodeToSubSubKode as $sub_sub_kode)
                        @foreach ($sub_sub_kode->subSubKodeToDana as $key => $dana)
                            @php
                                $jumlah += $dana->nominal;
                            @endphp
                            <tr>
                                <td class="text-center py-0 px-2">
                                    @if ($dana == $sub_sub_kode->subSubKodeToDana[0])
                                        @if ($sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan')
                                            4.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        @else
                                            5.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        @endif
                                    @endif
                                </td>
                                <td class="py-0 px-2">
                                    @php
                                        $kode_persembahan_syukur = $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode . '.' . $sub_sub_kode->subSubKodeToSubKode->no_sub_kode;
                                    @endphp
                                    @if ($kode_persembahan_syukur == '1.2')
                                        <span class="mr-3">-</span><span
                                            class="mr-3">P.S</span>{{ $sub_sub_kode->nama_sub_sub_kode }}
                                    @else
                                        {{ $sub_sub_kode->nama_sub_sub_kode }}
                                    @endif
                                </td>
                                <td class="py-0 px-2">
                                    {{ $dana->keterangan }}
                                </td>
                                <td class="py-0 px-2">Rp.<span class="float-right">@currency($dana->nominal)</span></td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="py-0 px-2 text-right font-weight-bold">JUMLAH</td>
                        <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">@currency($jumlah)</span></td>
                    </tr>
                </tbody>
                @php
                    $jumlah = 0;
                @endphp
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
