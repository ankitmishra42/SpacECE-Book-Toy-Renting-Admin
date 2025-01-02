@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4 my-md-0">
        <div class="row h-100vh align-items-center">
            <div class="col-md-8 m-auto">
                <form action="{{ route('about.update', $about?->id) }}" method="POST">
                    @csrf
                    <div class="card shadow border-0 rounded-12">
                        <div class="card-header py-3">
                            <h3 class="card-title m-0">{{ __('Edit') }} {{ __('about-us') }}</h3>
                        </div>
                        <div class="card-body">
                            <div>
                                <label class="m-0">{{ __('Title') }}</label>
                                <x-input type="text" name="title" :value="$about?->title" placeholder="Title" />
                            </div>
                            <div>
                                <label class="m-0">{{ __('Phone_number') }}</label>
                                <x-input type="number" name="phone" :value="$about?->phone" placeholder="Phone Number" />
                            </div>
                            <div>
                                <label class="m-0">{{ __('Email_Address') }}</label>
                                <x-input type="email" name="email" :value="$about?->email" placeholder="Email Address" />
                            </div>
                            <div>
                                <label class="m-0">{{ __('Whatsapp') }}</label>
                                <x-input type="number" name="whatsapp" :value="$about?->whatsapp" placeholder="whatsapp" />
                            </div>
                            <div>
                                <label class="m-0">{{ __('Description') }}</label>
                                <x-textarea name="description" :value="$about?->description" placeholder="description" />
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between gap-2 flex-wrap">
                            <a href="{{ route('about.index') }}" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                            <button class="btn btn-primary">{{ __('Save_And_Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        $('#name').keyup(function() {
            $('#slug').val($(this).val().toLowerCase().split(',').join('').replace(/\s/g, "-"));
        });
    </script>
@endpush
