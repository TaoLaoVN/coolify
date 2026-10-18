<div class="application-settings-form">
    <form class="flex flex-col gap-4" wire:submit="createPrivateKey">
        <div class="grid gap-4 lg:grid-cols-2">
            <x-forms.input id="name" label="Tên" required />
            <x-forms.input id="description" label="Mô tả" />
            <div class="lg:col-span-2">
                <x-forms.textarea realtimeValidation id="value" rows="10" monospace
                    placeholder="-----BEGIN OPENSSH PRIVATE KEY-----" label="Khóa riêng tư" required />
            </div>
            <div class="lg:col-span-2">
                <x-forms.input id="publicKey" readonly label="Khóa công khai (Public key)"
                    helper="Sao chép giá trị này vào ~/.ssh/authorized_keys trên máy chủ đích." />
            </div>
        </div>
        <div class="flex justify-end border-t border-neutral-200 pt-4 dark:border-white/[0.08]">
            <button type="submit"
                class="button button-highlighted">
                Tiếp tục
            </button>
        </div>
    </form>
</div>
