<div class="account-nav flex-grow-1">
    <h4 class="account-nav__title">Navigation</h4>
    <ul>
        <li class="account-nav__item {{ (request()->is('account/dashboard')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.dashboard')}}">Dashboard</a>
        </li>
        <li class="account-nav__item {{ (request()->is('account/profile')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.profile')}}">Profile</a>
        </li>
        <li class="account-nav__item {{ (request()->is('account/cashback')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.cashback')}}">Earning</a>
        </li>
        <li class="account-nav__item {{ (request()->is('account/clicks')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.clicks')}}">Clicks</a>
        </li>
        <li class="account-nav__item {{ (request()->is('account/withdraw')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.withdraw.index')}}">Withdraw</a>
        </li>
        <li class="account-nav__item {{ (request()->is('account/payment-methods')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.payment_details')}}">Payment Methods</a>
        </li>
        <li class="account-nav__item {{ (request()->is('account/tickets')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.tickets.index')}}">Tickets</a>
        </li>
        <li class="account-nav__item {{ (request()->is('account/change_password')) ? 'account-nav__item--active ' : '' }}">
            <a href="{{route('account.change_password')}}">Change Password</a>
        </li>
        <li class="account-nav__item ">
            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul>
</div>