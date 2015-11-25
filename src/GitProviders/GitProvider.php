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
    protected abstract function convertStatus($baseStatus);

    /**
     * Add a status to a commit
     *
     * @param string $owner
     * @param string $repos
     * @param string $sha
     * @param string $buildRef
     * @param string $status A status from the GitProvider::STATUS_XXX set of constants
     * @param string $description An optional description
     * @param string $ciUrl A URL for the full build status.
     * @return mixed
     */
    public abstract function updateCommitStatus( $owner, $repos, $sha, $buildRef, $status, $description = "", $ciUrl = "" );
}