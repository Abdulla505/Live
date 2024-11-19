<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

// http://book.cakephp.org/3.0/en/core-libraries/form.html

class MessageUserForm extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('file.php', ['file.php' => 'file.php'])
            ->addField('file.php', 'file.php')
            ->addField('file.php', ['file.php' => 'file.php']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator
            ->add('file.php', 'file.php', [
                'file.php' => 'file.php',
                'file.php' => __('file.php')
            ])
            ->notEmpty('file.php', __('file.php'))
            ->notEmpty('file.php', __('file.php'));
    }

    protected function _execute(array $data)
    {
        $email = new Email();
        $email
            ->profile(get_option('file.php', 'file.php'))
            ->replyTo(get_option('file.php'), h(get_option('file.php')))
            ->to($data['file.php'])
            ->subject(h($data['file.php']))
            ->viewVars([
                'file.php' => $data['file.php']
            ])
            ->template('file.php')// By default template with same name as method name is used.
            ->emailFormat('file.php')
            ->send();

        return true;
    }
}
