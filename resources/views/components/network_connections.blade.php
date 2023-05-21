<div class="row justify-content-center mt-5">
    <div class="col-12">
        <div class="card shadow  text-white bg-dark">
            <div class="card-header">Coding Challenge - Network connections</div>
            <div class="card-body">
                <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                        {{ $type == 'suggestions' ? 'checked' : '' }}>
                    <a href="{{ route('home', ['type' => 'suggestions']) }}" class="btn btn-outline-primary"
                        for="btnradio1" id="get_suggestions_btn">Suggestions ({{ $suggestions->total() }})</a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                        {{ $type == 'sent' ? 'checked' : '' }}>
                    <a href="{{ route('home', ['type' => 'sent']) }}" class="btn btn-outline-primary" for="btnradio2"
                        id="get_sent_requests_btn">Sent Requests ({{ $sendRequests->total() }})</a>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3"
                        autocomplete="off"{{ $type == 'received' ? 'checked' : '' }}>
                    <a href="{{ route('home', ['type' => 'received']) }}" class="btn btn-outline-primary"
                        for="btnradio3" id="get_received_requests_btn">Received
                        Requests({{ $receivedRequests->total() }})</a>
                        
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off"
                        {{ $type == 'connections' ? 'checked' : '' }}>
                    <a href="{{ route('home', ['type' => 'connections']) }}" class="btn btn-outline-primary"
                        for="btnradio4" id="get_connections_btn">Connections ({{ $connections->total() }})</a>
                </div>
                <hr>
                <div id="content" class="d-none">
                    {{-- Display data here --}}
                </div>
                @if ($type == 'sent')
                    <x-request :mode="'sent'" :sendRequests="$sendRequests" :receivedRequests="$receivedRequests" />
                    <div id="skeleton_sent" class="d-none">
                        @for ($i = 0; $i < 10; $i++)
                            <x-skeleton />
                        @endfor
                    </div>
                    <div class="d-flex justify-content-center mt-2 py-3 {{ $sendRequests->lastPage() == $sendRequests->currentPage() ? 'd-none' : '' }}"
                        id="load_more_btn_parent_sent">
                        <button class="btn btn-primary" onclick="loadMore('{{ $type }}');"
                            id="load_more_btn_sent">Load more
                        </button>
                    </div>
                @endif
                @if ($type == 'received')
                    <x-request :mode="'received'" :sendRequests="$sendRequests" :receivedRequests="$receivedRequests" />
                    <div id="skeleton_received" class="d-none">
                        @for ($i = 0; $i < 10; $i++)
                            <x-skeleton />
                        @endfor
                    </div>
                    <div class="d-flex justify-content-center mt-2 py-3 {{ $receivedRequests->lastPage() == $receivedRequests->currentPage() ? 'd-none' : '' }}"
                        id="load_more_btn_parent_received">
                        <button class="btn btn-primary" onclick="loadMore('{{ $type }}');"
                            id="load_more_btn_received">Load more</button>
                    </div>
                @endif
                @if ($type == 'suggestions')
                    <x-suggestion :suggestions="$suggestions" />
                    <div id="skeleton_suggestions" class="d-none">
                        @for ($i = 0; $i < 10; $i++)
                            <x-skeleton />
                        @endfor
                    </div>
                    <div class="d-flex justify-content-center mt-2 py-3 {{ $suggestions->lastPage() == $suggestions->currentPage() ? 'd-none' : '' }}"}}"
                        id="load_more_btn_parent_suggestions">
                        <button class="btn btn-primary" onclick="loadMore('{{ $type }}');"
                            id="load_more_btn_suggestions">Load more</button>
                    </div>
                @endif
                @if ($type == 'connections')
                    <x-connection :connections="$connections" />
                    <div id="skeleton_connections" class="d-none">
                        @for ($i = 0; $i < 10; $i++)
                            <x-skeleton />
                        @endfor
                    </div>
                    <div class="d-flex justify-content-center mt-2 py-3 {{ $connections->lastPage() == $connections->currentPage() ? 'd-none' : '' }}"
                        id="load_more_btn_parent_connections">
                        <button class="btn btn-primary"
                            onclick="loadMore('{{ $type }}',{{ auth()->user()->id }});"
                            id="load_more_btn_connections">Load more</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
