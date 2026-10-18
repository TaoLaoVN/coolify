<div>
    <x-slot:title>
        Thông báo | Coolify
    </x-slot>

    <x-notification.settings-layout>
    <div class="flex flex-col gap-6">
        <form wire:submit="submit" class="application-settings-form">
            <x-unsaved-bar action="submit" />
            <x-application.settings-section title="Gửi nhận Email">
                <x-slot:actions>
                    @if (auth()->user()->isAdminFromSession())
                        @can('sendTest', $settings)
                            @if ($team->isNotificationEnabled('email'))
                                <x-modal-input title="Gửi Email thử nghiệm">
                                    <x-slot:content>
                                        <button type="button" class="button">
                                            <x-reicon name="notifications" class="size-3.5" />
                                            Gửi thử nghiệm
                                        </button>
                                    </x-slot:content>
                                    <form wire:submit.prevent="sendTestEmail" class="flex w-full flex-col gap-4">
                                        <x-forms.input wire:model="testEmailAddress" placeholder="test@example.com"
                                            id="testEmailAddress" label="Người nhận" required />
                                        <div class="flex justify-end border-t border-neutral-200 pt-4 dark:border-white/[0.08]">
                                            <button type="submit" @click="modalOpen=false"
                                                class="button button-highlighted">
                                                Gửi email
                                            </button>
                                        </div>
                                    </form>
                                </x-modal-input>
                            @else
                                <button type="button" class="button" disabled>Gửi thử nghiệm</button>
                            @endif
                        @endcan
                    @endif
                </x-slot:actions>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="lg:col-span-2">
                        @if (isCloud())
                            <div class="w-full sm:w-72">
                                <x-forms.listbox canGate="update" :canResource="$settings" id="useInstanceEmailSettings" label="Dịch vụ Email"
                                    onChange="instantSave"
                                    :disabled="!auth()->user()->can('update', $settings)" :options="[
                                        ['value' => true, 'label' => 'Sử dụng dịch vụ email được lưu trữ'],
                                        ['value' => false, 'label' => 'Sử dụng cài đặt email của nhóm'],
                                    ]" />
                            </div>
                        @else
                            <div class="w-full sm:w-72">
                                <x-forms.listbox canGate="update" :canResource="$settings" id="useInstanceEmailSettings" label="Dịch vụ Email"
                                    onChange="instantSave"
                                    :disabled="!auth()->user()->can('update', $settings)" :options="[
                                        ['value' => true, 'label' => 'Sử dụng cài đặt toàn hệ thống'],
                                        ['value' => false, 'label' => 'Sử dụng cài đặt email của nhóm'],
                                    ]" />
                            </div>
                        @endif
                    </div>

                    @if (!$useInstanceEmailSettings)
                        <x-forms.input canGate="update" :canResource="$settings" required id="smtpFromName"
                            helper="Tên người gửi hiển thị trong email." label="Tên người gửi" />
                        <x-forms.input canGate="update" :canResource="$settings" required id="smtpFromAddress"
                            helper="Địa chỉ email dùng để gửi thư." label="Địa chỉ người gửi" />

                        @if (isInstanceAdmin())
                            <div class="lg:col-span-2">
                                <x-forms.button type="button" wire:click="copyFromInstanceSettings">
                                    Sao chép từ cài đặt toàn hệ thống
                                </x-forms.button>
                            </div>
                        @endif
                    @endif
                </div>
            </x-application.settings-section>
        </form>

        @if (!$useInstanceEmailSettings)
            <div class="application-settings-form">
                <x-application.settings-section title="Máy chủ SMTP"
                    description="Gửi email thông qua máy chủ SMTP của riêng bạn.">
                    <div class="grid gap-4 lg:grid-cols-3">
                        <div class="lg:col-span-3">
                            <div class="w-full sm:w-72">
                                <x-forms.listbox canGate="update" :canResource="$settings" id="smtpEnabled" label="Gửi qua SMTP"
                                    onChange="submitSmtp"
                                    :disabled="!auth()->user()->can('update', $settings)" :options="[
                                        ['value' => true, 'label' => 'Đã bật'],
                                        ['value' => false, 'label' => 'Đã tắt'],
                                    ]" />
                            </div>
                        </div>
                        <x-forms.input canGate="update" :canResource="$settings" required id="smtpHost"
                            placeholder="smtp.mailgun.org" label="Máy chủ (Host)" />
                        <x-forms.input canGate="update" :canResource="$settings" required id="smtpPort"
                            type="number" placeholder="587" label="Cổng (Port)" />
                        <x-forms.listbox canGate="update" :canResource="$settings" id="smtpEncryption" label="Mã hóa" required
                            :disabled="!auth()->user()->can('update', $settings)" :options="[
                            ['value' => 'starttls', 'label' => 'StartTLS'],
                            ['value' => 'tls', 'label' => 'TLS / SSL'],
                            ['value' => 'none', 'label' => 'Không'],
                        ]" />
                        <x-forms.input canGate="update" :canResource="$settings" id="smtpUsername"
                            label="Tên đăng nhập SMTP" />
                        @can('update', $settings)
                            <x-forms.input canGate="update" :canResource="$settings" id="smtpPassword" type="password"
                                label="Mật khẩu SMTP" />
                        @else
                            <x-forms.input disabled label="Mật khẩu SMTP" value="Đã ẩn (chỉ quản trị viên mới xem được)" />
                        @endcan
                        <x-forms.input canGate="update" :canResource="$settings" id="smtpTimeout" type="number"
                            helper="Thời gian chờ tối đa khi gửi email." label="Thời gian chờ (giây)" />
                        <x-forms.input canGate="update" :canResource="$settings" id="smtpEhloDomain"
                            placeholder="coolify.example.com"
                            helper="Tên miền đầy đủ gửi trong lệnh SMTP EHLO. Để trống sẽ dùng mặc định hệ thống."
                            label="Tên miền EHLO" />
                    </div>
                </x-application.settings-section>
            </div>

            <div class="application-settings-form">
                <x-application.settings-section title="Resend">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <x-forms.listbox canGate="update" :canResource="$settings" id="resendEnabled" label="Gửi qua Resend"
                            onChange="submitResend"
                            :disabled="!auth()->user()->can('update', $settings)" :options="[
                                ['value' => true, 'label' => 'Đã bật'],
                                ['value' => false, 'label' => 'Đã tắt'],
                            ]" />
                        @can('update', $settings)
                            <x-forms.input canGate="update" :canResource="$settings" :required="$resendEnabled"
                                type="password" id="resendApiKey" placeholder="Mã khóa API" label="Mã khóa API"
                                autocomplete="new-password" />
                        @else
                            <x-forms.input disabled label="Mã khóa API" value="Đã ẩn (chỉ quản trị viên mới xem được)" />
                        @endcan
                    </div>
                </x-application.settings-section>
            </div>
        @endif

        <div class="application-settings-form">
            <x-application.settings-section title="Sự kiện thông báo">
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-notification.event-multiselect :settings="$settings" id="deployment-email-events" label="Triển khai"
                        :events="[
                            ['property' => 'deploymentSuccessEmailNotifications', 'label' => 'Triển khai thành công', 'enabled' => $deploymentSuccessEmailNotifications],
                            ['property' => 'deploymentFailureEmailNotifications', 'label' => 'Triển khai thất bại', 'enabled' => $deploymentFailureEmailNotifications],
                        ]" />
                    <x-notification.event-multiselect :settings="$settings" id="resource-email-events" label="Tài nguyên"
                        :events="[
                            ['property' => 'statusChangeEmailNotifications', 'label' => 'Thay đổi trạng thái tài nguyên', 'enabled' => $statusChangeEmailNotifications],
                            ['property' => 'restartLimitReachedEmailNotifications', 'label' => 'Đạt giới hạn khởi động lại', 'enabled' => $restartLimitReachedEmailNotifications],
                        ]" />
                    <x-notification.event-multiselect :settings="$settings" id="backup-email-events" label="Sao lưu"
                        :events="[
                            ['property' => 'backupSuccessEmailNotifications', 'label' => 'Sao lưu thành công', 'enabled' => $backupSuccessEmailNotifications],
                            ['property' => 'backupFailureEmailNotifications', 'label' => 'Sao lưu thất bại', 'enabled' => $backupFailureEmailNotifications],
                        ]" />
                    <x-notification.event-multiselect :settings="$settings" id="scheduled-task-email-events"
                        label="Tác vụ định kỳ" :events="[
                            ['property' => 'scheduledTaskSuccessEmailNotifications', 'label' => 'Tác vụ định kỳ thành công', 'enabled' => $scheduledTaskSuccessEmailNotifications],
                            ['property' => 'scheduledTaskFailureEmailNotifications', 'label' => 'Tác vụ định kỳ thất bại', 'enabled' => $scheduledTaskFailureEmailNotifications],
                        ]" />
                    <x-notification.event-multiselect :settings="$settings" id="server-email-events" label="Máy chủ"
                        :events="[
                            ['property' => 'dockerCleanupSuccessEmailNotifications', 'label' => 'Dọn dẹp Docker thành công', 'enabled' => $dockerCleanupSuccessEmailNotifications],
                            ['property' => 'dockerCleanupFailureEmailNotifications', 'label' => 'Dọn dẹp Docker thất bại', 'enabled' => $dockerCleanupFailureEmailNotifications],
                            ['property' => 'serverDiskUsageEmailNotifications', 'label' => 'Cảnh báo dung lượng ổ đĩa', 'enabled' => $serverDiskUsageEmailNotifications],
                            ['property' => 'serverReachableEmailNotifications', 'label' => 'Máy chủ kết nối được', 'enabled' => $serverReachableEmailNotifications],
                            ['property' => 'serverUnreachableEmailNotifications', 'label' => 'Mất kết nối máy chủ', 'enabled' => $serverUnreachableEmailNotifications],
                            ['property' => 'serverPatchEmailNotifications', 'label' => 'Bản vá máy chủ', 'enabled' => $serverPatchEmailNotifications],
                            ['property' => 'traefikOutdatedEmailNotifications', 'label' => 'Proxy Traefik đã cũ', 'enabled' => $traefikOutdatedEmailNotifications],
                        ]" />
                </div>
            </x-application.settings-section>
        </div>
    </div>
    </x-notification.settings-layout>
</div>
