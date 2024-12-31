@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-9 mt-2 mx-auto ">
                <form action="{{ route('language.update', $language->id) }}" method="POST">
                    @csrf @method('put')
                    <div class="card border-0 shadow-sm rounded-12 mb-5">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                            <h3 class="m-0">{{ __('Edit_Language') }}</h3>
                            <a href="{{ route('language.index') }}" class="btn btn-danger">
                                {{ __('Back') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <div>
                                <label for="" class="mb-0">{{ __('Title') }}</label>
                                <x-input type="text" name="title" :value="$language->title"/>
                                <input type="hidden" name="name" value="{{ $language->name }}"/>
                            </div>
                            <table class="table table-bordered">
                                <tr class="table-light">
                                    <th>{{ __('Key') }}</th>
                                    <th>{{ __('Value') }}</th>
                                </tr>
                                <tbody>
                                    @foreach ($languageData as $key => $value)
                                        <tr>
                                            <td>
                                                {{ $key }}
                                                <input type="hidden" name="key[]" value="{{ $key }}">
                                            </td>
                                            <td class="py-2">
                                                <input type="text" class="languageInput" name="value[]" value="{{ $value }}" placeholder="value for {{ $language->title }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="submitBtn shadow-sm">
                                <button type="submit" class="btn btn-primary">{{ __('Save_And_Update') }}</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .submitBtn{
            position: fixed;
            bottom: 0;
            left: 0 ;
            right: 0;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
            border-top: 1px solid #eceff1;
            z-index: 2;
        }
    </style>
@endsection
