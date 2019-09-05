<?php

include_once __DIR__ . '/../src/ObjectCopier.php';


use BABA\Utils\ObjectCopier;

/**
 * Class A
 */
class A
{
    private $private;
    public $public;
    protected $protected;

    /**
     * A constructor.
     * @param $private
     * @param $public
     * @param $protected
     */
    public function __construct($private, $public, $protected)
    {
        $this->private = $private;
        $this->public = $public;
        $this->protected = $protected;
    }

    /**
     * @return mixed
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @param mixed $private
     */
    public function setPrivate($private)
    {
        $this->private = $private;
    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return mixed
     */
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * @param mixed $protected
     */
    public function setProtected($protected)
    {
        $this->protected = $protected;
    }

}

$a = new A('private', 'public', 'protected');
$b = new A('', '', '');

ObjectCopier::copyProperties($a, $b);

var_dump($a);
var_dump($b);

$a = new A('private', 'public', 'protected');
$b = new A('', '', '');

ObjectCopier::copyProperties($a, $b, array('private'));

var_dump($a);
var_dump($b);

$a = new A('private', 'public', 'protected');
$b = new A('', '', '');

ObjectCopier::copyPropertiesMap($a, $b, array('private' => 'public'));

var_dump($a);
var_dump($b);