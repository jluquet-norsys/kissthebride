<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\ExpenseAccount;
use App\Factory\CompanyFactory;
use App\Factory\EmployeeFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ExpensesAccountTest extends ApiTestCase
{

    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;

    private function getIriEmployee (): string
    {
        EmployeeFactory::createOne(['name' => 'Dupond', 'email' => 'test@test.fr', 'firstName' => 'Julie']);
        return $this->findIriBy(Employee::class, ['email' => 'test@test.fr']);
    }

    private function getIriCompany (): string
    {
        CompanyFactory::createOne(['name' => 'Norsys']);
        return $this->findIriBy(Company::class, ['name' => 'Norsys']);
    }

    public function testCreateExpense(): void
    {
        $iriEmployee = $this->getIriEmployee();
        $iriCompany = $this->getIriCompany();

        $response = static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'expenseDate' => '2023-06-19',
            'amount' => 62.30,
            'employee' => $iriEmployee,
            'company' => $iriCompany,
            'type' => 'Fuel'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@type' => 'ExpenseAccount',
            'expenseDate' => '2023-06-19T00:00:00+00:00',
            'employee' => $iriEmployee,
            'company' => $iriCompany,
            'amount' => 62.3,
            'type' => 'Fuel',
        ]);
        $this->assertMatchesRegularExpression('~^/expense_accounts/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(ExpenseAccount::class);
    }

    public function testCreateExpenseBadType(): void
    {
        $iriEmployee = $this->getIriEmployee();
        $iriCompany = $this->getIriCompany();

        static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'type' => 'Sport',
            'employee' => $iriEmployee,
            'company' => $iriCompany,
            'amount' => 30,
            'expenseDate' => '2023-06-19'
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'The data must belong to a backed enumeration of type App\\Enums\\ExpenseType',
        ]);
    }

    public function testCreateExpenseAmountShouldBePositive(): void
    {
        $iriEmployee = $this->getIriEmployee();
        $iriCompany = $this->getIriCompany();

        static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'type' => 'Meal',
            'amount' => -10,
            'employee' => $iriEmployee,
            'company' => $iriCompany,
            'expenseDate' => '2023-06-19'
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'amount: This value should be positive.',
        ]);
    }

    public function testCreateExpenseWithoutDate(): void
    {
        $iriEmployee = $this->getIriEmployee();
        $iriCompany = $this->getIriCompany();

        static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'type' => 'Conference',
            'employee' => $iriEmployee,
            'company' => $iriCompany,
            'amount' => 60
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'expenseDate: This value should not be blank.',
        ]);
    }

    public function testCreateExpenseWithoutEmployee(): void
    {
        $iriCompany = $this->getIriCompany();

        static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'type' => 'Fuel',
            'company' => $iriCompany,
            'expenseDate' => '2023-06-19',
            'amount' => 60
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'employee: This value should not be blank.',
        ]);
    }

    public function testCreateExpenseBadEmployee(): void
    {
        $iriCompany = $this->getIriCompany();

        static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'type' => 'Fuel',
            'amount' => 60,
            'expenseDate' => '2023-06-19',
            'employee' => '/employees/693',
            'company' => $iriCompany
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'Item not found for "/employees/693".',
        ]);
    }

    public function testCreateExpenseWithoutCompany(): void
    {
        $iriEmployee = $this->getIriEmployee();

        static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'type' => 'Toll',
            'employee' => $iriEmployee,
            'expenseDate' => '2023-06-19',
            'amount' => 60
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'company: This value should not be blank.',
        ]);
    }

    public function testCreateExpenseBadCompany(): void
    {
        $iriEmployee = $this->getIriEmployee();

        static::createClient()->request('POST', '/expense_accounts', ['json' => [
            'type' => 'Meal',
            'amount' => 60,
            'expenseDate' => '2023-06-19',
            'company' => '/companies/402',
            'employee' => $iriEmployee
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'Item not found for "/companies/402".',
        ]);
    }


}
