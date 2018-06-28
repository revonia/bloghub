<?php

namespace Revonia\BlogHub;

class Env implements \ArrayAccess
{
    public const APP_PREFIX = 'BLOG_HUB';

    private $prefix;

    private $defaults = [];

    public function setPerfix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function get(string $name, $defaults = null)
    {
        $name = $this->envNameWithPrefix($name);
        $value = getenv($name);

        if ($value === false) {
            if (is_required($defaults)) {
                throw new \RuntimeException('Environment variable \'' . $name . '\' is required.');
            }
            $value = $defaults === null && isset($this->defaults[$name])
                ? $this->defaults[$name]
                : $defaults;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (\strlen($value) > 1 && preg_match('/^".*"$/', $value)) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    public function setDefaults(array $variables)
    {
        foreach ($variables as $name => $defaults) {
            $name = $this->envNameWithPrefix($name);
            $value = getenv($name);
            if (is_required($defaults)) {
                if ($value === false) {
                    throw new \RuntimeException('Environment variable \'' . $name . '\' is required.');
                }
            } else {
                $this->defaults[$name] = $defaults;
            }
        }

        return $this;
    }

    public function envNameWithPrefix($name)
    {
        return $this->prefix === null
            ? static::APP_PREFIX . '_' . $name
            : static::APP_PREFIX . '_' . $this->prefix . '_' . $name;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return getenv($this->envNameWithPrefix($offset)) !== false;
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Can\'t modify ' . static::class . ' instance.');
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Can\'t modify ' . static::class . ' instance.');
    }
}