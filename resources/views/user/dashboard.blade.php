<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h1 class="text-3xl font-bold mb-8 text-center">My Dashboard</h1>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="p-6 bg-white rounded-lg shadow hover:shadow-md transition text-center">
                    <h3 class="text-sm font-medium text-gray-500">Total Orders</h3>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>

                <div class="p-6 bg-white rounded-lg shadow hover:shadow-md transition text-center">
                    <h3 class="text-sm font-medium text-gray-500">Pending Orders</h3>
                    <p class="mt-2 text-2xl font-bold text-yellow-600">{{ $pendingOrders }}</p>
                </div>

                <div class="p-6 bg-white rounded-lg shadow hover:shadow-md transition text-center">
                    <h3 class="text-sm font-medium text-gray-500">Completed Orders</h3>
                    <p class="mt-2 text-2xl font-bold text-green-600">{{ $completedOrders }}</p>
                </div>

                <div class="p-6 bg-white rounded-lg shadow hover:shadow-md transition text-center">
                    <h3 class="text-sm font-medium text-gray-500">Total Spent</h3>
                    <p class="mt-2 text-2xl font-bold text-gray-900">${{ number_format($totalSpent, 2) }}</p>
                </div>
            </div>

            {{-- Recent Orders Table --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">#</th>
                                <th class="py-2 px-4 border-b text-left">Status</th>
                                <th class="py-2 px-4 border-b text-left">Total</th>
                                <th class="py-2 px-4 border-b text-left">Date</th>
                                <th class="py-2 px-4 border-b text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b">{{ $order->id }}</td>
                                    <td class="py-2 px-4 border-b">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border-b font-medium">
                                        ${{ number_format($order->total_price, 2) }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-gray-600">
                                        {{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('user.orders.show', $order->id) }}"
                                            class="text-blue-600 hover:text-blue-900 font-medium">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 px-4 border-b text-center text-gray-500">
                                        No recent orders.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-right">
                    <a href="{{ route('user.orders.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                        View All Orders &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
