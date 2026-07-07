@props([
    'id' => '',
])
<div id="{{ $id }}">

    <div class="flex gap-2 mb-4">
        <input type="text" placeholder="Search"
            class="table-search-input w-full max-w-xs rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500 dark:text-black ">
        <button type="button"
            class="table-search-button inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 px-3.5 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.3 5.3a7.5 7.5 0 0 0 10.5 10.5Z" />
            </svg>
            Search
        </button>
    </div>
    <div class="bg-white border border-zinc-200 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 text-sm">
            <thead class="bg-zinc-50">
                <tr class="table-header"></tr>
            </thead>
            <tbody class="table-body divide-y divide-zinc-100 "></tbody>
        </table>

        <div class="table-pagination"></div>
    </div>
</div>
