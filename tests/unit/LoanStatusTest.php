<?php

use CodeIgniter\Test\CIUnitTestCase;

final class LoanStatusTest extends CIUnitTestCase
{
    public function testNormalizeLoanStatusHandlesEmptyAndVariantValues(): void
    {
        $this->assertSame('pending', normalizeLoanStatusValue(''));
        $this->assertSame('pending', normalizeLoanStatusValue('   '));
        $this->assertSame('pending', normalizeLoanStatusValue('menunggu acc'));
        $this->assertSame('aktif', normalizeLoanStatusValue('Approved'));
        $this->assertSame('aktif', normalizeLoanStatusValue('verified'));
        $this->assertSame('ditolak', normalizeLoanStatusValue('ditolak admin'));
        $this->assertSame('lunas', normalizeLoanStatusValue('selesai'));
    }
}
