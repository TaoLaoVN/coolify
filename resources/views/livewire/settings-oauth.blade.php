<div>
    <x-slot:title>
        Xác thực & Đăng nhập | Coolify
    </x-slot>

    <x-settings.layout>
        <x-slot:submenu>
            <div
                x-data="{ activeProvider: location.hash.slice(1).replace('-oauth-section', '') || @js($selectedProvider ?? array_key_first($oauth_settings_map)) }"
                @hashchange.window="activeProvider = location.hash.slice(1).replace('-oauth-section', '')">
                <nav aria-label="OAuth providers" class="grid gap-0.5 py-1">
                    @foreach ($oauth_settings_map as $provider => $oauth_setting)
                        <a href="#{{ $provider }}-oauth-section" class="menu-item min-h-8! py-1! text-[12px]!"
                            :class="{ 'menu-item-active': activeProvider === '{{ $provider }}' }"
                            @click.prevent="activeProvider = '{{ $provider }}'; history.replaceState(null, '', '#{{ $provider }}-oauth-section'); window.scrollToSettingsSection?.('{{ $provider }}-oauth-section')">
                            <span class="menu-item-icon bg-current"
                                style="mask: url('{{ asset('svgs/' . $provider . '.svg') }}') center / contain no-repeat; -webkit-mask: url('{{ asset('svgs/' . $provider . '.svg') }}') center / contain no-repeat;"></span>
                            <span class="menu-item-label">{{ $oauth_setting['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </x-slot:submenu>

        <form wire:submit="submit" class="application-settings-form flex w-full min-w-0 flex-col gap-6">
            <x-unsaved-bar action="submit" />

            <x-application.settings-section title="Đăng ký tài khoản"
                description="Kiểm soát đăng ký bằng mật khẩu khi có nhà cung cấp OAuth.">
                <x-forms.checkbox canGate="update" :canResource="$settings"
                    id="disable_registration_when_oauth_enabled"
                    label="Vô hiệu hóa đăng ký bằng mật khẩu khi OAuth được bật"
                    helper="Các nhà cung cấp OAuth vẫn có thể tạo người dùng khi tính năng đăng ký được bật cho nhà cung cấp đó."
                    instantSave="saveRegistrationPolicy" />
            </x-application.settings-section>

            @foreach ($oauth_settings_map as $provider => $oauth_setting)
                <x-application.settings-section id="{{ $provider }}-oauth-section" class="scroll-mt-28"
                    title="{{ $oauth_setting['label'] }}">
                    <x-slot:actions>
                        <div x-data="{ enabled: @js((bool) $oauth_setting['enabled']), provider: @js($provider) }">
                            <x-forms.button canGate="update" :canResource="$settings" type="button"
                                :isHighlighted="!$oauth_setting['enabled']"
                                x-on:click="
                                    if (!enabled) {
                                        const invalidField = [...$el.closest('section').querySelectorAll('[required]')]
                                            .find(field => !field.checkValidity());
                                        if (invalidField) { invalidField.reportValidity(); return; }
                                    }
                                    $wire.toggleProvider(provider);
                                ">
                                {{ $oauth_setting['enabled'] ? 'Tắt' : 'Bật' }}
                            </x-forms.button>
                        </div>
                    </x-slot:actions>

                    <div class="grid gap-4 lg:grid-cols-2">
                        @if ($provider === 'oidc')
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.redirect_uri"
                                placeholder="{{ oauth_default_redirect_uri($provider) }}" label="URI chuyển hướng (Redirect URI)" />
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.base_url" label="URL nhà phát hành (Issuer URL)" required
                                helper="URL nhà phát hành OpenID Provider, ví dụ https://example.okta.com. Coolify sử dụng URL này để khám phá các điểm cuối ủy quyền, mã thông báo, userinfo và JWKS. Đối với Microsoft Entra ID, hãy sử dụng https://login.microsoftonline.com/&lt;tenant ID&gt;/v2.0 và thêm các xác nhận quyền sở hữu mã thông báo ID tùy chọn email và xms_edov vào đăng ký ứng dụng." />
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.client_id" label="Client ID" required />
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.client_secret" type="password"
                                label="Mã bí mật (Client secret)" autocomplete="new-password" required />
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.scopes" label="Phạm vi (Scopes)"
                                helper="Phải bao gồm openid. Các phạm vi phổ biến là openid email profile groups." />
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.clock_skew_seconds" type="number"
                                label="Độ lệch đồng hồ (giây)" />
                            <div class="lg:col-span-2">
                                <x-forms.input canGate="update" :canResource="$settings"
                                    id="oauth_settings_map.{{ $provider }}.custom_label" label="Nhãn nút đăng nhập"
                                    placeholder="Đăng nhập bằng SSO" />
                            </div>
                        @else
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.redirect_uri"
                                placeholder="{{ oauth_default_redirect_uri($provider) }}" label="URI chuyển hướng (Redirect URI)" />
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.client_id" label="Client ID" required />
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.client_secret" type="password"
                                label="Mã bí mật (Client secret)" autocomplete="new-password" required />
                        @endif

                        @if ($provider === 'azure')
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.tenant" label="Mã đối tượng thuê (Tenant)" required />
                        @endif

                        @if ($provider === 'google')
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.tenant"
                                helper="Tên miền Google Workspace tùy chọn, ví dụ: example.com. Chỉ các tài khoản từ tên miền này mới có thể đăng nhập. Sử dụng * để cho phép bất kỳ tài khoản Workspace nào nhưng không cho phép tài khoản Google cá nhân."
                                label="Tên miền được lưu trữ (Hosted domain)" />
                        @endif

                        @if (in_array($provider, ['authentik', 'clerk', 'zitadel', 'gitlab'], true))
                            <x-forms.input canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.base_url" label="URL cơ sở (Base URL)"
                                :required="in_array($provider, ['authentik', 'clerk'], true)" />
                        @endif

                    </div>

                    <div class="mt-4 grid gap-3 lg:grid-cols-2">
                        @if ($provider === 'oidc')
                            <x-forms.checkbox canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.allow_registration"
                                label="Cho phép tạo người dùng qua OIDC"
                                helper="Cho phép đăng nhập OIDC thành công để tạo người dùng khi tính năng đăng ký bằng mật khẩu bị tắt." />
                            <x-forms.checkbox canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.require_email_verified"
                                label="Yêu cầu email đã được xác minh" />
                            <x-forms.checkbox canGate="update" :canResource="$settings"
                                id="oauth_settings_map.{{ $provider }}.use_pkce" label="Sử dụng PKCE" />
                        @endif
                        <x-forms.checkbox canGate="update" :canResource="$settings"
                            id="oauth_settings_map.{{ $provider }}.auto_join_root_team"
                            label="Tự động thêm người dùng mới vào nhóm Gốc (Root)"
                            helper="Thêm người dùng OAuth mới tạo vào nhóm Root với tư cách là thành viên mà không tạo nhóm cá nhân riêng." />
                    </div>
                </x-application.settings-section>
            @endforeach
        </form>
    </x-settings.layout>
</div>
