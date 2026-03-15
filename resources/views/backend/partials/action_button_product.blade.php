@php
    $packages = \App\Models\Package::orderBy('priority')->get();
    $package_current = $package ?? '';
@endphp
<div class="card">
    <div class="card-header">
        <h5>Publish</h5>
    </div> <!-- /.card-header -->
    <div class="card-body">
        <div class="form-group clearfix">
            <div class="mb-3">
                <label class="form-label">@lang('Loại tin đăng')</label>
                <select class="form-control" name="package" id="package">
                    @foreach ($packages as $package)
                        <option value="{{ $package->code }}" {{ $package_current == $package->code ? 'selected' : '' }}>{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">@lang('Ngày bắt đầu')</label>

                <div class="input-group date" id="package_start" data-target-input="nearest">
                    <input type="text" name="package_start" class="form-control datetimepicker-input" data-bs-target="#package_start" value="{{ $package_start ?? '' }}">
                    <div class="input-group-append" data-bs-target="#package_start" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label">@lang('Ngày kết thúc')</label>
                <div class="input-group date" id="package_end" data-target-input="nearest">
                    <input type="text" name="package_end" class="form-control datetimepicker-input" data-bs-target="#package_end" value="{{ $package_end ?? '' }}">
                    <div class="input-group-append" data-bs-target="#package_end" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>

            <div class="icheck-primary d-block mb-2">
                <input type="radio" id="radioDraft" name="status" value="2" @if (isset($status) && $status == 2) checked @endif>
                <label for="radioDraft">Chưa thanh toán</label>
            </div>
            <div class="icheck-primary d-block mb-2">
                <input type="radio" id="radioDraft0" name="status" value="0" @if (isset($status) && $status == 0) checked @endif>
                <label for="radioDraft0">Chưa duyệt</label>
            </div>
            <div class="icheck-primary d-block">
                <input type="radio" id="radioPublic" name="status" value="1" @if (isset($status) && $status == 1) checked @endif>
                <label for="radioPublic">Duyệt</label>
            </div>
        </div>



        <div class="mb-3">
            <h5 class="mb-2">Ghi chú:</h5>
            <textarea name="note" class="form-control w-100" rows="5">{{ $note ?? '' }}</textarea>
        </div>
        {{--
        <div class="form-group">
            <label>Date:</label>
            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                <input type="text" name="created" class="form-control datetimepicker-input" data-bs-target="#reservationdate" value="{{ $date_update ?? '' }}">
                <div class="input-group-append" data-bs-target="#reservationdate" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
        --}}
        <div class="form-group text-end">
            <button type="submit" name="submit" value="save" class="btn btn-info">Save</button>
            <button type="submit" name="submit" value="apply" class="btn btn-success">Save & Edit</button>
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->
