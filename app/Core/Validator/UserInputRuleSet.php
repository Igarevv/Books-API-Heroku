<?php

namespace App\Core\Validator;

use App\App;

class UserInputRuleSet
{
    private array $default_error_message = [
      'required' => 'Field %s is required!',
      'numeric' => 'Field %s can be only numeric!',
      'email' => 'Field %s is not a valid email!',
      'alphanumeric' => "Field %s can only contains letters, numbers and special symbols: ' or - ",
      'max' => "Field %s cannot be more than %d symbols",
      'min' => "Field %s cannot be less than %d symbols",
      'unique' => "Sorry, but this %s is already exists",
      'string' => "Field %s must be string",
      'digits' => "Field %s can be only numeric and %d number(s) long"
    ];
    public function findViolation(string $rule_name, array $data, string $field_name, string $rule_value = null): string|false
    {
        if (! method_exists($this, $rule_name)) {
            throw new \InvalidArgumentException("Invalid rule: $rule_name");
        }
        if (call_user_func_array([$this, $rule_name], [$data[$field_name], $rule_value, $field_name])) {
            return false;
        }
        if (! is_null($rule_value)) {
            return sprintf(
              $this->default_error_message[$rule_name],
              $field_name,
              $rule_value
            );
        }
        return sprintf($this->default_error_message[$rule_name], $field_name);
    }
    public function changeErrorMessage(string $key, string $message): void
    {
        $this->default_error_message[$key] = $message;
    }
    public function parseRule(string $ruleString): array
    {
        $ruleParts = explode(":", trim($ruleString));
        $ruleName = $ruleParts[0];
        $ruleValue = $ruleParts[1] ?? null;
        return [$ruleName, $ruleValue];
    }

    /**
     * @param  mixed  $data
     *
     * @return bool
     */
    protected function required(mixed $data): bool
    {
        if(is_array($data) && $data){
            return count($data) === count(array_filter($data));
        }
        return $data && trim($data) !== '';
    }

    /**
     * @param  mixed  $data
     *
     * @return bool
     */
    protected function numeric(mixed $data): bool
    {
        if (is_numeric($data)) {
            return true;
        }
        return false;
    }

    /**
     * @param  string  $data
     *
     * @return bool
     */
    protected function alphanumeric(string $data): bool
    {
        $extra_spaces = preg_replace('/\s{2,}/', ' ', $data);
        if (preg_match('/^[a-zA-Z0-9\'\s-]+([a-zA-Z0-9\'\s-]+)*$/', $extra_spaces)) {
            return true;
        }
        return false;
    }

    /**
     * @param  string  $data
     *
     * @return bool
     */
    protected function string(string $data): bool
    {
        $extra_spaces = preg_replace('/\s{2,}/', ' ', $data);
        if (preg_match('/^[a-zA-Z\'\s-]+([a-zA-Z\'\s-]+)*$/', $extra_spaces)) {
            return true;
        }
        return false;
    }
    /**
     * @param  string  $data
     * @param  int  $value
     *
     * @return bool
     */
    protected function max(string $data, int $value): bool
    {
        return mb_strlen($data) <= $value;
    }

    /**
     * @param  string  $data
     * @param  int  $value
     *
     * @return bool
     */
    protected function min(string $data, int $value): bool
    {
        return mb_strlen($data) >= $value;
    }

    /**
     * @param  mixed  $data
     * @param  int  $value
     *
     * @return bool
     */
    protected function digits(mixed $data, int $value): bool
    {
        return mb_strlen($data) === $value;
    }
    /**
     * @param  string  $data
     *
     * @return bool
     */
    protected function email(string $data): bool
    {
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    protected function unique(string $data, string $value, string $column): bool
    {
        $pdo = App::db();

        $sql = "SELECT $column FROM $value WHERE $column = :$column";

        $stmt = $pdo->execute($sql, [":$column" => $data]);
        return $stmt->fetchColumn() === false;
    }
}