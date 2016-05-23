<?php

namespace Rhubarb\BuildStatusUpdater\GitProviders;

use Bitbucket\API\Http\Client;
use Bitbucket\API\Http\Listener\BasicAuthListener;
use Bitbucket\API\Repositories\Commits;
use Bitbucket\API\User;
use Rhubarb\BuildStatusUpdater\Settings\GitProviderSettings;

class BitbucketGitProvider extends GitProvider
{

    /**
     * Should take a base status string as provided by GitProvider and return the appropriate
     * status for this provider.
     *
     * @param $baseStatus
     * @return string
     */
    protected function convertStatus($baseStatus)
    {
        switch($baseStatus){
            case GitProvider::STATUS_SUCCESS:
                return "SUCCESSFUL";
            case GitProvider::STATUS_FAILURE:
            case GitProvider::STATUS_ERROR:
                return "FAILED";
            case GitProvider::STATUS_PENDING:
                return "INPROGRESS";
        }

        return "FAILURE";
    }

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
    public function updateCommitStatus($owner, $repos, $sha, $buildRef, $status, $description = "", $ciUrl = "")
    {
        $statusEndPoint = new BitbucketBuildStatus();
        $settings = GitProviderSettings::singleton();

        if ($settings->username) {

            $statusEndPoint->getClient()->addListener(
                new BasicAuthListener($settings->username, $settings->password)
            );
        }

        $params = [];

        if ($description != "") {
            $params["description"] = $description;
        }

        $statusEndPoint->create($owner, $repos, $sha, $buildRef, $this->convertStatus($status), $ciUrl, $params);
    }
}