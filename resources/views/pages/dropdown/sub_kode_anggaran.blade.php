<div class="form-group">
    <label for="sub_kode_anggaran">Sub Kode Anggaran <code>*</code></label>
    <select class="form-control @error('sub_kode_anggaran') is-invalid @enderror" id="sub_kode_anggaran"
        name="sub_kode_anggaran" onchange="getSubSubKode(event)" required>
        <option value="">Pilih Sub Kode Anggaran</option>
        @foreach ($sub_kodes as $sub_kode)
            <option value="{{ $sub_kode->id }}">
                {{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                ({{ $sub_kode->nama_sub_kode }})
            </option>
        @endforeach
    </select>
    @error('sub_kode_anggaran')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
