<div>
    <x-slot:title>
        Nhóm | Coolify
    </x-slot>

    <x-team.settings-layout>
    <div class="flex flex-col gap-6">
        <form wire:submit="submit" class="application-settings-form">
            <x-unsaved-bar action="submit" />
            <x-application.settings-section title="Cài đặt chung"
                description="Quản lý danh tính của nhóm này và quyền truy cập API dùng chung.">
                <x-slot:actions>
                    <x-modal-input buttonTitle="Nhóm mới" title="Tạo nhóm mới">
                        <livewire:team.create />
                    </x-modal-input>
                </x-slot:actions>
                <div class="grid gap-4 lg:grid-cols-2">
                    <x-forms.input id="name" label="Tên nhóm" required canGate="update" :canResource="$team" />
                    <x-forms.input id="description" label="Mô tả" canGate="update" :canResource="$team" />
                    <div class="lg:col-span-2">
                        <x-forms.listbox canGate="update" :canResource="$team" id="is_mcp_server_enabled" label="Máy chủ MCP"
                            helper="Kiểm soát việc mã token API của nhóm này có thể sử dụng điểm cuối MCP hay không."
                            :disabled="! auth()->user()->can('update', $team)" :options="[
                                ['value' => false, 'label' => 'Đã tắt cho nhóm này'],
                                ['value' => true, 'label' => 'Đã bật cho nhóm này'],
                            ]" />
                    </div>
                    <div class="lg:col-span-2">
                        <x-forms.listbox canGate="update" :canResource="$team"
                            id="is_build_server_fallback_enabled" label="Dự phòng máy chủ xây dựng"
                            helper="Kiểm soát hành động khi một ứng dụng được cấu hình sử dụng máy chủ xây dựng chuyên dụng, nhưng không có máy chủ xây dựng khả dụng."
                            :disabled="! auth()->user()->can('update', $team)" :options="[
                                ['value' => true, 'label' => 'Xây dựng trên chính máy chủ triển khai'],
                                ['value' => false, 'label' => 'Báo lỗi và dừng triển khai'],
                            ]" />
                    </div>
                </div>
            </x-application.settings-section>
        </form>

    </div>
    </x-team.settings-layout>
</div>
