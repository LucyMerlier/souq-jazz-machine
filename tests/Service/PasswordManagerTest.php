<?php

namespace App\Tests\Service;

use App\Service\PasswordManager;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PasswordManagerTest extends KernelTestCase
{
    public function testClassExists(): void
    {
        $this->assertTrue(
            class_exists('App\Service\PasswordManager'),
            'The App\Service\PasswordManager class must exist'
        );
    }

    /**
     * @depends testClassExists
     */
    public function testGeneratePasswordMethodExists(): void
    {
        $this->assertTrue(
            method_exists('App\Service\PasswordManager', 'generateRandomPassword'),
            'The App\Service\PasswordManager::generateRandomPassword() method must exist'
        );
    }

    /**
     * @depends testGeneratePasswordMethodExists
     * @dataProvider negativeIntegersProvider
     */
    public function testNegativeLengthThrowsException(int $length): void
    {
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage(
            'Argument 1 of App\Service\PasswordManager::generateRandomPassword() must be a positive integer'
        );
        (new PasswordManager())->generateRandomPassword($length);
    }

    public function negativeIntegersProvider(): Generator
    {
        for ($i = 0; $i < 50; $i++) {
            yield [random_int(-50, 0)];
        }
    }

    /**
     * @depends testGeneratePasswordMethodExists
     * @dataProvider mixedValuesProvider
     * @param string|bool|array|float|null $mixedValue
     */
    public function testMixedValueThrowsTypeError($mixedValue): void
    {
        $this->expectError();
        $this->expectErrorMessage(
            'Argument 1 passed to App\Service\PasswordManager::generateRandomPassword() must be of the type int'
        );
        /** @phpstan-ignore-next-line */
        (new PasswordManager())->generateRandomPassword($mixedValue);
    }

    public function mixedValuesProvider(): Generator
    {
        yield ['a'];
        yield ['bibi'];
        yield [[]];
        yield [[0, 1]];
    }

    /**
     * @depends testGeneratePasswordMethodExists
     * @dataProvider positiveIntegersProvider
     */
    public function testValidInputPasswordLength(int $length): void
    {
        $this->assertEquals(
            $length,
            strlen((new PasswordManager())->generateRandomPassword($length)),
            'The length of the returned value of App\Service\PasswordManager::generateRandomPassword()
            must match the value provided in argument 1'
        );
    }

    /**
     * @depends testGeneratePasswordMethodExists
     * @dataProvider positiveIntegersProvider
     */
    public function testValidInputPasswordCharacters(int $length): void
    {
        $this->assertMatchesRegularExpression(
            '/^[a-zA-Z0-9]+$/',
            (new PasswordManager())->generateRandomPassword($length),
            'The returned value of App\Service\PasswordManager::generateRandomPassword()
            must only contain alphanumerical characters'
        );
    }

    public function positiveIntegersProvider(): Generator
    {
        for ($i = 0; $i < 50; $i++) {
            yield [random_int(1, 50)];
        }
    }

    public function testNullInputReturnsDefaultValue(): void
    {
        $this->assertEquals(
            6,
            strlen((new PasswordManager())->generateRandomPassword()),
            'The default returned value of App\Service\PasswordManager::generateRandomPassword()
            must be 6 characters long'
        );
    }
}
