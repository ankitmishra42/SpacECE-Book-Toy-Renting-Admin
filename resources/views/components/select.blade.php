<div>
    <div class="form-group">
        <select {{ $multi ? 'multiple' : '' }} name="{{ $name }}" class="select2 @error($name) is-invalid @enderror" style="width: 100%">
            <option value="" disabled selected>{{ __('Select') }}</option>
            {{ $slot }}
        </select>
        @error($name)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
