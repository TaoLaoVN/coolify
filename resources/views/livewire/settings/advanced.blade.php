<div>
    <x-slot:title>
        Cài đặt nâng cao | Coolify
    </x-slot>

    <x-settings.layout>
        <form wire:submit="submit" class="application-settings-form flex min-w-0 flex-col gap-6">
            {{-- Scope dirty tracking to fields that need an explicit Save. Instant-save
                 listboxes (API, MCP, telemetry, …) update the snapshot on the server
                 immediately; without wire:target they briefly flash this bar. --}}
            <x-unsaved-bar action="submit"
                targets="custom_dns_servers,allowed_ips,webhook_allowed_internal_hosts,webhook_allow_localhost,domain_connect_private_key,image_cdn_url" />

            <x-application.settings-section id="access-section" title="Quyền truy cập & Đăng ký">
                <div class="grid gap-4 lg:grid-cols-2">
                     <x-forms.listbox id="is_registration_enabled" label="Đăng ký tài khoản"
                        helper="Cho phép người dùng tự tạo tài khoản. Khi tắt, chỉ có quản trị viên mới có thể tạo tài khoản."
                        onChange="instantSave" :options="[
                            ['value' => true, 'label' => 'Mọi người đều có thể đăng ký'],
                            ['value' => false, 'label' => 'Vô hiệu hóa đăng ký'],
                         ]" />
                     <x-forms.listbox canGate="update" :canResource="$settings"
                         id="disable_registration_when_oauth_enabled" label="Đăng ký bằng mật khẩu khi có OAuth"
                         helper="Ẩn phần đăng ký bằng mật khẩu khi có ít nhất một nhà cung cấp OAuth được kích hoạt."
                         onChange="instantSave" :options="[
                             ['value' => false, 'label' => 'Cho phép đăng ký bằng mật khẩu'],
                             ['value' => true, 'label' => 'Tắt khi OAuth được bật'],
                         ]" />
                    <x-forms.listbox id="disable_two_step_confirmation" label="Xác nhận hành động nguy hiểm"
                        helper="Chọn xem các hành động nguy hiểm/xóa có yêu cầu nhập mật khẩu và văn bản xác nhận hay không."
                        onChange="instantSave" :options="[
                            ['value' => false, 'label' => 'Yêu cầu xác nhận hai bước'],
                            ['value' => true, 'label' => 'Bỏ qua xác nhận hai bước'],
                        ]" />
                </div>
            </x-application.settings-section>

            <x-application.settings-section id="dns-section" title="Xác thực DNS">
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.listbox id="is_dns_validation_enabled" label="Xác thực DNS"
                        helper="Xác thực tên miền tùy chỉnh trước khi triển khai." onChange="instantSave" :options="[
                            ['value' => true, 'label' => 'Đã bật'],
                            ['value' => false, 'label' => 'Đã tắt'],
                        ]" />
                    <x-forms.input id="custom_dns_servers" label="Máy chủ DNS tùy chỉnh"
                        helper="Danh sách DNS phân tách bởi dấu phẩy. Để trống để sử dụng mặc định của hệ thống."
                        placeholder="1.1.1.1, 8.8.8.8" />
                </div>
            </x-application.settings-section>

            @if (isCloud())
                <x-application.settings-section id="domain-connect-section" title="Domain Connect"
                    helper="Optional RSA private key used to sign Cloudflare Domain Connect apply URLs on Coolify Cloud. Leave blank to keep the existing key.">
                    <div class="grid gap-4">
                        <x-forms.input id="domain_connect_private_key" type="password" allowToPeak
                            label="Domain Connect private key (PEM)"
                            helper="Paste a PEM private key to set or rotate. Public key must be published at domainconnect.coolify.io. Env DOMAIN_CONNECT_PRIVATE_KEY is used as a fallback when this is empty."
                            placeholder="-----BEGIN PRIVATE KEY-----" />
                        @if (filled(data_get($settings, 'domain_connect_private_key')))
                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge status="Key configured" type="success" />
                                <x-forms.button type="button" wire:click="clearDomainConnectPrivateKey" isError>
                                    Remove key
                                </x-forms.button>
                            </div>
                        @elseif (filled(config('services.domain_connect.private_key')))
                            <x-status-badge status="Using DOMAIN_CONNECT_PRIVATE_KEY from environment" type="neutral" />
                        @else
                            <x-callout type="info" title="Not configured">
                                Automated Cloudflare DNS (Domain Connect) stays hidden until a private key is set.
                            </x-callout>
                        @endif
                    </div>
                </x-application.settings-section>
            @endif

            <x-application.settings-section id="api-section" title="API và máy chủ MCP">
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.listbox id="is_api_enabled" label="Truy cập API"
                        helper="Cho phép các yêu cầu có xác thực đến Coolify REST API." onChange="instantSave"
                        :options="[
                            ['value' => true, 'label' => 'Đã bật'],
                            ['value' => false, 'label' => 'Đã tắt'],
                        ]" />
                    <x-forms.listbox id="is_mcp_server_enabled" label="Máy chủ MCP"
                        helper="Mở endpoint HTTP Streamable có xác thực tại /mcp." onChange="instantSave"
                        :options="[
                            ['value' => true, 'label' => 'Đã bật'],
                            ['value' => false, 'label' => 'Đã tắt'],
                        ]" />
                    <div class="lg:col-span-2">
                        <x-forms.input id="allowed_ips" label="IP được phép truy cập"
                            helper="Áp dụng cho cả REST API và endpoint MCP. Nhập các IP hoặc dải mạng CIDR phân tách bởi dấu phẩy. Để trống hoặc 0.0.0.0 để cho phép tất cả nguồn."
                            placeholder="192.168.1.100, 10.0.0.0/8" />
                    </div>
                </div>
                @if ($is_api_enabled && (empty($allowed_ips) || in_array('0.0.0.0', array_map('trim', explode(',', $allowed_ips ?? '')))))
                    <x-callout type="warning" title="Truy cập API đang mở cho mọi nguồn" class="mt-4">
                        Vui lòng giới hạn danh sách IP được phép trước khi sử dụng quyền truy cập API trên môi trường máy chủ công khai.
                    </x-callout>
                @endif
                @if ($is_mcp_server_enabled)
                    <x-callout type="info" title="Endpoint MCP" class="mt-4">
                        <div class="flex items-center gap-1">
                            <code>{{ url('/mcp') }}</code>
                            <x-copy-button :value="url('/mcp')" label="Sao chép endpoint MCP" />
                        </div>
                        Xác thực bằng bearer token từ
                        <a href="{{ route('security.api-tokens') }}" class="font-medium underline" {{ wireNavigate() }}>Khóa & Mã token → Mã token API</a>.
                    </x-callout>
                @endif
            </x-application.settings-section>

            <x-application.settings-section id="endpoint-section" title="Điểm cuối gửi đi (Outbound endpoints)">
                <div class="flex flex-col gap-4">
                    <x-forms.textarea id="webhook_allowed_internal_hosts" rows="4"
                        label="Mục tiêu nội bộ được phép"
                        helper="Tên máy chủ (hostname), IP hoặc dải mạng CIDR phân tách bởi dấu phẩy hoặc xuống dòng."
                        placeholder="hooks.company.local, 10.50.0.0/16" />
                    <div class="max-w-md">
                        <x-forms.listbox id="webhook_allow_localhost" label="Mục tiêu Localhost"
                            helper="Các mục tiêu loopback (127.0.0.1) cũng phải xuất hiện trong danh sách cho phép." :options="[
                                ['value' => true, 'label' => 'Được phép'],
                                ['value' => false, 'label' => 'Bị chặn'],
                            ]" />
                    </div>
                </div>
            </x-application.settings-section>

            <x-application.settings-section id="interface-section" title="Giao diện và Dữ liệu đo lường (Telemetry)">
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.listbox id="is_wire_navigate_enabled" label="Cơ chế điều hướng trang"
                        helper="Tải trước trang và chuyển trang mượt mà không cần tải lại toàn bộ." onChange="instantSave" :options="[
                            ['value' => true, 'label' => 'Điều hướng SPA (mượt mà)'],
                            ['value' => false, 'label' => 'Tải lại toàn trang'],
                        ]" />
                    <x-forms.listbox id="do_not_track" label="Dữ liệu đo lường ẩn danh"
                        helper="Kiểm soát việc thống kê số lượng cài đặt và gửi báo cáo lỗi ẩn danh." onChange="instantSave" :options="[
                            ['value' => false, 'label' => 'Đã bật'],
                            ['value' => true, 'label' => 'Đã tắt'],
                        ]" />
                    <div class="flex flex-col gap-2">
                        <x-forms.listbox id="is_sponsorship_popup_enabled" label="Nhắc nhở ủng hộ dự án"
                            helper="Hiển thị thông báo nhắc nhở tài trợ dự án hàng tháng." onChange="instantSave" :options="[
                                ['value' => true, 'label' => 'Đã bật'],
                                ['value' => false, 'label' => 'Đã tắt'],
                            ]" />
                        @if (isDev())
                            <x-forms.button type="button" @click="$dispatch('show-sponsorship-reminder')">
                                Xem thông báo nhắc nhở tài trợ
                            </x-forms.button>
                        @endif
                    </div>
                </div>
            </x-application.settings-section>

            <x-application.settings-section id="avatar-storage-section" title="Lưu trữ hình ảnh"
                helper="Chọn nơi lưu trữ ảnh đại diện và biểu tượng dự án đã nén. Sử dụng S3 cho các mô hình đa máy chủ hoặc cloud để mọi bản sao ứng dụng đều truy cập được cùng một tệp.">
                <div class="flex max-w-md flex-col gap-4">
                    <x-forms.listbox id="avatar_storage" label="Nơi lưu trữ" onChange="instantSave"
                        :options="$avatar_storage_options" />
                    <x-forms.input id="image_cdn_url" label="Đường dẫn CDN hình ảnh"
                        helper="URL công khai tùy chọn (ví dụ CDN hoặc tên miền bucket công khai) phục vụ ảnh đại diện và biểu tượng lưu trữ trên S3. Để trống để phục vụ trực tiếp qua Coolify." placeholder="https://images.example.com" />
                </div>
                @if (count($avatar_storage_options) === 1)
                    <x-callout type="info" title="Chưa cấu hình kho lưu trữ S3 nào" class="mt-4">
                        Vui lòng thêm và kiểm tra kho lưu trữ tương thích S3 trong mục Kho lưu trữ S3 trước khi chọn tại đây.
                    </x-callout>
                @endif
            </x-application.settings-section>
        </form>
    </x-settings.layout>
</div>
