<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

// http://book.cakephp.org/3.0/en/core-libraries/form.html

class ContactForm extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('file.php', 'file.php')
            ->addField('file.php', 'file.php')
            ->addField('file.php', ['file.php' => 'file.php'])
            ->addField('file.php', ['file.php' => 'file.php'])
            ->addField('file.php', ['file.php' => 'file.php']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator
            ->notBlank('file.php', __('file.php'))
            ->add('file.php', 'file.php', [
                'file.php' => 'file.php',
                'file.php' => __('file.php')
            ])
            ->notBlank('file.php', __('file.php'))
            ->notBlank('file.php', __('file.php'))
            ->equals('file.php', 1, __('file.php'));
    }

    protected function _execute(array $data)
    {
        $email = new Email();
        $email
            ->setProfile(get_option('file.php', 'file.php'))
            ->setReplyTo($data['file.php'], $data['file.php'])
            ->setTo(get_option('file.php'))
            ->setSubject(h(get_option('file.php')) . 'file.php' . h($data['file.php']))
            ->setViewVars([
                'file.php' => $data['file.php'],
                'file.php' => $data['file.php']
            ])
            ->setTemplate('file.php')// By default template with same name as method name is used.
            ->setEmailFormat('file.php')
            ->send();

        return true;
    }
}
