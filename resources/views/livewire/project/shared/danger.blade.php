<div>
    @php
        $resourceLabel = match ($resource?->type()) {
            'application' => 'application',
            'service' => 'service',
            'service-application' => 'service application',
            'service-database' => 'service database',
            default => 'resource',
        };
    @endphp
    @if ($resource && !data_get($resource, 'destination.server')?->isFunctional())
        <x-callout type="warning" title="Server is not reachable" class="mb-4">
            Coolify cannot stop or remove Docker resources on this server. Deleting removes this {{ $resourceLabel }}
            from Coolify only. Its containers, volumes, networks, and configuration files remain on the server and can
            start again when the server is back.
        </x-callout>
    @endif
    <x-application.settings-section id="danger-zone-section" title="Vùng nguy hiểm"
        helper="Các thao tác xóa tài nguyên không thể khôi phục lại.">
        <x-danger-zone title="Xóa {{ $resourceLabel }}">
                    <p>
                        Permanently delete
                        <strong class="font-semibold text-black dark:text-fg">{{ $resourceName }}</strong>,
                        stop its containers, and remove the selected Docker resources and configuration.
                    </p>
                    <ul class="space-y-1 text-xs">
                        <li>• Các tiến trình triển khai đang chạy sẽ bị dừng lại.</li>
                        <li>• Các ổ đĩa lưu trữ và dữ liệu đã chọn có thể bị xóa vĩnh viễn.</li>
                        <li>• Không thể khôi phục {{ $resourceLabel }} sau khi xóa khỏi Coolify.</li>
                    </ul>
                <x-slot:action>
                    @if ($canDelete)
                        <x-modal-confirmation title="Xóa vĩnh viễn {{ $resourceLabel }}?"
                            buttonTitle="Xóa {{ $resourceLabel }}"
                            isErrorButton submitAction="delete" :checkboxes="$checkboxes"
                            :actions="['Permanently delete this resource and its selected Docker resources.']"
                            confirmationText="{{ $resourceName }}"
                            confirmationLabel="Enter the resource name to confirm permanent deletion"
                            shortConfirmationLabel="Resource name" />
                        @if ($resource instanceof \App\Models\Service)
                            <x-modal-confirmation title="Remove service from Coolify only?"
                                buttonTitle="Remove from Coolify only"
                                isErrorButton submitAction="deleteFromCoolifyOnly"
                                :actions="[
                                    'Permanently remove this service from Coolify.',
                                    'Leave all containers, volumes, networks, and configuration files on the server.',
                                ]"
                                warningMessage="Coolify will no longer track or manage the Docker resources for this service. Use this only when normal cleanup cannot complete."
                                confirmationText="{{ $resourceName }}"
                                confirmationLabel="Enter the resource name to confirm metadata-only deletion"
                                shortConfirmationLabel="Resource name" />
                        @endif
                    @else
                        <x-forms.button isError disabled tooltip="You do not have permission to delete this resource.">
                            Xóa {{ $resourceLabel }}
                        </x-forms.button>
                    @endif
                </x-slot:action>
        </x-danger-zone>

        @if (!$canDelete)
            <div class="mt-4">
                <x-callout type="danger" title="Insufficient permissions">
                    Contact a team administrator if this resource must be deleted.
                </x-callout>
            </div>
        @endif
    </x-application.settings-section>
</div>
