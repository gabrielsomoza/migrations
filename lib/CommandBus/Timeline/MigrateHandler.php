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
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\DBAL\Migrations\CommandBus\Timeline;

use Baleen\Cli\CommandBus\Timeline\MigrateHandler as BaseMigrateHandler;
use Baleen\Migrations\Event\Timeline\CollectionEvent;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MigrateHandler
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class MigrateHandler extends BaseMigrateHandler
{
    /**
     * onCollectionBefore
     *
     * @param CollectionEvent $event
     */
    public function onCollectionBefore(CollectionEvent $event)
    {
        parent::onCollectionBefore($event);

        if ($event->getOptions()->isDryRun()) {
            /** @var MigrateMessage $command */
            $command = $this->command;
            $this->setLogger($command);
        }
    }

    /**
     * onCollectionAfter
     */
    public function onCollectionAfter()
    {
        parent::onCollectionAfter();

        /** @var MigrateMessage $command */
        $command = $this->command;
        $om = $command->getObjectManager();
        if ($om instanceof EntityManagerInterface) {
            $logger = $om->getConfiguration()->getSQLLogger();
            if ($logger instanceof DebugStack && $logger->enabled) {
                $queries = print_r($logger->queries, true);
                $command->getOutput()->write($queries);
            }
        }
    }

    /**
     * setLogger
     *
     * @param MigrateMessage $command
     */
    protected function setLogger(MigrateMessage $command)
    {
        $om = $command->getObjectManager();
        if ($om instanceof EntityManagerInterface) {
            $config = $om->getConfiguration();
            if (null === $config->getSQLLogger()) {
                $logger = new DebugStack();
                $config->setSQLLogger($logger);
            }
        }
    }
}
