<?php

declare(strict_types=1);

namespace ZenginCode;

final class Branch
{
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly string $kana,
        public readonly string $hira,
        public readonly string $roma
    ) {
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
        ];
    }
}
