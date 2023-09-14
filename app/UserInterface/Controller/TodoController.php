<?php

namespace App\UserInterface\Controller;

use App\Domain\Shared\ValueObject\DateTimeValueObject;
use App\Domain\Todo\Aggregate\Todo;
use App\Domain\Todo\TodoRepository;
use App\Domain\Todo\ValueObject\Id;
use App\Domain\Todo\ValueObject\Title;
use App\Domain\Todo\ValueObject\Content;
use App\Domain\Todo\ValueObject\Due;
use App\Infrastructure\Laravel\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DB;

class TodoController extends Controller
{

    public function __invoke(Request $request, TodoRepository $todoRepository)
    {
        $todos = $todoRepository->all();

        return view('index')->with('todos', $todos);
    }

    public function show($id, TodoRepository $todoRepository)
    {
        $todoData = $todoRepository->findById(Id::fromPrimitives($id));
        $todo = $todoData->asArray();

        return view('show')->with('todo', $todo);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request,TodoRepository $todoRepository)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'content' => 'required',
                'due' => 'required'
            ]
        );

        $providedTitle = $request->input('title');
        $providedContent = $request->input('content');
        $providedDue = $request->input('due');

        $maxId = DB::table('todos')->max('id'); 
        $newId = $maxId + 1;

        $todo = Todo::create(
            Id::fromPrimitives($newId),
            Title::fromString($providedTitle),
            Content::fromString($providedContent),
            Due::fromString($providedDue),
            DateTimeValueObject::now()
        );

        $todoRepository->create($todo);

        return redirect('/')->with('success', 'Todo created successfuly!');
    }

    public function edit($id, TodoRepository $todoRepository)
    {
        $todoData = $todoRepository->findById(Id::fromPrimitives($id));
        $todo = $todoData->asArray();

        return view('edit')->with('todo', $todo);
    }
  
    public function update(Request $request,TodoRepository $todoRepository, $id)
    {
        $todo = $todoRepository->findById(Id::fromPrimitives($id));

        $providedTitle = $request->input('title');
        $providedContent = $request->input('content');
        $providedDue = $request->input('due');

        if (!empty($providedTitle)) {
            $todo->updateTitle($providedTitle);
        }

        if (!empty($providedContent)) {
            $todo->updateContent($providedContent);
        }

        if (!empty($providedDue)) {
            $todo->updateDue($providedDue);
        }

        $todoRepository->update($todo);
    
        return redirect('/')->with('success', 'Todo edited successfuly!');
    }

    public function destroy(TodoRepository $todoRepository,$id)
    {
        $todo = $todoRepository->findById(Id::fromPrimitives($id));
        $todoRepository->delete($todo);

        return redirect('/')->with('success', 'Todo deleted successfuly!');
    }
  
}
