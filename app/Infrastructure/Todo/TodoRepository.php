<?php namespace App\Infrastructure\Todo;

use App\Domain\Shared\ValueObject\DateTimeValueObject;
use App\Domain\Todo\ValueObject\Id;
use App\Domain\Todo\ValueObject\Title;
use App\Domain\Todo\ValueObject\Content;
use App\Domain\Todo\ValueObject\Due;
use App\Domain\Todo\Aggregate\Todo;
use App\Domain\Todo\Exception\TodoNotFoundException;
use App\Domain\Todo\TodoRepository as TodoRepositoryInterface;
use App\Domain\Todo\ValueObject\Name;
use App\Infrastructure\Laravel\Model\TodoModel;

class TodoRepository implements TodoRepositoryInterface
{
    public function create(Todo $todo): void
    {
        $todoModel = new TodoModel();

        $todoModel->id = $todo->id()->value();
        $todoModel->title = $todo->title()->value();
        $todoModel->content = $todo->content()->value();
        $todoModel->due = $todo->due()->value();
        $todoModel->created_at = DateTimeValueObject::now()->value();

        $todoModel->save();
    }

    public function update(Todo $todo): void
    {
        $todoModel = TodoModel::find($todo->id()->value());

        $todoModel->title = $todo->title()->value();
        $todoModel->content = $todo->content()->value();
        $todoModel->due = $todo->due()->value();
        $todoModel->updated_at = DateTimeValueObject::now()->value();

        $todoModel->save();
    }

    /**
     * @throws TodoNotFoundException
     */
    public function findById(Id $userId): Todo
    {
        $todoModel = TodoModel::find($userId->value());

        if (empty($todoModel)) {
            throw new TodoNotFoundException('Todo does not exist');
         
        }
       
        return self::map($todoModel);
    }

    public function delete(Todo $todo): void
    {
        $todoModel = TodoModel::find($todo->id()->value());

        $todoModel->delete();
    }

    private static function map(TodoModel $model): Todo
    {
        return Todo::create(
            Id::fromPrimitives($model->id),
            Title::fromString($model->title),
            Content::fromString($model->content),
            Due::fromString($model->due),
            DateTimeValueObject::fromPrimitives($model->created_at),
            !empty($model->updated_at) ? DateTimeValueObject::fromPrimitives($model->updated_at) : null,
        );
    }

    public function all() 
    {
        return TodoModel::orderBy('created_at', 'desc')->get();;
    }
}
