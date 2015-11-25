<?php

namespace Rhubarb\BuildStatusUpdater\GitProviders;

use GitHubClient;
use Rhubarb\BuildStatusUpdater\Settings\GitProviderSettings;

class GitHubGitProvider extends GitProvider
{
    public function updateCommitStatus($owner, $repos, $sha, $buildRef, $status, $description = "", $ciUrl = "")
    {
        $client = new GitHubClient();

        $settings = new GitProviderSettings();

        if ( $settings->Username ){
            $client->setCredentials($settings->Username, $settings->Password);
        }

        $client->repos->statuses->createStatus( $owner, $repos, $sha, $this->convertStatus($status),
            $ciUrl, $description, $buildRef);
    }

    /**
     * Should take a base status string as provided by GitProvider and return the appropriate
     * status for this provider.
     *
     * @param $baseStatus
     * @return string
     */
    protected function convertStatus($baseStatus)
    {
        return $baseStatus;
    }
}