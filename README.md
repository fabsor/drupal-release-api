# Drupal Release API

## Introduction The Drupal project module provides an XML-based API
for getting project and release information for project hosted on the
server. This library makes it easy to access data from drupal.org and
other sources.

## Usage example

This example fetches the Drupal project and dumps info about the
current release:

    use Fabsor\DrupalReleaseApi\HTTPReleaseFetcher;

	$fetcher = new HTTPReleaseFetcher();
	$project = $fetcher->getReleaseInfo('drupal', '7.x');
    var_dump($project->getCurrentRelease());
