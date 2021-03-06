<?php

namespace Construct\Helpers;

class Str
{
    /**
     * Regex to match project name against.
     * Must be: vendor/package
     *
     * @var string
     */
    protected $regEx = '{^[A-Za-z0-9][A-Za-z0-9_.-]*/[A-Za-z0-9][A-Za-z0-9_.-]*$}u';

    /**
     * Check if the entered project name or
     * Composer package name is valid.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function isValid($name)
    {
        if (preg_match($this->regEx, $name) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if the entered project name contains a given string.
     *
     * @param string $name
     * @param string $needle
     *
     * @return boolean
     */
    public function contains($name, $needle)
    {
        return strstr($name, $needle) !== false;
    }

    /**
     * Convert string to lower case.
     *
     * @param string $string
     *
     * @return string
     */
    public function toLower($string)
    {
        return strtolower($string);
    }

    /**
     * Convert string to studly case.
     *
     * @param string $string
     *
     * @return string
     */
    public function toStudly($string)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $string));

        return str_replace(' ', '', $value);
    }

    /**
     * Convert string to camel case.
     *
     * @param string  $string
     * @param boolean $capitalizeFirstCharacter
     *
     * @return string
     */
    public function toCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $string = str_replace(
            ' ',
            '',
            ucwords(str_replace(['-', '_'], ' ', $string))
        );

        if (!$capitalizeFirstCharacter) {
            $string = lcfirst($string);
        }

        return $string;
    }

    /**
     * Split project name in a pretty array.
     *
     * @param string $string
     *
     * @return array
     */
    public function split($string)
    {
        $project = explode('/', $string);

        return [
            'vendor' => $project[0],
            'project' => $project[1],
        ];
    }

    /**
     * Construct a correct project namespace name.
     *
     * @param string  $namespace        The entered namespace.
     * @param boolean $usesProjectName  Whether or not it's using the project name.
     * @param boolean $useDoubleSlashes Whether or not use double slashes \\.
     *
     * @return string
     */
    public function createNamespace($namespace, $usesProjectName = false, $useDoubleSlashes = false)
    {
        $delimiter = $usesProjectName ? '/' : '\\';
        $slash = $useDoubleSlashes ? '\\\\' : '\\';

        // strip dots and dashes from project name
        if ($usesProjectName) {
            $namespace = str_replace(['-', '.'], '_', $namespace);
            $namespace = $this->toStudly($namespace);
        }

        return implode($slash, array_map(function ($v) {
            return $this->toStudly($v);
        }, explode($delimiter, $namespace)));
    }

    /**
     * Check if the operating system is windowsish.
     *
     * @param string $os
     *
     * @return boolean
     */
    public function isWindows($os = PHP_OS)
    {
        if (strtoupper(substr($os, 0, 3)) !== 'WIN') {
            return false;
        }

        return true;
    }

    /**
     * Convert keywords to quoted keywords.
     * Ex: "test,php,vagrant,provision" -> '"test","php","vagrant","provision"'
     *
     * @param string $keywords
     *
     * @return string
     */
    public function toQuotedKeywords($keywords)
    {
        if (trim($keywords) === '' || $keywords === null) {
            return '';
        }

        $keywordsQuoted = array_map(function ($keyword) {
            return '"' . trim($keyword) . '"';
        }, explode(',', $keywords));

        return implode(', ', $keywordsQuoted);
    }

    /**
     * Returns the minor version of the given version.
     *
     * @param string $version
     *
     * @return string
     */
    public function toMinorVersion($version)
    {
        list($major, $minor) = explode('.', $version);

        return $major . '.' . $minor;
    }

    /**
     * Validate php version string
     *
     * @param string $version
     *
     * @return boolean
     */
    public function phpVersionIsValid($version)
    {
        return preg_match('/\d\.\d(\.\d)?/', $version) === 1;
    }
}
