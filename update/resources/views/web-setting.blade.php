@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row h-100vh align-items-center">
            <div class="col-xl-10 col-2xl-9 col-sm-12 m-auto">
                <form action="{{ route('appSetting.update', $appSetting?->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow border-0 rounded-12">
                        <div class="card-header bg-primary py-3">
                            <h3 class="text-white m-0">{{ __('App_Setting') }}</h3>
                        </div>
                        <div class="card-body pb-3">
                            <div class="row">
                                <div class="col-lg-6 border-right">
                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('Website_Name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $appSetting?->name }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('Website_Title') }} </label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ $appSetting?->title }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('Logo') }}</label>
                                        <input type="file" name="logo" class="form-control-file" accept="image/*"
                                            onchange="previewLogoFile(event)">
                                        <img src="{{ $appSetting?->websiteLogoPath }}" alt="" id="logoPreview"
                                            width="80">
                                    </div>

                                    <label class="mb-0 text-dark">{{ __('Time_Zone') }}</label>
                                    <x-select name="timezone">
                                        @foreach ($zones as $zone)
                                            <option {{ $zone['zone'] == config('app.timezone') ? 'selected' : '' }}
                                                value="{{ $zone['zone'] }}">
                                                {{ $zone['diff_from_GMT'] . ' - ' . $zone['zone'] }}
                                            </option>
                                        @endforeach
                                    </x-select>

                                    <label class="mb-0 text-dark">{{ __('Direction') }}</label>
                                    <div class="d-flex" style="gap: 16px">
                                        <div>
                                            <label for="ltr">LTR</label>
                                            <input type="radio" name="direction" id="ltr" value="ltr"
                                                {{ $appSetting?->direction == 'ltr' ? 'checked' : '' }} />
                                        </div>
                                        <div>
                                            <label for="rtl">RTL</label>
                                            <input type="radio" name="direction" id="rtl" value="rtl"
                                                {{ $appSetting?->direction == 'rtl' ? 'checked' : '' }} />
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('City') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ $appSetting?->city }}" required>
                                    </div>
                                    <div class="">
                                        <label class="mb-0 text-dark">{{ __('Invoice_Signature') }}</label>
                                        <input type="file" name="signature" class="form-control-file" accept="image/*"
                                            onchange="previewSignature(event)">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('favicon') }}</label>
                                        <input type="file" name="fav_icon" class="form-control-file" accept="image/*"
                                            onchange="previewFavIco(event)">
                                        <img src="{{ $appSetting?->websiteFaviconPath }}" alt="" id="favionPreview"
                                            width="60">
                                    </div>

                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('Address') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea name="address" class="form-control" rows="3" required>{{ $appSetting?->address }}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('Mobile_number') }}</label>
                                        <input type="text" name="mobile" class="form-control"
                                            value="{{ $appSetting?->mobile }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="mb-0 text-dark">{{ __('Currency_Symbol') }}</label>
                                        <input type="text" name="currency" class="form-control"
                                            value="{{ $appSetting?->currency }}" required>
                                    </div>

                                    <div>
                                        <label class="mb-0 text-dark">{{ __('Currency_Position') }}</label>
                                        <x-select name="currency_position">
                                            <option {{ $appSetting?->currency_position == 'prefix' ? 'selected' : '' }}
                                                value="prefix">
                                                {{ __('Prefix') }}
                                            </option>
                                            <option {{ $appSetting?->currency_position == 'suffix' ? 'selected' : '' }}
                                                value="suffix">
                                                {{ __('Suffix') }}
                                            </option>
                                        </x-select>
                                    </div>

                                    <div>
                                        <img src="{{ $appSetting?->signaturePath }}" alt=""
                                            id="signaturePreview" width="80"> <small class="font-italic">{{ __('signature') }}</small>
                                    </div>
                                </div>
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
        var previewLogoFile = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('logoPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };

        var previewFavIco = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('favionPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };

        var previewSignature = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('signaturePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endpush
