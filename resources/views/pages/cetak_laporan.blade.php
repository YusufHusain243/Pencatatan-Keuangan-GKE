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
            @php
                $jumlahPenerimaan = 0;
                $jumlahPengeluaran = 0;
            @endphp

            @if (array_key_exists('kode', $kode_penerimaans))
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th colspan="4" class="text-center" style="font-size: 14pt">PENCATATAN KAS GKE SINAR KASIH
                            </th>
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
                            $jumlahPenerimaanPerSubSubKode = 0;
                        @endphp
                        @foreach ($kode_penerimaans['kode'] as $kode_penerimaan)
                            <tr>
                                <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                    {{ '4.' . $kode_penerimaan['no_kode'] }}
                                <th class="py-0 px-2" style="vertical-align: middle">{{ $kode_penerimaan['nama_kode'] }}
                                </th>
                                <th class="py-0 px-2" style="vertical-align: middle"></th>
                                <th class="py-0 px-2" style="vertical-align: middle"></th>
                                </th>
                            </tr>
                            @if (!array_key_exists('sub_kode', $kode_penerimaan))
                                @php
                                    continue;
                                @endphp
                            @endif
                            @foreach ($kode_penerimaan['sub_kode'] as $sub_kode)
                                @if (in_array($sub_kode['id'], $listAllowIdSubKodePenerimaan))
                                    <tr>
                                        <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                            4.{{ $kode_penerimaan['no_kode'] }}.{{ $sub_kode['no_sub_kode'] }}
                                        </th>
                                        <th class="py-0 px-2" style="vertical-align: middle">
                                            {{ $sub_kode['nama_sub_kode'] }}
                                        </th>
                                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                                    </tr>
                                    @if (!array_key_exists('sub_sub_kode', $sub_kode))
                                        @php
                                            continue;
                                        @endphp
                                    @endif
                                    @foreach ($sub_kode['sub_sub_kode'] as $sub_sub_kode)
                                        @if (!array_key_exists('dana', $sub_sub_kode))
                                            @php
                                                continue;
                                            @endphp
                                        @endif
                                        @foreach ($sub_sub_kode['dana'] as $key => $dana)
                                            @php
                                                $jumlah += $dana['nominal'];
                                                $jumlahPenerimaan += $dana['nominal'];
                                                $jumlahPenerimaanPerSubSubKode += $dana['nominal'];
                                            @endphp
                                            <tr>
                                                @if ($key == 0)
                                                    <td class="text-center py-0 px-2">
                                                        4.{{ $kode_penerimaan['no_kode'] }}.{{ $sub_kode['no_sub_kode'] }}.{{ $sub_sub_kode['no_sub_sub_kode'] }}
                                                    </td>
                                                    <td class="py-0 px-2">
                                                        {{ $sub_sub_kode['nama_sub_sub_kode'] }}
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                                <td class="py-0 px-2">
                                                    {{ $dana['keterangan'] ? '- ' . $dana['keterangan'] : '' }}
                                                </td>
                                                <td class="py-0 px-2">Rp.<span class="float-right">@currency($dana['nominal'])</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($jumlah != 0)
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="py-0 px-2 text-right font-weight-bold">JUMLAH
                                                </td>
                                                <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">
                                                        @currency($jumlahPenerimaanPerSubSubKode)</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @php
                                            $jumlahPenerimaanPerSubSubKode = 0;
                                        @endphp
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                        <tr class="bg-success">
                            <td class="py-0 px-2 text-center font-weight-bold" colspan="3">JUMLAH PENERIMAAN
                            </td>
                            <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">
                                    @currency($jumlahPenerimaan)</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif

            @if (array_key_exists('kode', $kode_pengeluarans))
                <table class="table table-bordered mt-4">
                    <tbody>
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
                            $jumlahPengeluaranPerSubSubKode = 0;
                        @endphp
                        @foreach ($kode_pengeluarans['kode'] as $kode)
                            <tr>
                                <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                    {{ '5.' . $kode['no_kode'] }}
                                <th class="py-0 px-2" style="vertical-align: middle">{{ $kode['nama_kode'] }}</th>
                                <th class="py-0 px-2" style="vertical-align: middle"></th>
                                <th class="py-0 px-2" style="vertical-align: middle"></th>
                                </th>
                            </tr>
                            @if (!array_key_exists('sub_kode', $kode))
                                @php
                                    continue;
                                @endphp
                            @endif
                            @foreach ($kode['sub_kode'] as $sub_kode)
                                @if (in_array($sub_kode['id'], $listAllowIdSubKodePengeluaran))
                                    <tr>
                                        <th class="py-0 px-2 text-center" style="vertical-align: middle">
                                            5.{{ $kode['no_kode'] }}.{{ $sub_kode['no_sub_kode'] }}
                                        </th>
                                        <th class="py-0 px-2" style="vertical-align: middle">
                                            {{ $sub_kode['nama_sub_kode'] }}
                                        </th>
                                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                                        <th class="py-0 px-2" style="vertical-align: middle"></th>
                                    </tr>
                                    @if (!array_key_exists('sub_sub_kode', $sub_kode))
                                        @php
                                            continue;
                                        @endphp
                                    @endif
                                    @foreach ($sub_kode['sub_sub_kode'] as $sub_sub_kode)
                                        @if (!array_key_exists('dana', $sub_sub_kode))
                                            @php
                                                continue;
                                            @endphp
                                        @endif
                                        @foreach ($sub_sub_kode['dana'] as $key => $dana)
                                            @php
                                                $jumlah += $dana['nominal'];
                                                $jumlahPengeluaran += $dana['nominal'];
                                                $jumlahPengeluaranPerSubSubKode += $dana['nominal'];
                                            @endphp
                                            <tr>
                                                @if ($key == 0)
                                                    <td class="text-center py-0 px-2">
                                                        5.{{ $kode['no_kode'] }}.{{ $sub_kode['no_sub_kode'] }}.{{ $sub_sub_kode['no_sub_sub_kode'] }}
                                                    </td>
                                                    <td class="py-0 px-2">
                                                        {{ $sub_sub_kode['nama_sub_sub_kode'] }}
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                                <td class="py-0 px-2">
                                                    {{ $dana['keterangan'] ? '- ' . $dana['keterangan'] : '' }}
                                                </td>
                                                <td class="py-0 px-2">Rp.<span class="float-right">
                                                        @currency($dana['nominal'])</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($jumlah != 0)
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="py-0 px-2 text-right font-weight-bold">JUMLAH
                                                </td>
                                                <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">
                                                        @currency($jumlahPengeluaranPerSubSubKode)</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @php
                                            $jumlahPengeluaranPerSubSubKode = 0;
                                        @endphp
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                        <tr class="bg-danger">
                            <td class="py-0 px-2 text-center font-weight-bold" colspan="3">JUMLAH PENGELUARAN</td>
                            <td class="py-0 px-2 font-weight-bold">Rp.<span class="float-right">@currency($jumlahPengeluaran)</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
            @php
                function getIndonesianDate($dates)
                {
                    return \Carbon\Carbon::createFromDate($dates)
                        ->locale('id')
                        ->settings(['formatFunction' => 'translatedFormat'])
                        ->format('d F Y');
                }
            @endphp
            <table class="table table-bordered mt-4">
                <tbody>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold">Keterangan :</td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-bottom-0">Saldo terakhir tanggal,
                            {{ getIndonesianDate($tanggalAwal) }}
                            <span class="float-right">Rp. @currency($saldo_akhir ?? 0)</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0">Penerimaan,
                            {{ getIndonesianDate($tanggalAwal) }} - {{ getIndonesianDate($tanggalAkhir) }}
                            <span class="float-right"><u>Rp. @currency($jumlahPenerimaan ?? 0)</u></span>
                        </td>
                    </tr>
                    <tr>
                        @php
                            $jumlahSaldoPenerimaan = $jumlahPenerimaan + $saldo_akhir;
                            $jumlahSaldoPengeluaran = $jumlahSaldoPenerimaan - $jumlahPengeluaran;
                        @endphp
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0"><span class="float-right">Rp.
                                @currency($jumlahSaldoPenerimaan ?? 0)</span></td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0">Pengeluaran,
                            {{ getIndonesianDate($tanggalAwal) }} - {{ getIndonesianDate($tanggalAkhir) }}
                            <span class="float-right"><u>Rp. @currency($jumlahPengeluaran ?? 0)</u></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-top-0">Saldo terakhir tanggal,
                            {{ getIndonesianDate($tanggalAkhir) }}<span class="float-right">Rp.
                                @currency($jumlahSaldoPengeluaran ?? 0)</span></td>
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
                    @php
                        $totalSaldoBank = 0;
                        $totalKasGereja = 0;
                    @endphp
                    @foreach ($saldo_banks as $saldo_bank)
                        @php
                            $totalSaldoBank += $saldo_bank['nominalDana'];
                            $totalKasGereja = $totalSaldoBank + $saldo_tunai;
                        @endphp
                        <tr>
                            <td class="py-0 px-2 font-weight-bold border-top-0 border-bottom-0 pl-5">
                                {{ $saldo_bank['nama_bank'] }}<span class="float-right">Rp. @currency($saldo_bank['nominalDana'])</span></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="py-0 px-2 font-weight-bold border-bottom-0">
                            TOTAL SALDO KAS GEREJA<span class="float-right">Rp. @currency($totalKasGereja)</span></td>
                    </tr>
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
