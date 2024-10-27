<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>

                <!-- Navigation Links -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">User Links</h3>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-300 mt-2">
                        <li><a href="{{ route('profile.edit') }}" class="text-blue-500 hover:text-blue-700">Edit Profile</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-blue-500 hover:text-blue-700">View Cart</a></li>
                    </ul>
                </div>

                @can('admin')
                <!-- Admin Links (Visible to Admin Only) -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin Links</h3>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-300 mt-2">
                        <li><a href="{{ route('admin.products.index') }}" class="text-blue-500 hover:text-blue-700">Manage Products</a></li>
                        <li><a href="{{ route('admin.orders.index') }}" class="text-blue-500 hover:text-blue-700">Manage Orders</a></li>
                    </ul>
                </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
