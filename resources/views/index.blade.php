@extends('layouts.app')

@section('content')
    <h1>Todos</h1>
    @if (count($todos) > 0)
        @foreach ($todos as $todo)
            <div class="card m-2">
                <span class="badge bg-danger ">{{ $todo->due}}</span>
                <h2 class="text-center"><a href="todo/{{ $todo->id }}">{{ $todo->title }}</a></h2>
              
            </div>
           
        @endforeach
    @endif

@endsection