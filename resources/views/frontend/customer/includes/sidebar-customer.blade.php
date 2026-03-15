<div class="bg-dark">
    <div class="widget mb-3">
        <a class="dropdown-item" href="{{ route('customer.my-orders') }}">
            <img src="/upload/images/general/bag.svg" class="icon_menu" width="24"> {{ __('My Order') }}
        </a>
        <a class="dropdown-item" href="{{ route('customer.profile') }}">
            <img src="/upload/images/general/user-1.svg" class="icon_menu" width="24"> {{ __('My Profile') }}
        </a>
        <a class="dropdown-item" href="{{ route('customer.messages') }}">
            <img src="/upload/images/general/messages.svg" class="icon_menu" width="24"> {{ __('Messages') }}
        </a>
        <a class="dropdown-item" href="{{ route('customer.changePassword') }}">
            <img src="/upload/images/general/lock-1.svg" class="icon_menu" width="24"> {{ __('Change Password') }}
        </a>
        <a class="dropdown-item" href="{{ route('customer.logout') }}">
            <img src="/upload/images/general/logout-1.svg" class="icon_menu" width="24"> {{ __('Sign Out') }}
        </a>
    </div>
</div>
