<?php

/*
 * (c) Fabian Sörqvist <fabian@sorqvist.nu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabsor\DrupalReleaseApi\Tests;
use Fabsor\DrupalReleaseApi\XMLProjectParser;

/**
 * Test the XML Drupal Project implementation.
 */
class XMLProjectParserTest extends \PHPUnit_Framework_TestCase
{
  /**
   * Test parsing the project xml file.
   */
  function testParseProjectXML()
  {
    // We load the file from our local directory.
    $data = simplexml_load_file(__DIR__ . '/project.xml');
    $parser = new XMLProjectParser();
    $project = $parser->parse($data);
    // Check that the basics are right.
    $this->assertEquals($project->getTitle(), 'Drupal core');
    $this->assertEquals($project->getApiVersion(), '7.x');
    $this->assertEquals($project->getShortName(), 'drupal');
    $this->assertEquals($project->getRecommendedMajor(), "7");
    $this->assertEquals($project->getProjectStatus(), "published");
    $this->assertEquals($project->getLink(), "http://drupal.org/project/drupal");
    // Check that the current release is the right one, 7.17.
    $currentRelease = $project->getCurrentRelease();
    $this->assertEquals($currentRelease['version'], '7.17');
    // Terms should be an associative array.
    $terms = $project->getTerms();
    $expectedTerms = array(
      'Projects' => 'Drupal core',
      'Development status' => 'Under active development',
      'Maintenance status' => 'Actively maintained',
    );
    $this->assertEquals($expectedTerms, $terms);
  }
}
