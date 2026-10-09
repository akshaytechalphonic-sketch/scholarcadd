@extends('layouts.master')
@section('Page_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="fdo-left">
                <img src="{{ asset('assets_new/images/welcome-fdo-back.svg') }}" alt="Welcome Image">
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="fdo-logo">
                <img src="{{ asset('assets_new/images/fdo-logo.svg') }}" alt="FDO Logo">
            </div>
            <div class="fdo-right">
                <form action="{{ route('2fa.verify') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3 position-relative">
                            <p class="d-flex justify-content-center font-size-14 gap-2">
                                <span><i class="fas fa-envelope"></i></span>
                                <span>{{Auth::user()->email}}</span>
                            </p>
                            <h2 class="heading-app mb-2 text-center">Approve Employee Sign in Request</h2>
                            <p class="font-size-12 text-center">Please give token to verify access and approve the employee login request.</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="inputWithIcon">
                                <input type="text" class="fd-input" id="numberInput" name="token" placeholder="Enter Given Number" maxlength="6" onkeypress="return (event.charCode > 47 && event.charCode < 58)" required>
                                <i class="fa fa-phone-alt fa-md fa-fw" aria-hidden="true"></i>
                                @if ($errors->has('token'))
                                    <div class="text-danger d-none">{{ $errors->first('token') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 d-flex gap-3">
                            <a href="{{route('login')}}"><button type="reset" class="col-md-6 can-btn" >Cancel</button></a>
                            <button type="submit" id="verifyButton" class="col-md-6 log-btn" disabled>Verify</button>
                        </div>
                        <div class="col-md-12 mb-3 d-flex justify-content-center align-items-center text-center">
                            <!-- Anchor tag to open modal -->
                            <a class="text-center" href="#" data-bs-toggle="modal" data-bs-target="#qrCodeModal">Didn't get token ?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
</div>
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Scan below QR code with your MFA to get token</p>
                @if(isset($qrCodeUrl))
                    <img src="{{ $qrCodeUrl }}" alt="QR Code" style="width: 100%; height: auto;">
                @else
                    <p>No QR code available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
 
<script>
    $(document).ready(function () {
        var countdownElement = $('#countdown');
 
 
        $('#numberInput').on('input', function () {
            var value = $(this).val();
            if (/^\d{6}$/.test(value)) {
                $('#verifyButton').prop('disabled', false);
            } else {
                $('#verifyButton').prop('disabled', true);
            }
        });
 
        $('#resendButton').on('click', function () {
            showLoader();
            $.ajax({
                url: '{{ route('2fa.resend') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideLoader();
                    toastr.success(response.message, 'OTP Resend');
                    endTime = new Date().getTime() + duration * 1000;
                    $('#resendButton').prop('disabled', true);
                    startTimer();
                },
                error: function(xhr) {
                    hideLoader();
                    toastr.error('Failed to resend OTP. Please try again.', 'Error');
                }
            });
        });
 
        const errorMessage = {!! json_encode($errors->first('token')) !!};
        if (errorMessage) {
            toastr.error(errorMessage, 'Error');
        }
    });
</script>
 
 
 
@endsection