<div>
    <x-slot:title>
        Thông báo Discord | Coolify
    </x-slot>

    <x-notification.settings-layout>
    <div class="application-settings-form flex flex-col gap-6">
        <form wire:submit="submit">
            <x-unsaved-bar action="submit" />
            <x-application.settings-section title="Discord"
                description="Gửi thông báo nhóm đến kênh Discord thông qua incoming webhook.">
                <x-slot:actions>
                    <x-notification.channel-actions :enabled="$discordEnabled" enabledProperty="discordEnabled"
                        toggleMethod="instantSaveDiscordEnabled" :canUpdate="auth()->user()->can('update', $settings)"
                        :canResource="$settings" />
                </x-slot:actions>

                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.listbox canGate="update" :canResource="$settings" id="discordPingEnabled" label="Nhắc đến khi có sự kiện khẩn cấp"
                        helper="Nhắc @here khi xảy ra sự kiện quan trọng/khẩn cấp."
                        onChange="instantSaveDiscordPingEnabled"
                        :disabled="!auth()->user()->can('update', $settings)" :options="[
                            ['value' => true, 'label' => 'Nhắc @here'],
                            ['value' => false, 'label' => 'Không nhắc'],
                        ]" />
                    <div class="lg:col-span-2">
                        @can('update', $settings)
                            <x-forms.input type="password" required id="discordWebhookUrl" label="Webhook URL"
                                helper="Tạo một incoming webhook trong phần cài đặt kênh máy chủ Discord của bạn." />
                        @else
                            <x-forms.input disabled label="Webhook URL" value="Đã ẩn (chỉ quản trị viên mới xem được)" />
                        @endcan
                    </div>
                </div>
            </x-application.settings-section>
        </form>

        <x-notification.event-grid :settings="$settings" channel="discord" />
    </div>
    </x-notification.settings-layout>
</div>
