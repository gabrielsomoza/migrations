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

use Baleen\Cli\Application as BaseApplication;
use Baleen\Cli\Command\AbstractCommand;
use Baleen\Cli\Command\InitCommand;
use Baleen\Cli\Command\Repository\AbstractRepositoryCommand;
use Baleen\Cli\Command\Storage\AbstractStorageCommand;
use Baleen\Cli\Command\Timeline\AbstractTimelineCommand;
use Baleen\Cli\Container\ServiceProvider\DefaultProvider;
use Baleen\Cli\Container\Services;
use Baleen\Migrations\Version\Comparator\DefaultComparator;
use Doctrine\DBAL\Migrations\Application;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class ApplicationProvider
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class ApplicationProvider extends DefaultProvider
{
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

        $container->singleton(Services::APPLICATION, function(array $commands, HelperSet $helperSet) {
            return new Application($commands, $helperSet);
        })->withArguments([
            Services::COMMANDS,
            Services::HELPERSET,
        ]);

        parent::register(); // won't register application again
    }
}
