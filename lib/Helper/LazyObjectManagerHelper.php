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

namespace Doctrine\DBAL\Migrations\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use League\Container\Container;
use Symfony\Component\Console\Helper\Helper;

/**
 * Class LazyObjectManagerHelper
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class LazyObjectManagerHelper extends Helper
{
    /** @var string */
    protected $serviceName;
    /** @var Container */
    protected $container;

    /**
     * @param Container $container
     * @param           $serviceName
     */
    public function __construct(Container $container, $serviceName)
    {
        $this->serviceName = $serviceName;
        $this->container = $container;
    }

    /**
     * getEntityManager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->container->get($this->serviceName);
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return 'om';
    }
}
