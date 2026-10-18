<x-emails.layout>
@if ($pull_request_id === 0)
Phiên bản mới của {{ $name }} hiện đã sẵn sàng tại [{{ $fqdn }}]({{ $fqdn }}) .
@else
Pull request #{{ $pull_request_id }} của {{ $name }} đã được triển khai thành công tại [{{ $fqdn }}]({{ $fqdn }}).
@endif

[Xem nhật ký triển khai]({{ $deployment_url }})

</x-emails.layout>
