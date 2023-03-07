<!-- resources/views/gmail.blade.php -->

<h1>My Gmail Inbox</h1>

<ul>
    @foreach ($messages as $message)
        <li>{{ $message->id }}</li>
    @endforeach
</ul>
