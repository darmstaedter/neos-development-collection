<?php
namespace Neos\Neos\Domain\Context\Domain;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\EventSourcing\Event\EventPublisher;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Context\Domain\Command\ActivateDomain;
use Neos\Neos\Domain\Context\Domain\Command\AddDomain;
use Neos\Neos\Domain\Context\Domain\Command\DeactivateDomain;
use Neos\Neos\Domain\Context\Domain\Command\DeleteDomain;
use Neos\Neos\Domain\Context\Domain\Event\DomainWasActivated;
use Neos\Neos\Domain\Context\Domain\Event\DomainWasAdded;
use Neos\Neos\Domain\Context\Domain\Event\DomainWasDeactivated;
use Neos\Neos\Domain\Context\Domain\Event\DomainWasDeleted;

/**
 * WorkspaceCommandHandler
 */
final class DomainCommandHandler
{
    /**
     * @Flow\Inject
     * @var EventPublisher
     */
    protected $eventPublisher;

    /**
     * @param AddDomain $command
     */
    public function handleAddDomain(AddDomain $command)
    {
        // TODO: Necessary checks

        $this->eventPublisher->publish(
            'Neos.Neos:Domain:' . $command->getDomainHostname(),
            new DomainWasAdded(
                $command->getSiteNodeName(),
                $command->getDomainHostname(),
                $command->getScheme(),
                $command->getPort()
            )
        );
    }

    /**
     * @param ActivateDomain $command
     */
    public function handleActivateDomain(ActivateDomain $command)
    {
        // TODO: Necessary checks

        $this->eventPublisher->publish(
            'Neos.Neos:Domain:' . $command->getHostName(),
            new DomainWasActivated(
                $command->getHostName()
            )
        );
    }

    /**
     * @param DeactivateDomain $command
     */
    public function handleDeactivateDomain(DeactivateDomain $command)
    {
        // TODO: Necessary checks

        $this->eventPublisher->publish(
            'Neos.Neos:Domain:' . $command->getHostName(),
            new DomainWasDeactivated(
                $command->getHostName()
            )
        );
    }

    /**
     * @param DeleteDomain $command
     */
    public function handleDeleteDomain(DeleteDomain $command)
    {
        // TODO: Necessary checks

        $this->eventPublisher->publish(
            'Neos.Neos:Domain:' . $command->getHostName(),
            new DomainWasDeleted(
                $command->getHostName()
            )
        );
    }
}
