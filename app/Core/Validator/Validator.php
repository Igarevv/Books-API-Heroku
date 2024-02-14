<?php

namespace App\Core\Validator;

class Validator implements ValidatorInterface
{
    protected array $errors;

    protected array $data;

    public function __construct()
    {
        $this->errors = [];
        $this->data = [];
    }

    public function validate(array $data, array $rules): bool
    {
        $this->data = $data;
        return (empty($this->checkDataByRule($data, $rules)));
    }

    private function checkDataByRule(array $data, array $rules): array
    {
        $ruleset = new UserInputRuleSet();
        foreach ($rules as $rule_key => $rule_array) {
            foreach ($rule_array as $one_rule) {
                [$rule_name, $rule_value] = $ruleset->parseRule($one_rule);
                $errors = $ruleset->findViolation(
                  $rule_name,
                  $data,
                  $rule_key,
                  $rule_value
                );
                if ($errors) {
                    $this->errors[$rule_key][] = $errors;
                }
            }
        }
        return $this->errors ?? [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

}