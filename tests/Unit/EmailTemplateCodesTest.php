<?php

namespace Tests\Unit;

use App\Support\EmailTemplateCodes;
use PHPUnit\Framework\TestCase;

class EmailTemplateCodesTest extends TestCase
{
    public function test_normalize_slugifies_code_input(): void
    {
        $this->assertSame('customer_register', EmailTemplateCodes::normalize('Customer-Register'));
        $this->assertSame('order_to_user', EmailTemplateCodes::normalize('  ORDER TO USER  '));
    }

    public function test_registry_contains_new_register_codes(): void
    {
        $registry = EmailTemplateCodes::registry();

        $this->assertArrayHasKey(EmailTemplateCodes::NEW_REGISTER, $registry);
        $this->assertSame('new_register', EmailTemplateCodes::NEW_REGISTER);
        $this->assertArrayHasKey(EmailTemplateCodes::CUSTOMER_REGISTER_ADMIN, $registry);
        $this->assertNotEmpty($registry[EmailTemplateCodes::NEW_REGISTER]['placeholders']);
    }
}
