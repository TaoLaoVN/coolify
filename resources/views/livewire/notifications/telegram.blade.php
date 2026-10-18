<div>
    <x-slot:title>
        Thông báo Telegram | Coolify
    </x-slot>

    <x-notification.settings-layout>
    <div class="application-settings-form flex flex-col gap-6">
        <form wire:submit="submit">
            <x-unsaved-bar action="submit" />
            <x-application.settings-section title="Telegram"
                description="Gửi thông báo nhóm thông qua bot và đoạn chat Telegram.">
                <x-slot:actions>
                    <x-notification.channel-actions :enabled="$telegramEnabled" enabledProperty="telegramEnabled"
                        toggleMethod="instantSaveTelegramEnabled" :canUpdate="auth()->user()->can('update', $settings)"
                        :canResource="$settings" />
                </x-slot:actions>

                <div class="grid gap-4 lg:grid-cols-2">
                    @can('update', $settings)
                        <x-forms.input type="password" autocomplete="new-password" required id="telegramToken"
                            label="Mã token Bot API" helper="Tạo bot thông qua @BotFather để nhận mã token này." />
                        <x-forms.input type="password" autocomplete="new-password" required id="telegramChatId"
                            label="ID nhóm chat (Chat ID)" helper="Thêm bot vào nhóm chat của bạn, sau đó điền Chat ID vào đây." />
                    @else
                        <x-forms.input disabled label="Mã token Bot API" value="Đã ẩn (chỉ quản trị viên mới xem được)" />
                        <x-forms.input disabled label="ID nhóm chat (Chat ID)" value="Đã ẩn (chỉ quản trị viên mới xem được)" />
                    @endcan
                </div>
            </x-application.settings-section>
        </form>

        <x-notification.event-grid :settings="$settings" channel="telegram" threaded />
    </div>
    </x-notification.settings-layout>
</div>
