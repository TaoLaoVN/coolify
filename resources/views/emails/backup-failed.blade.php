<x-emails.layout>
Sao lưu cơ sở dữ liệu cho {{ $name }} @if($database_name)(db:{{ $database_name }})@endif với tần suất {{ $frequency }} ĐÃ THẤT BẠI.

### Lý do lỗi

{{ $output }}
</x-emails.layout>
