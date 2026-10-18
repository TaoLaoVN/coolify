<div>
    @can('manageInvitations', currentTeam())
        <form wire:submit="viaLink">
            <x-application.settings-section title="Mời thành viên"
                description="Tạo liên kết lời mời hoặc gửi trực tiếp qua email.">
                <x-slot:actions>
                    @if (is_transactional_emails_enabled())
                        <x-forms.button type="button" wire:click.prevent="viaEmail">
                            <x-reicon name="notifications" class="size-3.5" />
                            Gửi email
                        </x-forms.button>
                    @endif
                    <x-forms.button type="submit" wire:target="viaLink"
                        defaultClass="button button-highlighted">
                        <x-reicon name="plus" class="size-3.5" />
                        Tạo liên kết
                    </x-forms.button>
                </x-slot:actions>

                @if (!is_transactional_emails_enabled() && isInstanceAdmin())
                    <x-callout type="warning" title="Chưa cấu hình gửi email">
                        Cấu hình email giao dịch trong cài đặt hệ thống để gửi lời mời trực tiếp.
                    </x-callout>
                @endif

                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    <x-forms.input id="email" type="email" label="Địa chỉ email"
                        placeholder="dongnghiep@example.com" required />
                    <x-forms.listbox id="role" label="Vai trò" :options="array_values(array_filter([
                        auth()->user()->role() === 'owner' ? ['value' => 'owner', 'label' => 'Chủ sở hữu'] : null,
                        ['value' => 'admin', 'label' => 'Quản trị viên'],
                        ['value' => 'member', 'label' => 'Thành viên'],
                    ]))" />
                </div>
            </x-application.settings-section>
        </form>
    @endcan
</div>
