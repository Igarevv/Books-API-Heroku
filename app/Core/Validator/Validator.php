<?php

namespace App\Core\Validator;

class Validator implements ValidatorInterface
{
    protected array $errors;


    public function __construct()
    {
        $this->errors = [];
    }

    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $rule_key => $rule_array) {
            if (! array_key_exists($rule_key, $data)) {
                $this->errors[$rule_key][] = "Field $rule_key is missing!";
                return false;
            }
        }
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