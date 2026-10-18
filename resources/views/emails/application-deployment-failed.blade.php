<x-emails.layout>
@if ($pull_request_id === 0)
Triển khai phiên bản mới của {{ $name }} tại [{{ $fqdn }}]({{ $fqdn }}) thất bại.
@else
Triển khai pull request #{{ $pull_request_id }} của {{ $name }} tại [{{ $fqdn }}]({{ $fqdn }}) thất bại.
@endif

[Xem nhật ký triển khai]({{ $deployment_url }})
</x-emails.layout>
