<nav class="bg-primary border-gray-200 dark:bg-white fixed top-0 z-50 w-full border-b">
    <div class="max-w-screen-3xl flex flex-wrap items-center justify-between py-2 sm:py-3 w-full px-2 lg:px-4 ">
        <div class="flex gap-2">
            <button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar"
                aria-controls="sidebar-multi-level-sidebar" type="button"
                class="ms-4 inline-flex items-center p-2 text-sm text-white rounded-lg lg:hidden hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                    </path>
                </svg>
            </button>
            <a href="/" class="flex items-center rtl:space-x-reverse">
                <img src="{{ url('/images/assets/Wedangan-DRio-White.png') }}" class="ms-4 lg:ms-0 h-8 hidden lg:block"
                    alt="logo" />
                <h6 class="font-bold text-white text-xl lg:hidden">Wedangan D'Rio</h6>
            </a>
        </div>

        <div class="flex items-center lg:order-2 space-x-3 lg:space-x-0 rtl:space-x-reverse">
            <button type="button"
                class="flex text-sm bg-gray-800 rounded-full lg:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                data-dropdown-placement="bottom">
                <span class="sr-only">Open user menu</span>
            </button>
            <!-- Dropdown menu -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex me-4 lg:me-8 items-center">
                        {{-- <div>{{ Auth::user()->name }}</div> --}}
                        <img class="w-9 h-9 sm:h-12 sm:w-12 rounded-full"
                            src="{{ Auth::user()->img ? asset('storage/' . Auth::user()->img) : asset('https://flowbite.com/docs/images/people/profile-picture-5.jpg') }}"
                            alt="user">
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
        <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1" id="navbar-user">

        </div>
    </div>
</nav>

<aside id="sidebar-multi-level-sidebar"
    class="fixed top-0 left-0 pt-14 sm:pt-16 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-primary  dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @if (Auth::user()->role == 1)
                <li>
                    <a href="/dashboard"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-secondary dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-white   transition duration-75 dark:text-gray-400 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <button type="button"
                        class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-secondary dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                        <svg class="flex-shrink-0 w-5 h-5 text-white     transition duration-75 dark:text-gray-400 group-hover:text-white dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">CRUD</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul id="dropdown-example" class="hidden py-2 space-y-2">
                        <li>
                            <a href="/categories"
                                class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-secondary dark:text-white dark:hover:bg-gray-700">Kategori</a>
                        </li>
                        <li>
                            <a href="/products"
                                class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-secondary dark:text-white dark:hover:bg-gray-700">Produk</a>
                        </li>
                        <li>
                            <a href="/users"
                                class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-secondary dark:text-white dark:hover:bg-gray-700">Users</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li>
                <a href="/history"
                    class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-secondary dark:hover:bg-gray-700 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white     transition duration-75 dark:text-gray-400 group-hover:text-white dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Riwayat Transaksi</span>
                </a>
            </li>
            <li>
                <a href="/transaction"
                    class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-secondary dark:hover:bg-gray-700 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white     transition duration-75 group-hover:text-white dark:text-gray-400 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                        <path
                            d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Transaksi</span>
                </a>
            </li>
            <li>
                <a href="/backup"
                    class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-secondary dark:hover:bg-gray-700 group">

                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-white dark:text-gray-400 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M9.25 1.75a3.75 3.75 0 0 0-3.75 3.75v.75h-1a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-1v-.75a3.75 3.75 0 0 0-3.75-3.75h-1.5zM7 5.5a2.25 2.25 0 0 1 2.25-2.25h1.5a2.25 2.25 0 0 1 2.25 2.25v.75H7v-.75zm5 4.75a.75.75 0 0 1-.75.75h-2.5a.75.75 0 0 1 0-1.5h2.5a.75.75 0 0 1 .75.75z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Backup</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<div class="p-4 lg:ml-64">
    <div class="px-4 py-1  sm:px-4 sm:py-12 border-gray-200 rounded-lg dark:border-gray-700 mt-14 sm:mt-10">
        @yield('content')
    </div>
</div>
