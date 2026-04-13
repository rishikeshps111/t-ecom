<!-- ======= Header ======= -->
<header id="header" class="header fixed-top header-blur ">
    <div class="header-top-cs">
        <div class="digital-time-container">
            <div class="display-date">
                <h5>Hi, {{ auth()->user()->name ?? 'User' }}</h5>
            </div>
        </div>
        <div class="digital-time-container">
            <div class="display-date">
                <span id="day">day</span>,
                <span id="daynum">00</span>
                <span id="month">month</span>
                <span id="year">0000</span>
            </div>
            <div class="display-time"></div>
        </div>

        <div class="header-section-right">
            <div class="header-section-right dropdown position-relative">
                @php
                    $unreadItems = unreadMessagesByUser();
                    $unreadAnnouncements = unreadAnnouncement();
                    $totalUnread = $unreadItems->count() + $unreadAnnouncements->count();
                @endphp

                <a href="#" class="btn btn-light position-relative mr-cs" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fa-regular fa-comments fa-lg"></i>
                    @if ($totalUnread > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $totalUnread }}
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="min-width: 250px;">
                    <li class="dropdown-header fw-bold text-center">Messages</li>
                    <hr class="dropdown-divider my-1">

                    @forelse($unreadItems as $item)
                        <li>
                            <a href="{{ route('admin.messages.conversation', $item->chat->id) }}"
                                class="dropdown-item d-flex align-items-center">
                                <div class="flex-grow-1 text-truncate mb-1">
                                    @role('Super Admin')
                                        <strong>{{ $item->chat->user->name ?? 'Unknown' }}</strong> - <small
                                            class="text-muted">{{ \Illuminate\Support\Str::limit($item->message, 35, '...') }}</small>
                                    @else
                                        <small
                                            class="text-muted">{{ \Illuminate\Support\Str::limit($item->message, 35, '...') }}</small>
                                    @endrole
                                </div>
                                @if (!$item->is_read)
                                    <span class="badge bg-danger ms-2">New</span>
                                @endif
                            </a>
                        </li>
                    @empty
                        <li class="dropdown-item text-center text-muted">No unread messages</li>
                    @endforelse

                    @unlessrole('Super Admin')
                        @if ($unreadItems->count() > 0 || $unreadAnnouncements->count() > 0)
                            <hr class="dropdown-divider my-1">
                            <li class="dropdown-header fw-bold text-center">Announcements</li>
                            <hr class="dropdown-divider my-1">
                        @endif

                        @forelse($unreadAnnouncements as $item)
                            <li>
                                <a href="{{ route('admin.announcements.show', $item->id) }}"
                                    class="dropdown-item d-flex align-items-center">
                                    <div class="flex-grow-1 text-truncate mb-1">
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($item->message, 35, '...') }}</small>
                                    </div>
                                    <span class="badge bg-danger ms-2">New</span>
                                </a>
                            </li>
                        @empty
                            @if ($unreadItems->count() == 0)
                                <li class="dropdown-item text-center text-muted">No unread announcements</li>
                            @endif
                        @endforelse
                    @endunlessrole
                </ul>
            </div>
            {{-- <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form> --}}
            @role('Super Admin')
                <div class="headertogle">
                    <a href="{{ route('admin.settings.index') }}" style="color: inherit; text-decoration: none;">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                </div>
            @endrole
            <div class="headertogle">
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div>
            <div class="headertogle">
                <a href="{{ route('admin.profile.edit') }}" style="color: inherit; text-decoration: none;">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
            <div class="headertogle">
                <form id="logoutForm" action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="button" id="logoutBtn"
                        style="background: none; border: none; color: inherit; cursor: pointer;">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>


    <!-- End Logo -->

    <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> -->


    <!--<nav class="header-nav ms-auto">-->
    <!--    <ul class="d-flex align-items-center">-->

    <!--        <li class="nav-item d-block d-lg-none">-->
    <!--            <a class="nav-link nav-icon search-bar-toggle " href="#">-->
    <!--                <i class="bi bi-search"></i>-->
    <!--            </a>-->
    <!--        </li>-->



    <!--    </ul>-->
    <!--</nav>-->
    <!-- End Icons Navigation -->

</header><!-- End Header -->
