<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

abstract class Criteria
{
    private ?CriteriaPagination $pagination;
    private ?CriteriaSort $sort;

    protected function __construct(?CriteriaPagination $pagination = null, ?CriteriaSort $sort = null)
    {
        $this->pagination = $pagination;
        $this->sort = $sort;
    }

    public function sortBy(CriteriaSort $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    public function pagination(): ?CriteriaPagination
    {
        return $this->pagination;
    }

    public function sort(): ?CriteriaSort
    {
        return $this->sort;
    }
}
