<?php

namespace App\Core\Validator;

class UserInputRuleSet
{
    private array $default_error_message = [
      'required' => 'Field %s is required!',
      'numeric' => 'Field %s can be only numeric!',
      'email' => 'Field %s is not a valid email!',
      'alphanumeric' => "Field %s can only contains letters, numbers and special symbols: ' or - ",
      'max' => "Field %s cannot be more than %d symbols",
      'min' => "Field %s cannot be less than %d symbols",
    ];
    public function findViolation(string $rule_name, array $data, string $field_name, string $rule_value = null): string|false
    {
        if (! method_exists($this, $rule_name)) {
            throw new \InvalidArgumentException("Invalid rule: $rule_name");
        }
        if (call_user_func_array([$this, $rule_name], [$data[$field_name], $rule_value])) {
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
     * @param  string|null  $data
     *
     * @return bool
     */
    protected function required(?string $data): bool
    {
        if (is_null($data) || trim($data) === '') {
            return false;
        }
        return true;
    }

    /**
     * @param  mixed  $data
     *
     * @return bool
     */
    protected function numeric(mixed $data): bool
    {
        if (! is_numeric($data)) {
            return false;
        }
        return true;
    }

    /**
     * @param  string  $data
     *
     * @return bool
     */
    protected function alphanumeric(string $data): bool
    {
        $extra_spaces = preg_replace('/\s{2,}/', ' ', $data);
        if (! preg_match('/^[a-zA-Z0-9\'\s-]+([a-zA-Z0-9\'\s-]+)*$/', $extra_spaces)) {
            return false;
        }
        return true;
    }

    /**
     * @param  string  $data
     * @param  int  $value
     *
     * @return bool
     */
    protected function max(string $data, int $value): bool
    {
        if (mb_strlen($data) > $value) {
            return false;
        }
        return true;
    }

    /**
     * @param  string  $data
     * @param  int  $value
     *
     * @return bool
     */
    protected function min(string $data, int $value): bool
    {
        if (mb_strlen($data) < $value) {
            return false;
        }
        return true;
    }

    /**
     * @param  string  $data
     *
     * @return bool
     */
    protected function email(string $data): bool
    {
        if (! filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

}