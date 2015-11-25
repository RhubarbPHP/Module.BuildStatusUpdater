<?php

namespace Rhubarb\BuildStatusUpdater\GitProviders;

use Bitbucket\API;
use Buzz\Message\MessageInterface;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
class BitbucketBuildStatus extends API\Api
{
    /**
     * @access public
     * @param  string           $account The team or individual account owning the repository.
     * @param  string           $repo    The repository identifier.
     * @param  string           $revision    The sha1 of the commit the build is for
     * @param  string           $key    A vendor unique string for the build reference
     * @param  string           $state    "INPROGRESS", "SUCCESSFUL", or "FAILED"
     * @param  string           $url    The URL to the build systems report
     * @param  array            $params  Additional service parameters
     * @return MessageInterface
     *
     * @throws \InvalidArgumentException
     */
    public function create($account, $repo, $revision, $key, $state, $url, array $params = array())
    {
        $mandatory = array(
            'state'        => $state,
            'key'          => $key,
            'url'          => $url
        );

        $params = array_merge($mandatory, $params);

        return $this->getClient()->setApiVersion('2.0')->post(
            sprintf('repositories/%s/%s/commit/%s/statuses/build', $account, $repo, $revision),
            json_encode($params),
            array('Content-Type' => 'application/json')
        );
    }

    /**
     * @access public
     * @param  string           $account The team or individual account owning the repository.
     * @param  string           $repo    The repository identifier.
     * @param  string           $revision    The sha1 of the commit the build is for
     * @param  string           $key    A vendor unique string for the build reference
     * @param  string           $state    "INPROGRESS", "SUCCESSFUL", or "FAILED"
     * @param  string           $url    The URL to the build systems report
     * @param  array            $params  Additional service parameters
     * @return MessageInterface
     *
     * @throws \InvalidArgumentException
     */
    public function update($account, $repo, $revision, $key, $state, $url, array $params = array())
    {
        $mandatory = array(
            'state' => $state,
            'url' => $url
        );

        $params = array_merge($mandatory, $params);

        return $this->getClient()->setApiVersion('2.0')->put(
            sprintf('repositories/%s/%s/commit/%s/statuses/build/%s', $account, $repo, $revision, $key),
            json_encode($params),
            array('Content-Type' => 'application/json')
        );
    }

    /**
     * @access public
     * @param  string           $account The team or individual account owning the repository.
     * @param  string           $repo    The repository identifier.
     * @param  string           $revision    The sha1 of the commit the build is for
     * @param  string           $key    A vendor unique string for the build reference
     * @return MessageInterface
     */
    public function get($account, $repo, $revision, $key)
    {
        return $this->getClient()->setApiVersion('2.0')->get(
            sprintf('repositories/%s/%s/commit/%s/statuses/build/%s', $account, $repo, $revision, $key)
        );
    }
}