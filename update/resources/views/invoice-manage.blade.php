@extends('layouts.app')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 col-lg-6 col-sm-12">
                <div class="card shadow rounded-12 border-0">
                    <div class="card-header bg-primary py-3">
                        <h3 class="text-white m-0">{{ __('Printer_Type') }}</h3>
                    </div>
                    <div class="card-body pb-3">
                        <div class="d-flex justify-content-start flex-wrap" style="gap: 24px">
                            <div class="printerBox p-2">
                                <input type="radio" class="btn-check" name="type" id="regular"
                                    {{ $invoice?->type == 'regular' ? 'checked' : '' }} />
                                <label for="regular" class="printer_type">
                                    <i class="fa fa-print icon"></i>
                                    <div class="title mt-2">{{ __('Regular_Printer') }}</div>
                                    <div class="chackBox">
                                        <div class="dot"></div>
                                    </div>
                                </label>
                            </div>
                            <div class="printerBox p-2">
                                <input type="radio" class="btn-check" name="type" id="pos"
                                    {{ $invoice?->type == 'pos' ? 'checked' : '' }} />
                                <label for="pos" class="printer_type">
                                    <i class="fas fa-receipt icon"></i>
                                    <div class="title mt-2">{{ __('POS Printer') }}</div>
                                    <div class="chackBox">
                                        <div class="dot"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--=== invoice ===-->
            <div class="col-12 mt-4" id="invoicePrint" style="display: none">
                <div class="card shadow rounded-12 border-0">
                    <div class="card-header bg-primary py-3">
                        <h3 class="card-title m-0 text-white">
                            {{ __('Select Regular Printer Invoice') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!--=== invoice1 ===-->
                            <div class="col-md-6 col-lg-4 col-sm-12  mb-3">
                                <div class="previewPdf" id="invoice1">
                                    <img src="{{ asset('images/pdf/invoice1.png') }}" alt="" loading="lazy"
                                        width="100%" />
                                    <div class="action">
                                        <button type="button" class="activeBtn" disabled>
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                        <button type="button" class="useBtn" onclick="setInvoicePrinter('invoice1')">
                                            {{ __('User This Invoice') }}
                                        </button>
                                        <a class="previewBtn" target="_blank"
                                            href="{{ route('invoiceManage.preview', 'invoice1') }}" title="Preview">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!--=== invoice2 ===-->
                            <div class="col-md-6 col-lg-4 col-sm-12 mb-3">
                                <div class="previewPdf" id="invoice2">
                                    <img src="{{ asset('images/pdf/invoice2.png') }}" alt="" loading="lazy"
                                        width="100%" />
                                    <div class="action">
                                        <button type="button" class="activeBtn" disabled>
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                        <button type="button" class="useBtn" onclick="setInvoicePrinter('invoice2')">
                                            {{ __('User This Invoice') }}
                                        </button>
                                        <a class="previewBtn" target="_blank"
                                            href="{{ route('invoiceManage.preview', 'invoice2') }}" title="Preview">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!--=== invoice3 ===-->
                            <div class="col-md-6 col-lg-4 col-sm-12 mb-3">
                                <div class="previewPdf" id="invoice3">
                                    <img src="{{ asset('images/pdf/invoice3.png') }}" alt="" loading="lazy"
                                        width="100%" />
                                    <div class="action">
                                        <button type="button" class="activeBtn" disabled>
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                        <button type="button" class="useBtn" onclick="setInvoicePrinter('invoice3')">
                                            {{ __('User This Invoice') }}
                                        </button>
                                        <a class="previewBtn" target="_blank"
                                            href="{{ route('invoiceManage.preview', 'invoice3') }}" title="Preview">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".printerBox").on("click", function() {
            var selectedPrinterType = $(this).find("input[type='radio']").attr("id");
            $(this).find("input[type='radio']").prop("checked", true);
            $.ajax({
                type: "POST",
                url: "{{ route('invoiceManage.update') }}",
                data: {
                    type: selectedPrinterType,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    successMessage(response.message)
                    showHidePDfPreview(response.data.type)
                },
                error: function(error) {
                    console.error("Error type:", error);
                }
            });
        });

        const showHidePDfPreview = (type) => {
            if (type == 'pos') {
                $("#invoicePrint").hide();
            } else {
                $("#invoicePrint").show();
            }
        }

        var checkedId = $("input[name='type']:checked").attr("id");
        showHidePDfPreview(checkedId);

        var name = "{{ $invoice?->invoice_name ?? 'invoice1' }}";
        $('.previewPdf').removeClass('active');
        $('#' + name).addClass('active');


        const setInvoicePrinter = (name) => {
            $.ajax({
                type: "POST",
                url: "{{ route('invoiceManage.pdfUpdate') }}",
                data: {
                    invoice: name,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    successMessage(response.message)
                    $('.previewPdf').removeClass('active');
                    $('#' + name).addClass('active');
                },
                error: function(error) {
                    console.error("Error type:", error);
                }
            });
        };

        const successMessage = (message) => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: message
            });
        }
    </script>
@endpush
