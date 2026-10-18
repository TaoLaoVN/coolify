<div>
    <x-slot:title>
        Cài đặt hệ thống | Coolify
    </x-slot>

    <x-settings.layout>
        <form wire:submit="submit" class="application-settings-form flex w-full min-w-0 flex-col gap-6">
            {{-- instance_timezone auto-saves via $wire.set + submit; exclude it so
                 the bar does not flash while the snapshot catches up. --}}
            <x-unsaved-bar action="submit"
                targets="fqdn,instance_name,public_ipv4,public_ipv6,dev_helper_version" />
            <x-application.settings-section title="Cài đặt chung">
                <div class="grid gap-4 lg:grid-cols-2">
                    <div @class([
                        'lg:col-span-2' => !str_starts_with(strtolower($fqdn ?? ''), 'https://'),
                    ])>
                        <x-forms.input canGate="update" :canResource="$settings" id="fqdn" label="Địa chỉ URL"
                            helper="Nhập địa chỉ URL đầy đủ của hệ thống (ví dụ: https://coolify.yourdomain.com).<br><br><span class='text-coollabs dark:text-warning'>Quan trọng:</span> Hãy bao gồm <b>https://</b> để bảo vệ bảng điều khiển bằng HTTPS."
                            placeholder="https://coolify.yourdomain.com" />
                    </div>

                    @if (str_starts_with(strtolower($fqdn ?? ''), 'https://'))
                        <div>
                            <x-forms.listbox canGate="update" :canResource="$settings"
                                id="is_dashboard_force_https_enabled" label="Chuyển hướng HTTP sang HTTPS"
                                onChange="submit"
                                helper="Chỉ tắt khi Cloudflare Tunnel hoặc proxy khác kết nối tới Coolify qua HTTP. Giữ bật khi Cloudflare sử dụng chế độ SSL Full hoặc Full (Strict)."
                                :options="[
                                    ['value' => true, 'label' => 'Đã bật'],
                                    ['value' => false, 'label' => 'Đã tắt'],
                                ]" />
                        </div>
                    @endif

                    <x-forms.input canGate="update" :canResource="$settings" id="instance_name" label="Tên hệ thống"
                        placeholder="Coolify" helper="Tên tùy chỉnh cho phiên bản Coolify này." />

                    {{-- Use a searchable listbox so the label row (h-4) and control height match
                         sibling x-forms.input fields (Name). onChange auto-saves like before. --}}
                    <x-forms.listbox searchable id="instance_timezone" label="Múi giờ hệ thống"
                        helper="Múi giờ sử dụng cho kiểm tra bản cập nhật và lịch cập nhật tự động."
                        searchPlaceholder="Tìm kiếm múi giờ" searchEmptyText="Không tìm thấy múi giờ"
                        onChange="submit" :options="collect($this->timezones)->map(fn ($timezone) => [
                            'value' => $timezone,
                            'label' => $timezone,
                        ])->all()" canGate="update" :canResource="$settings" />
                </div>
            </x-application.settings-section>

            <x-application.settings-section title="Địa chỉ mạng">
                <x-slot:actions>
                    <x-forms.button canGate="update" :canResource="$settings" type="button"
                        wire:click="detectPublicIps">
                        Tự động nhận diện
                    </x-forms.button>
                </x-slot:actions>
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.input canGate="update" :canResource="$settings" id="public_ipv4" type="password"
                        label="IPv4 công khai của hệ thống"
                        helper="Coolify tự động nhận diện địa chỉ này khi khởi động nếu ô để trống. Đặt thủ công nếu địa chỉ nhận diện bị sai. Nút Tự động nhận diện sẽ tra cứu lại và lưu."
                        placeholder="1.2.3.4" autocomplete="new-password" />
                    <x-forms.input canGate="update" :canResource="$settings" id="public_ipv6" type="password"
                        label="IPv6 công khai của hệ thống"
                        helper="Coolify tự động nhận diện địa chỉ này khi khởi động nếu ô để trống. Đặt thủ công nếu địa chỉ nhận diện bị sai. Nút Tự động nhận diện sẽ tra cứu lại và lưu."
                        placeholder="2001:db8::1" autocomplete="new-password" />
                </div>
            </x-application.settings-section>

            @if (isDev())
                <x-application.settings-section title="Công cụ hỗ trợ phát triển (Development helper)">
                    <x-forms.input canGate="update" :canResource="$settings" id="dev_helper_version"
                        label="Ghi đè phiên bản"
                        helper="Ghi đè phiên bản image coolify-helper mặc định. Để trống để sử dụng {{ config('constants.coolify.helper_version') }}."
                        placeholder="{{ config('constants.coolify.helper_version') }}" />
                </x-application.settings-section>
            @endif
        </form>

    <x-domain-conflict-modal :conflicts="$domainConflicts" :showModal="$showDomainConflictModal"
        confirmAction="confirmDomainUsage">
        <x-slot:consequences>
            <ul class="mt-2 ml-4 list-disc">
                <li>Tên miền phiên bản Coolify sẽ xung đột với các tài nguyên hiện có.</li>
                <li>Chứng chỉ SSL có thể không hoạt động chính xác.</li>
                <li>Hành vi định tuyến mạng sẽ không thể dự đoán trước.</li>
                <li>Bạn có thể không truy cập được vào bảng điều khiển Coolify bình thường.</li>
            </ul>
        </x-slot:consequences>
    </x-domain-conflict-modal>
    </x-settings.layout>
</div>
