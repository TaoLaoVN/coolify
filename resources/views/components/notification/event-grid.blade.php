@props([
    'settings',
    'channel',
    'threaded' => false,
])

@php
    $eventGroups = [
        'Triển khai' => [
            ['key' => 'deploymentSuccess', 'label' => 'Triển khai thành công'],
            ['key' => 'deploymentFailure', 'label' => 'Triển khai thất bại'],
        ],
        'Tài nguyên' => [
            [
                'key' => 'statusChange',
                'label' => 'Thay đổi trạng thái tài nguyên',
                'helper' => 'Thông báo khi tài nguyên dừng hoạt động hoặc khi Coolify tự động khởi động lại.',
            ],
            [
                'key' => 'restartLimitReached',
                'label' => 'Đạt giới hạn khởi động lại',
                'helper' => 'Thông báo khi tài nguyên bị dừng sau khi đạt số lần khởi động lại tối đa.',
            ],
        ],
        'Sao lưu' => [
            ['key' => 'backupSuccess', 'label' => 'Sao lưu thành công'],
            ['key' => 'backupFailure', 'label' => 'Sao lưu thất bại'],
        ],
        'Tác vụ định kỳ' => [
            ['key' => 'scheduledTaskSuccess', 'label' => 'Tác vụ định kỳ thành công'],
            ['key' => 'scheduledTaskFailure', 'label' => 'Tác vụ định kỳ thất bại'],
        ],
        'Máy chủ' => [
            ['key' => 'dockerCleanupSuccess', 'label' => 'Dọn dẹp Docker thành công'],
            ['key' => 'dockerCleanupFailure', 'label' => 'Dọn dẹp Docker thất bại'],
            ['key' => 'serverDiskUsage', 'label' => 'Cảnh báo dung lượng ổ đĩa'],
            ['key' => 'serverReachable', 'label' => 'Máy chủ kết nối được'],
            ['key' => 'serverUnreachable', 'label' => 'Mất kết nối máy chủ'],
            ['key' => 'serverPatch', 'label' => 'Bản vá máy chủ'],
            ['key' => 'traefikOutdated', 'label' => 'Proxy Traefik đã cũ'],
        ],
    ];

    $enabledThreadEvents = [];
    if ($threaded) {
        foreach ($eventGroups as $group => $events) {
            foreach ($events as $event) {
                $enabled = (bool) data_get(
                    $settings,
                    Str::snake($event['key'] . '_' . $channel . '_notifications'),
                );

                if (! $enabled) {
                    continue;
                }

                $enabledThreadEvents[] = [
                    'group' => $group,
                    'key' => $event['key'],
                    'label' => $event['label'],
                    'threadModel' => Str::camel(
                        Str::studly($channel) . 'Notifications' . Str::studly($event['key']) . 'ThreadId',
                    ),
                ];
            }
        }

        $enabledThreadEventsByGroup = collect($enabledThreadEvents)->groupBy('group');
    }
@endphp

<div class="flex flex-col gap-6">
    <x-application.settings-section title="Sự kiện thông báo"
        description="Chọn các sự kiện sẽ gửi thông báo qua kênh này.">
        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($eventGroups as $group => $events)
                @php
                    $multiselectEvents = collect($events)
                        ->map(fn ($event) => [
                            'property' => $event['key'] . Str::studly($channel) . 'Notifications',
                            'label' => $event['label'],
                            'enabled' => (bool) data_get(
                                $settings,
                                Str::snake($event['key'] . '_' . $channel . '_notifications'),
                            ),
                        ])
                        ->all();
                    $groupId = Str::slug($channel . '-' . $group . '-events');
                @endphp
                <div class="min-w-0">
                    <x-notification.event-multiselect :settings="$settings" :id="$groupId"
                        :label="$group" :events="$multiselectEvents" />
                </div>
            @endforeach
        </div>
    </x-application.settings-section>

    @if ($threaded)
        <x-application.settings-section title="Chủ đề diễn đàn (Forum topics)"
            description="Tùy chọn. Điều hướng các sự kiện đã bật đến chủ đề diễn đàn Telegram bằng ID luồng tin nhắn (thread ID). Để trống để gửi vào nhóm chat chính.">
            @if ($enabledThreadEvents === [])
                <p class="text-[13px] leading-relaxed text-neutral-500 dark:text-fg-dim">
                    Bật một hoặc nhiều sự kiện ở trên để gán ID chủ đề diễn đàn.
                </p>
            @else
                <div class="flex flex-col gap-5">
                    @foreach ($enabledThreadEventsByGroup as $group => $events)
                        <div class="min-w-0">
                            <div
                                class="mb-2 text-[11px] font-medium tracking-wide text-neutral-500 uppercase dark:text-fg-dim">
                                {{ $group }}
                            </div>
                            <div
                                class="divide-y divide-neutral-200 overflow-hidden rounded-xl border border-neutral-200 dark:divide-white/[0.06] dark:border-white/[0.08]">
                                @foreach ($events as $event)
                                    <div
                                        class="grid gap-2 px-3.5 py-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,14rem)] sm:items-center sm:gap-4"
                                        wire:key="{{ $channel }}-thread-row-{{ $event['key'] }}">
                                        <div class="min-w-0">
                                            <div
                                                class="truncate text-[13px] font-medium text-black dark:text-fg">
                                                {{ $event['label'] }}
                                            </div>
                                            <div class="text-[11px] text-neutral-500 dark:text-fg-dim">
                                                Topic ID
                                            </div>
                                        </div>
                                        <x-forms.input wire:key="{{ $channel }}-thread-{{ $event['key'] }}"
                                            canGate="update" :canResource="$settings" type="password"
                                            :id="$event['threadModel']" :label="null"
                                            :placeholder="'Tùy chọn'"
                                            :aria-label="$event['label'] . ' topic ID'" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-application.settings-section>
    @endif
</div>
