<?php

declare(strict_types=1);

namespace ZenginCode;

class Bank
{
    /**
     * @param array<string, Branch> $branches
     */
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly string $kana,
        public readonly string $hira,
        public readonly string $roma,
        public readonly array $branches = []
    ) {
    }

    public function findBranch(string $code): ?Branch
    {
        return $this->branches[$code] ?? null;
    }

    /**
     * @return array<Branch>
     */
    public function searchBranches(string $query): array
    {
        $results = [];

        foreach ($this->branches as $branch) {
            if (
                str_contains(mb_strtolower($branch->name), $query) ||
                str_contains(mb_strtolower($branch->kana), $query) ||
                str_contains(mb_strtolower($branch->hira), $query) ||
                str_contains(mb_strtolower($branch->roma), $query)
            ) {
                $results[] = $branch;
            }
        }

        return $results;
    }

    /**
     * @return array<Branch>
     */
    public function getAllBranches(): array
    {
        return array_values($this->branches);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'kana' => $this->kana,
            'hira' => $this->hira,
            'roma' => $this->roma,
            'branches' => array_map(
                fn(Branch $branch) => $branch->toArray(),
                $this->branches
            ),
        ];
    }
}
