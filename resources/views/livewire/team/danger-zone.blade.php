<div>
    @php
        $deletionBlockers = currentTeam()->deletionBlockers();
        $blockerDetails = [
            'projects' => ['label' => 'dự án', 'route' => 'project.index'],
            'servers' => ['label' => 'máy chủ', 'route' => 'server.index'],
            'sources' => ['label' => 'nguồn Git', 'route' => 'source.all'],
        ];
    @endphp
    <x-slot:title>
        Vùng nguy hiểm của nhóm | Coolify
    </x-slot>

    <x-team.settings-layout>
        <div class="application-settings-form">
            <x-application.settings-section id="team-danger-zone" title="Vùng nguy hiểm"
                helper="Các thao tác hủy đối với nhóm này không thể hoàn tác.">
                <x-danger-zone title="Xóa nhóm">
                            @if (auth()->user()->roleInTeam(currentTeam()->id) !== 'owner')
                                <p>
                                    Chỉ chủ sở hữu nhóm mới có thể xóa nhóm này.
                                </p>
                            @elseif (session('currentTeam.id') === 0)
                                <p>
                                    Nhóm mặc định không thể bị xóa.
                                </p>
                            @elseif(auth()->user()->teams()->count() === 1 || auth()->user()->currentTeam()->personal_team)
                                <p>
                                    Nhóm cuối cùng hoặc nhóm cá nhân của bạn không thể bị xóa.
                                </p>
                            @elseif(currentTeam()->subscription)
                                <p>
                                    Hủy <a class="font-medium text-coollabs hover:underline dark:text-warning"
                                        {{ wireNavigate() }} href="{{ route('subscription.show') }}">gói đăng ký</a>
                                    trước khi xóa nhóm này.
                                </p>
                            @elseif($deletionBlockers === [])
                                <p>
                                    Xóa vĩnh viễn <strong class="font-semibold text-black dark:text-fg">{{ currentTeam()->name }}</strong>
                                    khỏi Coolify. Hành động này không thể hoàn tác.
                                </p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Tất cả thành viên sẽ mất quyền truy cập vào nhóm này.</li>
                                    <li>• Nhóm này không thể khôi phục lại từ Coolify sau khi xóa.</li>
                                </ul>
                            @else
                                <p>
                                    Nhóm này vẫn đang sở hữu:
                                </p>
                                <ul class="space-y-1">
                                    @foreach ($deletionBlockers as $type => $count)
                                        <li>
                                            <a class="font-medium text-coollabs hover:underline dark:text-warning"
                                                {{ wireNavigate() }} href="{{ route($blockerDetails[$type]['route']) }}">
                                                {{ $count }} {{ $blockerDetails[$type]['label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <p>
                                    Hãy xóa hoặc chuyển giao các tài nguyên này trước khi xóa nhóm.
                                </p>
                            @endif
                        <x-slot:action>
                            @if (
                                session('currentTeam.id') !== 0 &&
                                    auth()->user()->roleInTeam(currentTeam()->id) === 'owner' &&
                                    auth()->user()->teams()->count() > 1 &&
                                    !auth()->user()->currentTeam()->personal_team &&
                                    !currentTeam()->subscription &&
                                    $deletionBlockers === [])
                                <x-modal-confirmation title="Xác nhận xóa nhóm?" buttonTitle="Xóa nhóm"
                                    isErrorButton submitAction="delete"
                                    :actions="['Nhóm hiện tại sẽ bị xóa vĩnh viễn khỏi Coolify và cơ sở dữ liệu.']"
                                    confirmationText="{{ currentTeam()->name }}"
                                    confirmationLabel="Nhập tên nhóm để xác nhận xóa vĩnh viễn"
                                    shortConfirmationLabel="Tên nhóm" :confirmWithPassword="false"
                                    step2ButtonText="Xóa vĩnh viễn" canGate="delete"
                                    :canResource="$team" />
                            @else
                                <x-forms.button isError disabled tooltip="Vui lòng hoàn thành các yêu cầu được hiển thị trước khi xóa nhóm này.">
                                    Xóa nhóm
                                </x-forms.button>
                            @endif
                        </x-slot:action>
                </x-danger-zone>

                @if (session('currentTeam.id') !== 0 && !currentTeam()->subscription && (currentTeam()->projects->isNotEmpty() || currentTeam()->servers->isNotEmpty()))
                    <div class="mt-4 overflow-hidden rounded-lg border border-neutral-200 dark:border-white/[0.08]">
                        <div class="flex items-center justify-between gap-3 border-b border-neutral-200 px-3 py-2 dark:border-white/[0.08]">
                            <h5 class="text-sm font-medium text-black dark:text-fg">Tài nguyên</h5>
                            <x-forms.button type="button" wire:click="refreshResources">
                                <x-reicon name="refresh" class="size-3.5" />
                                Làm mới
                            </x-forms.button>
                        </div>
                        <table class="w-full text-left text-sm">
                            <thead class="bg-neutral-50 text-[11px] uppercase tracking-wide text-neutral-500 dark:bg-coolgray-100 dark:text-fg-dim">
                                <tr>
                                    <th class="px-3 py-2 font-medium">Loại tài nguyên</th>
                                    <th class="px-3 py-2 font-medium">Tên</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-white/[0.08]">
                                @foreach (currentTeam()->projects as $project)
                                    <tr class="text-[13px] text-neutral-600 hover:bg-neutral-50 dark:text-fg-dim dark:hover:bg-white/[0.03]">
                                        <td>
                                            <a class="block px-3 py-2.5" href="{{ route('project.show', ['project_uuid' => $project->uuid]) }}"
                                                target="_blank" rel="noopener noreferrer">Dự án</a>
                                        </td>
                                        <td>
                                            <a class="block px-3 py-2.5 font-medium text-black dark:text-fg"
                                                href="{{ route('project.show', ['project_uuid' => $project->uuid]) }}"
                                                target="_blank" rel="noopener noreferrer">{{ $project->name }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach (currentTeam()->servers as $server)
                                    <tr class="text-[13px] text-neutral-600 hover:bg-neutral-50 dark:text-fg-dim dark:hover:bg-white/[0.03]">
                                        <td>
                                            <a class="block px-3 py-2.5" href="{{ route('server.show', ['server_uuid' => $server->uuid]) }}"
                                                target="_blank" rel="noopener noreferrer">Máy chủ</a>
                                        </td>
                                        <td>
                                            <a class="block px-3 py-2.5 font-medium text-black dark:text-fg"
                                                href="{{ route('server.show', ['server_uuid' => $server->uuid]) }}"
                                                target="_blank" rel="noopener noreferrer">{{ $server->name }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-application.settings-section>
        </div>
    </x-team.settings-layout>
</div>
