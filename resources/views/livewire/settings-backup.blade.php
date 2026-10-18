<div>
    <x-slot:title>
        Sao lưu hệ thống | Coolify
    </x-slot>

    <x-settings.layout>
    <div class="application-settings-form mx-auto flex w-full max-w-none min-w-0 flex-col gap-6">
        @if ($server->isFunctional())
            @if (isset($database) && isset($backup))
                <form wire:submit="submit">
                    <x-unsaved-bar action="submit" />

                    <x-application.settings-section title="Cơ sở dữ liệu hệ thống">
                        <div class="grid gap-4 lg:grid-cols-2">
                            <x-forms.input label="Tên" readonly :value="$database->name" />
                            <x-forms.input label="Mô tả" id="description" />
                            <div class="lg:col-span-2">
                                <x-forms.input label="UUID" readonly :value="$database->uuid" />
                            </div>
                            <x-forms.input label="Người dùng" readonly :value="$database->postgres_user" />
                        </div>
                    </x-application.settings-section>
                </form>

                <livewire:project.database.backup-edit :backup="$backup" :available-s3-storages="$s3s"
                    :status="data_get($database, 'status')" />

                <livewire:project.database.backup-executions :backup="$backup" />
            @else
                <x-application.settings-section title="Sao lưu hệ thống">
                    <x-empty title="Chưa cấu hình sao lưu"
                        description="Coolify cần một tài nguyên cơ sở dữ liệu nội bộ để tạo các bản sao lưu tự động."
                        icon-name="database" size="sm">
                        <x-slot:actions>
                            <x-forms.button wire:click="addCoolifyDatabase" isHighlighted>
                                Cấu hình sao lưu
                            </x-forms.button>
                        </x-slot:actions>
                    </x-empty>
                </x-application.settings-section>
            @endif
        @else
            <x-application.settings-section title="Sao lưu hệ thống">
                <x-callout type="danger" title="Máy chủ Localhost chưa sẵn sàng">
                    Xác thực kết nối localhost trước khi cấu hình sao lưu hệ thống.
                    <a href="{{ route('server.show', [$server->uuid]) }}" class="font-medium underline"
                        {{ wireNavigate() }}>Mở cài đặt máy chủ</a>
                </x-callout>
            </x-application.settings-section>
        @endif
    </div>
    </x-settings.layout>
</div>
