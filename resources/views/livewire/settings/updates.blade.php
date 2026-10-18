<div>
    <x-slot:title>
        Cài đặt cập nhật | Coolify
    </x-slot>

    <x-settings.layout>
        <form wire:submit="submit" class="application-settings-form flex min-w-0 flex-col gap-6">
            {{-- Exclude is_auto_update_enabled (instantSave) so the bar does not flash. --}}
            <x-unsaved-bar action="submit" targets="update_check_frequency,auto_update_frequency" />

            <x-application.settings-section title="Cập nhật Coolify"
                helper="Cài đặt phiên bản Coolify mới nhất theo cách thủ công khi có bản cập nhật.">
                <livewire:upgrade :full-button="true" key="settings-upgrade" />
            </x-application.settings-section>

            <x-application.settings-section title="Kiểm tra bản cập nhật">
                <x-slot:actions>
                    <x-forms.button type="button" wire:click="checkManually">
                        <x-reicon name="refresh" class="size-3.5" />
                        Kiểm tra ngay
                    </x-forms.button>
                </x-slot:actions>
                <x-forms.input required id="update_check_frequency" label="Tần suất kiểm tra"
                    placeholder="0 * * * *"
                    helper="Biểu thức cron hoặc định kỳ như hourly, daily, weekly, monthly, hoặc yearly." />
            </x-application.settings-section>

            <x-application.settings-section title="Tự động cập nhật">
                <div class="grid gap-4 lg:grid-cols-2">
                    @if (!is_null(config('constants.coolify.autoupdate', null)))
                        <x-forms.listbox disabled id="is_auto_update_enabled" label="Tự động cập nhật"
                            helper="Được kiểm soát bởi biến môi trường AUTOUPDATE." :options="[
                                ['value' => true, 'label' => 'Đã bật'],
                                ['value' => false, 'label' => 'Đã tắt'],
                            ]" />
                    @else
                        <x-forms.listbox id="is_auto_update_enabled" label="Tự động cập nhật"
                            onChange="instantSave" :options="[
                                ['value' => true, 'label' => 'Đã bật'],
                                ['value' => false, 'label' => 'Đã tắt'],
                            ]" />
                    @endif

                    @if (is_null(config('constants.coolify.autoupdate', null)) && $is_auto_update_enabled)
                        <x-forms.input required id="auto_update_frequency" label="Tần suất cập nhật"
                            placeholder="0 0 * * *"
                            helper="Biểu thức cron hoặc định kỳ để cài đặt các bản cập nhật." />
                    @else
                        <x-forms.input label="Tần suất cập nhật" disabled placeholder="Đã tắt" />
                    @endif
                </div>
            </x-application.settings-section>

            <x-application.settings-section title="Kho lưu trữ ảnh (Registry)">
                <div class="max-w-md">
                    <x-forms.listbox id="docker_registry_url" label="Docker registry" :options="[
                        ['value' => 'docker.io', 'label' => 'Docker Hub'],
                        ['value' => 'ghcr.io', 'label' => 'GitHub Container Registry'],
                    ]"
                        helper="Chuyển đổi registry nếu nguồn hiện tại bị giới hạn tần suất yêu cầu (rate limited)." />
                </div>
            </x-application.settings-section>
        </form>
    </x-settings.layout>
</div>
