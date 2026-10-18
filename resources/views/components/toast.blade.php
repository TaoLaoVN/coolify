<div x-data
    x-init="
        const toastDict = {
            'Success': 'Thành công',
            'Error': 'Lỗi',
            'Warning': 'Cảnh báo',
            'Info': 'Thông tin',
            'Saved.': 'Đã lưu.',
            'Saved': 'Đã lưu',
            'Configuration saved.': 'Đã lưu cấu hình.',
            'Configuration saved successfully.': 'Đã lưu cấu hình thành công.',
            'Settings saved.': 'Đã lưu cài đặt.',
            'Settings updated!': 'Đã cập nhật cài đặt!',
            'Settings updated.': 'Đã cập nhật cài đặt.',
            'Copied to clipboard': 'Đã sao chép vào bộ nhớ tạm',
            'Copied to clipboard.': 'Đã sao chép vào bộ nhớ tạm.',
            'Realtime events configured!': 'Đã cấu hình sự kiện thời gian thực!',
            'Server validated successfully.': 'Xác thực máy chủ thành công.',
            'Server validated, proxy is starting in a moment.': 'Máy chủ đã xác thực, proxy đang khởi động trong giây lát.',
            'Server settings updated.': 'Đã cập nhật cài đặt máy chủ.',
            'Server updated.': 'Đã cập nhật máy chủ.',
            'Server is reachable.': 'Máy chủ có thể kết nối được.',
            'Server is not reachable.': 'Không thể kết nối đến máy chủ.',
            'Database saved.': 'Đã lưu cơ sở dữ liệu.',
            'Database updated.': 'Đã cập nhật cơ sở dữ liệu.',
            'Database deleted.': 'Đã xóa cơ sở dữ liệu.',
            'Application deleted.': 'Đã xóa ứng dụng.',
            'Application settings updated!': 'Đã cập nhật cài đặt ứng dụng!',
            'Application source updated!': 'Đã cập nhật nguồn ứng dụng!',
            'Test notification sent.': 'Đã gửi thông báo thử nghiệm.',
            'Test Email sent.': 'Đã gửi email thử nghiệm.',
            'Test email sent successfully! Check your email inbox.': 'Đã gửi email thử nghiệm thành công! Vui lòng kiểm tra hộp thư đến.',
            'Environment variable added.': 'Đã thêm biến môi trường.',
            'Environment variable updated.': 'Đã cập nhật biến môi trường.',
            'Environment variable deleted successfully.': 'Đã xóa biến môi trường thành công.',
            'Environment variables updated.': 'Đã cập nhật các biến môi trường.',
            'Proxy configuration saved.': 'Đã lưu cấu hình proxy.',
            'Proxy configuration reset to default.': 'Đã khôi phục cấu hình proxy về mặc định.',
            'Proxy is restarting.': 'Proxy đang khởi động lại.',
            'Proxy is starting.': 'Proxy đang khởi động.',
            'Proxy is stopping.': 'Proxy đang dừng.',
            'Proxy has exited.': 'Proxy đã thoát.',
            'Proxy is running.': 'Proxy đang chạy.',
            'Restarting Sentinel.': 'Đang khởi động lại Sentinel.',
            'Restarting database.': 'Đang khởi động lại cơ sở dữ liệu.',
            'Container started.': 'Đã khởi động container.',
            'Container stopped.': 'Đã dừng container.',
            'Container restarted.': 'Đã khởi động lại container.',
            'Container removed.': 'Đã gỡ bỏ container.',
            'Profile updated.': 'Đã cập nhật hồ sơ cá nhân.',
            'Password updated.': 'Đã cập nhật mật khẩu.',
            'Team updated.': 'Đã cập nhật nhóm.',
            'Project updated.': 'Đã cập nhật dự án.',
            'Tag added.': 'Đã thêm thẻ.',
            'Tag deleted.': 'Đã xóa thẻ.',
            'Private key created successfully.': 'Đã tạo khóa SSH thành công.',
            'Private key generated successfully.': 'Đã sinh khóa SSH thành công.',
            'Private key updated successfully.': 'Đã cập nhật khóa SSH thành công.',
            'Private key updated!': 'Đã cập nhật khóa SSH!',
            'Private key updated.': 'Đã cập nhật khóa SSH.',
            'Cloudflare Tunnel enabled.': 'Đã bật Cloudflare Tunnel.',
            'Cloudflare Tunnel disabled.': 'Đã tắt Cloudflare Tunnel.',
            'Email notifications settings updated.': 'Đã cập nhật cài đặt thông báo Email.',
            'Transactional email settings updated.': 'Đã cập nhật cài đặt email giao dịch.',
            'SMTP settings updated.': 'Đã cập nhật cài đặt SMTP.',
            'Resend settings updated.': 'Đã cập nhật cài đặt Resend.',
            'No notifications enabled': 'Chưa bật kênh thông báo nào',
            'Backup queued.': 'Đã đưa tác vụ sao lưu vào hàng đợi.',
            'Backup queued. It will be available in a few minutes.': 'Đã đưa tác vụ sao lưu vào hàng đợi. Bản sao lưu sẽ sẵn sàng trong vài phút.',
            'Backup updated.': 'Đã cập nhật sao lưu.',
            'Backup deleted.': 'Đã xóa sao lưu.',
            'Backup recovery queued.': 'Đã đưa tác vụ phục hồi sao lưu vào hàng đợi.',
            'Scheduled task added.': 'Đã thêm tác vụ định kỳ.',
            'Scheduled task updated.': 'Đã cập nhật tác vụ định kỳ.',
            'Scheduled task executed.': 'Đã thực thi tác vụ định kỳ.',
            'Scheduled backup deleted.': 'Đã xóa lịch sao lưu.',
            'Mass deployment started.': 'Đã bắt đầu triển khai hàng loạt.',
            'Deployment skipped': 'Bỏ qua đợt triển khai',
            'Domain added.': 'Đã thêm tên miền.',
            'Domain removed.': 'Đã xóa tên miền.',
            'Domain updated.': 'Đã cập nhật tên miền.',
            'Domain generated.': 'Đã tạo tên miền.',
            'Wildcard domain generated.': 'Đã tạo tên miền ký tự đại diện (wildcard).',
            'Welcome onboard!': 'Chào mừng bạn!',
            'Feedback sent.': 'Đã gửi phản hồi.',
            'We will get in touch with you as soon as possible.': 'Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.',
            'Invalid API token. Please check your token and try again.': 'Mã token API không hợp lệ. Vui lòng kiểm tra lại mã và thử lại.',
            'Current password is incorrect.': 'Mật khẩu hiện tại không chính xác.',
            'The two new passwords does not match.': 'Hai mật khẩu mới không khớp nhau.',
        };
        const translateToast = (str) => {
            if (typeof str !== 'string') return str;
            const t = str.trim();
            if (toastDict[t]) return toastDict[t];
            return str;
        };
        window.toast = function(message, options = {}) {
            try {
                window.dispatchEvent(new CustomEvent('toast-show', {
                    detail: {
                        type: options.type ?? 'default',
                        message: translateToast(message),
                        description: translateToast(options.description ?? ''),
                        position: options.position ?? 'bottom-right',
                        html: options.html ?? '',
                        persistent: options.persistent ?? false,
                    },
                }));
            } catch (error) {
                console.error('Error showing toast:', error);
            }
        };
    ">
    <template x-teleport="body">
        <ul x-data="{
            toasts: [],
            position: 'bottom-right',
            addToast(event) {
                this.position = event.detail.position || 'bottom-right';

                const toast = {
                    id: `toast-${Math.random().toString(16).slice(2)}`,
                    visible: false,
                    message: event.detail.message,
                    description: event.detail.description,
                    type: event.detail.type,
                    html: event.detail.html ? window.sanitizeHTML(event.detail.html) : '',
                    timeout: null,
                    persistent: event.detail.persistent === true,
                    copied: false,
                    copiedTimeout: null,
                };

                this.toasts.unshift(toast);
                if (this.toasts.length > 4) {
                    const index = this.toasts.findLastIndex(item => !item.persistent);
                    if (index !== -1) {
                        const [removed] = this.toasts.splice(index, 1);
                        clearTimeout(removed.timeout);
                        clearTimeout(removed.copiedTimeout);
                    }
                }

                this.$nextTick(() => {
                    const activeToast = this.toasts.find(item => item.id === toast.id);
                    if (!activeToast) return;

                    activeToast.visible = true;
                    this.scheduleToast(activeToast, 4000);
                });
            },
            scheduleToast(toast, delay = 2000) {
                clearTimeout(toast.timeout);
                if (toast.persistent) return;
                toast.timeout = setTimeout(() => this.removeToast(toast.id), delay);
            },
            pauseToast(toast) {
                clearTimeout(toast.timeout);
            },
            resumeToast(toast) {
                this.scheduleToast(toast);
            },
            async copyToast(toast) {
                await navigator.clipboard.writeText(toast.description);
                toast.copied = true;
                clearTimeout(toast.copiedTimeout);
                toast.copiedTimeout = setTimeout(() => {
                    toast.copied = false;
                }, 2000);
            },
            removeToast(id) {
                const toast = this.toasts.find(item => item.id === id);
                if (!toast) return;

                toast.visible = false;
                clearTimeout(toast.timeout);
                clearTimeout(toast.copiedTimeout);
                setTimeout(() => {
                    this.toasts = this.toasts.filter(item => item.id !== id);
                }, 150);
            },
        }" @toast-show.window="addToast($event)"
            class="fixed z-9999 flex w-[calc(100%-2rem)] gap-2.5 sm:max-w-[26rem]"
            :class="{
                'right-4 top-4 flex-col': position === 'top-right',
                'left-4 top-4 flex-col': position === 'top-left',
                'left-1/2 top-4 -translate-x-1/2 flex-col': position === 'top-center',
                'right-4 bottom-4 flex-col-reverse': position === 'bottom-right',
                'left-4 bottom-4 flex-col-reverse': position === 'bottom-left',
                'left-1/2 bottom-4 -translate-x-1/2 flex-col-reverse': position === 'bottom-center',
            }"
            x-cloak>
            <template x-for="toast in toasts" :key="toast.id">
                <li x-show="toast.visible"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="translate-y-2 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-1 opacity-0"
                    @mouseenter="pauseToast(toast)" @mouseleave="resumeToast(toast)"
                    class="surface-popover relative flex w-full items-start rounded-lg group"
                    :class="{ 'p-3.5 pr-20': !toast.html, 'p-0': toast.html }">
                    <template x-if="!toast.html">
                        <div class="flex min-w-0 items-start gap-3">
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-lg"
                                :class="{
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400': toast.type === 'success',
                                    'bg-coollabs/10 text-coollabs dark:bg-warning/10 dark:text-warning': toast.type === 'info',
                                    'bg-amber-100 text-amber-700 dark:bg-warning/10 dark:text-warning': toast.type === 'warning',
                                    'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400': toast.type === 'danger',
                                    'bg-neutral-100 text-neutral-600 dark:bg-white/[0.06] dark:text-fg-dim': toast.type === 'default',
                                }">
                                <x-reicon name="check-circle" x-show="toast.type === 'success'"
                                    class="size-4" />
                                <x-reicon name="info-circle"
                                    x-show="toast.type === 'info' || toast.type === 'default'"
                                    class="size-4" />
                                <x-reicon name="alert-triangle" x-show="toast.type === 'warning'"
                                    class="size-4" />
                                <x-reicon name="alert-circle" x-show="toast.type === 'danger'"
                                    class="size-4" />
                            </div>

                            <div class="min-w-0 flex-1 pt-0.5">
                                <p class="text-sm font-semibold leading-5 text-neutral-950 dark:text-fg"
                                    x-text="toast.message"></p>
                                <div x-show="toast.description"
                                    class="mt-0.5 w-full whitespace-pre-wrap break-words text-xs leading-5 text-neutral-600 dark:text-fg-dim"
                                    x-html="window.sanitizeHTML(toast.description)"></div>
                            </div>
                        </div>
                    </template>

                    <template x-if="toast.html">
                        <div class="w-full" x-html="window.sanitizeHTML(toast.html)"></div>
                    </template>

                    <button type="button" x-show="toast.description && !toast.html"
                        @click="copyToast(toast)" :title="toast.copied ? 'Đã sao chép' : 'Sao chép chi tiết'"
                        class="absolute right-10 top-2.5 flex size-7 items-center justify-center rounded-md text-neutral-400 opacity-0 transition-colors hover:bg-black/5 hover:text-neutral-700 group-hover:opacity-100 dark:text-fg-faint dark:hover:bg-white/[0.06] dark:hover:text-fg"
                        :class="{ 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400': toast.copied }">
                        <svg x-show="!toast.copied" class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 7.5V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0120.25 6v7.5A2.25 2.25 0 0118 15.75h-1.5m-8.25-8.25H6A2.25 2.25 0 003.75 9.75v7.5A2.25 2.25 0 006 19.5h7.5a2.25 2.25 0 002.25-2.25V15m-7.5-7.5h5.25A2.25 2.25 0 0115.75 9.75V15" />
                        </svg>
                        <x-reicon name="check" x-show="toast.copied" class="size-3.5" />
                    </button>

                    <button type="button" @click="removeToast(toast.id)" aria-label="Đóng"
                        class="absolute right-2.5 top-2.5 flex size-7 items-center justify-center rounded-md text-neutral-400 transition-colors hover:bg-black/5 hover:text-neutral-700 dark:text-fg-faint dark:hover:bg-white/[0.06] dark:hover:text-fg">
                        <x-reicon name="x" class="size-3.5" />
                    </button>
                </li>
            </template>
        </ul>
    </template>
</div>
