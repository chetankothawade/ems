<?php

declare(strict_types=1);

namespace App\Validation;

class Validator
{
    /**
     * @var array<string, string[]>
     */
    private array $errors = [];

    /**
     * @var array<string, mixed>
     */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate required field
     */
    public function required(string $field, string $message = ''): self
    {
        if (!isset($this->data[$field]) || trim((string)$this->data[$field]) === '') {
            $this->addError($field, $message ?: "$field is required");
        }

        return $this;
    }

    /**
     * Validate string
     */
    public function string(string $field, string $message = ''): self
    {
        if (isset($this->data[$field]) && !is_string($this->data[$field])) {
            $this->addError($field, $message ?: "$field must be a string");
        }

        return $this;
    }

    /**
     * Validate integer
     */
    public function integer(string $field, string $message = ''): self
    {
        if (isset($this->data[$field]) && (!is_int($this->data[$field]) && !is_numeric($this->data[$field]))) {
            $this->addError($field, $message ?: "$field must be an integer");
        }

        return $this;
    }

    /**
     * Validate minimum length
     */
    public function minLength(string $field, int $min, string $message = ''): self
    {
        if (isset($this->data[$field]) && strlen((string)$this->data[$field]) < $min) {
            $this->addError($field, $message ?: "$field must be at least $min characters");
        }

        return $this;
    }

    /**
     * Validate maximum length
     */
    public function maxLength(string $field, int $max, string $message = ''): self
    {
        if (isset($this->data[$field]) && strlen((string)$this->data[$field]) > $max) {
            $this->addError($field, $message ?: "$field must not exceed $max characters");
        }

        return $this;
    }

    /**
     * Validate minimum value
     */
    public function min(string $field, int|float $min, string $message = ''): self
    {
        if (isset($this->data[$field]) && (int)$this->data[$field] < $min) {
            $this->addError($field, $message ?: "$field must be at least $min");
        }

        return $this;
    }

    /**
     * Validate maximum value
     */
    public function max(string $field, int|float $max, string $message = ''): self
    {
        if (isset($this->data[$field]) && (int)$this->data[$field] > $max) {
            $this->addError($field, $message ?: "$field must not exceed $max");
        }

        return $this;
    }

    /**
     * Validate email format
     */
    public function email(string $field, string $message = ''): self
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $message ?: "$field must be a valid email");
        }

        return $this;
    }

    /**
     * Validate regex pattern
     */
    public function matches(string $field, string $pattern, string $message = ''): self
    {
        if (isset($this->data[$field]) && !preg_match($pattern, (string)$this->data[$field])) {
            $this->addError($field, $message ?: "$field format is invalid");
        }

        return $this;
    }

    /**
     * Validate in array
     */
    public function in(string $field, array $values, string $message = ''): self
    {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $values, true)) {
            $this->addError($field, $message ?: "$field contains invalid value");
        }

        return $this;
    }

    /**
     * Validate unique (custom callback)
     */
    public function unique(string $field, callable $callback, string $message = ''): self
    {
        if (isset($this->data[$field]) && !$callback($this->data[$field])) {
            $this->addError($field, $message ?: "$field already exists");
        }

        return $this;
    }

    /**
     * Add custom error
     */
    public function addError(string $field, string $message): self
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $message;

        return $this;
    }

    /**
     * Check if validation passed
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     */
    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * Get all errors
     *
     * @return array<string, string[]>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Throw exception if validation fails
     */
    public function validate(): void
    {
        if ($this->fails()) {
            throw new ValidationException($this->errors);
        }
    }
}
