@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="rounded-xl p-4 shadow  flex gap-4 bg-white mb-4 flex-wrap sm:flex-nowrap">
    <a href="{{ route('user.index') }}" class="flex w-full gap-8 lg:gap-16 bg-white px-4 py-2 lg:py-8 sm:flex-grow sm:justify-center hover:bg-secondary group rounded-xl transition duration-300">
        <div class="my-auto text-blue-500 rounded-xl group-hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" class="md:w-12 md:h-12 w-8 h-8" fill="currentColor"><path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path></svg>
        </div>
        <div class="my-auto">
            <h4 class="text-base text-gray-400 group-hover:text-white text-left">Total Kasir</h4>
            <h2 class="text-lg text-left group-hover:text-white">{{ $totalCashiers }}</h2>
        </div>
    </a>
    <div class="lg:border-x lg:px-4 sm:flex-grow w-full ">
        <a href="{{ route('category.index') }}" class="flex w-full gap-8 lg:gap-16 bg-white px-4 py-2 lg:py-8  sm:justify-center hover:bg-secondary group rounded-xl transition duration-300">
            <div class="my-auto group-hover:text-white text-amber-500 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="md:w-12 md:h-12 w-8 h-8"><path d="M22 7.999a1 1 0 0 0-.516-.874l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5A1 1 0 0 0 22 7.999zm-9.977 3.855L5.06 7.965l6.917-3.822 6.964 3.859-6.918 3.852z"></path><path d="M20.515 11.126 12 15.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"></path><path d="M20.515 15.126 12 19.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"></path></svg>
            </div>
            <div class="my-auto">
                <h4 class="text-base text-gray-400 group-hover:text-white text-left">Total Kategori</h4>
                <h2 class="text-lg text-left group-hover:text-white">{{ $totalCategories }}</h2>
            </div>
        </a>
    </div>

    <a href="{{ route('product.index') }}" class="flex w-full  gap-8 lg:gap-16 bg-white px-4 py-2 lg:py-8  sm:flex-grow sm:justify-center hover:bg-secondary group rounded-xl transition duration-300">
        <div class="my-auto group-hover:text-white  text-amber-500 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="md:w-12 md:h-12 w-8 h-8"><path d="M5 22h14c1.103 0 2-.897 2-2V9a1 1 0 0 0-1-1h-3V7c0-2.757-2.243-5-5-5S7 4.243 7 7v1H4a1 1 0 0 0-1 1v11c0 1.103.897 2 2 2zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v1H9V7zm-4 3h2v2h2v-2h6v2h2v-2h2l.002 10H5V10z"></path></svg>
        </div>
        <div class="my-auto">
            <h4 class="text-base text-gray-400 group-hover:text-white text-left">Total Produk</h4>
            <h2 class="text-lg text-left group-hover:text-white">{{ $totalProducts }}</h2>
        </div>
    </a>



</div>

<div class="flex gap-2 lg:gap-8 flex-wrap md:flex-nowrap mb-4 ">
    <div class="order-2 md:order-1 w-full">
        <form id="timeRangeForm" action="" method="GET" class="rounded-xl text-sm w-full">
            @csrf
            <ul class="flex gap-4">
                <li class="flex-grow">
                    <input type="radio" class="hidden peer" id="all_time" name="time_range" value="all_time"  {{ (empty(request('time_range')) || request('time_range') == 'all_time') && !request('start_date') && !request('end_date') ? 'checked' : '' }}>
                    <label for="all_time" class="peer-checked:border-secondary peer-checked:text-secondary flex gap-16 bg-white p-2 flex-grow justify-center border border-transparent hover:border-secondary hover:text-secondary group rounded-full transition duration-300 shadow">
                        <p class="my-auto hidden sm:block">
                            All Time
                        </p>
                        <p class="my-auto block sm:hidden">
                            AT
                        </p>
                    </label>
                </li>
                <li class="flex-grow">
                    <input type="radio" class="hidden peer" id="daily" name="time_range" value="daily" {{ $selectedTimeRange == 'daily' ? 'checked' : '' }}>
                    <label for="daily" class="peer-checked:border-secondary peer-checked:text-secondary flex gap-16 bg-white p-2 flex-grow justify-center border border-transparent hover:border-secondary hover:text-secondary group rounded-full transition duration-300 shadow">
                        <p class="my-auto hidden sm:block">
                            Harian
                        </p>
                        <p class="my-auto block sm:hidden">
                            1H
                        </p>
                    </label>
                </li>
                <li class="flex-grow">
                    <input type="radio" class="hidden peer" id="weekly" name="time_range" value="weekly" {{ $selectedTimeRange == 'weekly' ? 'checked' : '' }}>
                    <label for="weekly" class="peer-checked:border-secondary peer-checked:text-secondary flex flex-grow gap-16 bg-white p-2 justify-center border border-transparent hover:border-secondary hover:text-secondary group rounded-full transition duration-300 shadow">
                        <p class="my-auto hidden sm:block">
                            Mingguan
                        </p>
                        <p class="my-auto block sm:hidden">
                            1M
                        </p>
                    </label>
                </li>
                <li class="flex-grow">
                    <input type="radio" class="hidden peer" id="monthly" name="time_range" value="monthly" {{ $selectedTimeRange == 'monthly' ? 'checked' : '' }}>
                    <label for="monthly" class="peer-checked:border-secondary peer-checked:text-secondary flex gap-16 bg-white p-2 flex-grow justify-center border border-transparent hover:border-secondary hover:text-secondary group rounded-full transition duration-300 shadow">
                        <p class="my-auto hidden sm:block">
                            Bulanan
                        </p>
                        <p class="my-auto block sm:hidden">
                            1B
                        </p>
                    </label>
                </li>
            </ul>
        </form>
    </div>

    <div class="order-1 md:order-2 ">
        <form action="{{ route('dashboard.index') }}" method="GET" class="flex justify-between  py-[1px] sm:py-0 ps-[1px] rounded-lg text-white text-xs items-center w-full sm:w-auto relative ">
            <div id="date-range-picker" date-rangepicker class="flex items-center">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>
                    <input id="datepicker-range-start" name="start_date" type="text" value="{{ request('start_date', now()->format('m/d/Y')) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date start">
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>
                    <input id="datepicker-range-end" name="end_date" type="text" value="{{ request('end_date', now()->format('m/d/Y')) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full ps-10  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date end">
                </div>
            </div>
            <button type="submit" class=" p-2.5 text-sm font-medium h-full text-white bg-primary rounded-e-lg border border-primary hover:border-secondary hover:bg-secondary focus:ring-4 focus:outline-none focus:ring-blue-300">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
                <span class="sr-only">Search</span>
            </button>
        </form>
    </div>

</div>

    <div class="flex flex-wrap gap-2 md:gap-8 w-full mb-4">

        <div class="flex gap-4 bg-white rounded-lg shadow p-4 flex-grow">
            <div class="p-2 sm:p-4 bg-lime-500 text-white rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="sm:w-6 sm:h-6 w-4 h-4" fill="currentColor"><path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/></svg>
            </div>
            <div class="my-auto">
                <h4 class="text-xs sm:text-sm text-gray-400 ">Total Transaksi</h4>
                <h2 class="text-sm sm:text-base">{{ number_format($totalTransactions) }}</h2>
            </div>
        </div>

        <div class="flex gap-4 bg-white rounded-lg shadow p-4 flex-grow">
            <div class="p-2 sm:p-4 bg-lime-500 text-white rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="sm:w-6 sm:h-6 w-4 h-4 my-auto" fill="currentColor"><path d="M96 96l0 224c0 35.3 28.7 64 64 64l416 0c35.3 0 64-28.7 64-64l0-224c0-35.3-28.7-64-64-64L160 32c-35.3 0-64 28.7-64 64zm64 160c35.3 0 64 28.7 64 64l-64 0 0-64zM224 96c0 35.3-28.7 64-64 64l0-64 64 0zM576 256l0 64-64 0c0-35.3 28.7-64 64-64zM512 96l64 0 0 64c-35.3 0-64-28.7-64-64zM288 208a80 80 0 1 1 160 0 80 80 0 1 1 -160 0zM48 120c0-13.3-10.7-24-24-24S0 106.7 0 120L0 360c0 66.3 53.7 120 120 120l400 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-400 0c-39.8 0-72-32.2-72-72l0-240z"/></svg>
            </div>
            <div class="my-auto">
                <h4 class="text-xs sm:text-sm text-gray-400">Total Pendapatan</h4>
                <h2 class="text-sm sm:text-base">Rp. {{ number_format($totalRevenue) }}</h2>
            </div>
        </div>

        <div class="flex gap-4 bg-white rounded-lg shadow p-4 flex-grow">
            <div class="p-2 sm:p-4 bg-lime-500 text-white rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="sm:w-6 sm:h-6 w-4 h-4" fill="currentColor"><path d="M160 0c17.7 0 32 14.3 32 32l0 35.7c1.6 .2 3.1 .4 4.7 .7c.4 .1 .7 .1 1.1 .2l48 8.8c17.4 3.2 28.9 19.9 25.7 37.2s-19.9 28.9-37.2 25.7l-47.5-8.7c-31.3-4.6-58.9-1.5-78.3 6.2s-27.2 18.3-29 28.1c-2 10.7-.5 16.7 1.2 20.4c1.8 3.9 5.5 8.3 12.8 13.2c16.3 10.7 41.3 17.7 73.7 26.3l2.9 .8c28.6 7.6 63.6 16.8 89.6 33.8c14.2 9.3 27.6 21.9 35.9 39.5c8.5 17.9 10.3 37.9 6.4 59.2c-6.9 38-33.1 63.4-65.6 76.7c-13.7 5.6-28.6 9.2-44.4 11l0 33.4c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-34.9c-.4-.1-.9-.1-1.3-.2l-.2 0s0 0 0 0c-24.4-3.8-64.5-14.3-91.5-26.3c-16.1-7.2-23.4-26.1-16.2-42.2s26.1-23.4 42.2-16.2c20.9 9.3 55.3 18.5 75.2 21.6c31.9 4.7 58.2 2 76-5.3c16.9-6.9 24.6-16.9 26.8-28.9c1.9-10.6 .4-16.7-1.3-20.4c-1.9-4-5.6-8.4-13-13.3c-16.4-10.7-41.5-17.7-74-26.3l-2.8-.7s0 0 0 0C119.4 279.3 84.4 270 58.4 253c-14.2-9.3-27.5-22-35.8-39.6c-8.4-17.9-10.1-37.9-6.1-59.2C23.7 116 52.3 91.2 84.8 78.3c13.3-5.3 27.9-8.9 43.2-11L128 32c0-17.7 14.3-32 32-32z"/></svg>
            </div>
            <div class="my-auto">
                <h4 class="text-xs sm:text-sm text-gray-400">Rata-rata Pendapatan Per Transaksi</h4>
                <h2 class="text-sm sm:text-base">Rp. {{ number_format($averageRevenuePerTransaction) }}</h2>
            </div>
        </div>
    </div>
    <div>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                    Top 10 Produk Terlaris
                    @if($selectedTimeRange != 'all_time')
                        <span class="text-sm text-gray-500"> ({{ ucfirst($selectedTimeRange) }})</span>
                    @endif
                </caption>
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No.
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Produk
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Terjual
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Pendapatan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $index => $product)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $product->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $product->category->name ?? 'Tidak Berkategori' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($product->total_sold) }} pcs
                        </td>
                        <td class="px-6 py-4">
                            Rp. {{ number_format($product->total_revenue) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4">Tidak ada data produk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeRangeForm = document.getElementById('timeRangeForm');
        const timeRangeInputs = timeRangeForm.querySelectorAll('input[name="time_range"]');

        timeRangeInputs.forEach(input => {
            input.addEventListener('change', function() {
                timeRangeForm.submit();
            });
        });

        document.querySelector('input[name="start_date"]').addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.querySelector('input[name="end_date"]');

            // Set minimum end date
            endDateInput.min = startDate;

            // Reset end date jika tidak valid
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = '';
            }
        });
    });
    </script>
@endsection
