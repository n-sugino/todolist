<?php

namespace App\TodoInterface\Controller;

use App\Domain\Shared\Model\CriteriaField;
use App\Domain\Shared\Model\CriteriaSort;
use App\Domain\Shared\Model\CriteriaSortDirection;
use App\Domain\Shared\ValueObject\DateTimeValueObject;

use App\Domain\Todo\Aggregate\Todo;
use App\Domain\Todo\TodoRepository;
use App\Domain\Todo\TodoSearchCriteria;
use App\Domain\Todo\ValueObject\Id;
use App\Domain\Todo\ValueObject\Title;
use App\Domain\Todo\ValueObject\Content;
use App\Domain\Todo\ValueObject\Due;

use App\Infrastructure\Laravel\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TodoController extends Controller
{
    public function index(Request $request, TodoRepository $todoRepository): JsonResponse
    {
        $offset = $request->query('offset');
        $email = $request->query('email');
        $name = $request->query('name');

        $criteria = TodoSearchCriteria::create(
            !empty($offset) && !is_array($offset) ? (int) $offset : null,
            !empty($email) && !is_array($email) ? $email : null,
            !empty($name) && !is_array($name) ? $name : null,
        );

        $criteria->sortBy(new CriteriaSort(CriteriaField::fromString('name'), CriteriaSortDirection::ASC));

        $todos = $todoRepository->searchByCriteria($criteria);
        
        return response()->json([
            'todos' => array_map(fn (Todo $todo) => $todo->asArray(), $todos)
        ]);
    }

    public function store(TodoRepository $todoRepository): JsonResponse
    {
        $todos = [];

        for ($i = 1; $i <= 3; $i++) {
            $todo = Todo::create(
                Id::random(),
                Email::fromString(sprintf('email_%d', $i)),
                Name::fromString(sprintf('name_%d', $i)),
                DateTimeValueObject::now()
            );

            $todoRepository->create($todo);

            $todos[] = $todo->asArray();
        }

        return response()->json([
            'todos' => $todos
        ], JsonResponse::HTTP_CREATED);
    }

    public function show(TodoRepository $todoRepository, string $id): JsonResponse
    {
        $todo = $todoRepository->findById(Id::fromPrimitives($id));

        return response()->json([
            'todo' => $todo->asArray()
        ]);
    }

    public function update(Request $request, TodoRepository $todoRepository, string $id): JsonResponse
    {
        $todo = $todoRepository->findById(Id::fromPrimitives($id));

        $providedEmail = $request->input('email');
        $providedName = $request->input('name');

        if (!empty($providedEmail)) {
            $todo->updateEmail($providedEmail);
        }

        if (!empty($providedName)) {
            $todo->updateName($providedName);
        }

        $todoRepository->update($todo);

        return response()->json([
            'todo' => $todo->asArray()
        ]);
    }

    public function destroy(TodoRepository $todoRepository, string $id): JsonResponse
    {
        $todo = $todoRepository->findById(Id::fromPrimitives($id));

        $todoRepository->delete($todo);

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
