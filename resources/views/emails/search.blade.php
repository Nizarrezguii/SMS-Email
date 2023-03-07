@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Results</h1>

    @if($paginator->total() > 0)
        <p>Found {{ $paginator->total() }} results for "{{ $keyword }}"</p>
        <ul class="list-group">
            @foreach ($paginator as $message)
                <li class="list-group-item">
                    <h5 class="mb-1">{{ $message->subject }}</h5>
                    <p class="mb-1">From: {{ $message->from }}<br>To: {{ $message->to }}<br>Date: {{ $message->date }}</p>
                    <p class="mb-1">{{ $message->body }}</p>
                </li>
            @endforeach
        </ul>
        {{ $paginator->appends(['q' => $keyword])->links() }}
    @endif
    @if (!empty($keyword))
        <p>Search results for keyword: {{ $keyword }}</p>
    @endif
</div>
@endsection
