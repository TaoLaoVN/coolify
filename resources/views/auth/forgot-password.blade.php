<x-layout-simple>
    <x-auth.shell title="Coolify"
        description="Nhập địa chỉ email tài khoản của bạn và chúng tôi sẽ gửi liên kết đặt lại mật khẩu an toàn.">
        <div class="flex flex-col gap-4">
            @if (session('status'))
                <x-auth.alert type="success">{{ session('status') }}</x-auth.alert>
            @endif

            @if ($errors->any())
                <x-auth.alert type="error">
                    <div class="flex flex-col gap-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </x-auth.alert>
            @endif

            @if (is_transactional_emails_enabled())
                <form action="/forgot-password" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <x-forms.input required type="email" name="email" autocomplete="email" autofocus
                        label="{{ __('input.email') }}" />
                    <x-forms.button class="w-full justify-center" type="submit" isHighlighted>
                        {{ __('auth.forgot_password_send_email') }}
                    </x-forms.button>
                </form>
            @else
                <x-auth.alert type="warning">
                    <p class="font-medium">Chưa cấu hình gửi email (Transactional email)</p>
                    <p class="mt-0.5 text-black/70 dark:text-white/70">
                        Cấu hình dịch vụ email hoặc xem
                        <a class="font-medium underline underline-offset-2" target="_blank" rel="noopener noreferrer"
                            href="{{ config('constants.urls.docs') }}">hướng dẫn đặt lại mật khẩu thủ công</a>.
                    </p>
                </x-auth.alert>
            @endif
        </div>

        <x-slot:footer>
            <span>Bạn đã nhớ lại mật khẩu?</span>
            <a href="/login" class="auth-text-link">Quay lại đăng nhập</a>
        </x-slot:footer>
    </x-auth.shell>
</x-layout-simple>
