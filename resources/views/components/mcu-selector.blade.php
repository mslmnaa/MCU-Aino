@props(['patient', 'selectedOrderId' => null])

@php
    $mcuOrders = $patient->mcu_orders;
    $selectedOrder = $selectedOrderId ? $mcuOrders->find($selectedOrderId) : $mcuOrders->first();
@endphp

@if($mcuOrders->count() > 1)
<div class="mb-4">
    <label for="mcu-selector" class="block text-sm font-medium text-gray-700 mb-2">
        📋 Select MCU Record
    </label>
    <select id="mcu-selector"
            onchange="window.location.href = this.value"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
        @foreach($mcuOrders as $order)
            <option value="{{ route('medical-records.patient', $patient->id) }}?order_id={{ $order->id }}"
                    {{ $selectedOrder && $selectedOrder->id === $order->id ? 'selected' : '' }}>
                {{ $order->mcu_display_text }}
            </option>
        @endforeach
    </select>
</div>
@endif

<!-- Current MCU Display with Badge -->
@if($selectedOrder)
<div class="mb-4 p-4 bg-gray-50 rounded-lg border">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">
                MCU Results - {{ $selectedOrder->tgl_order->format('M j, Y') }}
            </h3>
            <p class="text-sm text-gray-500">Lab: {{ $selectedOrder->no_lab }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <x-mcu-badge :order="$selectedOrder" />
            @if($mcuOrders->count() > 1)
                <span class="text-xs text-gray-400">
                    {{ $mcuOrders->search($selectedOrder) + 1 }} of {{ $mcuOrders->count() }}
                </span>
            @endif
        </div>
    </div>
</div>
@endif