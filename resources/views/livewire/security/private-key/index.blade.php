<div>
    <x-slot:title>
        Khóa & Mã xác thực | Coolify
    </x-slot>

    <x-security.settings-layout>
        <x-application.settings-section title="Khóa riêng tư (SSH)"
            description="Khóa SSH được sử dụng để kết nối các máy chủ và kho lưu trữ riêng tư." flush>
        <x-slot:actions>
            @can('create', App\Models\PrivateKey::class)
                <x-modal-confirmation title="Xác nhận xóa các khóa SSH không dùng?"
                    isErrorButton submitAction="cleanupUnusedKeys"
                    :actions="['Tất cả các khóa SSH không sử dụng (được đánh dấu không dùng) sẽ bị xóa vĩnh viễn.']"
                    :confirmWithText="false" :confirmWithPassword="false">
                    <x-slot:trigger>
                        <button type="button"
                            class="button whitespace-nowrap text-error! hover:text-error! dark:text-error!">
                            <span class="max-sm:hidden">Xóa khóa không dùng</span>
                            <span class="sm:hidden">Xóa không dùng</span>
                        </button>
                    </x-slot:trigger>
                </x-modal-confirmation>

                <div x-data="{ dropdownOpen: false }" class="relative"
                    @click.outside="dropdownOpen = false" @keydown.escape.window="dropdownOpen = false">
                    <button type="button" @click="dropdownOpen = !dropdownOpen"
                        class="button whitespace-nowrap button-highlighted"
                        aria-haspopup="menu" :aria-expanded="dropdownOpen">
                        <x-reicon name="plus" class="size-3.5" />
                        <span class="max-sm:hidden">Thêm khóa riêng tư</span>
                        <span class="sm:hidden">Thêm khóa</span>
                        <x-reicon name="chevron-down" class="size-3 opacity-55" />
                    </button>

                    <div x-cloak x-show="dropdownOpen" x-transition.origin.top.right role="menu"
                        class="listbox-panel left-auto! right-0! z-[90]! w-52! min-w-52!">
                        <button type="button" class="listbox-option justify-start! gap-2.5!"
                            wire:click="generatePrivateKey('ed25519')" @click="dropdownOpen = false"
                            role="menuitem">
                            <x-reicon name="keys" class="size-3.5 shrink-0 opacity-70" />
                            Tạo khóa ED25519
                        </button>
                        <button type="button" class="listbox-option justify-start! gap-2.5!"
                            wire:click="generatePrivateKey('rsa')" @click="dropdownOpen = false"
                            role="menuitem">
                            <x-reicon name="keys" class="size-3.5 shrink-0 opacity-70" />
                            Tạo khóa RSA
                        </button>
                        <x-modal-input title="Thêm khóa riêng tư thủ công">
                            <x-slot:content>
                                <button type="button" @click="dropdownOpen = false"
                                    class="listbox-option justify-start! gap-2.5!" role="menuitem">
                                    <x-reicon name="plus" class="size-3.5 shrink-0 opacity-70" />
                                    Thêm thủ công
                                </button>
                            </x-slot:content>
                            <livewire:security.private-key.create :modal_mode="true" />
                        </x-modal-input>
                    </div>
                </div>
            @endcan
        </x-slot:actions>


    @if ($privateKeys->isEmpty())
        <x-empty title="Chưa có khóa riêng tư nào"
            description="Tạo hoặc thêm khóa SSH để kết nối Coolify với máy chủ và kho lưu trữ riêng tư."
            icon-name="keys" />
    @else
        <div>
            <div class="grid grid-cols-[minmax(0,1fr)_7rem_1.75rem] items-center gap-3 border-b border-neutral-200 bg-neutral-50 px-4 py-2.5 text-[13px] font-medium text-neutral-500 sm:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)_7rem_1.75rem] dark:border-white/[0.08] dark:bg-white/[0.05] dark:text-fg-faint">
                <div class="pl-11">Khóa riêng tư</div>
                <div class="hidden sm:block">Mô tả</div>
                <div class="text-center">Trạng thái</div>
                <div class="w-7"></div>
            </div>
            @foreach ($privateKeys as $key)
                @can('view', $key)
                    <div wire:key="private-key-{{ $key->id }}"
                        class="border-b border-neutral-200 last:border-b-0 dark:border-white/[0.07]">
                    <div
                        class="grid min-h-14 w-full grid-cols-[minmax(0,1fr)_7rem_1.75rem] items-center gap-3 px-4 py-2.5 text-left transition-colors hover:bg-neutral-50 sm:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)_7rem_1.75rem] dark:hover:bg-white/[0.025]"
                    >
                        <div class="flex min-w-0 items-center gap-3">
                            <div
                                class="flex size-8 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-neutral-50 text-neutral-500 dark:border-white/[0.08] dark:bg-white/[0.04] dark:text-fg-dim">
                                <x-reicon name="keys" class="size-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-[13px]! leading-4! font-semibold! text-black dark:text-fg">
                                    {{ data_get($key, 'name') }}
                                </h3>
                            </div>
                        </div>
                        <p class="hidden truncate text-[12px] text-neutral-500 sm:block dark:text-fg-dim">
                            {{ $key->description ?: '-' }}
                        </p>
                        <div class="flex justify-center">
                            @if ($key->isInUse())
                                <x-status-badge label="Đang dùng" type="success" />
                            @else
                                <x-status-badge label="Chưa dùng" type="warning" />
                            @endif
                        </div>
                        <button type="button" class="icon-button" title="Chỉnh sửa khóa riêng tư"
                            aria-label="Chỉnh sửa {{ $key->name }}"
                            @click="$dispatch('open-private-key-editor', { name: @js($key->name), description: @js($key->description ?? '') })"
                            wire:click="openEditor('{{ $key->uuid }}')">
                            <x-reicon name="settings" class="size-3.5" />
                        </button>
                    </div>
                    </div>
                @else
                    <div class="grid min-h-14 cursor-not-allowed grid-cols-[minmax(0,1fr)_7rem_1.75rem] items-center gap-3 border-b border-neutral-200 px-4 py-2.5 opacity-65 last:border-b-0 sm:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)_7rem_1.75rem] dark:border-white/[0.07]"
                        title="Bạn không có quyền xem khóa riêng tư này">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex size-8 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-400 dark:border-white/[0.08] dark:bg-white/[0.05] dark:text-fg-faint">
                                <x-reicon name="keys" class="size-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-[13px]! leading-4! font-semibold! text-black dark:text-fg">
                                    {{ data_get($key, 'name') }}
                                </h3>
                            </div>
                        </div>
                        <p class="hidden truncate text-[12px] text-neutral-500 sm:block dark:text-fg-dim">{{ $key->description ?: '-' }}</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <x-status-badge label="Chỉ xem" type="neutral" />
                            @if (!$key->isInUse())
                                <x-status-badge label="Chưa dùng" type="warning" />
                            @endif
                        </div>
                        <span class="size-7"></span>
                    </div>
                @endcan
            @endforeach
        </div>
    @endif

    <x-modal-input title="Chỉnh sửa khóa riêng tư" :wireIgnore="false" :contentClicks="false"
        @open-private-key-editor.window="modalOpen=true; $nextTick(() => { $refs.loadingPrivateKeyName.value = $event.detail.name; $refs.loadingPrivateKeyDescription.value = $event.detail.description })">
        <x-slot:content><span class="hidden" aria-hidden="true"></span></x-slot:content>
        <div wire:loading.flex wire:target="openEditor" aria-label="Loading private key editor"
            class="w-full flex-col gap-4">
            <div class="grid gap-4 lg:grid-cols-2">
                <x-forms.input label="Tên" required x-ref="loadingPrivateKeyName" />
                <x-forms.input label="Mô tả" x-ref="loadingPrivateKeyDescription" />
                <div class="lg:col-span-2">
                    <x-forms.input label="Khóa công khai (Public key)" loading
                        helper="Sao chép giá trị này vào ~/.ssh/authorized_keys trên máy chủ đích." />
                </div>
                <div class="lg:col-span-2">
                    <div class="mb-1.5 flex items-center justify-between gap-3">
                        <label class="text-[13px] font-medium">Khóa riêng tư <span class="text-helper">*</span></label>
                        <span class="text-[11px] font-medium text-neutral-400 dark:text-fg-faint">Sửa khóa</span>
                    </div>
                    <x-forms.input loading :allowToPeak="false" />
                </div>
            </div>
            <div class="flex items-center justify-between gap-2 border-t border-neutral-200 pt-4 dark:border-white/[0.08]">
                <x-forms.button disabled isError>Xóa</x-forms.button>
                <x-forms.button disabled isHighlighted>Lưu thay đổi</x-forms.button>
            </div>
        </div>
        @if ($selectedPrivateKeyUuid)
            <div wire:loading.remove wire:target="openEditor">
                <livewire:security.private-key.show :private_key_uuid="$selectedPrivateKeyUuid" :modalMode="true"
                    :key="'private-key-editor-'.$selectedPrivateKeyUuid" />
            </div>
        @endif
    </x-modal-input>
        </x-application.settings-section>

    </x-security.settings-layout>
</div>
