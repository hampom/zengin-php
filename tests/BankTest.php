<?php

declare(strict_types=1);

namespace ZenginCode\Tests;

use PHPUnit\Framework\TestCase;
use ZenginCode\ZenginCode;
use ZenginCode\Bank;
use ZenginCode\Branch;

class BankTest extends TestCase
{
    protected function setUp(): void
    {
        ZenginCode::clearCache();
    }

    public function testFindBankByCode(): void
    {
        $bank = ZenginCode::find('0001');
        
        $this->assertInstanceOf(Bank::class, $bank);
        $this->assertSame('0001', $bank->code);
        $this->assertNotEmpty($bank->name);
    }

    public function testFindNonExistentBank(): void
    {
        $bank = ZenginCode::find('9999');
        
        $this->assertNull($bank);
    }

    public function testSearchBanksByName(): void
    {
        $results = ZenginCode::search('三菱');
        
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        
        foreach ($results as $bank) {
            $this->assertInstanceOf(Bank::class, $bank);
        }
    }

    public function testFindBranch(): void
    {
        $branch = ZenginCode::findBranch('0001', '001');
        
        if ($branch !== null) {
            $this->assertInstanceOf(Branch::class, $branch);
            $this->assertSame('001', $branch->code);
        } else {
            $this->markTestSkipped('Branch data not available');
        }
    }

    public function testBankToArray(): void
    {
        $bank = ZenginCode::find('0001');
        
        if ($bank === null) {
            $this->markTestSkipped('Bank data not available');
        }
        
        $array = $bank->toArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('kana', $array);
        $this->assertArrayHasKey('hira', $array);
        $this->assertArrayHasKey('roma', $array);
        $this->assertArrayHasKey('branches', $array);
    }

    public function testSearchBranches(): void
    {
        $bank = ZenginCode::find('0001');
        
        if ($bank === null) {
            $this->markTestSkipped('Bank data not available');
        }
        
        $branches = $bank->searchBranches('本店');
        
        $this->assertIsArray($branches);
    }
}
