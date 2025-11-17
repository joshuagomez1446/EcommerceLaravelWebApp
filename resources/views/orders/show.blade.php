<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Order Info --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Order Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><span class="font-semibold">Order ID:</span> #{{ $order->id }}</p>
                        <p><span class="font-semibold">Status:</span> {{ ucfirst($order->status) }}</p>
                        <p><span class="font-semibold">Date:</span> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p><span class="font-semibold">Customer:</span> {{ $order->user->name ?? 'Guest' }}</p>
                        <p><span class="font-semibold">Email:</span> {{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Ordered Products --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Products</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Product</th>
                                <th class="py-2 px-4 border-b text-left">Price</th>
                                <th class="py-2 px-4 border-b text-left">Quantity</th>
                                <th class="py-2 px-4 border-b text-left">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                                    <td class="py-2 px-4 border-b">${{ number_format($product->pivot->price, 2) }}</td>
                                    <td class="py-2 px-4 border-b">{{ $product->pivot->quantity }}</td>
                                    <td class="py-2 px-4 border-b">
                                        ${{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-right">
                    <p class="text-xl font-bold">Total: ${{ number_format($order->total_price, 2) }}</p>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                    &larr; Back to Orders
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
