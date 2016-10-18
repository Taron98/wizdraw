<?php

use Illuminate\Database\Seeder;

/**
 * Class AbstractTableSeeder
 */
abstract class AbstractTableSeeder extends Seeder
{
    /**
     * @param string $modelName
     * @param string $prefix
     */
    public function createByConsts(string $modelName, string $prefix)
    {
        $modelReflection = new ReflectionClass($modelName);

        foreach ($modelReflection->getConstants() as $name => $value) {
            $constPrefix = strtoupper($prefix) . '_';

            /** @var ReflectionProperty $constant */
            if (stripos($name, $constPrefix) === 0) {
                call_user_func(
                    [
                        $modelName,
                        'create',
                    ],
                    [
                        $prefix => $value,
                    ]
                );
            }
        }
    }
}
