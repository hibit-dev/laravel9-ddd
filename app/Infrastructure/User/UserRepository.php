<?php namespace App\Infrastructure\User;

use App\Domain\Shared\ValueObject\DateTimeValueObject;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Id;
use App\Domain\User\Aggregate\User;
use App\Domain\User\UserSearchCriteria;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\UserRepository as UserRepositoryInterface;

use App\Domain\User\ValueObject\Name;
use App\Infrastructure\Laravel\Model\UserModel;

class UserRepository implements UserRepositoryInterface
{
    public function create(User $user): void
    {
        $userModel = new UserModel();

        $userModel->id = $user->id()->value();
        $userModel->email = $user->email()->value();
        $userModel->name = $user->name()->value();
        $userModel->created_at = DateTimeValueObject::now()->value();

        $userModel->save();
    }

    public function update(User $user): void
    {
        $userModel = UserModel::find($user->id()->value());

        $userModel->email = $user->email()->value();
        $userModel->name = $user->name()->value();
        $userModel->updated_at = DateTimeValueObject::now()->value();

        $userModel->save();
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(Id $userId): User
    {
        $userModel = UserModel::find($userId->value());

        if (empty($userModel)) {
            throw new UserNotFoundException('User does not exist');
        }

        return self::map($userModel);
    }

    public function searchById(Id $userId): ?User
    {
        $userModel = UserModel::find($userId->value());

        return ($userModel !== null) ? self::map($userModel) : null;
    }

    public function searchByCriteria(UserSearchCriteria $criteria): array
    {
        $userModel = new UserModel();

        if (!empty($criteria->email())) {
            $userModel = $userModel->where('email', 'LIKE', '%' . $criteria->email() . '%');
        }

        if (!empty($criteria->name())) {
            $userModel = $userModel->where('name', 'LIKE', '%' . $criteria->name() . '%');
        }

        if ($criteria->pagination() !== null) {
            $userModel = $userModel->take($criteria->pagination()->limit()->value())
                                   ->skip($criteria->pagination()->offset()->value());
        }

        if ($criteria->sort() !== null) {
            $userModel = $userModel->orderBy($criteria->sort()->field()->value(), $criteria->sort()->direction()->value());
        }

        return array_map(
            static fn (UserModel $user) => self::map($user),
            $userModel->get()->all()
        );
    }

    public function delete(User $user): void
    {
        $userModel = UserModel::find($user->id()->value());

        $userModel->delete();
    }

    private static function map(UserModel $model): User
    {
        return User::create(
            Id::fromPrimitives($model->id),
            Email::fromString($model->email),
            Name::fromString($model->name),
            DateTimeValueObject::fromPrimitives($model->created_at),
            !empty($model->updated_at) ? DateTimeValueObject::fromPrimitives($model->updated_at) : null,
        );
    }
}
