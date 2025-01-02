@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0 mt-2 mt-sm-0 d-flex">
                <a href="{{ route('product.index') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-7 col-xxl-7 col-lg-7 m-auto">
                <div class="card shadow rounded-12 border-0">
                    <div class="card-header py-3 bg-primary">
                        <h2 class="card-title m-0 text-white">{{ __('Edit_Product') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img width="50%" id="preview" src="{{ $product->thumbnailPath }}" alt="">
                        </div>
                        <x-form type="Update" method="true" route="product.update" updateId="{{ $product->id }}">
                            <label class="mb-1">{{ __('Name') }}</label>
                            <x-input name="name" type='text' placeholder="Category name"
                                value="{{ old('name') ?? $product->name }}"/>

                            <label class="mb-1">{{ __('Price') }}</label>
                            <input name="price" type='text' class="form-control" placeholder="Product price"
                                value="{{ old('price') ?? $product->price }}" onkeypress="onlyNumber(event)"/>
                            @error('price')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror

                            <label class="mb-1 mt-3">{{ __('Discount_Price') }}</label>
                            <input name="discount_price" type='text' class="form-control" placeholder="Discount Price"
                                value="{{ old('discount_price') ?? $product->discount_price }}" onkeypress="onlyNumber(event)"/>
                            @error('discount_price')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror

                            <label class="mb-1 mt-3">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control mb-3" placeholder="Product Description">{{$product->description}}</textarea>

                            <input type="hidden" id="slug" name="slug" class="form-control input-default"
                                value="{{ old('slug') ?? $product->slug }}">

                            <label class="mb-1">{{ __('Service') }}</label>
                            <x-select name="service_id">
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ $product->service_id == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}</option>
                                @endforeach
                            </x-select>

                            <label class="mb-1">{{ __('Variant') }}</label>
                            <x-select name="variant_id">
                                @foreach ($variants as $variant)
                                    <option {{ $product->variant_id == $variant->id ? 'selected' : '' }}
                                        value="{{ $variant->id }}">{{ $variant->name }}</option>
                                @endforeach
                            </x-select>

                            <label class="mb-1">{{ __('Thumbnail') }}</label>
                            <x-input-file name="image" type="file"/>

                            <div class="form-group">
                                <label for="active" class="mr-2">
                                    <input type="radio" id="active" name="active"
                                        {{ $product->is_active ? 'checked' : '' }} value="1"> {{ __('Active') }}
                                </label>

                                <label for="in_active">
                                    <input type="radio" id="in_active" name="active"
                                        {{ !$product->is_active ? 'checked' : '' }} value="0">
                                        {{ __('Inactive') }}
                                </label>
                            </div>

                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function onlyNumber(evt) {
            var chars = String.fromCharCode(evt.which);
            if (!(/[0-9.]/.test(chars))) {
                evt.preventDefault();
            }
        }

        $('#name').keyup(function() {
            $('#slug').val($(this).val().toLowerCase().split(',').join('').replace(/\s/g, "-"));
        });

        $('select[name="service_id"]').on('change', function() {
            var serviceId = $(this).val();
            if (serviceId) {
                $.ajax({
                    url: `/services/${serviceId}/variants`,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="variant_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="variant_id"]').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="variant_id"]').empty();
            }
        });
    </script>
@endpush
