<div class="my-2 shadow text-white bg-dark p-1" id="connections">
    @foreach ($connections as $connection)
        @if (auth()->user()->id != $connection->sender[0]->id)
            <div class="d-flex justify-content-between mt-1">
                <table class="ms-1">
                    <td class="align-middle">{{ $connection->sender[0]->name }}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">{{ $connection->sender[0]->email }}</td>
                    <td class="align-middle">
                </table>
                <div>
                    <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse_{{ $connection->id }}" aria-expanded="false"
                        aria-controls="collapseExample">
                        Connections in common ({{ $connection->commonConnections->total() }})
                    </button>
                    <button id="create_request_btn_" onclick="removeConnection('{{ $connection->id }}','connection')"
                        class="btn btn-danger me-1">Remove Connection</button>
                </div>
            </div>
            <div class="collapse" id="collapse_{{ $connection->id }}">
                <div id="content_{{ $connection->id }}" class="p-2">
                    <x-connection_in_common :connection="$connection" />
                </div>
                <div id="connections_in_common_skeletons_{{ $connection->id }}" class="d-none">
                    @for ($i = 0; $i < 10; $i++)
                        <x-skeleton />
                    @endfor
                </div>
                <div class="d-flex justify-content-center mt-1 w-100 py-2 {{ $connection->commonConnections->lastPage() == $connection->commonConnections->currentPage() ? 'd-none' : '' }}"
                    id="load_more_{{ $connection->id }}">
                    <button class="btn btn-sm btn-primary" id="load_more_connections_in_common_"
                        onclick="loadMoreCommon('{{ $connection->sender[0]->id }}','{{ $connection->id }}');">Load
                        more</button>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-between mt-1">
                <table class="ms-1">
                    <td class="align-middle">{{ $connection->receiver[0]->name }}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">{{ $connection->receiver[0]->email }}</td>
                    <td class="align-middle">
                </table>
                <div>
                    <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse_{{ $connection->id }}"
                        aria-expanded="false" aria-controls="collapseExample">
                        Connections in common ({{ $connection->commonConnections->total() }})
                    </button>
                    <button id="create_request_btn_" onclick="removeConnection('{{ $connection->id }}','connection')"
                        class="btn btn-danger me-1">Remove Connection</button>
                </div>
            </div>
            <div class="collapse" id="collapse_{{ $connection->id }}">
                <div id="content_{{ $connection->id }}" class="p-2">
                    <x-connection_in_common :connection="$connection" />
                </div>
                <div id="connections_in_common_skeletons_{{ $connection->id }}" class="d-none">
                    @for ($i = 0; $i < 10; $i++)
                        <x-skeleton />
                    @endfor
                </div>
                <div class="d-flex justify-content-center mt-1 w-100 py-2 {{ $connection->commonConnections->lastPage() == $connection->commonConnections->currentPage() ? 'd-none' : '' }}"
                    id="load_more_{{ $connection->id }}">
                    <button class="btn btn-sm btn-primary" id="load_more_connections_in_common_"
                        onclick="loadMoreCommon('{{ $connection->receiver[0]->id }}','{{ $connection->id }}');">Load
                        more</button>
                </div>
            </div>
        @endif
    @endforeach
</div>
