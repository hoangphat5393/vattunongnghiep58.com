<script type="text/javascript" src="{{ asset('assets/plugin/ckfinder/ckfinder.js') }}"></script>
<script>
    CKFinder.config({
        connectorPath: @json(route('ckfinder_connector'))
    });
</script>
