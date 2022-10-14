<div class="form-group">
    <label for="no_sub_kode">No Sub Kode <code>*</code></label>
    <select class="form-control @error('no_sub_kode') is-invalid @enderror" id="no_sub_kode" name="no_sub_kode"
        onchange="maskSubKode(event)" required>
        <option value="">Pilih No Sub Kode</option>
        @foreach ($sub_kodes as $sub_kode)
            <option value="{{ $sub_kode->id }}"
                data-type="{{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}"
                data-value="{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}">
                {{ $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $sub_kode->subKodeToKode->no_kode }}.{{ $sub_kode->no_sub_kode }}
                ({{ $sub_kode->nama_sub_kode }})
            </option>
        @endforeach
    </select>
    @error('no_sub_kode')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
