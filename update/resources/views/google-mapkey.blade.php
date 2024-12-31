@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row h-100vh align-items-center">
            <div class="col-md-10 col-lg-9 col-sm-12 m-auto">
                <form action="{{ route('mapApiKey.update', $mapApiKey?->id) }}" method="POST">
                    @csrf
                    <div class="card shadow rounded-12 border-0">
                        <div class="card-header bg-primary py-3">
                            <h3 class="text-white m-0">{{ __('Google Map Setup') }}</h3>
                        </div>
                        <div class="card-body pb-3">
                            <div class="mb-2">
                                <label class="mb-1 text-dark">{{ __('Google Map Api Key') }}<span class="text-danger">*</span></label>
                                <textarea name="key" class="form-control" rows="2" placeholder="Enter Google Map Api Key">
                                    {{ $mapApiKey?->key }}
                                </textarea>
                                @error('key')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer py-3 ">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary">{{ __('Save_And_Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
</script>
@endpush

