<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
        <div class="bg-white dark:bg-[#0e1726] h-full">
            <div class="flex items-center justify-between px-4 py-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center main-logo shrink-0">
                    <img x-show="$store.app.theme !== 'dark'" x-transition.opacity
                        class="flex-none object-contain w-auto h-16" src="/assets/images/logo.png" alt="Light Logo" />
                    <img x-show="$store.app.theme === 'dark'" x-transition.opacity
                        class="flex-none object-contain w-auto h-16" src="/assets/images/logo-dark.png"
                        alt="Dark Logo" />
                </a>

                <a href="javascript:;"
                    class="flex items-center w-8 h-8 transition duration-300 rounded-full collapse-icon hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light rtl:rotate-180"
                    @click="$store.app.toggleSidebar()">
                    <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <ul
                class="perfect-scrollbar relative font-semibold space-y-0.5 h-[calc(100vh-80px)] overflow-y-auto overflow-x-hidden p-4 py-0">

                {{-- ===== MAIN ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Main</span>
                </h2>

                {{-- Dashboard --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link group {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    fill="currentColor" />
                                <path
                                    d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                        </div>
                    </a>
                </li>

                {{-- ===== USER MANAGEMENT ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>User Management</span>
                </h2>

                {{-- Customers --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.customers.index') }}" class="nav-link group {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="6" r="4" fill="currentColor" />
                                <path opacity="0.5"
                                    d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Customers</span>
                        </div>
                    </a>
                </li>

                {{-- Drivers (collapsible) --}}
                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active': activeDropdown === 'drivers'}" @click="activeDropdown === 'drivers' ? activeDropdown = '' : activeDropdown = 'drivers'">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="6" r="4" fill="currentColor" />
                                <path opacity="0.5"
                                    d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                    fill="currentColor" />
                                <path d="M18 5L20 7L18 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Drivers</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90': activeDropdown === 'drivers'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'drivers'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.drivers.index') }}" class="{{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">All Drivers</a></li>
                        <li><a href="{{ route('admin.driver-documents.index') }}" class="{{ request()->routeIs('admin.driver-documents.*') ? 'active' : '' }}">Driver Documents</a></li>
                        <li><a href="{{ route('admin.driver-rules.index') }}" class="{{ request()->routeIs('admin.driver-rules.*') ? 'active' : '' }}">Driver Rules</a></li>
                    </ul>
                </li>

                {{-- ===== ORDER MANAGEMENT ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Order Management</span>
                </h2>

                {{-- City Orders --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link group {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 3L2.26491 3.0883C3.58495 3.52832 4.24497 3.74832 4.62248 4.2721C5 4.79587 5 5.49159 5 6.88304V9.5C5 12.3284 5 13.7426 5.87868 14.6213C6.75736 15.5 8.17157 15.5 11 15.5H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M16.5 18C17.3284 18 18 18.6716 18 19.5C18 20.3284 17.3284 21 16.5 21C15.6716 21 15 20.3284 15 19.5C15 18.6716 15.6716 18 16.5 18Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M5 6H16.4504C18.5054 6 19.5328 6 19.9775 6.67426C20.4221 7.34853 20.0173 8.29294 19.2078 10.1818L18.7792 11.1818C18.4013 12.0636 18.2123 12.5045 17.8366 12.7523C17.4609 13 16.9812 13 16.0218 13H5" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">City Orders</span>
                        </div>
                    </a>
                </li>

                {{-- Intercity Orders --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.intercity-orders.index') }}" class="nav-link group {{ request()->routeIs('admin.intercity-orders.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 3L2.26491 3.0883C3.58495 3.52832 4.24497 3.74832 4.62248 4.2721C5 4.79587 5 5.49159 5 6.88304V9.5C5 12.3284 5 13.7426 5.87868 14.6213C6.75736 15.5 8.17157 15.5 11 15.5H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M16.5 18C17.3284 18 18 18.6716 18 19.5C18 20.3284 17.3284 21 16.5 21C15.6716 21 15 20.3284 15 19.5C15 18.6716 15.6716 18 16.5 18Z" stroke="currentColor" stroke-width="1.5" />
                                <path d="M14 6L17 9M17 9L20 6M17 9V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Intercity Orders</span>
                        </div>
                    </a>
                </li>

                {{-- ===== SERVICE MANAGEMENT ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Service Management</span>
                </h2>

                {{-- Services --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.services.index') }}" class="nav-link group {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" fill="currentColor" />
                                <path d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M6 12H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Services</span>
                        </div>
                    </a>
                </li>

                {{-- Intercity Services --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.intercity-services.index') }}" class="nav-link group {{ request()->routeIs('admin.intercity-services.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" fill="currentColor" />
                                <path d="M15 12H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M12 9L15 12L12 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Intercity Services</span>
                        </div>
                    </a>
                </li>

                {{-- ===== VEHICLE MANAGEMENT (Collapsible) ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Vehicle Management</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active': activeDropdown === 'vehicles'}" @click="activeDropdown === 'vehicles' ? activeDropdown = '' : activeDropdown = 'vehicles'">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 14.5H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.5" d="M4.5 17C5.32843 17 6 17.6716 6 18.5C6 19.3284 5.32843 20 4.5 20C3.67157 20 3 19.3284 3 18.5C3 17.6716 3.67157 17 4.5 17Z" fill="currentColor" />
                                <path opacity="0.5" d="M19.5 17C20.3284 17 21 17.6716 21 18.5C21 19.3284 20.3284 20 19.5 20C18.6716 20 18 19.3284 18 18.5C18 17.6716 18.6716 17 19.5 17Z" fill="currentColor" />
                                <path d="M2 14.5L3.5 8.5C3.77614 7.39543 4.76751 6.5 5.9 6.5H18.1C19.2325 6.5 20.2239 7.39543 20.5 8.5L22 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.5" d="M8 14.5V11.5H16V14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Vehicles</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90': activeDropdown === 'vehicles'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'vehicles'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.vehicle-types.index') }}" class="{{ request()->routeIs('admin.vehicle-types.*') ? 'active' : '' }}">Vehicle Types</a></li>
                        <li><a href="{{ route('admin.vehicle-companies.index') }}" class="{{ request()->routeIs('admin.vehicle-companies.*') ? 'active' : '' }}">Vehicle Companies</a></li>
                        <li><a href="{{ route('admin.vehicle-models.index') }}" class="{{ request()->routeIs('admin.vehicle-models.*') ? 'active' : '' }}">Vehicle Models</a></li>
                        <li><a href="{{ route('admin.insurance-companies.index') }}" class="{{ request()->routeIs('admin.insurance-companies.*') ? 'active' : '' }}">Insurance Companies</a></li>
                        <li><a href="{{ route('admin.freight-vehicles.index') }}" class="{{ request()->routeIs('admin.freight-vehicles.*') ? 'active' : '' }}">Freight Vehicles</a></li>
                    </ul>
                </li>

                {{-- ===== LOCATION MANAGEMENT (Collapsible) ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Location Management</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active': activeDropdown === 'locations'}" @click="activeDropdown === 'locations' ? activeDropdown = '' : activeDropdown = 'locations'">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21C16 17 20 13.4183 20 9C20 4.58172 16.4183 1 12 1C7.58172 1 4 4.58172 4 9C4 13.4183 8 17 12 21Z" stroke="currentColor" stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="9" r="3" fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Locations</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90': activeDropdown === 'locations'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'locations'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.zones.index') }}" class="{{ request()->routeIs('admin.zones.*') ? 'active' : '' }}">Zones</a></li>
                        <li><a href="{{ route('admin.states.index') }}" class="{{ request()->routeIs('admin.states.*') ? 'active' : '' }}">States</a></li>
                        <li><a href="{{ route('admin.cities.index') }}" class="{{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">Cities</a></li>
                        <li><a href="{{ route('admin.districts.index') }}" class="{{ request()->routeIs('admin.districts.*') ? 'active' : '' }}">Districts</a></li>
                        <li><a href="{{ route('admin.airports.index') }}" class="{{ request()->routeIs('admin.airports.*') ? 'active' : '' }}">Airports</a></li>
                    </ul>
                </li>

                {{-- ===== PRICING (Collapsible) ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Pricing</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active': activeDropdown === 'pricing'}" @click="activeDropdown === 'pricing' ? activeDropdown = '' : activeDropdown = 'pricing'">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Pricing</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90': activeDropdown === 'pricing'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'pricing'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.taxes.index') }}" class="{{ request()->routeIs('admin.taxes.*') ? 'active' : '' }}">Taxes</a></li>
                        <li><a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">Coupons</a></li>
                        <li><a href="{{ route('admin.currencies.index') }}" class="{{ request()->routeIs('admin.currencies.*') ? 'active' : '' }}">Currencies</a></li>
                    </ul>
                </li>

                {{-- ===== SUBSCRIPTIONS ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Subscriptions</span>
                </h2>

                {{-- Subscription Plans --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.subscription-plans.index') }}" class="nav-link group {{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.5 12C3.5 7.52166 3.5 5.28249 4.89124 3.89124C6.28249 2.5 8.52166 2.5 13 2.5C17.4783 2.5 19.7175 2.5 21.1088 3.89124C22.5 5.28249 22.5 7.52166 22.5 12C22.5 16.4783 22.5 18.7175 21.1088 20.1088C19.7175 21.5 17.4783 21.5 13 21.5C8.52166 21.5 6.28249 21.5 4.89124 20.1088C3.5 18.7175 3.5 16.4783 3.5 12Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M1.5 8H3.5M1.5 12H3.5M1.5 16H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M13 7V9M13 9V11M13 9H15M13 9H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <circle opacity="0.5" cx="13" cy="16" r="2" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Subscription Plans</span>
                        </div>
                    </a>
                </li>

                {{-- Recharge Plans --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.recharge-plans.index') }}" class="nav-link group {{ request()->routeIs('admin.recharge-plans.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 6V18M12 6L7 11M12 6L17 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.5" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Recharge Plans</span>
                        </div>
                    </a>
                </li>

                {{-- ===== CONTENT (Collapsible) ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Content</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active': activeDropdown === 'content'}" @click="activeDropdown === 'content' ? activeDropdown = '' : activeDropdown = 'content'">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M8 8H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M8 16H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Content</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90': activeDropdown === 'content'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'content'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">Banners</a></li>
                        <li><a href="{{ route('admin.cms.index') }}" class="{{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">CMS Pages</a></li>
                        <li><a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">FAQs</a></li>
                        <li><a href="{{ route('admin.onboarding.index') }}" class="{{ request()->routeIs('admin.onboarding.*') ? 'active' : '' }}">Onboarding</a></li>
                    </ul>
                </li>

                {{-- ===== FINANCE (Collapsible) ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Finance</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active': activeDropdown === 'finance'}" @click="activeDropdown === 'finance' ? activeDropdown = '' : activeDropdown = 'finance'">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path opacity="0.5" d="M2 10H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Finance</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{'!rotate-90': activeDropdown === 'finance'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'finance'" x-collapse class="sub-menu text-gray-500">
                        <li><a href="{{ route('admin.payout-requests.index') }}" class="{{ request()->routeIs('admin.payout-requests.*') ? 'active' : '' }}">Payout Requests</a></li>
                        <li><a href="{{ route('admin.wallet.driver') }}" class="{{ request()->routeIs('admin.wallet.driver') ? 'active' : '' }}">Driver Wallet</a></li>
                        <li><a href="{{ route('admin.wallet.user') }}" class="{{ request()->routeIs('admin.wallet.user') ? 'active' : '' }}">User Wallet</a></li>
                    </ul>
                </li>

                {{-- ===== SOS ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>SOS</span>
                </h2>

                {{-- SOS Contacts --}}
                <li class="menu nav-item">
                    <a href="{{ route('admin.sos.index') }}" class="nav-link group {{ request()->routeIs('admin.sos.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 8.45V12.45" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.5" d="M12 16.45H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10.2898 3.86001L1.81978 18C1.64514 18.3024 1.55274 18.6453 1.55177 18.9945C1.55079 19.3437 1.64127 19.6871 1.8142 19.9905C1.98714 20.2939 2.2365 20.5468 2.53748 20.7239C2.83847 20.9009 3.18058 20.9961 3.52978 21H20.4698C20.819 20.9961 21.1611 20.9009 21.4621 20.7239C21.7631 20.5468 22.0124 20.2939 22.1854 19.9905C22.3583 19.6871 22.4488 19.3437 22.4478 18.9945C22.4468 18.6453 22.3544 18.3024 22.1798 18L13.7098 3.86001C13.5315 3.56611 13.2805 3.32312 12.981 3.15449C12.6814 2.98585 12.3435 2.89726 11.9998 2.89726C11.656 2.89726 11.3181 2.98585 11.0186 3.15449C10.7191 3.32312 10.468 3.56611 10.2898 3.86001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">SOS Contacts</span>
                        </div>
                    </a>
                </li>

                {{-- ===== SETTINGS ===== --}}
                <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <span>Settings</span>
                </h2>

                {{-- Settings --}}
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="group nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M13.7654 2.15224C13.3978 2 12.9319 2 12 2C11.0681 2 10.6022 2 10.2346 2.15224C9.74457 2.35523 9.35523 2.74458 9.15224 3.23463C9.05957 3.45834 9.0233 3.7185 9.00911 4.09799C8.98826 4.65568 8.70226 5.17189 8.21894 5.45093C7.73564 5.72996 7.14559 5.71954 6.65219 5.45876C6.31645 5.2813 6.07301 5.18262 5.83294 5.15102C5.30704 5.08178 4.77518 5.22429 4.35436 5.5472C4.03874 5.78938 3.80577 6.1929 3.33983 6.99993C2.87389 7.80697 2.64092 8.21048 2.58899 8.60491C2.51976 9.1308 2.66227 9.66266 2.98518 10.0835C3.13256 10.2756 3.3397 10.437 3.66119 10.639C4.1338 10.936 4.43789 11.4419 4.43786 12C4.43783 12.5581 4.13375 13.0639 3.66118 13.3608C3.33965 13.5629 3.13248 13.7244 2.98508 13.9165C2.66217 14.3373 2.51966 14.8691 2.5889 15.3951C2.64082 15.7895 2.87379 16.193 3.33973 17.0001C3.80568 17.8071 4.03865 18.2106 4.35426 18.4528C4.77508 18.7757 5.30694 18.9182 5.83284 18.849C6.07289 18.8174 6.31632 18.7187 6.65204 18.5413C7.14547 18.2804 7.73556 18.27 8.2189 18.5491C8.70224 18.8281 8.98826 19.3443 9.00911 19.9021C9.02331 20.2815 9.05957 20.5417 9.15224 20.7654C9.35523 21.2554 9.74457 21.6448 10.2346 21.8478C10.6022 22 11.0681 22 12 22C12.9319 22 13.3978 22 13.7654 21.8478C14.2554 21.6448 14.6448 21.2554 14.8478 20.7654C14.9404 20.5417 14.9767 20.2815 14.9909 19.902C15.0117 19.3443 15.2977 18.8281 15.781 18.549C16.2643 18.2699 16.8544 18.2804 17.3479 18.5412C17.6836 18.7186 17.927 18.8174 18.167 18.849C18.693 18.9182 19.2248 18.7757 19.6456 18.4528C19.9613 18.2106 20.1942 17.8071 20.6602 17C21.1261 16.193 21.3591 15.7895 21.411 15.395C21.4802 14.8691 21.3377 14.3373 21.0148 13.9164C20.8674 13.7243 20.6602 13.5628 20.3387 13.3608C19.8662 13.0639 19.5621 12.558 19.5621 11.9999C19.5621 11.4418 19.8662 10.9361 20.3387 10.6392C20.6603 10.4371 20.8675 10.2757 21.0149 10.0835C21.3378 9.66273 21.4803 9.13087 21.4111 8.60497C21.3592 8.21055 21.1262 7.80703 20.6602 7C20.1943 6.19297 19.9613 5.78945 19.6457 5.54727C19.2249 5.22436 18.693 5.08185 18.1671 5.15109C17.9271 5.18269 17.6837 5.28136 17.3479 5.4588C16.8545 5.71959 16.2644 5.73002 15.7811 5.45099C15.2977 5.17196 15.0117 4.65574 14.9909 4.09805C14.9767 3.71856 14.9404 3.45838 14.8478 3.23463C14.6448 2.74458 14.2554 2.35523 13.7654 2.15224Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Settings</span>
                        </div>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</div>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({
            activeDropdown: '',
            init() {
                // Auto-open the dropdown containing the active link
                const selector = document.querySelector('.sidebar ul a.active');
                if (selector) {
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        const button = ul.previousElementSibling;
                        if (button) {
                            const match = button.getAttribute('@click')?.match(/'([^']+)'/);
                            if (match) this.activeDropdown = match[1];
                        }
                    }
                }
            },
        }));
    });
</script>
