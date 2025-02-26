<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">

        <ul class="navbar-nav">
            @php
                $notifications = auth()->user()->notifications->take(6); // Ambil 6 notifikasi terbaru
            @endphp
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell"></i>
                    @if ($notifications->count() > 0)
                        <div class="indicator">
                            <div class="circle"></div>
                        </div>
                    @endif
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p>{{ $notifications->count() }} New Notifications</p>
                        @if ($notifications->count() > 0)
                            <a href="{{ route('notifications.clear') }}" class="text-muted">Clear all</a>
                        @endif
                    </div>
                    <div class="p-1">
                        @forelse ($notifications as $notification)
                            <a href="{{ $notification->data['url'] }}"
                                class="dropdown-item d-flex align-items-center py-2">
                                <div
                                    class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                    <i class="icon-sm text-white" data-feather="info"></i>
                                </div>
                                <div class="flex-grow-1 me-2">
                                    <p>{{ $notification->data['message'] }}</p>
                                    <p class="tx-12 text-muted">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-muted py-2">No new notifications</p>
                        @endforelse
                    </div>
                    <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                        <a href="#">View all</a>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="wd-30 ht-30 rounded-circle"
                        src="{{ Auth::user()->employee->photo_url ? asset('storage/' . Auth::user()->employee->photo_url) : 'https://static.vecteezy.com/system/resources/thumbnails/003/337/584/small/default-avatar-photo-placeholder-profile-icon-vector.jpg' }}"
                        alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        <div class="mb-3">
                            <img class="wd-80 ht-80 rounded-circle"
                                src="{{ Auth::user()->employee->photo_url ? asset('storage/' . Auth::user()->employee->photo_url) : 'https://static.vecteezy.com/system/resources/thumbnails/003/337/584/small/default-avatar-photo-placeholder-profile-icon-vector.jpg' }}"
                                alt="">
                        </div>
                        <div class="text-center">
                            <p class="tx-16 fw-bolder">{{ Auth::user()->employee->fullname }}</p>
                            <p class="tx-12 text-muted">{{ Auth::user()->employee->email }}</p>
                        </div>
                    </div>
                    <ul class="list-unstyled p-1">
                        <li class="dropdown-item py-2">
                            <a href="{{ route('pegawais.index') }}" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="user"></i>
                                <span>Profile</span>
                            </a>
                        </li>

                        <li class="dropdown-item py-2">
                            <a href="{{ route('logout') }}" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="log-out"></i>
                                <span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchNotifications();
    });

    function fetchNotifications() {
        fetch("{{ route('notifications') }}")
            .then(response => response.json())
            .then(data => {
                let notifList = document.getElementById('notifList');
                let notifCount = document.getElementById('notifCount');

                if (data.length > 0) {
                    notifList.innerHTML = "";
                    notifCount.innerText = `${data.length} New Notifications`;

                    data.forEach(notif => {
                        let notifItem = `
                        <a href="${notif.data.url}" class="dropdown-item d-flex align-items-center py-2">
                            <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="me-2 icon-md" data-feather="bell"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>${notif.data.message}</p>
                                <p class="tx-12 text-muted">${new Date(notif.created_at).toLocaleString()}</p>
                            </div>
                        </a>
                    `;
                        notifList.innerHTML += notifItem;
                    });
                } else {
                    notifList.innerHTML = `<p class="text-muted text-center">No notifications</p>`;
                    notifCount.innerText = "0 New Notifications";
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    function clearNotifications() {
        fetch("{{ route('notifications.clear') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => {
                document.getElementById('notifList').innerHTML =
                    `<p class="text-muted text-center">No notifications</p>`;
                document.getElementById('notifCount').innerText = "0 New Notifications";
            })
            .catch(error => console.error('Error clearing notifications:', error));
    }
</script>
