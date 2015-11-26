<?php

namespace Rhubarb\BuildStatusUpdater\Custard;

use Rhubarb\BuildStatusUpdater\GitProviders\BitbucketGitProvider;
use Rhubarb\BuildStatusUpdater\GitProviders\GitHubGitProvider;
use Rhubarb\BuildStatusUpdater\GitProviders\GitProvider;
use Rhubarb\BuildStatusUpdater\Settings\GitProviderSettings;
use Rhubarb\Custard\Command\CustardCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateBuildStatusCommand extends CustardCommand
{
    protected function configure()
    {
        $this->setName('build:update-status')
            ->setDescription('Updates bitbucket or github with build status details')
            ->addArgument("provider", InputArgument::REQUIRED, "\"bitbucket\" or \"github\"" )
            ->addArgument("owner", InputArgument::REQUIRED, "The repository owner e.g. rhubarbphp" )
            ->addArgument("repos", InputArgument::REQUIRED, "The repository name e.g. rhubarb" )
            ->addArgument("sha", InputArgument::REQUIRED, "The sha of the commit to target" )
            ->addArgument("ref", InputArgument::REQUIRED, "A unique reference for the build e.g. Jenkins-#23" )
            ->addArgument("state", InputArgument::REQUIRED, "The state of the build: \"pending\", \"success\", \"failure\", \"error\"" )
            ->addArgument("url", InputArgument::REQUIRED, "The url containing the build details" )
            ->addOption("username", "u", InputArgument::OPTIONAL, "A username with access to the repository" )
            ->addOption("password", "p", InputArgument::OPTIONAL, "Password for the repository user" )
            ->addOption("command", "c", InputArgument::OPTIONAL, "Pass the command class string if not included in a module" )
            ->addArgument("description", InputArgument::OPTIONAL, "A piece of description text" );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $providerName = $input->getArgument("provider");
        $owner = $input->getArgument("owner");
        $repos = $input->getArgument("repos");
        $sha = $input->getArgument("sha");
        $ref = $input->getArgument("ref");
        $state = $input->getArgument("state");
        $url = $input->getArgument("url");
        $description = $input->getArgument("description");

        $username = $input->getOption("username");
        $password = $input->getOption("password");

        $settings = [
            $providerName,
            $owner,
            $repos,
            $sha,
            $ref,
            $state,
            $url,
            $description,
            $username,
            $password
        ];

        $settings = print_r($settings, true);

        file_put_contents("build-status.txt", $settings);

        if ($username){
            $settings = new GitProviderSettings();
            $settings->Username = $username;
            $settings->Password = $password;
        }

        /**
         * @var GitProvider $provider
         */
        $provider = null;

        switch($providerName){
            case "bitbucket":
                $provider = new BitbucketGitProvider();
                break;
            case "github":
                $provider = new GitHubGitProvider();
                break;
        }

        if ( $provider != null ) {
            $provider->updateCommitStatus(
                $owner,
                $repos,
                $sha,
                $ref,
                $state,
                $description,
                $url
            );
        } else {
            $output->writeln( "Error: The provider `$providerName` wasn't recognised." );
        }
    }
}