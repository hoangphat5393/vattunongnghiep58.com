<div class="row mb-4">
    <div class="col-md-12">
        <div class="customer-intro">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <img style="max-width:180px" class="img-fluid d-block mx-auto rounded-circle avatar" src="{{ $avatar }}" alt="path to image">
                </div>

                <div class="col-md-5">
                    <dl class="row mt-3">
                        <dt class="col-sm-3">Username:</dt>
                        <dd class="col-sm-9">{{ auth()->user()->username }}</dd>
                    </dl>
                    <dl class="row">
                        <dt class="col-sm-3">Spent:</dt>
                        <dd class="col-sm-9">$ 100.00</dd>
                    </dl>
                    <dl class="row">
                        <dt class="col-sm-3">Orders:</dt>
                        <dd class="col-sm-9">20</dd>
                    </dl>
                </div>
                <div class="col-md-4 greeting text-center text-lg-start">
                    <p>Welcome</p>
                    <p>{{ auth()->user()->fullname }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
