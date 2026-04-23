<?php

namespace Acre\A25\Payments;

abstract class PaymentForm extends Renderable
{
    protected $form;
    abstract protected function setHeading();
    abstract protected function setFooter();
    abstract protected function getElements();

    public function amount()
    {
        return $_POST['x_amount'];
    }

    public function run()
    {
        $this->head();
        $this->makeFormElements();

        if ($this->checkFormAndRenderConfirmation()) {
            return;
        }

        $this->preFormContent();
        $this->renderForm();

        $renderer = new PaymentFormRenderer();
        $renderer->render($this);

        $this->postFormContent();
    }


    protected function head()
    {
        $head = \A25_DI::HtmlHead();
        $head->append('<style type="text/css">
          input {
            font-size: 14px;
          }
          .ui-widget-overlay{
            opacity: .7;
            background: black;
          }
        </style>');
    }

    protected function makeFormElements()
    {
        $this->instantiateForm();
        $this->form->addElements($this->getElements());
        \A25_Form::disableSubmitAfterSubmission();
    }

    protected function instantiateForm()
    {
        $this->form = new \Zend_Form();
    }

    protected function preFormContent()
    {
        // subclasses may utilize if they wish
    }

    protected function renderForm()
    {
        $this->setDecorators();
        $this->setHeading();
        $this->setFooter();
        $view = new \Zend_View();
        $view->setScriptPath(dirname(__FILE__));
        $this->output .= $this->form->render($view);
    }

    protected function setDecorators()
    {
        // subclasses may set decorators if they wish
    }

    protected function postFormContent()
    {
        // subclasses may utilize if they wish
    }

    protected function checkFormAndRenderConfirmation()
    {
        if ($_POST) {
            $this->form->populate($_POST);
            if ($this->form->isValid($_POST)) {
                $this->renderConfirmation();
                return true;
            }
        }
        return false;
    }

    abstract protected function renderConfirmation();

    protected function fireTopOfPaymentForm()
    {
        $return = array();
        foreach (\A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \A25_ListenerI_TopOfPaymentForm) {
                $return = array_merge($return, $listener->topOfPaymentForm()->elements());
            }
        }
        return $return;
    }

    protected function fireAppendPaymentForm()
    {
        $return = array();
        foreach (\A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \A25_ListenerI_AppendPaymentForm) {
                $return = array_merge($return, $listener->appendPaymentForm()->elements());
            }
        }
        return $return;
    }
}
