@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="page-header d-flex justify-content-between flex-wrap mb-3 align-items-center">
            <div class="title"><a href="{{ route('shop.index') }}">{{ __('Shops') }}</a> / <strong>{{ $store->name }}</strong>
            </div>
            <a href="{{ route('shop.index', $store->id) }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i>{{ __('Back') }}
            </a>
        </div>

        <div class="row mt-3">
            <div class="col-lg-10 m-auto">
                <form action="{{ route('shop.service.update', $store->id) }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header py-2">
                            <h3 class="m-0">{{ __('Services') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-md mt-2">
                                <table class="table table-bordered table-striped table-hover  notification_table">
                                    <thead>
                                        <tr>
                                            <th class="px-0 text-center" style="width: 42px">
                                                <input type="checkbox" onclick="toggle(this);" />
                                            </th>
                                            <th class="py-2">{{ __('Name') }}</th>
                                            <th class="py-2">{{ __('Thumbnail') }}</th>
                                            <th class="py-2">{{ __('Description') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($services as $service)
                                            <tr>
                                                <td class="py-2 px-0 text-center">
                                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}>
                                                </td>
                                                <td class="py-2">{{ $service->name }}</td>
                                                <td class="py-2">
                                                    <img width="100" src="{{ asset($service->thumbnailPath) }}"
                                                        alt="">
                                                </td>
                                                <td class="py-2">
                                                    {!! Str::limit($service->description, 30, '...') !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection
@push('scripts')
    <script>
        function toggle(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }
        }
    </script>
@endpush
