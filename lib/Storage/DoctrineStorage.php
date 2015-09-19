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

namespace Doctrine\DBAL\Migrations\Storage;

use Baleen\Migrations\Exception\StorageException;
use Baleen\Migrations\Repository\RepositoryInterface;
use Baleen\Migrations\Storage\AbstractStorage;
use Baleen\Migrations\Version;
use Baleen\Migrations\Version\Collection\MigratedVersions;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Migrations\Entity\VersionEntityFactoryInterface as VersionFactory;
use Doctrine\DBAL\Migrations\Entity\VersionInterface;

/**
 * Class DoctrineStorage
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class DoctrineStorage extends AbstractStorage
{
    /** @var ObjectRepository */
    protected $repository;
    /** @var ObjectManager */
    protected $om;
    /** @var VersionFactory */
    protected $versionFactory;

    /**
     * @param VersionFactory   $versionFactory
     * @param ObjectManager    $om
     * @param ObjectRepository $repository
     */
    public function __construct(VersionFactory $versionFactory, ObjectManager $om, ObjectRepository $repository)
    {
        $this->versionFactory = $versionFactory;
        $this->setObjectManager($om);
        $this->setRepository($repository);
    }

    /**
     * @return Version[]
     */
    protected function doFetchAll()
    {
        $items = $this->repository->findAll();

        return array_map(
            function (VersionInterface $item) {
                return new Version($item->getId());
            },
            $items
        );
    }

    /**
     * Write a collection of versions to the storage file.
     *
     * @param MigratedVersions $versions
     *
     * @return bool True unless there's an unhandled exception.
     * @throws \Exception
     */
    public function saveCollection(MigratedVersions $versions)
    {
        foreach ($versions as $version) {
            try {
                $this->save($version, false); // save but do not flush
            } catch (StorageException $e) {
                continue; // skip to the next one (statement is unnecessary but more readable than nothing)
            }
        }
        $this->om->flush();

        return true;
    }

    /**
     * Adds a version into storage
     *
     * @param Version $version
     * @param bool    $flush
     *
     * @return VersionInterface Returns the saved entity or false
     * @throws StorageException
     */
    public function save(Version $version, $flush = true)
    {
        if ($this->repository->find($version->getId())) {
            throw new StorageException(
                sprintf(
                    'Version with id "%s" already exists.',
                    $version->getId()
                )
            );
        }

        /** @var VersionInterface $entity */
        $entity = $this->versionFactory->create($version->getId());
        $this->om->persist($entity);

        if ($flush) {
            $this->om->flush();
        }

        return $entity;
    }

    /**
     * Removes a version from storage
     *
     * @param Version $version
     * @param bool    $flush
     *
     * @return VersionInterface The deleted entity
     * @throws StorageException
     */
    public function delete(Version $version, $flush = false)
    {
        $entity = $this->repository->find($version->getId());
        if (!$entity) {
            throw new StorageException(
                sprintf(
                    'Could not find a version with id "%s" in repository.',
                    $version->getId()
                )
            );
        }
        $this->om->remove($entity);;

        if ($flush) {
            $this->om->flush();
        }

        return $entity;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->om;
    }

    /**
     * @param ObjectManager $om
     */
    public function setObjectManager(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param ObjectRepository $repository
     */
    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }
}
