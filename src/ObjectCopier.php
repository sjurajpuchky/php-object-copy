<?php


namespace BABA\Utils;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use ReflectionException;

/**
 * Class ObjectCopier
 * @package BABA\Utils
 * @author Juraj Puchký - BABA Tumise s.r.o. <info@baba.bj>
 */
class ObjectCopier
{

    /**
     * @param $src object Source object.
     * @param $dst object Destination object.
     * @param array $exclude Excluded fields, which are not will be copied.
     * @throws ReflectionException
     */
    public static function copyProperties($src, $dst, $exclude = [])
    {
        $srcReflector = new ReflectionClass($src);
        $dstReflector = new ReflectionClass($dst);
        /** @var ReflectionProperty $srcProperty */
        foreach ($srcReflector->getProperties() as $srcProperty) {
            $name = $srcProperty->getName();
            $value = null;
            if ($srcProperty->isPublic() && !$srcProperty->isStatic()) {
                /** @var mixed $value */
                $value = $srcProperty->getValue($src);
            } elseif (!$srcProperty->isStatic() && ($srcProperty->isPrivate() || $srcProperty->isProtected())) {
                /** @var ReflectionMethod $getter */
                $getter = $srcReflector->getMethod('get' . ucfirst($name));
                $value = $getter->invoke($src);
            }
            if (!in_array($name, $exclude)) {
                $dstProperty = $dstReflector->getProperty($name);
                if ($dstProperty->isPublic() && !$dstProperty->isStatic()) {
                    $dstProperty->setValue($dst, $value);
                } elseif (!$srcProperty->isStatic() && ($srcProperty->isPrivate() || $srcProperty->isProtected())) {
                    /** @var ReflectionMethod $getter */
                    $setter = $srcReflector->getMethod('set' . ucfirst($name));
                    $setter->invoke($dst, $value);
                }
            }
        }
    }

    /**
     * @param $src object Source object.
     * @param $dst object Destination object.
     * @param array $map Mapped fields from source to destination
     * @throws ReflectionException
     */
    public static function copyPropertiesMap($src, $dst, $map = [])
    {
        $srcReflector = new ReflectionClass($src);
        $dstReflector = new ReflectionClass($dst);
        /** @var string $srcName */
        foreach ($map as $srcName => $dstName) {
            $value = null;
            $srcProperty = $srcReflector->getProperty($srcName);
            if ($srcProperty->isPublic() && !$srcProperty->isStatic()) {
                /** @var mixed $value */
                $value = $srcProperty->getValue($src);
            } elseif (!$srcProperty->isStatic() && ($srcProperty->isPrivate() || $srcProperty->isProtected())) {
                /** @var ReflectionMethod $getter */
                $getter = $srcReflector->getMethod('get' . ucfirst($srcName));
                $value = $getter->invoke($src);
            }
            $dstProperty = $dstReflector->getProperty($dstName);
            if ($dstProperty->isPublic() && !$dstProperty->isStatic()) {
                $dstProperty->setValue($dst, $value);
            } elseif (!$srcProperty->isStatic() && ($srcProperty->isPrivate() || $srcProperty->isProtected())) {
                /** @var ReflectionMethod $getter */
                $setter = $srcReflector->getMethod('set' . ucfirst($dstName));
                $setter->invoke($dst, $value);
            }
        }
    }

}