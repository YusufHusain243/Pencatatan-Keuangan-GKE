<div class="form-group">
    <label for="sub_sub_kode_anggaran">Sub Sub Kode Anggaran <code>*</code></label>
    <select class="form-control @error('sub_sub_kode_anggaran') is-invalid @enderror" id="sub_sub_kode_anggaran"
        name="sub_sub_kode_anggaran" required>
        <option value="">Pilih Sub Sub Kode Anggaran</option>
        @foreach ($sub_sub_kodes as $sub_sub_kode)
            <option value="{{ $sub_sub_kode->id }}">
                {{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode }}.{{ $sub_sub_kode->subSubKodeToSubKode->no_sub_kode }}.{{ $sub_sub_kode->no_sub_sub_kode }}
                ({{ $sub_sub_kode->nama_sub_sub_kode }})
            </option>
        @endforeach
    </select>
    @error('sub_sub_kode_anggaran')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
