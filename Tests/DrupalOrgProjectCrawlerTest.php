<?php

/*
 * (c) Fabian Sörqvist <fabian@sorqvist.nu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabsor\DrupalReleaseApi\Tests;
use Fabsor\DrupalReleaseApi\DrupalOrgProjectCrawler;

class DrupalOrgProjectCrawlerTest extends \PHPUnit_Framework_TestCase
{

    function testCrawlModules() {
        $crawler = new DrupalOrgProjectCrawler();
        $this->assertProjectArray($crawler->getModules());
        // Paging should work.
        $this->assertProjectArray($crawler->getModules(1));
    }

    function testCrawlThemes()
    {
        $crawler = new DrupalOrgProjectCrawler();
        $this->assertProjectArray($crawler->getThemes());
        // Paging should work.
        $this->assertProjectArray($crawler->getThemes(1));
    }


    function testCrawlProfiles()
    {
        $crawler = new DrupalOrgProjectCrawler();
        $this->assertProjectArray($crawler->getProfiles());
        // Paging should work.
        $this->assertProjectArray($crawler->getProfiles(1));
    }


    function assertProjectArray($projects)
    {
        foreach ($projects as $key => $project) {
            $this->assertTrue(isset($project['title']));
            $this->assertTrue(isset($project['link']));
        }
    }
}