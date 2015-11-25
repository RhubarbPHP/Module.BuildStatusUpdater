<?php

namespace Rhubarb\BuildStatusUpdater\GitProviders;

abstract class GitProvider
{
    const STATUS_PENDING = "pending";
    const STATUS_SUCCESS = "success";
    const STATUS_FAILURE = "failure";
    const STATUS_ERROR = "error";

    /**
     * Should take a base status string as provided by GitProvider and return the appropriate
     * status for this provider.
     *
     * @param $baseStatus
     * @return string
     */
    public abstract function ConvertStatus($baseStatus);

    public abstract function UpdateCommitStatus( $sha, $owner, $repos, $status );
}