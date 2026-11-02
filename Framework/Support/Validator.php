<?php

namespace Framework\Support;

use Framework\Http\Request;
use Framework\Http\UploadedFile;

/**
 * Provides default validation error messages.
 *
 * This class can be extended to customize messages globally or per validator instance.
 */
class ValidationMessages
{
    /**
     * Returns validation messages indexed by rule name.
     *
     * Child classes can override this method and merge custom messages with parent messages.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'required' => 'The :field field is required.',
            'present' => 'The :field field must be present.',

            'string' => 'The :field field must be a string.',
            'numeric' => 'The :field field must be a number.',
            'integer' => 'The :field field must be an integer.',
            'float' => 'The :field field must be a decimal number.',
            'boolean' => 'The :field field must be true or false.',
            'array' => 'The :field field must be an array.',

            'email' => 'The :field field must be a valid email address.',
            'url' => 'The :field field must be a valid URL.',
            'ip' => 'The :field field must be a valid IP address.',
            'uuid' => 'The :field field must be a valid UUID.',
            'date' => 'The :field field must be a valid date.',
            'dateFormat' => 'The :field field must match the format :format.',

            'minLength' => 'The :field field must be at least :min characters.',
            'maxLength' => 'The :field field must not be greater than :max characters.',
            'length' => 'The :field field must be exactly :length characters.',
            'betweenLength' => 'The :field field must be between :min and :max characters.',
            'startsWith' => 'The :field field must start with :value.',
            'endsWith' => 'The :field field must end with :value.',
            'contains' => 'The :field field must contain :value.',
            'matches' => 'The :field field format is invalid.',

            'min' => 'The :field field must be at least :min.',
            'max' => 'The :field field must not be greater than :max.',
            'between' => 'The :field field must be between :min and :max.',
            'greaterThan' => 'The :field field must be greater than :value.',
            'greaterThanOrEqual' => 'The :field field must be greater than or equal to :value.',
            'lessThan' => 'The :field field must be less than :value.',
            'lessThanOrEqual' => 'The :field field must be less than or equal to :value.',

            'in' => 'The :field field must be one of the following values: :allowed.',
            'notIn' => 'The :field field must not be one of the following values: :blocked.',

            'sameAs' => 'The :field field must match :other.',
            'differentFrom' => 'The :field field must be different from :other.',
            'requiredIf' => 'The :field field is required when :other is :value.',
            'requiredWith' => 'The :field field is required when :other is present.',
            'requiredWithout' => 'The :field field is required when :other is not present.',

            'accepted' => 'The :field field must be accepted.',
            'declined' => 'The :field field must be declined.',

            'file' => 'The :field file failed to upload.',
            'maxFileSize' => 'The :field file must not be greater than :max KB.',
            'mimeType' => 'The :field file must be of type: :allowed.',
            'extension' => 'The :field file must have one of the following extensions: :allowed.',
            'image' => 'The :field file must be an image.',

            'custom' => 'The :field field is invalid.',
        ];
    }

    /**
     * Returns the validation message for the given rule name.
     *
     * @param string $rule Validation rule name.
     * @return string Validation message template.
     */
    public function get(string $rule): string
    {
        return $this->messages()[$rule] ?? 'The :field field is invalid.';
    }
}

/**
 * Represents the result of a validation process.
 */
final class ValidationResult
{
    /**
     * @var array<string, string[]>
     */
    private array $errors = [];

    /**
     * Adds an error message for a specific field.
     *
     * @param string $field Field name.
     * @param string $message Validation error message.
     */
    public function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    /**
     * Checks whether the validation passed.
     *
     * @return bool True if there are no validation errors.
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Checks whether the validation failed.
     *
     * @return bool True if there is at least one validation error.
     */
    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * Checks whether a field has validation errors.
     *
     * @param string $field Field name.
     * @return bool True if the field has at least one error.
     */
    public function has(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /**
     * Returns all validation errors grouped by field name.
     *
     * @return array<string, string[]>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Returns all validation errors for a specific field.
     *
     * @param string $field Field name.
     * @return string[]
     */
    public function get(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Returns the first validation error.
     *
     * If a field is provided, the first error for that field is returned.
     * Otherwise, the first error from all fields is returned.
     *
     * @param string|null $field Optional field name.
     * @return string|null First error message or null if there are no errors.
     */
    public function first(?string $field = null): ?string
    {
        if ($field !== null) {
            return $this->errors[$field][0] ?? null;
        }

        foreach ($this->errors as $messages) {
            return $messages[0] ?? null;
        }

        return null;
    }

    /**
     * Returns all validation error messages as a flat list.
     *
     * @return string[]
     */
    public function all(): array
    {
        $messages = [];

        foreach ($this->errors as $fieldErrors) {
            foreach ($fieldErrors as $message) {
                $messages[] = $message;
            }
        }

        return $messages;
    }
}

/**
 * Represents a single validation rule.
 */
final class ValidatorRule
{
    public readonly Closure $callback;

    /**
     * Creates a new validation rule.
     *
     * @param string $name Rule name.
     * @param string $field Field name.
     * @param mixed $expected Expected value or rule metadata.
     * @param string|null $message Custom error message.
     * @param callable $callback Validation callback.
     * @param string $source Source of the field value. Usually "value" or "file".
     */
    public function __construct(
        public readonly string $name,
        public readonly string $field,
        public readonly mixed $expected,
        public readonly ?string $message,
        callable $callback,
        public readonly string $source = 'value',
    ) {
        $this->callback = $callback instanceof Closure
            ? $callback
            : Closure::fromCallable($callback);
    }
}

/**
 * Main validator class.
 *
 * Use Validator::make()->for('field') for normal request values
 * and Validator::make()->file('field') for uploaded files.
 */
final class Validator
{
    private static ?ValidationMessages $defaultMessages = null;

    /**
     * @var array<string, ValidatorRule[]>
     */
    private array $rules = [];

    /**
     * @var array<string, string>
     */
    private array $labels = [];

    private ValidationMessages $messages;

    /**
     * Creates a new validator instance.
     *
     * @param Request|null $request Optional request instance.
     * @param ValidationMessages|null $messages Optional validation messages provider.
     */
    public function __construct(
        private ?Request $request = null,
        ?ValidationMessages $messages = null,
    ) {
        $this->messages = $messages ?? self::$defaultMessages ?? new ValidationMessages();
    }

    /**
     * Creates a new validator instance.
     *
     * @param Request|null $request Optional request instance.
     * @param ValidationMessages|null $messages Optional validation messages provider.
     * @return self
     */
    public static function make(
        ?Request $request = null,
        ?ValidationMessages $messages = null
    ): self {
        return new self($request, $messages);
    }

    /**
     * Sets the global default validation messages provider.
     *
     * This provider will be used by all newly created validators unless overridden.
     *
     * @param ValidationMessages $messages Validation messages provider.
     */
    public static function setDefaultMessages(ValidationMessages $messages): void
    {
        self::$defaultMessages = $messages;
    }

    /**
     * Starts validation rules for a regular request value.
     *
     * The field value will be read using Request::value($field).
     *
     * @param string $field Field name.
     * @return FieldValidator Field-specific validator.
     */
    public function for(string $field): FieldValidator
    {
        return new FieldValidator($this, $field, 'value');
    }

    /**
     * Starts validation rules for an uploaded file.
     *
     * The field value will be read using Request::file($field).
     *
     * @param string $field File field name.
     * @return FieldValidator Field-specific validator.
     */
    public function file(string $field): FieldValidator
    {
        return new FieldValidator($this, $field, 'file');
    }

    /**
     * Adds a validation rule to the validator.
     *
     * @param string $name Rule name.
     * @param string $field Field name.
     * @param mixed $expected Expected value or rule metadata.
     * @param string|null $message Custom error message.
     * @param callable $callback Validation callback.
     * @param string $source Source of the field value. Usually "value" or "file".
     */
    public function addRule(
        string $name,
        string $field,
        mixed $expected,
        ?string $message,
        callable $callback,
        string $source = 'value'
    ): void {
        $this->rules[$field][] = new ValidatorRule(
            $name,
            $field,
            $expected,
            $message,
            $callback,
            $source
        );
    }

    /**
     * Sets a human-readable label for a field.
     *
     * The label will be used in validation error messages instead of the raw field name.
     *
     * @param string $field Field name.
     * @param string $label Human-readable field label.
     */
    public function setLabel(string $field, string $label): void
    {
        $this->labels[$field] = $label;
    }

    /**
     * Runs validation against the given request.
     *
     * If no request is provided, the request passed to the constructor or make() is used.
     *
     * @param Request|null $request Optional request instance.
     * @return ValidationResult Validation result object.
     *
     * @throws LogicException If no request is available.
     */
    public function validate(?Request $request = null): ValidationResult
    {
        $request ??= $this->request;

        if ($request === null) {
            throw new LogicException('Request was not provided to the validator.');
        }

        $result = new ValidationResult();

        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                $value = $this->valueForRule($request, $field, $rule);

                if (!$this->isImplicitRule($rule) && $this->isEmpty($value)) {
                    continue;
                }

                $valid = ($rule->callback)($value, $request, $field);

                if ($valid === true) {
                    continue;
                }

                if (is_string($valid)) {
                    $result->addError($field, $valid);
                } else {
                    $result->addError($field, $this->makeMessage($rule));
                }

                if ($this->shouldStopAfterFailure($rule, $value)) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Checks whether a regular value field exists in the request.
     *
     * If Request::hasValue() exists, it is used. Otherwise, the method falls back to checking
     * whether Request::value($field) returns a non-null value.
     *
     * @param Request $request Request instance.
     * @param string $field Field name.
     * @return bool True if the field exists.
     */
    public function fieldExists(Request $request, string $field): bool
    {
        if (method_exists($request, 'hasValue')) {
            return $request->hasValue($field);
        }

        return $request->value($field) !== null;
    }

    /**
     * Checks whether a value should be considered empty.
     *
     * Uploaded files are considered empty when their error code is UPLOAD_ERR_NO_FILE.
     * Arrays are considered empty when all their items are empty.
     *
     * @param mixed $value Value to check.
     * @return bool True if the value is empty.
     */
    public function isEmpty(mixed $value): bool
    {
        if ($value instanceof UploadedFile) {
            return $value->getError() === UPLOAD_ERR_NO_FILE;
        }

        if (is_array($value)) {
            if ($value === []) {
                return true;
            }

            foreach ($value as $item) {
                if (!$this->isEmpty($item)) {
                    return false;
                }
            }

            return true;
        }

        return $value === null || $value === '';
    }

    /**
     * Reads the field value for a validation rule.
     *
     * @param Request $request Request instance.
     * @param string $field Field name.
     * @param ValidatorRule $rule Validation rule.
     * @return mixed Field value.
     */
    private function valueForRule(Request $request, string $field, ValidatorRule $rule): mixed
    {
        return match ($rule->source) {
            'file' => $request->file($field),
            default => $request->value($field),
        };
    }

    /**
     * Checks whether a rule must be executed even when the value is empty.
     *
     * @param ValidatorRule $rule Validation rule.
     * @return bool True if the rule is implicit.
     */
    private function isImplicitRule(ValidatorRule $rule): bool
    {
        return in_array($rule->name, [
            'required',
            'present',
            'requiredIf',
            'requiredWith',
            'requiredWithout',
            'accepted',
            'declined',
        ], true);
    }

    /**
     * Checks whether validation for a field should stop after the current rule failed.
     *
     * This prevents duplicate errors after a required rule fails.
     *
     * @param ValidatorRule $rule Failed validation rule.
     * @param mixed $value Current field value.
     * @return bool True if validation for the current field should stop.
     */
    private function shouldStopAfterFailure(ValidatorRule $rule, mixed $value): bool
    {
        return $rule->name === 'required'
            && ($this->isEmpty($value) || $rule->source === 'file');
    }

    /**
     * Builds a final validation error message for a rule.
     *
     * Replaces placeholders such as :field, :min, :max, :allowed and :other.
     *
     * @param ValidatorRule $rule Validation rule.
     * @return string Final validation error message.
     */
    private function makeMessage(ValidatorRule $rule): string
    {
        $message = $rule->message ?? $this->messages->get($rule->name);

        $replacements = [
            ':field' => $this->labelFor($rule->field),
        ];

        if (is_array($rule->expected)) {
            foreach ($rule->expected as $key => $value) {
                if ($key === 'other' && is_string($value)) {
                    $replacements[':other'] = $this->labelFor($value);
                    continue;
                }

                if (($key === 'allowed' || $key === 'blocked') && is_array($value)) {
                    $replacements[':' . $key] = $this->stringifyList($value);
                    continue;
                }

                if (is_string($key)) {
                    $replacements[':' . $key] = $this->stringify($value);
                }
            }
        } else {
            $value = $this->stringify($rule->expected);

            $replacements[':value'] = $value;
            $replacements[':min'] = $value;
            $replacements[':max'] = $value;
            $replacements[':length'] = $value;
        }

        return strtr($message, $replacements);
    }

    /**
     * Returns the label for a field.
     *
     * If no custom label exists, the raw field name is returned.
     *
     * @param string $field Field name.
     * @return string Field label.
     */
    private function labelFor(string $field): string
    {
        return $this->labels[$field] ?? $field;
    }

    /**
     * Converts a value to a string usable in validation messages.
     *
     * @param mixed $value Value to stringify.
     * @return string String representation.
     */
    private function stringify(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        if (is_array($value)) {
            return $this->stringifyList($value);
        }

        return '';
    }

    /**
     * Converts a list of values to a comma-separated string.
     *
     * @param array<int|string, mixed> $values Values to stringify.
     * @return string Comma-separated values.
     */
    private function stringifyList(array $values): string
    {
        return implode(', ', array_map(
            fn (mixed $value): string => $this->stringify($value),
            $values
        ));
    }
}

/**
 * Field-specific validator used by the fluent validation API.
 */
final class FieldValidator
{
    /**
     * Creates a new field validator.
     *
     * @param Validator $validator Parent validator.
     * @param string $field Field name.
     * @param string $source Source of the value. Usually "value" or "file".
     */
    public function __construct(
        private readonly Validator $validator,
        private readonly string $field,
        private readonly string $source = 'value',
    ) {
    }

    /**
     * Switches fluent validation to another regular request value field.
     *
     * @param string $field Field name.
     * @return self New field validator.
     */
    public function for(string $field): self
    {
        return $this->validator->for($field);
    }

    /**
     * Switches fluent validation to an uploaded file field.
     *
     * @param string $field File field name.
     * @return self New field validator.
     */
    public function file(string $field): self
    {
        return $this->validator->file($field);
    }

    /**
     * Returns the parent validator instance.
     *
     * Useful when you want to leave the field-specific fluent API.
     *
     * @return Validator Parent validator.
     */
    public function done(): Validator
    {
        return $this->validator;
    }

    /**
     * Runs validation on the parent validator.
     *
     * @param Request|null $request Optional request instance.
     * @return ValidationResult Validation result.
     */
    public function validate(?Request $request = null): ValidationResult
    {
        return $this->validator->validate($request);
    }

    /**
     * Sets a human-readable label for the current field.
     *
     * @param string $label Human-readable field label.
     * @return self
     */
    public function label(string $label): self
    {
        $this->validator->setLabel($this->field, $label);

        return $this;
    }

    /**
     * Marks the current field as optional.
     *
     * Empty optional fields are skipped by non-implicit validation rules.
     *
     * @return self
     */
    public function isOptional(): self
    {
        return $this->addRule(
            'optional',
            null,
            null,
            fn (): bool => true
        );
    }

    /**
     * Allows the current field to be null or empty.
     *
     * Empty nullable fields are skipped by non-implicit validation rules.
     *
     * @return self
     */
    public function isNullable(): self
    {
        return $this->addRule(
            'nullable',
            null,
            null,
            fn (): bool => true
        );
    }

    /**
     * Requires the current field to be present in the request.
     *
     * For regular fields, Request::hasValue() is used if available.
     * For file fields, Request::file($field) must return a non-null value.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isPresent(?string $message = null): self
    {
        return $this->addRule(
            'present',
            null,
            $message,
            function (mixed $value, Request $request, string $field): bool {
                if ($this->source === 'file') {
                    return $request->file($field) !== null;
                }

                return $this->validator->fieldExists($request, $field);
            }
        );
    }

    /**
     * Requires the current field to have a non-empty value.
     *
     * For file fields, the file must be uploaded successfully.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isRequired(?string $message = null): self
    {
        if ($this->source === 'file') {
            return $this->addRule(
                'required',
                null,
                $message,
                function (mixed $value): bool|string {
                    $files = $this->filesFrom($value);

                    if ($files === []) {
                        return false;
                    }

                    foreach ($files as $file) {
                        if ($file->getError() === UPLOAD_ERR_NO_FILE) {
                            return false;
                        }

                        if (!$file->isOk()) {
                            return $file->getErrorMessage() ?? false;
                        }
                    }

                    return true;
                }
            );
        }

        return $this->addRule(
            'required',
            null,
            $message,
            fn (mixed $value): bool => !$this->validator->isEmpty($value)
        );
    }

    /**
     * Validates that the current field is a string.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isString(?string $message = null): self
    {
        return $this->addRule(
            'string',
            null,
            $message,
            fn (mixed $value): bool => is_string($value)
        );
    }

    /**
     * Validates that the current field is numeric.
     *
     * Numeric strings are accepted.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isNumeric(?string $message = null): self
    {
        return $this->addRule(
            'numeric',
            null,
            $message,
            fn (mixed $value): bool => is_numeric($value)
        );
    }

    /**
     * Validates that the current field is an integer.
     *
     * Integer strings are accepted.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isInteger(?string $message = null): self
    {
        return $this->addRule(
            'integer',
            null,
            $message,
            fn (mixed $value): bool => is_int($value)
                || (is_string($value) && preg_match('/^-?\d+$/', $value) === 1)
        );
    }

    /**
     * Validates that the current field is a floating-point number.
     *
     * Numeric strings accepted by FILTER_VALIDATE_FLOAT are accepted.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isFloat(?string $message = null): self
    {
        return $this->addRule(
            'float',
            null,
            $message,
            fn (mixed $value): bool => filter_var($value, FILTER_VALIDATE_FLOAT) !== false
        );
    }

    /**
     * Validates that the current field is a boolean-like value.
     *
     * Accepted values include true, false, 1, 0, "true", "false", "yes", "no", "on" and "off".
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isBoolean(?string $message = null): self
    {
        return $this->addRule(
            'boolean',
            null,
            $message,
            fn (mixed $value): bool => $this->isBooleanValue($value)
        );
    }

    /**
     * Validates that the current field is an array.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isArray(?string $message = null): self
    {
        return $this->addRule(
            'array',
            null,
            $message,
            fn (mixed $value): bool => is_array($value)
        );
    }

    /**
     * Validates that the current field is a valid email address.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isEmail(?string $message = null): self
    {
        return $this->addRule(
            'email',
            null,
            $message,
            fn (mixed $value): bool => is_string($value)
                && filter_var($value, FILTER_VALIDATE_EMAIL) !== false
        );
    }

    /**
     * Validates that the current field is a valid URL.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isUrl(?string $message = null): self
    {
        return $this->addRule(
            'url',
            null,
            $message,
            fn (mixed $value): bool => is_string($value)
                && filter_var($value, FILTER_VALIDATE_URL) !== false
        );
    }

    /**
     * Validates that the current field is a valid IP address.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isIp(?string $message = null): self
    {
        return $this->addRule(
            'ip',
            null,
            $message,
            fn (mixed $value): bool => is_string($value)
                && filter_var($value, FILTER_VALIDATE_IP) !== false
        );
    }

    /**
     * Validates that the current field is a valid UUID.
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isUuid(?string $message = null): self
    {
        return $this->addRule(
            'uuid',
            null,
            $message,
            fn (mixed $value): bool => is_string($value)
                && preg_match(
                    '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                    $value
                ) === 1
        );
    }

    /**
     * Validates that the current field is a valid date string.
     *
     * The validation is based on strtotime().
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isDate(?string $message = null): self
    {
        return $this->addRule(
            'date',
            null,
            $message,
            fn (mixed $value): bool => is_string($value)
                && trim($value) !== ''
                && strtotime($value) !== false
        );
    }

    /**
     * Validates that the current field matches a specific date format.
     *
     * Example format: "Y-m-d".
     *
     * @param string $format Date format accepted by DateTimeImmutable::createFromFormat().
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isDateFormat(string $format, ?string $message = null): self
    {
        return $this->addRule(
            'dateFormat',
            ['format' => $format],
            $message,
            function (mixed $value) use ($format): bool {
                if (!is_string($value)) {
                    return false;
                }

                $date = DateTimeImmutable::createFromFormat($format, $value);
                $errors = DateTimeImmutable::getLastErrors();

                if ($date === false) {
                    return false;
                }

                if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
                    return false;
                }

                return $date->format($format) === $value;
            }
        );
    }

    /**
     * Validates the minimum string length of the current field.
     *
     * @param int $min Minimum number of characters.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function minLength(int $min, ?string $message = null): self
    {
        return $this->addRule(
            'minLength',
            $min,
            $message,
            fn (mixed $value): bool => is_string($value) && mb_strlen($value) >= $min
        );
    }

    /**
     * Validates the maximum string length of the current field.
     *
     * @param int $max Maximum number of characters.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function maxLength(int $max, ?string $message = null): self
    {
        return $this->addRule(
            'maxLength',
            $max,
            $message,
            fn (mixed $value): bool => is_string($value) && mb_strlen($value) <= $max
        );
    }

    /**
     * Validates the exact string length of the current field.
     *
     * @param int $length Required number of characters.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function length(int $length, ?string $message = null): self
    {
        return $this->addRule(
            'length',
            $length,
            $message,
            fn (mixed $value): bool => is_string($value) && mb_strlen($value) === $length
        );
    }

    /**
     * Validates that the string length is between two values.
     *
     * @param int $min Minimum number of characters.
     * @param int $max Maximum number of characters.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function betweenLength(int $min, int $max, ?string $message = null): self
    {
        return $this->addRule(
            'betweenLength',
            ['min' => $min, 'max' => $max],
            $message,
            fn (mixed $value): bool => is_string($value)
                && mb_strlen($value) >= $min
                && mb_strlen($value) <= $max
        );
    }

    /**
     * Validates that the current field starts with a specific prefix.
     *
     * @param string $prefix Required prefix.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function startsWith(string $prefix, ?string $message = null): self
    {
        return $this->addRule(
            'startsWith',
            $prefix,
            $message,
            fn (mixed $value): bool => is_string($value) && str_starts_with($value, $prefix)
        );
    }

    /**
     * Validates that the current field ends with a specific suffix.
     *
     * @param string $suffix Required suffix.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function endsWith(string $suffix, ?string $message = null): self
    {
        return $this->addRule(
            'endsWith',
            $suffix,
            $message,
            fn (mixed $value): bool => is_string($value) && str_ends_with($value, $suffix)
        );
    }

    /**
     * Validates that the current field contains a specific substring.
     *
     * @param string $needle Required substring.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function contains(string $needle, ?string $message = null): self
    {
        return $this->addRule(
            'contains',
            $needle,
            $message,
            fn (mixed $value): bool => is_string($value) && str_contains($value, $needle)
        );
    }

    /**
     * Validates that the current field matches a regular expression.
     *
     * @param string $pattern Regular expression pattern.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function matches(string $pattern, ?string $message = null): self
    {
        return $this->addRule(
            'matches',
            $pattern,
            $message,
            fn (mixed $value): bool => is_string($value) && preg_match($pattern, $value) === 1
        );
    }

    /**
     * Validates that the current numeric field is at least the given value.
     *
     * @param int|float $min Minimum numeric value.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function min(int|float $min, ?string $message = null): self
    {
        return $this->addRule(
            'min',
            $min,
            $message,
            fn (mixed $value): bool => is_numeric($value) && (float) $value >= $min
        );
    }

    /**
     * Validates that the current numeric field is not greater than the given value.
     *
     * @param int|float $max Maximum numeric value.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function max(int|float $max, ?string $message = null): self
    {
        return $this->addRule(
            'max',
            $max,
            $message,
            fn (mixed $value): bool => is_numeric($value) && (float) $value <= $max
        );
    }

    /**
     * Validates that the current numeric field is between two values.
     *
     * @param int|float $min Minimum numeric value.
     * @param int|float $max Maximum numeric value.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function between(int|float $min, int|float $max, ?string $message = null): self
    {
        return $this->addRule(
            'between',
            ['min' => $min, 'max' => $max],
            $message,
            fn (mixed $value): bool => is_numeric($value)
                && (float) $value >= $min
                && (float) $value <= $max
        );
    }

    /**
     * Validates that the current numeric field is greater than the given value.
     *
     * @param int|float $value Comparison value.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function greaterThan(int|float $value, ?string $message = null): self
    {
        return $this->addRule(
            'greaterThan',
            $value,
            $message,
            fn (mixed $input): bool => is_numeric($input) && (float) $input > $value
        );
    }

    /**
     * Validates that the current numeric field is greater than or equal to the given value.
     *
     * @param int|float $value Comparison value.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function greaterThanOrEqual(int|float $value, ?string $message = null): self
    {
        return $this->addRule(
            'greaterThanOrEqual',
            $value,
            $message,
            fn (mixed $input): bool => is_numeric($input) && (float) $input >= $value
        );
    }

    /**
     * Validates that the current numeric field is less than the given value.
     *
     * @param int|float $value Comparison value.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function lessThan(int|float $value, ?string $message = null): self
    {
        return $this->addRule(
            'lessThan',
            $value,
            $message,
            fn (mixed $input): bool => is_numeric($input) && (float) $input < $value
        );
    }

    /**
     * Validates that the current numeric field is less than or equal to the given value.
     *
     * @param int|float $value Comparison value.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function lessThanOrEqual(int|float $value, ?string $message = null): self
    {
        return $this->addRule(
            'lessThanOrEqual',
            $value,
            $message,
            fn (mixed $input): bool => is_numeric($input) && (float) $input <= $value
        );
    }

    /**
     * Validates that the current field value is in the allowed values.
     *
     * @param array<int|string, mixed> $allowed Allowed values.
     * @param string|null $message Optional custom error message.
     * @param bool $strict Whether to use strict comparison.
     * @return self
     */
    public function in(array $allowed, ?string $message = null, bool $strict = false): self
    {
        return $this->addRule(
            'in',
            ['allowed' => $allowed],
            $message,
            fn (mixed $value): bool => in_array($value, $allowed, $strict)
        );
    }

    /**
     * Validates that the current field value is not in the blocked values.
     *
     * @param array<int|string, mixed> $blocked Blocked values.
     * @param string|null $message Optional custom error message.
     * @param bool $strict Whether to use strict comparison.
     * @return self
     */
    public function notIn(array $blocked, ?string $message = null, bool $strict = false): self
    {
        return $this->addRule(
            'notIn',
            ['blocked' => $blocked],
            $message,
            fn (mixed $value): bool => !in_array($value, $blocked, $strict)
        );
    }

    /**
     * Validates that the current field has the same value as another field.
     *
     * @param string $otherField Other field name.
     * @param string|null $message Optional custom error message.
     * @param bool $strict Whether to use strict comparison.
     * @return self
     */
    public function sameAs(string $otherField, ?string $message = null, bool $strict = true): self
    {
        return $this->addRule(
            'sameAs',
            ['other' => $otherField],
            $message,
            function (mixed $value, Request $request) use ($otherField, $strict): bool {
                $otherValue = $request->value($otherField);

                return $strict
                    ? $value === $otherValue
                    : $value == $otherValue;
            }
        );
    }

    /**
     * Validates that the current field has a different value than another field.
     *
     * @param string $otherField Other field name.
     * @param string|null $message Optional custom error message.
     * @param bool $strict Whether to use strict comparison.
     * @return self
     */
    public function differentFrom(string $otherField, ?string $message = null, bool $strict = true): self
    {
        return $this->addRule(
            'differentFrom',
            ['other' => $otherField],
            $message,
            function (mixed $value, Request $request) use ($otherField, $strict): bool {
                $otherValue = $request->value($otherField);

                return $strict
                    ? $value !== $otherValue
                    : $value != $otherValue;
            }
        );
    }

    /**
     * Requires the current field when another field has the expected value.
     *
     * @param string $otherField Other field name.
     * @param mixed $expectedValue Expected value of the other field.
     * @param string|null $message Optional custom error message.
     * @param bool $strict Whether to use strict comparison.
     * @return self
     */
    public function requiredIf(
        string $otherField,
        mixed $expectedValue,
        ?string $message = null,
        bool $strict = false
    ): self {
        return $this->addRule(
            'requiredIf',
            ['other' => $otherField, 'value' => $expectedValue],
            $message,
            function (mixed $value, Request $request) use ($otherField, $expectedValue, $strict): bool {
                $otherValue = $request->value($otherField);

                $matches = $strict
                    ? $otherValue === $expectedValue
                    : $otherValue == $expectedValue;

                if (!$matches) {
                    return true;
                }

                return !$this->validator->isEmpty($value);
            }
        );
    }

    /**
     * Requires the current field when another field is not empty.
     *
     * @param string $otherField Other field name.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function requiredWith(string $otherField, ?string $message = null): self
    {
        return $this->addRule(
            'requiredWith',
            ['other' => $otherField],
            $message,
            function (mixed $value, Request $request) use ($otherField): bool {
                if ($this->validator->isEmpty($request->value($otherField))) {
                    return true;
                }

                return !$this->validator->isEmpty($value);
            }
        );
    }

    /**
     * Requires the current field when another field is empty.
     *
     * @param string $otherField Other field name.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function requiredWithout(string $otherField, ?string $message = null): self
    {
        return $this->addRule(
            'requiredWithout',
            ['other' => $otherField],
            $message,
            function (mixed $value, Request $request) use ($otherField): bool {
                if (!$this->validator->isEmpty($request->value($otherField))) {
                    return true;
                }

                return !$this->validator->isEmpty($value);
            }
        );
    }

    /**
     * Validates that the current field is accepted.
     *
     * Accepted values include true, 1, "1", "true", "yes" and "on".
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function accepted(?string $message = null): self
    {
        return $this->addRule(
            'accepted',
            null,
            $message,
            fn (mixed $value): bool => $this->isAcceptedValue($value)
        );
    }

    /**
     * Validates that the current field is declined.
     *
     * Declined values include false, 0, "0", "false", "no" and "off".
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function declined(?string $message = null): self
    {
        return $this->addRule(
            'declined',
            null,
            $message,
            fn (mixed $value): bool => $this->isDeclinedValue($value)
        );
    }

    /**
     * Validates that the current file field contains successfully uploaded file data.
     *
     * This rule is intended for validators created through Validator::file().
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function isFile(?string $message = null): self
    {
        return $this->addRule(
            'file',
            null,
            $message,
            function (mixed $value): bool|string {
                $files = $this->filesFrom($value);

                if ($files === []) {
                    return false;
                }

                return $this->filesAreOk($files);
            }
        );
    }

    /**
     * Validates maximum uploaded file size in kilobytes.
     *
     * Applies to one file or multiple files.
     *
     * @param int $kilobytes Maximum file size in kilobytes.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function maxFileSize(int $kilobytes, ?string $message = null): self
    {
        return $this->addRule(
            'maxFileSize',
            ['max' => $kilobytes],
            $message,
            function (mixed $value) use ($kilobytes): bool|string {
                $files = $this->filesFrom($value);

                if ($files === []) {
                    return false;
                }

                $filesOk = $this->filesAreOk($files);

                if ($filesOk !== true) {
                    return $filesOk;
                }

                $maxBytes = $kilobytes * 1024;

                foreach ($files as $file) {
                    if ($file->getSize() > $maxBytes) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * Validates uploaded file MIME type.
     *
     * Wildcard MIME types such as "image/*" are supported.
     *
     * @param string[] $mimeTypes Allowed MIME types.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function mimeType(array $mimeTypes, ?string $message = null): self
    {
        return $this->addRule(
            'mimeType',
            ['allowed' => $mimeTypes],
            $message,
            function (mixed $value) use ($mimeTypes): bool|string {
                $files = $this->filesFrom($value);

                if ($files === []) {
                    return false;
                }

                $filesOk = $this->filesAreOk($files);

                if ($filesOk !== true) {
                    return $filesOk;
                }

                foreach ($files as $file) {
                    if (!$this->matchesMimeType($file->getType(), $mimeTypes)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * Validates uploaded file extension.
     *
     * Extensions can be provided with or without a leading dot.
     *
     * @param string[] $extensions Allowed file extensions.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function extension(array $extensions, ?string $message = null): self
    {
        return $this->addRule(
            'extension',
            ['allowed' => $extensions],
            $message,
            function (mixed $value) use ($extensions): bool|string {
                $files = $this->filesFrom($value);

                if ($files === []) {
                    return false;
                }

                $filesOk = $this->filesAreOk($files);

                if ($filesOk !== true) {
                    return $filesOk;
                }

                $allowed = array_map(
                    fn (string $extension): string => strtolower(ltrim($extension, '.')),
                    $extensions
                );

                foreach ($files as $file) {
                    $extension = strtolower(pathinfo($file->getName(), PATHINFO_EXTENSION));

                    if (!in_array($extension, $allowed, true)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * Validates that uploaded files are images.
     *
     * The file content is checked using getimagesize().
     *
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function image(?string $message = null): self
    {
        return $this->addRule(
            'image',
            null,
            $message,
            function (mixed $value): bool|string {
                $files = $this->filesFrom($value);

                if ($files === []) {
                    return false;
                }

                $filesOk = $this->filesAreOk($files);

                if ($filesOk !== true) {
                    return $filesOk;
                }

                foreach ($files as $file) {
                    if (@getimagesize($file->getFileTempPath()) === false) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * Adds a custom validation rule.
     *
     * Callback may return:
     * - true: valid
     * - false: invalid, default or provided message will be used
     * - string: invalid, returned string will be used as the error message
     *
     * The callback receives the current value, request instance and field name.
     *
     * @param callable $callback Custom validation callback.
     * @param string|null $message Optional custom error message.
     * @return self
     */
    public function custom(callable $callback, ?string $message = null): self
    {
        return $this->addRule(
            'custom',
            null,
            $message,
            fn (mixed $value, Request $request, string $field): bool|string => $callback($value, $request, $field)
        );
    }

    /**
     * Adds a validation rule for the current field.
     *
     * @param string $name Rule name.
     * @param mixed $expected Expected value or rule metadata.
     * @param string|null $message Optional custom error message.
     * @param callable $callback Validation callback.
     * @return self
     */
    private function addRule(
        string $name,
        mixed $expected,
        ?string $message,
        callable $callback
    ): self {
        $this->validator->addRule(
            $name,
            $this->field,
            $expected,
            $message,
            $callback,
            $this->source
        );

        return $this;
    }

    /**
     * Checks whether a value can be interpreted as a boolean.
     *
     * @param mixed $value Value to check.
     * @return bool True if the value is boolean-like.
     */
    private function isBooleanValue(mixed $value): bool
    {
        if (is_bool($value)) {
            return true;
        }

        if (is_int($value)) {
            return in_array($value, [0, 1], true);
        }

        if (is_string($value)) {
            return in_array(strtolower(trim($value)), [
                '1',
                '0',
                'true',
                'false',
                'yes',
                'no',
                'on',
                'off',
            ], true);
        }

        return false;
    }

    /**
     * Checks whether a value is considered accepted.
     *
     * @param mixed $value Value to check.
     * @return bool True if the value is accepted.
     */
    private function isAcceptedValue(mixed $value): bool
    {
        if ($value === true || $value === 1) {
            return true;
        }

        if (is_string($value)) {
            return in_array(strtolower(trim($value)), [
                '1',
                'true',
                'yes',
                'on',
            ], true);
        }

        return false;
    }

    /**
     * Checks whether a value is considered declined.
     *
     * @param mixed $value Value to check.
     * @return bool True if the value is declined.
     */
    private function isDeclinedValue(mixed $value): bool
    {
        if ($value === false || $value === 0) {
            return true;
        }

        if (is_string($value)) {
            return in_array(strtolower(trim($value)), [
                '0',
                'false',
                'no',
                'off',
            ], true);
        }

        return false;
    }

    /**
     * Extracts UploadedFile instances from a value.
     *
     * Supports a single UploadedFile instance or nested arrays of UploadedFile instances.
     *
     * @param mixed $value Value returned by Request::file().
     * @return UploadedFile[]
     */
    private function filesFrom(mixed $value): array
    {
        if ($value instanceof UploadedFile) {
            return [$value];
        }

        if (!is_array($value)) {
            return [];
        }

        $files = [];

        foreach ($value as $item) {
            if ($item instanceof UploadedFile) {
                $files[] = $item;
                continue;
            }

            if (is_array($item)) {
                $files = array_merge($files, $this->filesFrom($item));
            }
        }

        return $files;
    }

    /**
     * Checks whether all uploaded files were uploaded successfully.
     *
     * Returns true when all files are OK.
     * Returns an upload error message if any file failed.
     *
     * @param UploadedFile[] $files Uploaded files.
     * @return bool|string True if files are OK, otherwise an error message or false.
     */
    private function filesAreOk(array $files): bool|string
    {
        foreach ($files as $file) {
            if (!$file->isOk()) {
                return $file->getErrorMessage() ?? false;
            }
        }

        return true;
    }

    /**
     * Checks whether an actual MIME type matches allowed MIME types.
     *
     * Supports exact matches and wildcard matches such as "image/*".
     *
     * @param string $actualMimeType Actual file MIME type.
     * @param string[] $allowedMimeTypes Allowed MIME types.
     * @return bool True if the MIME type is allowed.
     */
    private function matchesMimeType(string $actualMimeType, array $allowedMimeTypes): bool
    {
        $actualMimeType = strtolower(trim($actualMimeType));

        foreach ($allowedMimeTypes as $allowedMimeType) {
            $allowedMimeType = strtolower(trim($allowedMimeType));

            if ($allowedMimeType === $actualMimeType) {
                return true;
            }

            if (str_ends_with($allowedMimeType, '/*')) {
                $prefix = substr($allowedMimeType, 0, -1);

                if (str_starts_with($actualMimeType, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }
}