{{--
    Empty pagination shell - filled in by createRemoteTable() from
    public/js/remote-table.js based on the Laravel paginator meta
    (current_page, last_page, total, etc.) in the API response.

    Usage: <x-table-pagination id="ports" />
    Produces: [data-table-pagination="ports"]
--}}
@props(['id'])

<div data-table-pagination="{{ $id }}"></div>
