<div class="mb-4 card card-secondary card-outline">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('admin.SEO Settings')</h5>
    </div>
    <div class="card-body">
        <div class="mb-3 form-group">
            <label for="seo_title" class="form-label font-weight-bold">@lang('admin.SEO Title')</label>
            <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ $seo_title ?? '' }}">
        </div>
        <div class="mb-3 form-group">
            <label for="seo_keyword" class="form-label font-weight-bold">@lang('admin.SEO Keyword')</label>
            <input type="text" class="form-control" id="seo_keyword" name="seo_keyword" value="{{ $seo_keyword ?? '' }}">
        </div>
        <div class="mb-3 form-group">
            <label for="seo_description" class="form-label font-weight-bold">@lang('admin.SEO Description')</label>
            <textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ $seo_description ?? '' }}</textarea>
        </div>
    </div>
</div>
