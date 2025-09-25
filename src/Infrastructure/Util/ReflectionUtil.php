<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Util;

final readonly class ReflectionUtil
{
    /**
     * @template T
     * @param array<mixed> $data
     * @param class-string<T> $className
     * @return T
     */
    public static function mapArrayToClassRecursive(array $data, string $className): object
    {
        $object = new $className();
        $refClass = new \ReflectionClass($object);

        foreach ($data as $key => $value) {
            if ($refClass->hasProperty($key)) {
                $prop = $refClass->getProperty($key);
                $prop->setAccessible(true);

                if (is_array($value)) {
                    $type = $prop->getType()?->getName();
                    if ($type && class_exists($type)) {
                        $value = self::mapArrayToClassRecursive($value, $type);
                    }
                }

                $prop->setValue($object, $value);
            }
        }

        return $object;
    }
}
