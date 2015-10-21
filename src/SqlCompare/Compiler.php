<?php

namespace SqlCompare;

use Symfony\Component\Finder\Finder;

class Compiler
{
    const OUTPUT_FILENAME = 'sql-compare.phar';

    /**
     * Compiles application to phar file
     */
    public function compile()
    {
        if (file_exists(self::OUTPUT_FILENAME)) {
            unlink(self::OUTPUT_FILENAME);
        }

        $phar = new \Phar(self::OUTPUT_FILENAME, 0, 'sql-compare.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA1);

        $phar->startBuffering();

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->notName('Compiler.php')
            ->in(__DIR__ . '/..')
            ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->name('LICENSE')
            ->exclude('Tests')
            ->exclude('tests')
            ->exclude('docs')
            ->in(__DIR__ . '/../../vendor/symfony/')
            ->in(__DIR__ . '/../../vendor/doctrine/')
            ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/autoload.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/autoload_namespaces.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/autoload_psr4.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/autoload_classmap.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/autoload_real.php'));

        if (file_exists(__DIR__ . '/../../vendor/composer/include_paths.php')) {
            $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/include_paths.php'));
        }

        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/ClassLoader.php'));
        $this->addBin($phar);

        $phar->setStub($this->getStub());
        $phar->stopBuffering();
    }


    private function addFile($phar, $file)
    {
        $path = strtr(str_replace(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR, '', $file->getRealPath()), '\\', '/');

        $phar->addFromString($path, file_get_contents($path));
    }

    private function addBin($phar)
    {
        $content = file_get_contents(__DIR__.'/../../bin/sql-compare');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $phar->addFromString('bin/sql-compare', $content);
    }

    /**
     * Get PHAR stub
     */
    private function getStub()
    {
        return <<<'EOF'
#!/usr/bin/env php
<?php
require 'phar://sql-compare.phar/bin/sql-compare';
__HALT_COMPILER();
EOF;
    }
}
