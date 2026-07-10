<script>
    window.AdminRoutes = Object.assign(window.AdminRoutes || {}, {
        bulkDelete: @json(route('admin.bulk.delete')),
        bulkReplicate: @json(route('admin.bulk.replicate')),
        quickChange: @json(route('admin.quick-change')),
        albumItemUpdateSort: @json(route('admin.albumItem.update_sort')),
        themeOptionUpdateSort: @json(route('admin.theme-option.update_sort')),
        legacyBulkDelete: @json(route('admin.ajax_delete')),
        legacyBulkReplicate: @json(route('admin.ajax_replicate')),
    });
</script>
