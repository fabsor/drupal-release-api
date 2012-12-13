<?php

/*
 * (c) Fabian Sörqvist <fabian@sorqvist.nu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabsor\DrupalReleaseApi;

/**
 * Representation of a Drupal Project with release information.
 */
class DrupalProject {

    protected $title;
    protected $shortName;
    protected $apiVersion;
    protected $recommendedMajor;
    protected $projectStatus;
    protected $link;
    protected $releases;
    protected $currentRelease;
    protected $terms;

    /**
     * Get the human readable title of the Drupal project.
     * @return string the title of the project.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the ShortName, meaning the machine name of the Drupal project.
     * @return string the short name for the project
     */
    public function getShortName() {
        return  $this->shortName;
    }

    /**
     * Get the api version for this project.
     * @return string the api version.
     */
    public function getApiVersion() {
        return  $this->apiVersion;
    }

    /**
     * Get a link to the project page, for instance http://drupal.org/project/views.
     * @return string A link to the project page.
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Get an array with all releases.
     * @return An array with info about each release.
     */
    public function getReleases() {
        return $this->releases;
    }

    /**
     * Get the recommended major release for the project.
     * For instance, if there are two versions, 7.x-1.x and 7.x-2.x,
     * and the maintainer has set 2.x to be the recommended major,
     * this will return 2.
     * @return int the recommended major version.
     */
    public function getRecommendedMajor() {
        return  $this->recommendedMajor;
    }

    /**
     * Get the project status. Possible values are:
     *  - published
     *  - unpublished
     * return string unpublished|published
     */
    public function getProjectStatus() {
        return $this->projectStatus;
    }

    /**
     * Get the current release, meaning the latest recommended release.
     * @return string the current release.
     */
    public function getCurrentRelease()
    {
        return $this->currentRelease;
    }

    /**
     * Get an array of terms that are set on the project.
     * @return array an associative array of term values, keyed by the term name
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @param SimpleXMLElement $data
     *  Raw XML data for a Drupal project.
     * @param string $apiVersion
     *   The API Version, for instance 7.x
     */
    public function __construct(\SimpleXMLElement $data, $apiVersion)
    {
        $this->apiVersion = $apiVersion;
        $this->parseData($data);
    }

}
