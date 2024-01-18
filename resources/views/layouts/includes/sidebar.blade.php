<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>
                <li>
                    <a href="{{ route('admin.home') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-chat">Dashboard</span>
                    </a>
                </li>
                @canany(['user-list', 'role-list', 'permission-list', 'activity-list'])
                    <li>
                        <a href="javascript:void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-user"></i>
                            <span key="t-utility">Manage Users</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            @can(['role-list', 'role-add'])
                                <li {{ \Request::is('admin/role') || \Request::is('admin/role/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.role.index') }}"
                                        class="waves-effect {{ \Request::is('admin/role') || \Request::is('admin/role/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Roles</span>
                                    </a>
                                </li>
                            @endcanany

                            @can(['permission-list', 'permission-add'])
                                <li
                                    {{ \Request::is('admin/permission') || \Request::is('admin/permission/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.permission.index') }}"
                                        class="waves-effect {{ \Request::is('admin/permission') || \Request::is('admin/permission/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Permission</span>
                                    </a>
                                </li>
                            @endcanany

                            @can(['user-list', 'user-add'])
                                <li {{ \Request::is('admin/user') || \Request::is('admin/user/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.user.index') }}"
                                        class="waves-effect {{ \Request::is('admin/user') || \Request::is('admin/user/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">User</span>
                                    </a>
                                </li>
                            @endcanany
                            {{-- @can('activity-list')
                                <li>
                                    <a href="{{ route('admin.activity.index') }}" class="" key="t-vertical">Activity
                                        Log</a>
                                </li>
                            @endcan --}}
                        </ul>
                    </li>
                @endcan

                @canany(['customer-list', 'customer-add'])
                    <li {{ \Request::is('admin/customer') || \Request::is('admin/customer/*') ? 'class=mm-active' : '' }}>
                        <a href="{{ route('admin.customer.index') }}"
                            class="waves-effect {{ \Request::is('admin/customer') || \Request::is('admin/customer/*') ? 'active' : '' }}">
                            <i class='bx bxs-group'></i>
                            <span key="t-dashboards">Customer</span>
                        </a>
                    </li>
                @endcanany

                @can(['buyer-list', 'buyer-add'])
                    <li {{ \Request::is('admin/buyer') || \Request::is('admin/buyer/*') ? 'class=mm-active' : '' }}>
                        <a href="{{ route('admin.buyer.index') }}"
                            class="waves-effect {{ \Request::is('admin/buyer') || \Request::is('admin/buyer/*') ? 'active' : '' }}">
                            <i class='bx bx-user'></i>
                            <span key="t-dashboards">Buyer</span>
                        </a>
                    </li>
                @endcanany

                @can(['expense-list', 'expense-add'])
                    <li {{ \Request::is('admin/expense') || \Request::is('admin/expense/*') ? 'class=mm-active' : '' }}>
                        <a href="{{ route('admin.expense.index') }}"
                            class="waves-effect {{ \Request::is('admin/expense') || \Request::is('admin/expense/*') ? 'active' : '' }}">
                            <i class='bx bx-money'></i>
                            <span key="t-dashboards">Expense</span>
                        </a>
                    </li>
                @endcanany

                @canany(['phone-damage-list', 'payment-mode-list', 'brand-list', 'category-list', 'phone-series-list',
                    'variant-list'])
                    <li>
                        <a href="javascript:void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-mobile"></i>
                            <span key="t-utility">Manage Phone</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            @can(['brand-list', 'brand-add'])
                                <li
                                    {{ \Request::is('admin/brand') || \Request::is('admin/brand/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.brand.index') }}"
                                        class="waves-effect {{ \Request::is('admin/brand') || \Request::is('admin/brand/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Brand</span>
                                    </a>
                                </li>
                            @endcanany
                            @can(['category-list', 'category-add'])
                                <li
                                    {{ \Request::is('admin/category') || \Request::is('admin/category/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.category.index') }}"
                                        class="waves-effect {{ \Request::is('admin/category') || \Request::is('admin/category/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Category</span>
                                    </a>
                                </li>
                            @endcanany
                            @can(['phone-series-list', 'phone-series-add'])
                                <li
                                    {{ \Request::is('admin/phone/series') || \Request::is('admin/phone/series/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.phone.series.index') }}"
                                        class="waves-effect {{ \Request::is('admin/phone/series') || \Request::is('admin/phone/series/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Phone Series</span>
                                    </a>
                                </li>
                            @endcanany
                            @can(['phone-model-list', 'phone-model-add'])
                                <li
                                    {{ \Request::is('admin/phone/model') || \Request::is('admin/phone/model/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.phone.model.index') }}"
                                        class="waves-effect {{ \Request::is('admin/phone/model') || \Request::is('admin/phone/model/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Phone Model</span>
                                    </a>
                                </li>
                            @endcanany
                            @can(['phone-damage-list', 'phone-damage-add'])
                                <li
                                    {{ \Request::is('admin/phone/damage') || \Request::is('admin/phone/damage/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.phone.damage.index') }}"
                                        class="waves-effect {{ \Request::is('admin/phone/damage') || \Request::is('admin/phone/damage/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Phone Damage</span>
                                    </a>
                                </li>
                            @endcanany
                            @can(['payment-mode-list', 'payment-mode-add'])
                                <li
                                    {{ \Request::is('admin/payment/mode') || \Request::is('admin/payment/mode/*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.payment.mode.index') }}"
                                        class="waves-effect {{ \Request::is('admin/payment/mode') || \Request::is('admin/payment/mode/*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Payment Mode</span>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['variant-list', 'variant-add'])
                                <li
                                    {{ \Request::is('admin/phone/variant') || \Request::is('admin/phone/varian*') ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.variant.index') }}"
                                        class="waves-effect {{ \Request::is('admin/phone/varian*') || \Request::is('admin/phone/varian*') ? 'active' : '' }}">
                                        {{-- <i class='bx bx-user'></i> --}}
                                        <span key="t-dashboards">Phone Variant</span>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcan

                @can(['pay-worker-list', 'pay-worker-add'])
                    <li
                        {{ \Request::is('admin/pay/worker') || \Request::is('admin/pay/worker/*') ? 'class=mm-active' : '' }}>
                        <a href="{{ route('admin.pay.worker.index') }}"
                            class="waves-effect {{ \Request::is('admin/pay/worker') || \Request::is('admin/pay/worker/*') ? 'active' : '' }}">
                            <i class='bx bx-rupee'></i>
                            <span key="t-dashboards">Pay Worker</span>
                        </a>
                    </li>
                @endcanany

                @can(['inventory-list', 'inventory-add'])
                    <li
                        {{ \Request::is('admin/inventory') || \Request::is('admin/inventory/*') ? 'class=mm-active' : '' }}>
                        <a href="{{ route('admin.inventory.index') }}"
                            class="waves-effect {{ \Request::is('admin/inventory') || \Request::is('admin/inventory/*') ? 'active' : '' }}">
                            <i class='bx bx-cart-alt'></i>
                            <span key="t-dashboards">Inventory</span>
                        </a>
                    </li>
                @endcanany

                @can(['order-list', 'order-add'])
                    <li {{ \Request::is('admin/order') || \Request::is('admin/order*') ? 'class=mm-active' : '' }}>
                        <a href="{{ route('admin.order.index') }}"
                            class="waves-effect {{ \Request::is('admin/order') || \Request::is('admin/order*') ? 'active' : '' }}">
                            <i class='bx bx-phone'></i>
                            <span key="t-dashboards">Phone Selling</span>
                        </a>
                    </li>
                @endcanany
                @can(['branch-list', 'branch-add'])
                    <li {{ \Request::is('admin/branch') || \Request::is('admin/branch*') ? 'class=mm-active' : '' }}>
                        <a href="{{ route('admin.branch.index') }}"
                            class="waves-effect {{ \Request::is('admin/branch') || \Request::is('admin/branch*') ? 'active' : '' }}">
                            <i class='bx bx-store-alt'></i>
                            <span key="t-dashboards">Branches</span>
                        </a>
                    </li>
                @endcanany

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
