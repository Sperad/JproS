<?php
namespace base\core;

class Object {

	public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            die('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            die('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            die('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            die('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }
}