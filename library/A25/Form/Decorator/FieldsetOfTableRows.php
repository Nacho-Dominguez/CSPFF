<?php

class A25_Form_Decorator_FieldsetOfTableRows extends Zend_Form_Decorator_Fieldset
{
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $legend        = $this->getLegend();
        $attribs       = $this->getOptions();
        $name          = $element->getFullyQualifiedName();

        $id = $element->getId();
        if (!empty($id)) {
            $attribs['id'] = 'fieldset-' . $id;
        }

        if (null !== $legend) {
            if (null !== ($translator = $element->getTranslator())) {
                $legend = $translator->translate($legend);
            }

            $attribs['legend'] = $legend;
        }

        foreach (array_keys($attribs) as $attrib) {
            $testAttrib = strtolower($attrib);
            if (in_array($testAttrib, $this->stripAttribs)) {
                unset($attribs[$attrib]);
            }
        }

        return $view->fieldsetOfTableRows($name, $content, $attribs);
    }
}
