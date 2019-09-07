<?php


namespace BABA\Utils;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

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
    public static function copyProperties($src, $dst, $exclude = array())
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
                } elseif (!$dstProperty->isStatic() && ($dstProperty->isPrivate() || $dstProperty->isProtected())) {
                    /** @var ReflectionMethod $setter */
                    $setter = $dstReflector->getMethod('set' . ucfirst($name));
                    $setter->invoke($dst, $value);
                }
            }
        }
    }

    /**
     * @param $obj object Source object.
     * @param array $exclude Excluded fields, which are not will be null.
     * @throws ReflectionException
     */
    public static function nullProperties($obj, $exclude = array())
    {
        $objReflector = new ReflectionClass($obj);
        /** @var ReflectionProperty $objProperty */
        foreach ($objReflector->getProperties() as $objProperty) {
            $name = $objProperty->getName();
            $value = null;
            if (!in_array($name, $exclude)) {
                if ($objProperty->isPublic() && !$objProperty->isStatic()) {
                    $objProperty->setValue($obj, $value);
                } elseif (!$objProperty->isStatic() && ($objProperty->isPrivate() || $objProperty->isProtected())) {
                    /** @var ReflectionMethod $setter */
                    $setter = $objReflector->getMethod('set' . ucfirst($name));
                    $setter->invoke($obj, $value);
                }
            }
        }
    }

    /**
     * @param $obj object Source object.
     * @param mixed $value
     * @param array $exclude Excluded fields, which are not will be set.
     * @throws ReflectionException
     */
    public static function setProperties($obj, $value, $exclude = array())
    {
        $objReflector = new ReflectionClass($obj);
        /** @var ReflectionProperty $objProperty */
        foreach ($objReflector->getProperties() as $objProperty) {
            $name = $objProperty->getName();
            if (!in_array($name, $exclude)) {
                if ($objProperty->isPublic() && !$objProperty->isStatic()) {
                    $objProperty->setValue($obj, $value);
                } elseif (!$objProperty->isStatic() && ($objProperty->isPrivate() || $objProperty->isProtected())) {
                    /** @var ReflectionMethod $setter */
                    $setter = $objReflector->getMethod('set' . ucfirst($name));
                    $setter->invoke($obj, $value);
                }
            }
        }
    }

    /**
     * @param $obj object Source object.
     * @param array $map
     * @throws ReflectionException
     */
    public static function nullPropertiesMap($obj, $map)
    {
        $objReflector = new ReflectionClass($obj);
        $value = null;
        /** @var ReflectionProperty $objProperty */
        foreach ($map as $name) {
            $objProperty = $objReflector->getProperty($name);
            if ($objProperty->isPublic() && !$objProperty->isStatic()) {
                $objProperty->setValue($obj, $value);
            } elseif (!$objProperty->isStatic() && ($objProperty->isPrivate() || $objProperty->isProtected())) {
                /** @var ReflectionMethod $setter */
                $setter = $objReflector->getMethod('set' . ucfirst($name));
                $setter->invoke($obj, $value);
            }
        }
    }

    /**
     * @param $obj object Source object.
     * @param mixed $value
     * @param array $map
     * @throws ReflectionException
     */
    public static function setPropertiesMap($obj, $value, $map)
    {
        $objReflector = new ReflectionClass($obj);
        /** @var ReflectionProperty $objProperty */
        foreach ($map as $name) {
            $objProperty = $objReflector->getProperty(name);
            if ($objProperty->isPublic() && !$objProperty->isStatic()) {
                $objProperty->setValue($obj, $value);
            } elseif (!$objProperty->isStatic() && ($objProperty->isPrivate() || $objProperty->isProtected())) {
                /** @var ReflectionMethod $setter */
                $setter = $objReflector->getMethod('set' . ucfirst($name));
                $setter->invoke($obj, $value);
            }
        }
    }

    /**
     * @param $src object Source object.
     * @param $dst object Destination object.
     * @param array $map Mapped fields from source to destination
     * @throws ReflectionException
     */
    public static function copyPropertiesMap($src, $dst, $map = array())
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
                /** @var ReflectionMethod $setter */
                $setter = $dstReflector->getMethod('set' . ucfirst($dstName));
                $setter->invoke($dst, $value);
            }
        }
    }

}