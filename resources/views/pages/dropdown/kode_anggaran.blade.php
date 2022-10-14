<div class="form-group">
    <label for="no_kode">No Kode <code>*</code></label>
    <select class="form-control @error('no_kode') is-invalid @enderror" id="no_kode" name="no_kode"
        onchange="maskSubKode(event)" required> required>
        <option value="">Pilih No Kode</option>
        @foreach ($kodes as $kode)
            <option value="{{ $kode->id }}" data-type="{{ $kode->jenis_kode == 'Penerimaan' ? 4 : 5 }}"
                data-value="{{ $kode->no_kode }}">
                {{ $kode->jenis_kode == 'Penerimaan' ? 4 : 5 }}.{{ $kode->no_kode }}
                ({{ $kode->nama_kode }})
            </option>
        @endforeach
    </select>
    @error('no_kode')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
