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

namespace Doctrine\DBAL\Migrations\Migration;

use Baleen\Migrations\Migration\Capabilities\TransactionAwareInterface;
use Baleen\Migrations\Migration\SimpleMigration;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AbstractMigration
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
abstract class AbstractMigration extends SimpleMigration implements TransactionAwareInterface
{

    /** @var ObjectManager */
    protected $om;

    /**
     * AbstractMigration constructor.
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }


    /**
     * setObjectManager
     * @param ObjectManager $om
     */
    public function setObjectManager(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * getObjectManager
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->om;
    }

    /**
     * begin
     */
    public function begin()
    {
        if ($this->isConnected()) {
            $this->getConnection()->beginTransaction();
        }
    }

    /**
     * finish
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function finish()
    {
        if ($this->isTransactionActive()) {
            if ($this->getOptions()->isDryRun()) {
                $this->getConnection()->rollBack();
            } else {
                $this->getObjectManager()->flush();
                $this->getConnection()->commit();
            }
        }
    }

    /**
     * abort
     * @param \Exception $e
     * @return mixed|void
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public function abort(\Exception $e)
    {
        if ($this->isTransactionActive()) {
            $this->getConnection()->rollBack();
        }
        throw $e;
    }

    /**
     * isConnected
     * @return bool
     */
    private function isConnected()
    {
        return $this->om instanceof EntityManagerInterface
            && $this->om->getConnection()
            && $this->om->getConnection()->isConnected();
    }

    /**
     * isTransactionActive
     * @return bool
     */
    private function isTransactionActive()
    {
        $active = false;
        $conn = $this->getConnection();
        if (null !== $conn) {
            $active = $conn->isTransactionActive();
        }
        return $active;
    }

    /**
     * getConnection
     * @return \Doctrine\DBAL\Connection|null
     */
    protected function getConnection()
    {
        $connection = null;
        if ($this->isConnected()) {
            /** @var EntityManager $om */
            $om = $this->om;
            return $om->getConnection();
        }
        return $connection;
    }

}
