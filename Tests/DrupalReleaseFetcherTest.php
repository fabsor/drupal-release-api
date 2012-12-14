<?php

namespace Fabsor\DrupalReleaseApi\Tests;
use Fabsor\DrupalReleaseApi\HTTPReleaseFetcher;

/**
 * Test the Drupal Project class.
 */
class HTTPReleaseFetcherTest extends \PHPUnit_Framework_TestCase
{
  /**
   * Test parsing the project xml file.
   */
  function testParseProjectXML()
  {
    // Get release information for drupal core, if that project goes away,
    // we´re in trouble =).
    $fetcher = new HTTPReleaseFetcher();
    $project = $fetcher->getReleaseInfo('drupal', '7.x');
    // We should get a DrupalProject instance.
    $this->assertEquals($project->getShortName(), 'drupal');
  }
}
