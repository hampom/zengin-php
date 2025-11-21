<?php

declare(strict_types=1);

namespace ZenginCode;

final class ZenginCode
{
    /** @var array<string, Bank>|null */
    private static ?array $banks = null;

    /** @var string|null */
    private static ?string $dataPath = null;

    public static function setDataPath(string $path): void
    {
        self::$dataPath = $path;
        self::clearCache();
    }

    /**
     * @return array<string, Bank>
     */
    public static function all(): array
    {
        return self::loadBanks();
    }

    public static function find(string $code): ?Bank
    {
        $banks = self::loadBanks();
        return $banks[$code] ?? null;
    }

    /**
     * @return array<Bank>
     */
    public static function search(string $query): array
    {
        $banks = self::loadBanks();
        $results = [];

        foreach ($banks as $bank) {
            if (
                str_contains(mb_strtolower($bank->name), $query) ||
                str_contains(mb_strtolower($bank->kana), $query) ||
                str_contains(mb_strtolower($bank->hira), $query) ||
                str_contains(mb_strtolower($bank->roma), $query)
            ) {
                $results[] = $bank;
            }
        }

        return $results;
    }

    public static function findBranch(string $bankCode, string $branchCode): ?Branch
    {
        $bank = self::find($bankCode);
        return $bank?->findBranch($branchCode);
    }

    /**
     * @return array<string, Bank>
     */
    private static function loadBanks(): array
    {
        if (self::$banks !== null) {
            return self::$banks;
        }

        $dataFile = self::$dataPath ?? __DIR__ . '/Data/banks.php';
        
        if (!file_exists($dataFile)) {
            throw new \RuntimeException("Bank data file not found: {$dataFile}");
        }

        $data = require $dataFile;

        if (!is_array($data)) {
            throw new \RuntimeException("Invalid bank data format");
        }

        self::$banks = [];

        foreach ($data as $bankData) {
            $code = $bankData['code'];
            $branches = [];
            
            if (isset($bankData['branches']) && is_array($bankData['branches'])) {
                foreach ($bankData['branches'] as $branchData) {
                    $branchCode = $branchData['code'];
                    $branches[$branchCode] = new Branch(
                        code: $branchCode,
                        name: $branchData['name'] ?? '',
                        kana: $branchData['kana'] ?? '',
                        hira: $branchData['hira'] ?? '',
                        roma: $branchData['roma'] ?? ''
                    );
                }
            }

            self::$banks[$code] = new Bank(
                code: $code,
                name: $bankData['name'] ?? '',
                kana: $bankData['kana'] ?? '',
                hira: $bankData['hira'] ?? '',
                roma: $bankData['roma'] ?? '',
                branches: $branches
            );
        }

        return self::$banks;
    }

    public static function clearCache(): void
    {
        self::$banks = null;
    }
}
