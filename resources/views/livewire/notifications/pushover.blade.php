<div>
    <x-slot:title>
        Thông báo Pushover | Coolify
    </x-slot>

    <x-notification.settings-layout>
    <div class="application-settings-form flex flex-col gap-6">
        <form wire:submit="submit">
            <x-unsaved-bar action="submit" />
            <x-application.settings-section title="Pushover"
                description="Gửi cảnh báo nhóm thông qua ứng dụng Pushover của bạn.">
                <x-slot:actions>
                    <x-notification.channel-actions :enabled="$pushoverEnabled" enabledProperty="pushoverEnabled"
                        toggleMethod="instantSavePushoverEnabled" :canUpdate="auth()->user()->can('update', $settings)"
                        :canResource="$settings" />
                </x-slot:actions>

                <div class="grid gap-4 lg:grid-cols-2">
                    @can('update', $settings)
                        <x-forms.input type="password" required id="pushoverUserKey" label="Mã khóa người dùng (User key)"
                            helper="Tìm mã này trong bảng điều khiển Pushover." />
                        <x-forms.input type="password" required id="pushoverApiToken" label="Mã token API"
                            helper="Tạo một ứng dụng trong Pushover để tạo token này." />
                    @else
                        <x-forms.input disabled label="Mã khóa người dùng (User key)" value="Đã ẩn (chỉ quản trị viên mới xem được)" />
                        <x-forms.input disabled label="Mã token API" value="Đã ẩn (chỉ quản trị viên mới xem được)" />
                    @endcan
                </div>
            </x-application.settings-section>
        </form>

        <x-notification.event-grid :settings="$settings" channel="pushover" />
    </div>
    </x-notification.settings-layout>
</div>
