<div class="modal login fade" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="notifyModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="list-content-loading">
                <div class="half-circle-spinner">
                    <div class="circle circle-1"></div>
                    <div class="circle circle-2"></div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body">
                <div class="content_group_offer_view mt-3 pb-3 text-center">
                    <p><img src="{{ asset('theme/images/circle-icon.png') }}" width="120" alt=""></p>
                    <p>Theo dõi thành công</p>
                    <p>Cám ơn bạn đã quan tâm tới {!! setting_option('domain') !!}</p>
                    <p><span>Quay về </span>
                        <a href="{{ url('/') }}" style="color: rgb(255 153 51);">Trang chủ</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
