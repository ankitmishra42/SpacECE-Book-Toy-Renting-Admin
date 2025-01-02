@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row h-100vh align-items-center">
            <div class="col-md-10 col-lg-9 col-sm-12 m-auto">
                <form action="{{ route('stripeKey.update', $stripeKey?->id) }}" method="POST">
                    @csrf
                    <div class="card shadow rounded-12 border-0">
                        <div class="card-header bg-primary py-3">
                            <h3 class="text-white m-0">{{ __('Stripe_payment_key_set') }}</h3>
                        </div>
                        <div class="card-body pb-3">
                            <div class="mb-2">
                                <label class="mb-1 text-dark">{{ __('Stripe_Public_Key') }}<span class="text-danger">*</span></label>
                                <textarea name="public_key" class="form-control" rows="2" placeholder="Public Key">
                                    {{ $stripeKey?->public_key }}
                                </textarea>
                                @error('public_key')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label class="mb-1 text-dark">{{ __('Stripe_Secret_Key') }}<span class="text-danger">*</span></label>
                                <textarea name="secret_key" class="form-control" rows="2" placeholder="Secret Key">
                                    {{ $stripeKey?->secret_key }}
                                </textarea>
                                @error('secret_key')
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

