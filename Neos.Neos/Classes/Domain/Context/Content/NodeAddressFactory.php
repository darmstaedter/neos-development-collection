<?php

namespace Neos\Neos\Domain\Context\Content;

/*
 * This file is part of the Neos.Neos package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\Domain\Projection\Content\ContentGraphInterface;
use Neos\ContentRepository\Domain\Projection\Content\NodeInterface;
use Neos\ContentRepository\Domain\Projection\Workspace\WorkspaceFinder;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class NodeAddressFactory
{

    /**
     * @Flow\Inject
     * @var WorkspaceFinder
     */
    protected $workspaceFinder;

    /**
     * @Flow\Inject
     * @var ContentGraphInterface
     */
    protected $contentGraph;

    public function findSiteNodeForNodeAddress(NodeAddress $nodeAddress): ?NodeInterface
    {
        $subgraph = $this->contentGraph->getSubgraphByIdentifier(
            $nodeAddress->getContentStreamIdentifier(),
            $nodeAddress->getDimensionSpacePoint()
        );
        $node = $subgraph->findNodeByNodeAggregateIdentifier($nodeAddress->getNodeAggregateIdentifier());

        do {
            if ($node->getNodeType()->isOfType('Neos.Neos:Site')) {
                return $node;
            }
        } while ($node = $subgraph->findParentNode($node->getNodeIdentifier()));

        // no Site node found at rootline
        return null;
    }

    public function isInLiveWorkspace(NodeAddress $nodeAddress): bool
    {
        $workspace = $this->workspaceFinder->findOneByCurrentContentStreamIdentifier($nodeAddress->getContentStreamIdentifier());
        return $workspace != null && $workspace->getWorkspaceName()->isLive();
    }

    public function createFromNode(NodeInterface $node): NodeAddress
    {
        return NodeAddress::fromNode($node);
    }

    public function createFromUriString(string $nodeAddressSerialized): NodeAddress
    {
        return NodeAddress::fromUriString($nodeAddressSerialized);
    }
}