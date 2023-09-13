<?php namespace App\Infrastructure\Todo;

use App\Domain\Shared\ValueObject\DateTimeValueObject;
use App\Domain\Todo\ValueObject\Email;
use App\Domain\Todo\ValueObject\Id;
use App\Domain\Todo\Aggregate\Todo;
use App\Domain\Todo\UserSearchCriteria;
use App\Domain\Todo\Exception\UserNotFoundException;
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
     * @throws UserNotFoundException
     */
    public function findById(Id $userId): Todo
    {
        $todoModel = TodoModel::find($userId->value());

        if (empty($todoModel)) {
            throw new UserNotFoundException('User does not exist');
        }

        return self::map($todoModel);
    }

    public function searchById(Id $userId): ?Todo
    {
        $todoModel = TodoModel::find($userId->value());

        return ($todoModel !== null) ? self::map($todoModel) : null;
    }

    public function searchByCriteria(UserSearchCriteria $criteria): array
    {
        $todoModel = new TodoModel();

        if (!empty($criteria->email())) {
            $todoModel = $todoModel->where('email', 'LIKE', '%' . $criteria->email() . '%');
        }

        if (!empty($criteria->name())) {
            $todoModel = $todoModel->where('name', 'LIKE', '%' . $criteria->name() . '%');
        }

        if ($criteria->pagination() !== null) {
            $todoModel = $todoModel->take($criteria->pagination()->limit()->value())
                                   ->skip($criteria->pagination()->offset()->value());
        }

        if ($criteria->sort() !== null) {
            $todoModel = $todoModel->orderBy($criteria->sort()->field()->value(), $criteria->sort()->direction()->value());
        }

        return array_map(
            static fn (TodoModel $user) => self::map($user),
            $todoModel->get()->all()
        );
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
}
