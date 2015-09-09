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
namespace Doctrine\DBAL\Migrations\Providers;
use Baleen\Cli\Container\Services;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Migrations\CommandBus\Util\ObjectManagerAwareInterface;
use Doctrine\DBAL\Migrations\Config\Config;
use Doctrine\DBAL\Migrations\Entity\Version;
use Doctrine\DBAL\Migrations\Exception\CliException;
use Doctrine\DBAL\Migrations\Helper\LazyObjectManagerHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use League\Container\ServiceProvider;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class DoctrineProvider
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class DoctrineProvider extends ServiceProvider
{
    const SERVICE_OBJECT_MANAGER = 'doctrine.migrations.object_manager';
    const SERVICE_DEFAULT_OBJECT_MANAGER = 'doctrine.migrations.entity_manager.default';
    const SERVICE_CONNECTION = 'doctrine.migrations.connection';
    const SERVICE_VERSIONS_REPOSITORY = 'doctrine.migrations.repository.versions';

    protected $provides = [
        self::SERVICE_CONNECTION,
        self::SERVICE_OBJECT_MANAGER,
        self::SERVICE_VERSIONS_REPOSITORY,
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

        $container->singleton(self::SERVICE_DEFAULT_OBJECT_MANAGER, function(Config $config) {
            $paths = array(
                realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, "/../Entity"]))
            );

            // the connection configuration
            $dbParams = $config->getConnectionParams();

            if (empty($dbParams)) {
                // TODO: show a message asking user to init
                throw new CliException('Please configure doctrine migrations by running the "init" command.');
            }

            $config = Setup::createAnnotationMetadataConfiguration($paths, true, null, null, false);
            $em = EntityManager::create($dbParams, $config);

            return $em;
        })->withArgument(Services::CONFIG);

        $container->singleton(self::SERVICE_CONNECTION, function (HelperSet $helperSet) {
            /** @var \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper $connection */
            $connection = $helperSet->get('db');
            return $connection ? $connection->getConnection() : null;
        })->withArgument(Services::HELPERSET);

        $container->singleton(self::SERVICE_OBJECT_MANAGER, function (HelperSet $helperSet) {
            /** @var LazyObjectManagerHelper $em */
            $em = $helperSet->get('em');
            return $em ? $em->getObjectManager() : null;
        })->withArgument(Services::HELPERSET);

        $container->singleton(
            self::SERVICE_VERSIONS_REPOSITORY,
            function (ObjectManager $om) {
                $repository = $om->getRepository(Version::class);
                return $repository;
            }
        )->withArgument(DoctrineProvider::SERVICE_OBJECT_MANAGER);
    }
}
