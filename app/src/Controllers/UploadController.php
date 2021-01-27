<?php
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;

class UploadController extends ContentController
{
    private static $allowed_actions = [
        'uploads/form'
    ];

    public function HelloForm()
    {
        $fields = new FieldList(
            TextField::create('Name', 'Your Name')
        );

        $actions = new FieldList(
            FormAction::create('doSayHello')->setTitle('Say hello')
        );

        $required = new RequiredFields('Name');

        $form = new Form($this, 'HelloForm', $fields, $actions, $required);

        return $form;
    }

    public function doSayHello($data, Form $form)
    {
        $form->sessionMessage('Hello ' . $data['Name'], 'success');

        return $this->redirectBack();
    }
}
