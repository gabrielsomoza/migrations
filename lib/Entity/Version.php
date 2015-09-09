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

namespace Doctrine\DBAL\Migrations\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Version. Please note that this entity has been forced to map to a table named "migrations" for several
 * reasons:
 *   - We're "invading" the user's database with a new table. A table named "migrations" is less likely to conflict
 *     with any existing tables than one named "versions".
 *   - We're "invading" the user's domain language with new terms. A table named "migrations" communicates its purpose
 *     better than one called "versions" (its purpose is less ambiguous).
 *   - Doctrine Migrations 1.* used "migrations" for the table name as well.
 *
 * The user has the ability to supply their own entity though instead of this one, which would be a good option if they
 * really need the table or entity named differently.
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 *
 * @ORM\Entity
 * @ORM\Table(name="migrations")
 */
class Version implements VersionInterface
{

    /**
     * @ORM\Column(type="string")
     * @ORM\Id()
     * @var string
     */
    protected $id;

    /**
     * Version constructor.
     * @param $id
     */
    public function __construct($id = null)
    {
        $this->id = (string) $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = (string) $id;
    }
}
