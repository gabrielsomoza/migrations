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

namespace Doctrine\DBAL\Migrations\Provider;

use Baleen\Cli\Container\Services;
use Baleen\Cli\Exception\CliException;
use Baleen\Cli\Helper\ConfigHelper;
use Doctrine\DBAL\Migrations\Helper\LazyObjectManagerHelper;
use League\Container\Container;
use League\Container\ServiceProvider;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * Class HelperSetProvider
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class HelperSetProvider extends ServiceProvider
{

    protected $provides = [
        Services::HELPERSET,
        Services::HELPERSET_QUESTION,
        Services::HELPERSET_CONFIG,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();
        $container->singleton(Services::HELPERSET, function(Container $container) {
            // Support for using the Doctrine ORM convention of providing a `cli-config.php` file.
            $configFile = getcwd() . DIRECTORY_SEPARATOR . 'cli-config.php';

            $helperSet = null;
            if (file_exists($configFile)) {
                if ( !is_readable($configFile) ) {
                    throw new CliException(sprintf(
                        'Configuration file [%s] does not have read permission.',
                        $configFile
                    ));
                }

                $helperSet = require $configFile;

                if ( !$helperSet instanceof HelperSet ) {
                    foreach ($GLOBALS as $helperSetCandidate) {
                        if ($helperSetCandidate instanceof HelperSet) {
                            $helperSet = $helperSetCandidate;
                            break;
                        }
                    }
                }
            } // file exists
            $helperSet = is_object($helperSet) ? $helperSet : new HelperSet();

            if (!$helperSet->has('em')) {
                $helperSet->set(
                    new LazyObjectManagerHelper($container, DoctrineProvider::SERVICE_DEFAULT_OBJECT_MANAGER)
                    , 'em'
                );
            }

            $helperSet->set($container->get(Services::HELPERSET_QUESTION), 'question');
            $helperSet->set($container->get(Services::HELPERSET_CONFIG));

            return $helperSet;
        })->withArgument(Container::class);

        $container->add(Services::HELPERSET_QUESTION, QuestionHelper::class);
        $container->add(Services::HELPERSET_CONFIG, ConfigHelper::class)
            ->withArgument(Services::CONFIG);
    }
}
