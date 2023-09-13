@extends('layouts.app')

@section('content')
    
    <a href="/" class="btn btn-secondary mt-2">Go Back</a>
    <br> <br>
    <div class="badge bg-danger">{{ $todo['due'] }}</div>
    <h1>{{ $todo['title'] }}</h1>
    <hr>
    <p>{{ $todo['content'] }}</p>

    <br> <br>

    <div class="d-flex">
        <form class="me-3" action="/todo/{{ $todo['id'] }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mt-2">Delete</button>
        </form>
        <a href="/todo/{{ $todo['id'] }}/edit" class="btn btn-primary mt-2 ">Edit</a>
    </div>
   


@endsection
