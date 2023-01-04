<?php

namespace App\Infrastructure\Laravel;

use App\Domain\Shared\Bus\Command;
use App\Domain\Shared\Bus\Query;
use App\Domain\Shared\Exception\DomainException;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private Dispatcher $bus;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->bus = $dispatcher;
    }

    /**
     * @throws DomainException
     */
    protected function dispatch(Command $command): void
    {
        $this->bus->dispatch($command);
    }

    /**
     * @throws DomainException
     */
    protected function ask(Query $query)
    {
        $response = $this->bus->dispatch($query);

        return $response[0] ?? null;
    }
}
