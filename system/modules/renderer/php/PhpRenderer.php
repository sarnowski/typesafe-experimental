<?php

/**
 * Uses php files to render content.
 *
 * @name
 * @author Tobias Sarnowski
 */
class PhpRenderer {

    /**
     * Includes the given file.
     *
     * @param string $file
     * @param array $arguments
     * @return void
     */
    public function render($file, $arguments = null) {
        if (is_array($arguments)) {
            extract($arguments);
        }
        include(__DIR__.'/../../../../'.$file);
    }
}
