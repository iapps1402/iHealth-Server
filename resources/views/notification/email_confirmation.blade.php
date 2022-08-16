@section('container')
    <p>To verify your email address, please use this below code:</p>
    <div style="font-weight: bold; letter-spacing: 12px;font-size: 15pt;margin: 15px 0">{{ $code->code }}</div>

    <p>If you didn't request any email, ignore this email.</p>
@endsection
@include('notification.theme.master')
