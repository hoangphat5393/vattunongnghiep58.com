@php $user_menu = Menu::getByName('User-menu'); @endphp
@if($user_menu)

    @foreach ($user_menu as $item)
    <a class="dropdown-item" href="{{ url('customer/'.$item['link']) }}">
        @if($item['icon'])
            <img src="{{asset($item['icon'])}}" class="icon_menu">
        @endif
        {{ $item['label'] }}
    </a>
    @endforeach
    <hr>
    <a class="dropdown-item" href="{{ route('customer.logout') }}"><img src="{{asset('upload/images/general/logout.svg')}}" class="icon_menu"> Đăng xuất</a>
@endif