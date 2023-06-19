<?php

namespace App\Factory;

use App\Entity\ExpenseAccount;
use App\Enums\ExpenseType;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ExpenseAccount>
 *
 * @method        ExpenseAccount|Proxy create(array|callable $attributes = [])
 * @method static ExpenseAccount|Proxy createOne(array $attributes = [])
 * @method static ExpenseAccount|Proxy find(object|array|mixed $criteria)
 * @method static ExpenseAccount|Proxy findOrCreate(array $attributes)
 * @method static ExpenseAccount|Proxy first(string $sortedField = 'id')
 * @method static ExpenseAccount|Proxy last(string $sortedField = 'id')
 * @method static ExpenseAccount|Proxy random(array $attributes = [])
 * @method static ExpenseAccount|Proxy randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static ExpenseAccount[]|Proxy[] all()
 * @method static ExpenseAccount[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ExpenseAccount[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static ExpenseAccount[]|Proxy[] findBy(array $attributes)
 * @method static ExpenseAccount[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ExpenseAccount[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ExpenseAccountFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomFloat(),
            'createdAt' => self::faker()->dateTime(),
            'expenseDate' => self::faker()->dateTime(),
            'type' => self::faker()->randomElement(ExpenseType::cases()),
            'employee' => lazy(fn() => EmployeeFactory::randomOrCreate()),
            'company' => lazy(fn() => CompanyFactory::randomOrCreate()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ExpenseAccount $expenseAccount): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ExpenseAccount::class;
    }
}
