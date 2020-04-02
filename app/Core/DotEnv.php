<?php

namespace Core;

class DotEnv
{
    private $env = [];

    public function __construct(string $filePath)
    {
        $envContent = file_get_contents($filePath);
        $envRows = explode("\n", $envContent);

        foreach ($envRows as $envRow) {
            if (empty($envRow)) {
                continue;
            }

            $rowElements = explode('=', $envRow);

            $key = trim($rowElements[0]);
            $value = isset($rowElements[1]) ? trim($rowElements[1]) : null;

            $this->env[$key] = $value;
        }
    }

    public function get(string $key = null)
    {
        if (!$key) {
            return $this->env;
        }

        return isset($this->env[$key]) ? $this->env[$key] : null;
    }
}
