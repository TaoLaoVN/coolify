<div>
    @if ($modalMode)
        <div class="relative">
        <form wire:submit="changePrivateKey" class="flex flex-col gap-4" x-data="{ showPrivateKey: false }"
            wire:loading.class="pointer-events-none opacity-50" wire:target="delete">
            <div class="grid gap-4 lg:grid-cols-2">
                <x-forms.input canGate="update" :canResource="$private_key" id="name" label="Tên" required />
                <x-forms.input canGate="update" :canResource="$private_key" id="description" label="Mô tả" />
                <div class="lg:col-span-2">
                    <x-forms.input canGate="update" :canResource="$private_key" readonly id="public_key"
                        label="Khóa công khai (Public key)" helper="Sao chép giá trị này vào ~/.ssh/authorized_keys trên máy chủ đích." />
                </div>
                <div class="lg:col-span-2">
                    <div class="mb-1.5 flex items-center justify-between gap-3">
                        <label class="text-[13px] font-medium">Khóa riêng tư <span class="text-helper">*</span></label>
                        <button type="button" class="text-[11px] font-medium text-coollabs hover:underline dark:text-warning"
                            x-on:click="showPrivateKey = !showPrivateKey" x-text="showPrivateKey ? 'Ẩn trình chỉnh sửa' : 'Sửa khóa'"></button>
                    </div>
                    <div x-show="!showPrivateKey">
                        <x-forms.input canGate="update" :canResource="$private_key" allowToPeak="false"
                            type="password" id="privateKeyValue" required disabled />
                    </div>
                    <div x-cloak x-show="showPrivateKey">
                        <x-forms.textarea canGate="update" :canResource="$private_key" rows="10"
                            id="privateKeyValue" required monospace />
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between gap-2 border-t border-neutral-200 pt-4 dark:border-white/[0.08]">
                @can('delete', $private_key)
                    <x-modal-confirmation title="Xác nhận xóa khóa riêng tư?" isErrorButton buttonTitle="Xóa"
                        submitAction="delete" :disabled="$isInUse" :disabledTooltip="$deleteDisabledReason"
                        :actions="['Khóa riêng tư này sẽ bị xóa vĩnh viễn.']" confirmationText="{{ $private_key->name }}"
                        :confirmWithPassword="false" step2ButtonText="Xóa khóa riêng tư" />
                @endcan
                <x-forms.button type="submit" isHighlighted>Lưu thay đổi</x-forms.button>
            </div>
        </form>
        <div wire:loading.flex wire:target="delete"
            class="absolute inset-0 z-10 items-center justify-center rounded-lg bg-white/50 dark:bg-black/40">
            <x-loading text="Đang xóa khóa riêng tư..." />
        </div>
        </div>
    @else
    <x-slot:title>
        {{ $private_key->name }} | Khóa riêng tư | Coolify
    </x-slot>

    <x-security.settings-layout>
        <x-slot:actions>
            @if ($isGitRelated)
                <x-status-badge label="Được dùng bởi GitHub App" type="neutral" />
            @endif
            @if (data_get($private_key, 'id') > 0)
                @can('delete', $private_key)
                    <x-modal-confirmation title="Xác nhận xóa khóa riêng tư?" isErrorButton buttonTitle="Xóa"
                        submitAction="delete({{ $private_key->id }})" :disabled="$isInUse"
                        :disabledTooltip="$deleteDisabledReason" :actions="[
                            'Khóa riêng tư này sẽ bị xóa vĩnh viễn.',
                            'Các máy chủ và nguồn Git đang dùng khóa này sẽ ngừng hoạt động.',
                        ]"
                        confirmationText="{{ $private_key->name }}"
                        confirmationLabel="Nhập tên khóa riêng tư để xác nhận xóa"
                        shortConfirmationLabel="Tên khóa riêng tư" :confirmWithPassword="false"
                        step2ButtonText="Xóa khóa riêng tư" />
                @endcan
            @endif
        </x-slot:actions>

    <form wire:submit="changePrivateKey" class="application-settings-form" x-data="{ showPrivateKey: false }">
        <x-unsaved-bar action="changePrivateKey" />
        <x-application.settings-section title="Cài đặt chung">
            <div class="grid gap-4 lg:grid-cols-2">
                <x-forms.input canGate="update" :canResource="$private_key" id="name" label="Tên" required />
                <x-forms.input canGate="update" :canResource="$private_key" id="description"
                    label="Mô tả" />
                <div class="lg:col-span-2">
                    <x-forms.input canGate="update" :canResource="$private_key" readonly id="public_key"
                        label="Khóa công khai (Public key)"
                        helper="Sao chép giá trị này vào ~/.ssh/authorized_keys trên máy chủ đích." />
                </div>
                <div class="lg:col-span-2">
                    <div class="mb-1.5 flex items-center justify-between gap-3">
                        <label class="text-[13px] font-medium">Khóa riêng tư <span class="text-helper">*</span></label>
                        <button type="button"
                            class="text-[11px] font-medium text-coollabs hover:underline dark:text-warning"
                            x-on:click="showPrivateKey = !showPrivateKey"
                            x-text="showPrivateKey ? 'Ẩn trình chỉnh sửa' : 'Sửa khóa'"></button>
                    </div>
                    <div x-show="!showPrivateKey">
                        <x-forms.input canGate="update" :canResource="$private_key" allowToPeak="false"
                            type="password" id="privateKeyValue" required disabled />
                    </div>
                    <div x-cloak x-show="showPrivateKey">
                        <x-forms.textarea canGate="update" :canResource="$private_key" rows="12"
                            id="privateKeyValue" required monospace />
                    </div>
                </div>
            </div>
        </x-application.settings-section>
    </form>
    </x-security.settings-layout>
    @endif
</div>
