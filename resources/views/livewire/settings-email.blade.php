<div>
    <x-slot:title>
        Email giao dịch | Coolify
    </x-slot>

    <x-settings.layout>
    <div class="application-settings-form mx-auto flex w-full max-w-none min-w-0 flex-col gap-6">
        {{-- One bar for the whole page. Three stacked bars made Save run
             submitResend(), which required an API key even when Resend was off. --}}
        <x-unsaved-bar action="submit"
            targets="smtpFromName,smtpFromAddress,smtpHost,smtpPort,smtpEncryption,smtpUsername,smtpPassword,smtpTimeout,smtpEhloDomain,resendApiKey" />

        <form wire:submit="submit">
            <x-application.settings-section title="Người gửi">
                <x-slot:actions>
                    @include('livewire.partials.settings-email-send-test')
                </x-slot:actions>
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.input required id="smtpFromName" helper="Tên hiển thị trong email gửi đi."
                        label="Tên người gửi" />
                    <x-forms.input required id="smtpFromAddress" helper="Địa chỉ email được sử dụng để gửi đi."
                        label="Địa chỉ gửi" />
                </div>
            </x-application.settings-section>
        </form>

        <form wire:submit.prevent="submitSmtp">
            <x-application.settings-section title="Máy chủ SMTP">
                <div class="grid gap-4 lg:grid-cols-3">
                    <div class="lg:col-span-3">
                        <div class="w-full sm:w-72">
                            <x-forms.listbox id="smtpEnabled" label="Gửi qua SMTP"
                                onChange="instantSaveSmtp" :options="[
                                    ['value' => true, 'label' => 'Đã bật'],
                                    ['value' => false, 'label' => 'Đã tắt'],
                                ]" />
                        </div>
                    </div>
                    <x-forms.input required id="smtpHost" placeholder="smtp.mailgun.org" label="Máy chủ (Host)" />
                    <x-forms.input required id="smtpPort" type="number" placeholder="587" label="Cổng (Port)" />
                    <x-forms.listbox required id="smtpEncryption" label="Mã hóa" :options="[
                        ['value' => 'starttls', 'label' => 'StartTLS'],
                        ['value' => 'tls', 'label' => 'TLS / SSL'],
                        ['value' => 'none', 'label' => 'Không có'],
                    ]" />
                    <x-forms.input id="smtpUsername" label="Tên đăng nhập" />
                    <x-forms.input id="smtpPassword" type="password" label="Mật khẩu"
                        autocomplete="new-password" />
                    <x-forms.input id="smtpTimeout" type="number"
                        helper="Thời gian gửi tối đa tính bằng giây." label="Thời gian chờ (Timeout)" />
                    <x-forms.input id="smtpEhloDomain" placeholder="coolify.example.com"
                        helper="Tên miền đủ điều kiện gửi trong lệnh SMTP EHLO. Sử dụng mặc định hệ thống nếu để trống."
                        label="Tên miền EHLO" />
                </div>
            </x-application.settings-section>
        </form>

        <form wire:submit.prevent="submitResend">
            <x-application.settings-section title="Resend">
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.listbox id="resendEnabled" label="Gửi qua Resend"
                        onChange="instantSaveResend" :options="[
                            ['value' => true, 'label' => 'Đã bật'],
                            ['value' => false, 'label' => 'Đã tắt'],
                        ]" />
                    <x-forms.input type="password" id="resendApiKey" placeholder="API key"
                        :required="$resendEnabled" label="Khóa API (API key)" autocomplete="new-password" />
                </div>
            </x-application.settings-section>
        </form>
    </div>
    </x-settings.layout>
</div>
