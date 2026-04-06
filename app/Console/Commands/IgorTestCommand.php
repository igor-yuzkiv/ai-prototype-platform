<?php

namespace App\Console\Commands;

use App\Libs\Bitbucket\BitbucketApiClient;
use App\Libs\ZohoProjects\ZohoProjectsApiClient;
use Illuminate\Console\Command;

class IgorTestCommand extends Command
{
    protected $signature = 'igor:test {--action=}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $action = $this->option('action');
        if ($action && method_exists($this, $action)) {
            $this->{$action}();
        }
    }

    public function listZohoProjects(): void
    {
        $connection = app(ZohoProjectsApiClient::class);
        $response = $connection->get('portal/'.config('zoho-api.projects.portal').'/projects');
        dd($response);
    }

    private function test()
    {
        $client = app(BitbucketApiClient::class);

        $workspace = config('bitbucket.workspace');

        $response = $client->get(path: '/repositories/'.$workspace);

        dd(
            collect($response->get('values'))->first()
        );
    }

    private function listCommits()
    {
        $client = app(BitbucketApiClient::class);

        $workspace = config('bitbucket.workspace');
        $repo = 'demo-4-repo';
        $response = $client->get(path: '/repositories/'.$workspace.'/'.$repo.'/commits', query: [
            'include' => '2fb20e5',
        ]);

        dd(
            collect($response->get('values'))->first()
        );
    }

    private function listRespositories()
    {
        $client = app(BitbucketApiClient::class);

        $workspace = config('bitbucket.workspace');
        $response = $client->get(
            path: '/repositories/'.$workspace,
            query: ['sort' => '-created_on']
        );

        dd(
            collect($response->get('values'))->first()
        );
    }
}
