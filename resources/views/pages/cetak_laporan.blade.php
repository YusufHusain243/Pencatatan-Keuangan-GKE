@extends('../main')

@section('page', 'Cetak Laporan')

@section('container')
    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                {{-- <a href="{{ asset('/export_laporan') }}" class="btn btn-primary">Cetak</a> --}}
                <form action="{{ asset('/export_laporan') }}" method="post">
                    @csrf
                    <input type="hidden" name="tanggalAwal" value="{{ $tanggalAwal }}">
                    <input type="hidden" name="tanggalAkhir" value="{{ $tanggalAkhir }}">
                    <button class="btn btn-primary">Cetak</button>
                </form>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="4" class="text-center" style="font-size: 14pt">PENCATATAN KAS GKE SINAR KASIH</th>
                    </tr>
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
                        $jumlahPenerimaan = 0;
                    @endphp
                    @foreach ($kodes as $kode)
                        @if ($kode->jenis_kode == 'Penerimaan')
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
                                        <th class="py-0 px-2" style="vertical-align: middle">{{ $sub_kode->nama_sub_kode }}
                                        </th>
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
                                $jumlahPenerimaan += $dana->nominal;
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
                                        @if ($dana == $sub_sub_kode->subSubKodeToDana[0])
                                            <span class="mr-3">-</span><span
                                                class="mr-3">P.S</span>{{ $sub_sub_kode->nama_sub_sub_kode }}
                                        @endif
                                    @else
                                        {{ $sub_sub_kode->nama_sub_sub_kode }}
                                    @endif
                                </td>
                                <td class="py-0 px-2">
                                    {{ $dana->keterangan }}
                                </td>
                                <td class="py-0 px-2">Rp.<span class="float-right">Rp. @currency($dana->nominal)</span></td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="py-0 px-2 text-center font-weight-bold">JUMLAH</td>
                        <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">Rp. @currency($jumlah)</span>
                        </td>
                    </tr>
                </tbody>
                @php
                    $jumlah = 0;
                @endphp
                @endforeach
                @endif
                @endforeach
            </table>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center py-0 px-2">B.</th>
                        <th class="py-0 px-2">PENGELUARAN</th>
                        <th class="py-0 px-2"></th>
                        <th class="py-0 px-2"></th>
                    </tr>
                    <tr class="text-center">
                        <th class="py-0 px-2" style="vertical-align: middle">Kode Anggaran</th>
                        <th class="py-0 px-2" style="vertical-align: middle" colspan="2">URAIAN</th>
                        <th class="py-0 px-2" style="vertical-align: middle">JUMLAH</th>
                    </tr>
                    <tr>
                        <th class="py-0 px-2 text-center" style="vertical-align: middle">5</th>
                        <th class="py-0 px-2" style="vertical-align: middle">BELANJA</th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                    </tr>
                    @php
                        $jumlah = 0;
                        $jumlahPengeluaran = 0;
                    @endphp
                    @foreach ($kodes as $kode)
                        @if ($kode->jenis_kode == 'Pengeluaran')
                            <tr>
                                <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                    @if ($kode->jenis_kode == 'Pengeluaran')
                                        5.{{ $kode->no_kode }}
                                    @else
                                        4.{{ $kode->no_kode }}
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
                                            @if ($sub_kode->subKodeToKode->jenis_kode == 'Pengeluaran')
                                                5.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                            @else
                                                4.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                                            @endif
                                        </th>
                                        <th class="py-0 px-2" style="vertical-align: middle">{{ $sub_kode->nama_sub_kode }}
                                        </th>
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
                                $jumlahPengeluaran += $dana->nominal;
                            @endphp
                            <tr>
                                <td class="text-center py-0 px-2">
                                    @if ($dana == $sub_sub_kode->subSubKodeToDana[0])
                                        @if ($sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Pengeluaran')
                                            5.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        @else
                                            4.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                                        @endif
                                    @endif
                                </td>
                                <td class="py-0 px-2">
                                    @if ($dana == $sub_sub_kode->subSubKodeToDana[0])
                                        {{ $sub_sub_kode->nama_sub_sub_kode }}
                                    @endif
                                </td>
                                <td class="py-0 px-2">
                                    {{ $dana->keterangan }}
                                </td>
                                <td class="py-0 px-2">Rp.<span class="float-right">Rp. @currency($dana->nominal)</span></td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="py-0 px-2 text-center font-weight-bold">JUMLAH</td>
                        @if (isset($key))
                            @if ($key == count($sub_sub_kode->subSubKodeToDana) - 1)
                                <td class="py-0 px-2">Rp.<span class="float-right">Rp. @currency($jumlah)</span>
                                </td>
                            @else
                                <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">Rp.
                                        @currency($jumlah)</span>
                                </td>
                            @endif
                        @endif
                    </tr>
                    @php
                        $jumlah = 0;
                    @endphp
                    @endforeach
                    @endif
                    @endforeach
                    <tr>
                        <td class="py-0 px-2 text-center font-weight-bold" colspan="3">JUMLAH PENGELUARAN</td>
                        <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">Rp. @currency($jumlahPengeluaran)</span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered mt-4">
                <tbody>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold">Keterangan :</td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-bottom-0">Saldo terakhir tanggal,
                            {{ date('d F Y', strtotime($tanggalAwal)) }}
                            <span class="float-right">Rp. @currency($saldo_akhir)</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0">Penerimaan,
                            {{ date('d F Y', strtotime($tanggalAwal)) }} - {{ date('d F Y', strtotime($tanggalAkhir)) }}
                            <span class="float-right"><u>Rp. @currency($jumlahPenerimaan)</u></span>
                        </td>
                    </tr>
                    <tr>
                        @php
                            $jumlahSaldoPenerimaan = $jumlahPenerimaan + $saldo_akhir;
                            $jumlahSaldoPengeluaran = $jumlahSaldoPenerimaan - $jumlahPengeluaran;
                        @endphp
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0"><span class="float-right">Rp.
                                @currency($jumlahSaldoPenerimaan)</span></td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0">Pengeluaran,
                            {{ date('d F Y', strtotime($tanggalAwal)) }} - {{ date('d F Y', strtotime($tanggalAkhir)) }}
                            <span class="float-right"><u>Rp. @currency($jumlahPengeluaran)</u></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0">Saldo terakhir tanggal,
                            {{ date('d F Y', strtotime($tanggalAkhir)) }}<span class="float-right">Rp.
                                @currency($jumlahSaldoPengeluaran)</span></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0">Tempat Penyimpanan :</td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0 pl-5">Kas Tunai<span
                                class="float-right">Rp. @currency($saldo_tunai)</span></td>
                    </tr>
                    @foreach ($saldo_banks as $saldo_bank)
                        <tr>
                            <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0 pl-5">
                                {{ $saldo_bank['nama_bank'] }}<span class="float-right">Rp. @currency($saldo_bank['nominalDana'])</span></td>
                        </tr>
                    @endforeach
                </tbody>
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
