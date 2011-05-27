<?php

/**
 * Parses all managed objects for path informations.
 *
 * @author Tobias Sarnowski
 */
class RestExtension implements ClassAnalyzerExtension {

    /**
     * @var MobManager
     */
    private $mobManager;

    /**
     * @var array list of http methods to a list of paths
     */
    private $methods = array();

    /**
     * Will be invoked before any other extension method will be called.
     *
     * @param MobManager $mobManager
     * @return void
     */
    public function setMobManager(MobManager $mobManager) {
        $this->mobManager = $mobManager;
    }

    /**
     * Called to analyze a loaded managed object class.
     *
     * @param string $name
     * @param ReflectionClass $class
     * @return void
     */
    public function analyzeClass($name, ReflectionClass $class) {
        // get path prefix
        $annotatedClass = $this->mobManager->getAnnotationParser()->getAnnotatedClass($class);
        if ($annotatedClass->hasAnnotation('path')) {
            $pathPrefix = $annotatedClass->getAnnotation('path')->getPayload();
        } else {
            $pathPrefix = '/';
        }
        $pathPrefix = $this->normalizePath($pathPrefix);

        // search for path methods
        foreach ($class->getMethods() as $method) {
            $annotatedMethod = $this->mobManager->getAnnotationParser()->getAnnotatedMethod($method);
            if ($annotatedMethod->hasAnnotation('path')) {
                $path = $annotatedMethod->getAnnotation('path')->getPayload();
            } else {
                $path = '';
            }
            $path = $this->normalizePath($path);

            foreach ($annotatedMethod->getAnnotations() as $annotation) {
                if ($annotation->getName() == strtoupper($annotation->getName())) {
                    // register method
                    $httpMethod = $annotation->getName();
                    if (!isset($this->methods[$httpMethod])) {
                        $this->methods[$httpMethod] = array();
                    }
                    $this->methods[$httpMethod][] = array(
                        'path' => $this->mergePath($pathPrefix, $path),
                        'mob' => $name,
                        'method' => $method
                    );
                }
            }
        }
    }

    private function normalizePath($path) {
        if (empty($path)) {
            return '/';
        }
        if (substr($path, 0, 1) != '/') {
            $path = "/$path";
        }
        return $path;
    }

    private function mergePath($parent, $child) {
        if (substr($parent, -1) == '/') {
            $parent = substr($parent, 0, strlen($parent) - 1);
        }
        return $parent.$child;
    }

    /**
     * A list of all registered methods for this http method.
     *
     * @throws MobException
     * @param string $httpMethod
     * @return array
     */
    public function getMethods($httpMethod) {
        if (!isset($this->methods[$httpMethod])) {
            throw new MobException("HTTP method $httpMethod not used by someone.");
        }
        return $this->methods[$httpMethod];
    }
}
