<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Util;

final readonly class ReflectionUtil
{
    /**
     * @template T of object
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
                    $type = $prop->getType();
                    $typeName = null;
                    if ($type instanceof \ReflectionNamedType) {
                        $typeName = $type->getName();
                    }

                    if ($typeName && class_exists($typeName)) {
                        $value = self::mapArrayToClassRecursive($value, $typeName);
                    }
                }

                $prop->setValue($object, $value);
            }
        }

        return $object;
    }
}
