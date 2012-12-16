<?php

/*
 * (c) Fabian Sörqvist <fabian@sorqvist.nu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabsor\DrupalReleaseApi;

class DrupalOrgProjectCrawler
{

    const BASE_URL = 'http://drupal.org';
    protected $baseUrl;

    function __construct($baseUrl = self::BASE_URL)
    {
        $this->baseUrl = $baseUrl;
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Get modules.
     * @return an array of projects, keyed with machine name.
     * each project is an array with the following keys:
     * - title
     * - link
     */
    function getModules($page = 0)
    {
        return $this->request('/project/modules', $page);
    }

    /**
     * Get themes.
     * @return an array of projects, keyed with machine name.
     * each project is an array with the following keys:
     * - title
     * - link
     */
    function getThemes($page = 0) {
        return $this->request('/project/themes', $page);
    }

    /**
     * Get installation profiles.
     * @return an array of projects, keyed with machine name.
     * each project is an array with the following keys:
     * - title
     * - link
     */
    function getProfiles($page = 0) {
        return $this->request('/project/distributions', $page);
    }

    /**
     * Find a project by executing a search on drupal.org.
     */
    public function findProject($name)
    {

    }

    protected function request($uri, $page = 0)
    {
        $uri = $this->baseUrl . $uri;
        if ($page) {
          $uri .= '?page=' . $page;
        }
        curl_setopt($this->curl, CURLOPT_HTTPGET, TRUE);
        curl_setopt($this->curl, CURLOPT_URL, $uri);
        libxml_use_internal_errors(true);
        $curl_response = curl_exec($this->curl);
        $projects = array();
        $document = new \DOMDocument();
        $document->loadHTML($curl_response);
        $body = $document->getElementsByTagName('body')->item(0);
        $xpath = new \DOMXPath($document);
        foreach ($xpath->evaluate('//div[contains(@class,project-overview)]/div[contains(@class, project)]/h2/a', $body) as $projectLink) {
          $project = array();
          $project['link'] = $this->baseUrl . $projectLink->attributes->getNamedItem('href')->textContent;
          $project['title'] = $projectLink->nodeValue;
          $machineName = array_pop(explode('/', $project['link']));
          $projects[$machineName] = $project;
        }
        return $projects;
    }
}
