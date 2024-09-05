<?php
namespace Core;

class Request
{
    private $data;
    private $errors = [];

    public function __construct()
    {
        // Collect and sanitize request data
        $this->data = $this->sanitize($_REQUEST);
    }

    // Sanitize input data
    private function sanitize($data)
    {
        $cleanData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cleanData[$key] = $this->sanitize($value); // Recursively sanitize array values
            } else {
                // Trim whitespace and escape special characters
                $cleanData[$key] = htmlspecialchars(strip_tags(trim($value)));
            }
        }

        return $cleanData;
    }

    // Get a specific value from the request
    public function input($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    // Check if a specific key exists in the request
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    // Get all sanitized request data
    public function all()
    {
        return $this->data;
    }

    // Validate the request data against rules
    public function validate($rules)
    {
        $this->errors = [];

        foreach ($rules as $key => $ruleSet) {
            $value = $this->input($key);

            foreach (explode('|', $ruleSet) as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $this->errors[$key][] = "$key is required.";
                }

                if ($rule === 'numeric' && !is_numeric($value)) {
                    $this->errors[$key][] = "$key must be number.";
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$key][] = "$key must be a valid email address.";
                }

                if (strpos($rule, 'min:') === 0) {
                    $min = explode(':', $rule)[1];
                    if (strlen($value) < $min) {
                        $this->errors[$key][] = "$key must be at least $min characters.";
                    }
                }

                if (strpos($rule, 'max:') === 0) {
                    $max = explode(':', $rule)[1];
                    if (strlen($value) > $max) {
                        $this->errors[$key][] = "$key cannot exceed $max characters.";
                    }
                }
            }
        }

        return empty($this->errors);
    }

    // Get validation errors
    public function errors()
    {
        return $this->errors;
    }
}
