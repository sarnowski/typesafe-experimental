<?php

/**
 * Welcome controller.
 *
 * @name
 * @author Tobias Sarnowski
 */
class ExampleController {

    /**
     * @inject
     * @var PhpRenderer
     */
    var $phpRenderer;

    /**
     *
     * @GET
     * @return string
     */
    public function welcome() {
        $this->phpRenderer->render('views/welcome.xhtml');
    }

}
