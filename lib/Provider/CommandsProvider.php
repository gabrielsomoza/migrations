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

use Baleen\Cli\Container\ServiceProvider\CommandsProvider as BaseCommandsProvider;
use Baleen\Cli\Container\Services;
use Doctrine\DBAL\Migrations\CommandBus\Repository\CreateHandler;
use Doctrine\DBAL\Migrations\CommandBus\Repository\CreateMessage;
use Doctrine\DBAL\Migrations\CommandBus\Timeline\ExecuteMessage;
use Doctrine\DBAL\Migrations\CommandBus\Timeline\MigrateHandler;
use Doctrine\DBAL\Migrations\CommandBus\Timeline\MigrateMessage;

/**
 * Class CommandsProvider
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class CommandsProvider extends BaseCommandsProvider
{
    /**
     * __construct
     */
    public function __construct()
    {
        $this->commands[Services::CMD_REPOSITORY_CREATE]= [
            'class' => CreateMessage::class,
            'handler' => CreateHandler::class,
        ];
        $this->commands[Services::CMD_TIMELINE_EXECUTE]['class'] = ExecuteMessage::class;
        $this->commands[Services::CMD_TIMELINE_MIGRATE] = [
            'class' => MigrateMessage::class,
            'handler' => MigrateHandler::class,
        ];
        BaseCommandsProvider::__construct();
    }
}
