<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\DBAL\Migrations\CommandBus\Repository;

use Baleen\Cli\CommandBus\Repository\CreateHandler as BaseCreateHandler;
use Doctrine\DBAL\Migrations\Migration\AbstractMigration;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class CreateHandler
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class CreateHandler extends BaseCreateHandler
{
    /**
     * Generate.
     *
     * @param string $className
     * @param null   $namespace
     *
     * @return \Zend\Code\Generator\ClassGenerator
     */
    protected function generate($className, $namespace = null)
    {
        $class = new ClassGenerator(
            $className,
            $namespace,
            null,
            'SimpleMigration',
            [],
            [],
            [
                new MethodGenerator('up', [], 'public', 'echo \'Hello world!\';'),
                new MethodGenerator('down', [], 'public', 'echo \'Goodbye world!\';'),
            ]
        );
        $class->setExtendedClass('AbstractMigration');
        $class->addUse(AbstractMigration::class);

        return $class;
    }
}
