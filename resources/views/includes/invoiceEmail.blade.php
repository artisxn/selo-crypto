<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    Halo {{ $name }},
    <br>
    Nomor Invoice anda adalah #{{ $invoice }} <br>
    Harap segera membayar pesanan anda, Jika anda belum membayar selama 24 jam maka pesanan anda akan dibatalkan secara otomatis<br>
    Pembayaran dapat melalui 
    @if ($method === 'Bank Transfer')
        {!! '<a href="'.$gv.'">Gudang Voucher</a>' !!}
    @else
        Aplikasi EDCCASH
    @endif
    
    <br>
    Untuk melihat detail invoice, <a href="{{ route('user-invoice-detail', base64_encode($invoice)) }}">klik disini</a>
    {{-- @if ($method === 'Bank Transfer')
        <br>
    {!! $response !!} 
    @elseif($method === 'EDCCASH')
        Aplikasi EDCCASH
    @endif --}}

    <br>
    <br>
    <br>
    Hormat Kami

    <br>
    <br>
    <br>
    EDCSTORE

</body>
</html>