@extends('layouts.cashier')
@section('title', 'Transaksi')
@section('content')
    <div x-data="{
        products: {{ $products->map(
                fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->selling_price,
                    'image' => asset('storage/' . $item->img),
                    'quantity' => 0,
                    'category_id' => $item->category_id,
                ],
            )->toJson() }},
        selectedCategory: null,
        searchTerm: '',
        paymentMethod: 'cash', // Add this line
    
        get filteredProducts() {
            return this.products.filter(product => {
                const matchCategory = this.selectedCategory === null || product.category_id === this.selectedCategory;
                const matchSearch = this.searchTerm === '' || product.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                return matchCategory && matchSearch;
            });
        },
    
        get selectedProducts() {
            return this.products.filter(p => p.quantity > 0);
        },
    
        get total() {
            return this.selectedProducts.reduce((sum, product) => sum + (product.price * product.quantity), 0);
        },
    
        getItemTotal(product) {
            return product.price * product.quantity;
        },
    
        formatPrice(price) {
            return price.toLocaleString('id-ID');
        },
    
        setCategory(categoryId) {
            this.selectedCategory = categoryId;
        }
    }" class="overflow-y-auto">

        <div class="md:flex">
            <!-- Search and Filter Section -->
            <div class="w-full">
                <div class="relative col-span-8">
                    <div class="">
                        <div class="w-full">
                            <input type="search" x-model="searchTerm"
                                class="block p-2.5 w-full z-20 text-sm text-black bg-gray-50 rounded-lg border border-primary focus:ring-secondary focus:border-secondary dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-secondary"
                                placeholder="Cari item" />
                        </div>
                        <button type="submit"
                            class="absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-primary rounded-e-lg border border-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-primary dark:focus:ring-primary">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </div>
                </div>

                <!-- Product Categories -->
                <div class="mt-4 overflow-x-auto">
                    <div class="sm:justify-normal flex gap-1 sm:gap-3">
                        <button @click="setCategory(null)"
                            :class="{ 'bg-primary': selectedCategory === null, 'bg-secondary': selectedCategory !== null }"
                            class="text-white rounded-full py-2 px-4 sm:p-3 hover:bg-primary">
                            <h6 class="text-sm sm:text-base">Semua</h6>
                        </button>
                        @foreach ($categories as $category)
                            <button @click="setCategory({{ $category->id }})"
                                :class="{
                                    'bg-primary': selectedCategory ===
                                        {{ $category->id }},
                                    'bg-secondary': selectedCategory !== {{ $category->id }}
                                }"
                                class="text-white rounded-full py-2 px-4 sm:p-3 hover:bg-primary">
                                <h6 class="text-sm sm:text-base">{{ $category->name }}</h6>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="md:flex-1 overflow-y-auto">
                    <div class="pt-3 grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 2xl:grid-cols-5"
                        :class="{ 'max-h-[calc(100vh-300px)]': selectedProducts.length > 0 }">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <button
                                class="group container card flex mb-2 items-center bg-white hover:bg-secondary hover:text-white justify-between px-3 py-2 rounded-xl shadow"
                                x-on:click="product.quantity++">
                                <div class="box flex items-center gap-x-3">
                                    <img :src="product.image" alt="" class="rounded h-12 w-12 sm:h-16 sm:w-16">
                                    <div class="flex flex-col justify-between">
                                        <h1 class="menu-item dark:text-white group-hover:text-white text-primary sm:text-base text-sm sm:font-medium text-left sm:leading-4 leading-4"
                                            x-text="product.name"></h1>
                                        <h3
                                            class="harga dark:text-slate-300 group-hover:text-white text-primary sm:text-sm text-xs sm:font-medium text-left">
                                            Rp <span x-text="formatPrice(product.price)"></span></h3>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>


            <div class="min-w-[400px] max-w-full flex-grow hidden md:block">
                <div class="px-5">
                    <div class="w-full rounded-t-lg px-2 py-5 lg:px-5 h-[75vh] flex flex-col shadow-lg bg-primary">
                        <h2 class="font-semibold mb-3 text-white sm:mx-4 border-b">Rincian Pembelian</h2>
                        <div class="overflow-y-auto flex-1 pr-2 sm:mx-4" x-show="selectedProducts.length > 0">
                            <template x-for="product in selectedProducts" :key="product.id">
                                <div class="flex items-center mb-4 border-b pb-2">
                                    <div class="basis-5/12">
                                        <div class="text-sm text-white" x-text="product.name"></div>
                                        <div class="text-sm text-white">Rp <span x-text="formatPrice(product.price)"></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 basis-4/12">
                                        <button class="text-white hover:text-gray-700"
                                            x-on:click="product.quantity > 0 && product.quantity--">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <span class="w-6 text-center text-white" x-text="product.quantity"></span>
                                        <button class="text-white hover:text-gray-700" x-on:click="product.quantity++">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="basis-3/12 text-right">
                                        <div class="text-sm text-white font-medium ">Rp <span
                                                x-text="formatPrice(getItemTotal(product))"></span></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="px-5  hidden md:block">
                    <div class="bg-white p-5 shadow-lg rounded-b-xl">
                        <div class="sm:mx-4 flex items-center justify-between">
                            <div>
                                <div class="text-sm text-primary">Total</div>
                                <div class="font-semibold text-primary">Rp <span x-text="formatPrice(total)"></span></div>
                            </div>
                            <button
                                class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-lg flex items-center gap-2"
                                data-modal-target="payment-modal" data-modal-toggle="payment-modal" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Bayar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Cart Section -->
        <div class="fixed bottom-0 left-0 right-0 px-5 md:hidden" x-show="selectedProducts.length > 0">
            <div class="rounded-t-lg p-5 mb-[75px] max-h-[38vh] flex flex-col shadow-lg bg-primary lg:ms-72">
                <h2 class="font-semibold mb-3 text-white sm:mx-4 border-b">Rincian Pembelian</h2>
                <div class="overflow-y-auto flex-1 pr-2 sm:mx-4">
                    <template x-for="product in selectedProducts" :key="product.id">
                        <div class="flex items-center mb-4 border-b pb-2">
                            <div class="basis-5/12">
                                <div class="text-sm text-white" x-text="product.name"></div>
                                <div class="text-sm text-white">Rp <span x-text="formatPrice(product.price)"></span></div>
                            </div>
                            <div class="flex items-center gap-2 basis-4/12">
                                <button class="text-white hover:text-gray-700"
                                    x-on:click="product.quantity > 0 && product.quantity--">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                <span class="w-6 text-center text-white" x-text="product.quantity"></span>
                                <button class="text-white hover:text-gray-700" x-on:click="product.quantity++">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="basis-3/12 text-right">
                                <div class="text-sm text-white font-medium ">Rp <span
                                        x-text="formatPrice(getItemTotal(product))"></span></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="fixed bottom-0 left-0 right-0 px-5 md:hidden">
            <div class="bg-white p-5 shadow-lg">
                <div class="sm:mx-4 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-primary">Total</div>
                        <div class="font-semibold text-primary">Rp <span x-text="formatPrice(total)"></span></div>
                    </div>
                    <button class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-lg flex items-center gap-2"
                        data-modal-target="payment-modal" data-modal-toggle="payment-modal" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Bayar
                    </button>
                </div>
            </div>
        </div>

        <!-- Main modal -->
        <div id="payment-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="fixed inset-0 bg-black opacity-50 z-40"></div>
            <div class="relative p-4 w-full max-w-md max-h-full z-50">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-black text-primary dark:text-white">
                            Metode Pembayaran
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-secondary rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="payment-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form id="paymentForm" class="p-4 md:p-5" x-data="{
                        showCashInput: false,
                        showQris: false,
                        cashAmount: '',
                        paymentMethod: '', // Remove default value
                        get isValid() {
                            if (this.paymentMethod === 'cash') {
                                return this.cashAmount && parseFloat(this.cashAmount) >= total;
                            }
                            return true;
                        }
                    }">
                        @csrf
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Pilih metode pembayaran yang diinginkan :</p>
                        <ul class="space-y-4 mb-4">
                            <li>
                                <label for="cash"
                                    class="inline-flex items-center justify-between w-full p-5 text-secondary bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary peer-checked:text-primary hover:bg-gray-100">
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">Cash</div>
                                        <div class="w-full text-gray-500">Tunai</div>
                                    </div>
                                    <input type="radio" id="cash" name="payment_method" value="cash"
                                        x-model="paymentMethod" @change="showCashInput = true; showQris = false">
                                </label>
                                <!-- Cash Input Field -->
                                <div x-show="showCashInput" class="mt-3 px-5">
                                    <label class="block text-sm font-medium text-gray-700">Jumlah Uang</label>
                                    <input type="number" x-model="cashAmount"
                                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                                        placeholder="Masukkan jumlah uang">
                                    <div x-show="cashAmount && parseFloat(cashAmount) < total"
                                        class="text-red-500 text-sm mt-1">
                                        Jumlah uang kurang dari total belanja
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label for="qris"
                                    class="inline-flex items-center justify-between w-full p-5 text-secondary bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary peer-checked:text-primary hover:bg-gray-100">
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">Non-cash</div>
                                        <div class="w-full text-gray-500">Qris</div>
                                    </div>
                                    <input type="radio" id="qris" name="payment_method" value="qris"
                                        x-model="paymentMethod" @change="showCashInput = false; showQris = true">
                                </label>
                            </li>
                        </ul>

                        <!-- QRIS Image -->
                        <div x-show="showQris" class="mt-4 text-center">
                            <img src="{{ asset('storage/images/qris.jpg') }}" alt="QRIS Code"
                                class="mx-auto max-w-full h-auto p-2">
                            <p class="mt-2 text-sm text-gray-600">Scan QRIS code untuk melakukan pembayaran</p>
                        </div>

                        <button type="submit" x-show="selectedProducts.length > 0" :disabled="!isValid || !paymentMethod"
                            @click.prevent="
                                const params = new URLSearchParams({
                                    items: JSON.stringify(selectedProducts),
                                    total: total,
                                    payment_method: paymentMethod,
                                    cash_amount: paymentMethod === 'cash' ? cashAmount : total
                                });
                                window.location.href = '{{ route('transaction.pay') }}?' + params;
                            "
                            class="text-white inline-flex w-full justify-center mt-4 p-2 rounded-lg"
                            :class="isValid && paymentMethod ? 'bg-secondary hover:bg-primary' : 'bg-gray-400'">
                            <span
                                x-text="paymentMethod === 'cash' ? 'Lanjutkan Pembayaran' : 'Konfirmasi Pembayaran QRIS'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
