<?php

namespace JSmart\Validation;

class Validator
{
    /**
     * Create a new Validator instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return Validator
     */
    public function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        //
    }

    /**
     * Run the validator's rules against its data.
     *
     */
    public function validate()
    {
        // редирект обратно если ошибка
    }

    /**
     * Determine if the data passes the validation rules.
     *
     * @return bool
     */
    public function passes(): bool
    {
        // прошла ли валидация
        return true;
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails(): bool
    {
        // не прошла ли валидация
        return false;
    }

    /**
     * Returns the data which was valid.
     *
     * @return array
     */
    public function valid(): array
    {
        // валидные данные
        return [];
    }

    /**
     * Get the failed validation rules.
     *
     * @return array
     */
    public function failed(): array
    {
        // не валидные данные
        return [];
    }

    /**
     * An alternative more semantic shortcut to the message container.
     */
    public function errors()
    {
        // сообщения об ошибках
        return [];
    }
}
