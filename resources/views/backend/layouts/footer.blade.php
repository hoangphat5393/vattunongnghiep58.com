<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">{{ setting_option('webtitle') }}</div>
    <strong>
        Copyright &copy; 2014-{{ date('Y') }}&nbsp;
        <a href="{{ route('index') }}" class="text-decoration-none" target="_blank" rel="noopener">{{ setting_option('webtitle') ?: config('app.name') }}</a>.
    </strong>
    All rights reserved.
</footer>
