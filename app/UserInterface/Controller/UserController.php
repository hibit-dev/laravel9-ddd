<?php

namespace App\UserInterface\Controller;

use App\Domain\Shared\ValueObject\DateTimeValueObject;

use App\Domain\User\Aggregate\User;
use App\Domain\User\UserRepository;
use App\Domain\User\UserSearchCriteria;
use App\Domain\User\ValueObject\Id;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Name;

use App\Infrastructure\Laravel\Controller;

use Hibit\CriteriaSort;
use Hibit\CriteriaSortDirection;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        $offset = $request->query('offset');
        $email = $request->query('email');
        $name = $request->query('name');

        $criteria = UserSearchCriteria::create(
            !empty($offset) && !is_array($offset) ? (int) $offset : null,
            !empty($email) && !is_array($email) ? $email : null,
            !empty($name) && !is_array($name) ? $name : null,
        );

        $criteria->sortBy(CriteriaSort::create('name', CriteriaSortDirection::ASC));

        $users = $userRepository->searchByCriteria($criteria);

        return response()->json([
            'users' => array_map(fn (User $user) => $user->asArray(), $users)
        ]);
    }

    public function store(UserRepository $userRepository): JsonResponse
    {
        $users = [];

        for ($i = 1; $i <= 3; $i++) {
            $user = User::create(
                Id::random(),
                Email::fromString(sprintf('email_%d', $i)),
                Name::fromString(sprintf('name_%d', $i)),
                DateTimeValueObject::now()
            );

            $userRepository->create($user);

            $users[] = $user->asArray();
        }

        return response()->json([
            'users' => $users
        ], JsonResponse::HTTP_CREATED);
    }

    public function show(UserRepository $userRepository, string $id): JsonResponse
    {
        $user = $userRepository->findById(Id::fromPrimitives($id));

        return response()->json([
            'user' => $user->asArray()
        ]);
    }

    public function update(Request $request, UserRepository $userRepository, string $id): JsonResponse
    {
        $user = $userRepository->findById(Id::fromPrimitives($id));

        $providedEmail = $request->input('email');
        $providedName = $request->input('name');

        if (!empty($providedEmail)) {
            $user->updateEmail($providedEmail);
        }

        if (!empty($providedName)) {
            $user->updateName($providedName);
        }

        $userRepository->update($user);

        return response()->json([
            'user' => $user->asArray()
        ]);
    }

    public function destroy(UserRepository $userRepository, string $id): JsonResponse
    {
        $user = $userRepository->findById(Id::fromPrimitives($id));

        $userRepository->delete($user);

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
