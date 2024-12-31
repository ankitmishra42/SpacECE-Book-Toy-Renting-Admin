<div class="form-group">
    <input type="{{ $type }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"  placeholder="{{ $placeholder }}" value="{{ $value ?? old($name) }}" @if($required) required @endif/>
    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
