<?php

/**
 * Renders object as json.
 *
 * @name
 * @author Tobias Sarnowski
 */
class JsonRenderer {

    /**
     * Prints out the rendered content.
     *
     * @param mixed $data
     * @return void
     */
    public function render($data) {
        echo json_encode($data);
    }
}
