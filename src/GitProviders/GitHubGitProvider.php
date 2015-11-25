<?php

namespace Rhubarb\BuildStatusUpdater\GitProviders;

use GitHubClient;
use Rhubarb\BuildStatusUpdater\Settings\GitProviderSettings;

class GitHubGitProvider extends GitProvider
{
    public function UpdateCommitStatus($owner, $repos, $sha, $status)
    {
        $client = new GitHubClient();

        $settings = new GitProviderSettings();

        if ( $settings->Username ){
            $client->setCredentials($settings->Username, $settings->Password);
        }

        $client->repos->statuses->createStatus( $owner, $repos, $sha, self::ConvertStatus($status) );
    }

    /**
     * Should take a base status string as provided by GitProvider and return the appropriate
     * status for this provider.
     *
     * @param $baseStatus
     * @return string
     */
    public function ConvertStatus($baseStatus)
    {
        return $baseStatus;
    }
}