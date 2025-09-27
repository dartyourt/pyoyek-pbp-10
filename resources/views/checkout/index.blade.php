<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">Complete Your Order</h1>

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
                        <!-- Order Summary -->
                        <div class="lg:col-span-2">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Order Summary</h2>
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex items-start space-x-4 py-4">
                                        <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                            @if($item->product->image_path)
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <span class="text-xs text-gray-500">No Image</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-md font-semibold text-gray-800 truncate">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600">Qty: {{ $item->qty }}</p>
                                            <p class="text-sm text-gray-500">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-md font-semibold text-gray-800">
                                                Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 border-t-2 border-dashed border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                                    <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <div class="lg:col-span-3">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Shipping & Payment</h2>
                            <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province *</label>
                                        <select id="province" name="province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="regency" class="block text-sm font-medium text-gray-700 mb-1">City/Regency *</label>
                                        <select id="regency" name="regency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required disabled>
                                            <option value="">Select City/Regency</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District *</label>
                                        <select id="district" name="district" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required disabled>
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="village" class="block text-sm font-medium text-gray-700 mb-1">Village *</label>
                                        <select id="village" name="village" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required disabled>
                                            <option value="">Select Village</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Shipping Address -->
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Full Address (Street, Building, etc.) *</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="e.g., Jl. Jend. Sudirman No. 5, RT 01/RW 02">{{ old('shipping_address') }}</textarea>
                                    <input type="hidden" name="shipping_city" id="shipping_city">
                                </div>

                                <div>
                                    <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                                    <input type="text" id="shipping_postal_code" name="shipping_postal_code" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           value="{{ old('shipping_postal_code') }}" 
                                           required 
                                           pattern="[0-9]*" 
                                           oninput="handlePostalCodeInput(this)"
                                           maxlength="5">
                                    <span id="postal_code_error" class="text-xs text-red-600 mt-1" style="display: none;">Please enter numbers only.</span>
                                </div>

                                <!-- Payment Proof -->
                                <div>
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">Proof of Payment *</label>
                                    <input type="file" id="payment_proof" name="payment_proof" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer" required>
                                    @error('payment_proof')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-6 border-t">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-300">
                                        Place Order
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function handlePostalCodeInput(input) {
            const errorSpan = document.getElementById('postal_code_error');
            const originalValue = input.value;
            const numericValue = originalValue.replace(/[^0-9]/g, '');

            if (originalValue !== numericValue) {
                errorSpan.style.display = 'inline';
            } else {
                errorSpan.style.display = 'none';
            }

            input.value = numericValue;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province');
            const regencySelect = document.getElementById('regency');
            const districtSelect = document.getElementById('district');
            const villageSelect = document.getElementById('village');
            const shippingCityInput = document.getElementById('shipping_city');

            const apiBaseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

            // Fetch Provinces
            fetch(`${apiBaseUrl}/provinces.json`)
                .then(response => response.json())
                .then(provinces => {
                    provinces.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                });

            // Province Change Event
            provinceSelect.addEventListener('change', function () {
                const provinceId = this.value;
                resetSelect(regencySelect, 'Select City/Regency');
                resetSelect(districtSelect, 'Select District');
                resetSelect(villageSelect, 'Select Village');
                regencySelect.disabled = true;
                districtSelect.disabled = true;
                villageSelect.disabled = true;

                if (provinceId) {
                    fetch(`${apiBaseUrl}/regencies/${provinceId}.json`)
                        .then(response => response.json())
                        .then(regencies => {
                            regencies.forEach(regency => {
                                const option = document.createElement('option');
                                option.value = regency.id;
                                option.textContent = regency.name;
                                regencySelect.appendChild(option);
                            });
                            regencySelect.disabled = false;
                        });
                }
            });

            // Regency Change Event
            regencySelect.addEventListener('change', function () {
                const regencyId = this.value;
                const selectedRegencyName = this.options[this.selectedIndex].text;
                shippingCityInput.value = selectedRegencyName; // Set hidden input for city

                resetSelect(districtSelect, 'Select District');
                resetSelect(villageSelect, 'Select Village');
                districtSelect.disabled = true;
                villageSelect.disabled = true;

                if (regencyId) {
                    fetch(`${apiBaseUrl}/districts/${regencyId}.json`)
                        .then(response => response.json())
                        .then(districts => {
                            districts.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district.id;
                                option.textContent = district.name;
                                districtSelect.appendChild(option);
                            });
                            districtSelect.disabled = false;
                        });
                }
            });

            // District Change Event
            districtSelect.addEventListener('change', function () {
                const districtId = this.value;
                resetSelect(villageSelect, 'Select Village');
                villageSelect.disabled = true;

                if (districtId) {
                    fetch(`${apiBaseUrl}/villages/${districtId}.json`)
                        .then(response => response.json())
                        .then(villages => {
                            villages.forEach(village => {
                                const option = document.createElement('option');
                                option.value = village.id;
                                option.textContent = village.name;
                                villageSelect.appendChild(option);
                            });
                            villageSelect.disabled = false;
                        });
                }
            });

            function resetSelect(selectElement, defaultText) {
                selectElement.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = defaultText;
                selectElement.appendChild(defaultOption);
            }
        });
    </script>
</x-app-layout>