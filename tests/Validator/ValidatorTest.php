<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Lightpack\Validator\Validator;

final class ValidatorTest extends TestCase
{
    public function testHasValidationErrors()
    {
        $data = [
            'name' => 'ma',
            'email' => 'hello@world',
        ];

        $rules = [
            'name' => 'required|min:3|max:12',
            'email' => 'required|email'
        ];

        $validator = new Validator($data);
        $validator->setrules($rules);

        $this->assertTrue($validator->hasErrors() === false);
    }

    public function testHasNoValidationErrors()
    {
        $data = [
            'name' => 'maxim',
            'email' => 'hello@world',
        ];

        $rules = [
            'name' => 'required|min:3|max:12',
            'email' => 'required|email'
        ];

        $validator = new Validator($data);
        $validator->setrules($rules)->run();

        $this->assertTrue($validator->hasErrors());
    }

    public function testCreatesAppropriateErrorMessages()
    {
        $data = ['password'  => 'hello', 'email' => 'hello@example.com'];
        $rules = ['password' => 'min:6', 'email' => 'email'];

        $validator = new Validator($data);
        $validator->setRules($rules)->run();

        $this->assertTrue(count($validator->getErrors()) === 1);
        $this->assertTrue($validator->getError('email') === '');
        $this->assertTrue($validator->getError('password') !== '');
    }

    public function testCanSetRulesIndividually()
    {
        $validator = new Validator([
            'phone' => '091234521',
            'fname' => 'Bob123',
        ]);

        $validator
            ->setRule('phone', 'required|length:10')
            ->setRule('fname', [
                'rules' => 'required|alpha',
                'label' => 'First name',
            ])
            ->run();

        $errors = $validator->getErrors();

        $this->assertTrue($validator->hasErrors());
        $this->assertTrue(isset($errors['fname']));
        $this->assertTrue(isset($errors['fname']));
    }
}
