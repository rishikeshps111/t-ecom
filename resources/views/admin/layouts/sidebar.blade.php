<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-blur">
        <div class="sidebar-cont">
            <div class="app-logo">
                <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center logo-title">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="">
                </a>
            </div>
            <ul class="sidebar-nav" id="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard </span>
                    </a>
                </li>
                @role(['Super Admin'])
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.manage.user') ||
                        request()->routeIs('admin.manage.business.owner') ||
                        request()->routeIs('admin.manage.roles') ||
                        request()->routeIs('admin.manage.planner') ||
                        request()->routeIs('admin.customers.index') ||
                        request()->routeIs('admin.business-users.index')
                            ? ''
                            : 'collapsed' }}"
                            data-bs-target="#user-nav" data-bs-toggle="collapse" href="#">
                            <i class="fa-solid fa-users"></i><span>User Management </span><i
                                class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="user-nav"
                            class="nav-content collapse sub-menu  {{ request()->routeIs('admin.manage.user') ||
                            request()->routeIs('admin.manage.business.owner') ||
                            request()->routeIs('admin.manage.roles') ||
                            request()->routeIs('admin.manage.planner') ||
                            request()->routeIs('admin.customers.index') ||
                            request()->routeIs('admin.business-users.index')
                                ? 'show'
                                : '' }}"
                            data-bs-parent="#sidebar-nav">
                            <li>
                                <a href="{{ route('admin.manage.user') }}?type=production"
                                    class="{{ request()->routeIs('admin.manage.user') && request()->get('type') == 'production' ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Production Staff <span
                                            class="badge bg-primary ms-1">
                                            {{ \App\Models\User::role('Production Staff')->count() }}</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manage.user') }}?type=management"
                                    class="{{ request()->routeIs('admin.manage.user') && request()->get('type') == 'management' ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Management Staff <span
                                            class="badge bg-primary ms-1">
                                            {{ \App\Models\User::role('Management Staff')->count() }}</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.business-users.index') }}"
                                    class="{{ request()->routeIs('admin.business-users.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Cus Users <span
                                            class="badge bg-primary ms-1">
                                            {{ \App\Models\User::role('Customer')->count() }}</span></span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manage.planner') }}"
                                    class="{{ request()->routeIs('admin.manage.planner') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Planner<span
                                            class="badge bg-primary ms-1">
                                            {{ \App\Models\User::role('Planner')->count() }}</span></span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.customers.index') }}"
                                    class="{{ request()->routeIs('admin.customers.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Total Group <span
                                            class="badge bg-primary ms-1"> {{ \App\Models\Customer::count() }}</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manage.roles') }}"
                                    class="{{ request()->routeIs('admin.manage.roles') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Roles & Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole
                @role('Super Admin')
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.manage.company') }}">
                            <i class="fa-solid fa-city"></i>
                            <span>Customer Management</span>
                        </a>
                    </li>
                @endrole


                @can('wo.view')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.work-orders.*') ? 'active' : '' }}"
                            href="{{ route('admin.work-orders.index') }}">
                            <i class="fa-brands fa-first-order"></i>
                            <span>Work Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.work-orders.completed.list') ? 'active' : '' }}"
                            href="{{ route('admin.work-orders.completed.list') }}">
                            <i class="fa-brands fa-first-order"></i>
                            <span>Completed Work Orders</span>
                        </a>
                    </li>
                @endcan
                @can('qt.view')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}"
                            href="{{ route('admin.quotations.index') }}">
                            <i class="fa-brands fa-first-order"></i>
                            <span>Quotations</span>
                        </a>
                    </li>
                @endcan
                @can('inv.view')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }} "
                            href="{{ route('admin.invoices.index') }}">
                            <i class="fa-solid fa-file-pen"></i>
                            <span>Invoices</span>
                        </a>
                    </li>
                @endcan
                @can('or.view')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                            href="{{ route('admin.payments.index') }}">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Original Receipts (OR)</span>
                        </a>
                    </li>
                @endcan
                @canany(['wo-report.view', 'invoice-report.view', 'or-report.view', 'cr-report.view',
                    'planner-commission-report.view', 'production-staff-commission-report.view', 'tg-report.view',
                    'consolidation-wo-report.view', 'monthly-report.view'])
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.account-statements.workOrders') ||
                        request()->routeIs('admin.account-statements.invoice') ||
                        request()->routeIs('admin.account-statements.original-receipts') ||
                        request()->routeIs('admin.account-statements.credit-notes') ||
                        request()->routeIs('admin.account-statements.planner-commission') ||
                        request()->routeIs('admin.account-statements.total-group') ||
                        request()->routeIs('admin.account-statements.consolidated') ||
                        request()->routeIs('admin.account-statements.production-commission') ||
                        request()->routeIs('admin.account-statements.monthly-summary')
                            ? ''
                            : 'collapsed' }}"
                            data-bs-target="#account-nav" data-bs-toggle="collapse" href="#">
                            <i class="fa-solid fa-gears"></i><span>Account Statements</span><i
                                class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="account-nav"
                            class="nav-content collapse sub-menu  {{ request()->routeIs('admin.account-statements.workOrders') ||
                            request()->routeIs('admin.account-statements.invoice') ||
                            request()->routeIs('admin.account-statements.original-receipts') ||
                            request()->routeIs('admin.account-statements.credit-notes') ||
                            request()->routeIs('admin.account-statements.planner-commission') ||
                            request()->routeIs('admin.account-statements.total-group') ||
                            request()->routeIs('admin.account-statements.consolidated') ||
                            request()->routeIs('admin.account-statements.production-commission') ||
                            request()->routeIs('admin.account-statements.monthly-summary')
                                ? 'show'
                                : '' }}"
                            data-bs-parent="#sidebar-nav">
                            @unlessrole(['Planner', 'Production Staff'])
                                @can('wo-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.workOrders') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.workOrders') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>WO Report</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('invoice-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.invoice') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.invoice') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>Invoice Report</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('or-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.original-receipts') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.original-receipts') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>OR Report</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('cr-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.credit-notes') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.credit-notes') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>CR Report</span>
                                        </a>
                                    </li>
                                @endcan
                            @endunlessrole

                            @role(['Super Admin', 'Admin', 'Management Staff', 'Planner'])
                                @can('planner-commission-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.planner-commission') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.planner-commission') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>Planner Commission Report</span>
                                        </a>
                                    </li>
                                @endcan
                            @endrole

                            @role(['Super Admin', 'Admin', 'Management Staff', 'Production Staff'])
                                @can('production-staff-commission-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.production-commission') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.production-commission') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>Production Staff Commission Report</span>
                                        </a>
                                    </li>
                                @endcan
                            @endrole
                            @unlessrole(['Planner', 'Production Staff'])
                                @can('tg-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.total-group') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.total-group') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>TG Report</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('consolidation-wo-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.consolidated') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.consolidated') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>Consolidated WO Report</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('monthly-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.monthly-summary') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.monthly-summary') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>Monthly Report</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('monthly-report.view')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.account-statements.monthly-summary') ? 'active' : '' }}"
                                            href="{{ route('admin.account-statements.outstanding-report') }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span>Outstanding Report</span>
                                        </a>
                                    </li>
                                @endcan
                            @endunlessrole
                            @role(['Super Admin', 'Admin', 'Management Staff', 'Planner'])
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.account-statements.planner-monthly-report') ? 'active' : '' }}"
                                        href="{{ route('admin.account-statements.planner-monthly-report') }}">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span>Planner Statement</span>
                                    </a>
                                </li>
                            @endrole
                            @role(['Super Admin', 'Admin', 'Management Staff', 'Production Staff'])
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.account-statements.ps-monthly-report') ? 'active' : '' }}"
                                        href="{{ route('admin.account-statements.ps-monthly-report') }}">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span>Production Staff Statement</span>
                                    </a>
                                </li>
                            @endrole
                        </ul>
                    </li>
                @endcanany
                @canany(['document.view'])
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.documents.*') || request()->routeIs('admin.planner-documents.*') ? '' : 'collapsed' }}"
                            data-bs-target="#document-nav" data-bs-toggle="collapse" href="#">
                            <i class="fa-solid fa-file-circle-check"></i><span>Document Management </span><i
                                class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="document-nav"
                            class="nav-content collapse sub-menu  {{ request()->routeIs('admin.documents.*') || request()->routeIs('admin.planner-documents.*') ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">
                            @can('document.view')
                                <li>
                                    <a href="{{ route('admin.documents.index') }}"
                                        class="{{ request()->routeIs('admin.documents.index') ? 'active' : '' }}">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i><span>WO Documents </span>
                                    </a>
                                </li>
                            @endcan
                            @can('document.view')
                                <li>
                                    <a href="{{ route('admin.documents.company') }}"
                                        class="{{ request()->routeIs('admin.documents.company') ? 'active' : '' }}">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Company Documents </span>
                                    </a>
                                </li>
                            @endcan
                            @can('document.view')
                                @role(['Super Admin', 'Planner'])
                                    <li>
                                        <a href="{{ route('admin.planner-documents.index') }}"
                                            class="{{ request()->routeIs('admin.planner-documents.index') ? 'active' : '' }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Planner Documents </span>
                                        </a>
                                    </li>
                                @endrole
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @role(['Super Admin'])
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.document-manger.*') ? 'active' : '' }}"
                            href="{{ route('admin.document-manger.index') }}">
                            <i class="fa-solid fa-file"></i>
                            <span>Document Manager </span>
                            <span class="badge bg-danger ms-1 blink">New</span> </a>
                    </li>
                @endrole
                @can('message.view')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"
                            href="{{ route('admin.messages.index') }}">
                            <i class="fa-solid fa-comments"></i>
                            <span>Messaging </span>
                            @php $unread = unreadMessagesCount(); @endphp
                            @if ($unread > 0)
                                <span class="badge bg-danger ms-1">{{ $unread }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

                @role(['Super Admin', 'Admin'])
                    @can('announcement.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"
                                href="{{ route('admin.announcements.index') }}">
                                <i class="fa-solid fa-bullhorn"></i>
                                <span>Announcements</span>
                            </a>
                        </li>
                    @endcan
                @endrole
                @role('Super Admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.activity.index') ? 'active' : '' }} "
                            href="{{ route('admin.activity.index') }}">
                            <i class="fa-solid fa-file-pen"></i>
                            <span>Activity Log</span>
                        </a>
                    </li>
                @endrole
                @role('Super Admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.manage.category') ||
                        request()->routeIs('admin.manage.sub.category') ||
                        request()->routeIs('admin.prefixes.index') ||
                        request()->routeIs('admin.system-setting.index') ||
                        request()->routeIs('admin.manage.states') ||
                        request()->routeIs('admin.manage.locations') ||
                        request()->routeIs('admin.financial-years.*') ||
                        request()->routeIs('admin.currencies.*') ||
                        request()->routeIs('admin.company-types.*') ||
                        request()->routeIs('admin.biller-profiles.*') ||
                        request()->routeIs('admin.note-types.*') ||
                        request()->routeIs('admin.document-types.*') ||
                        request()->routeIs('admin.manage.item')
                            ? ''
                            : 'collapsed' }}"
                            data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
                            <i class="fa-solid fa-gears"></i><span>Settings </span><i
                                class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="settings-nav"
                            class="nav-content collapse sub-menu  {{ request()->routeIs('admin.manage.category') ||
                            request()->routeIs('admin.system-setting.index') ||
                            request()->routeIs('admin.manage.sub.category') ||
                            request()->routeIs('admin.manage.states') ||
                            request()->routeIs('admin.prefixes.index') ||
                            request()->routeIs('admin.manage.locations') ||
                            request()->routeIs('admin.financial-years.*') ||
                            request()->routeIs('admin.currencies.*') ||
                            request()->routeIs('admin.company-types.*') ||
                            request()->routeIs('admin.biller-profiles.*') ||
                            request()->routeIs('admin.note-types.*') ||
                            request()->routeIs('admin.document-types.*') ||
                            request()->routeIs('admin.manage.item')
                                ? 'show'
                                : '' }}"
                            data-bs-parent="#sidebar-nav">
                            <li class="nav-item">
                                <a href="{{ route('admin.manage.item') }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    <span>Items Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.biller-profiles.index') }}"
                                    class="{{ request()->routeIs('admin.manage.sub.category') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    <span>Biller Profiles</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manage.category') }}"
                                    class="{{ request()->routeIs('admin.manage.category') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Business Categories </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manage.sub.category') }}"
                                    class="{{ request()->routeIs('admin.manage.sub.category') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Sub Categories </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manage.states') }}"
                                    class="{{ request()->routeIs('admin.manage.states') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>States</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.manage.locations') }}"
                                    class="{{ request()->routeIs('admin.manage.locations') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Locations</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.prefixes.index') }}"
                                    class="{{ request()->routeIs('admin.prefixes.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Master Key Prefixes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.financial-years.index') }}"
                                    class="{{ request()->routeIs('admin.financial-years.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Financial Years</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.currencies.index') }}"
                                    class="{{ request()->routeIs('admin.currencies.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Currencies</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.company-types.index') }}"
                                    class="{{ request()->routeIs('admin.company-types.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Service Types</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.note-types.index') }}"
                                    class="{{ request()->routeIs('admin.note-types.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Note Types</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.document-types.index') }}"
                                    class="{{ request()->routeIs('admin.document-types.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Document Types</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole
            </ul>
        </div>
    </div>
</aside>
