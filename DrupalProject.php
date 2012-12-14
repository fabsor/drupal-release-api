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
class DrupalProject implements \JsonSerializable {

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
    public function getShortName()
    {
        return  $this->shortName;
    }

    /**
     * Get the api version for this project.
     * @return string the api version.
     */
    public function getApiVersion()
    {
        return  $this->apiVersion;
    }

    /**
     * Get a link to the project page, for instance http://drupal.org/project/views.
     * @return string A link to the project page.
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Get an array with all releases.
     * @return An array with info about each release.
     */
    public function getReleases()
    {
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
     * Set the human readable title of the Drupal project.
     * @param string title the title of the project.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set the ShortName, meaning the machine name of the Drupal project.
     * @param $shortName The short name of the project.
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * Set the api version for this project.
     * @param @apiVerison the api version.
     */
    public function setApiVersion($apiVersion)
    {
        if (!preg_match("/\d+.x/", $apiVersion)) {
            throw new \Exception("Invalid api version specifier");
        }
        $this->apiVersion = $apiVersion;
    }

    /**
     * Set a link to the project page, for instance http://drupal.org/project/views.
     * @return string A link to the project page.
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Set an array with all releases. The current release
     * must be re-set if this is set, and the current release
     * isn't in the array.
     * @param $releases An array with release information.
     * @throws Exception if the $releases parameter isn't an array.
     */
    public function setReleases($releases)
    {
        if (!is_array($releases)) {
            throw new \Exception("The releases parameter must be an array.");
        }
        if (isset($this->currentRelease)) {
            if (!isset($releases[$this->currentRelease['version']])) {
                unset($this->currentRelease);
            }
            else {
                // Make sure the info about the current release is updated properly.
                $this->currentRelease = $releases[$this->currentRelease['version']];
            }
        }
        $this->releases = $releases;
    }

    /**
     * Set the recommended major release for the project.
     * For instance, if there are two versions, 7.x-1.x and 7.x-2.x,
     * and the maintainer has set 2.x to be the recommended major,
     * this will return 2.
     * @param int $recommendedMajor The recommended major release.
     * @throws Exception if the $recommendedMajor parameter isn't numeric.
     */
    public function setRecommendedMajor($recommendedMajor)
    {
        if (!is_numeric($recommendedMajor)) {
            throw new \Execption("The recommended major needs to be a number");
        }
        $this->recommendedMajor = $recommendedMajor;
    }

    /**
     * Set the project status. Possible values are:
     */
    public function setProjectStatus($projectStatus) {
        $this->projectStatus = $projectStatus;
    }

    /**
     * Set the current release, meaning the latest recommended release.
     * @param array the current release.
     * @throws Exeception if the current release isn't in the release array.
     */
    public function setCurrentRelease($currentRelease)
    {
        // The release must be in the array of releases.
        if (!isset($this->releases[$currentRelease['version']])) {
            throw new \Exception("The current release needs to be part of the release array.");
        }
        $this->currentRelease = $currentRelease;
    }

    /**
     * Set an array of terms that are set on the project.
     * @param array an associative array of term values, keyed by the term name
     * @throws Exception if the terms parameter isn't an array.
     */
    public function setTerms($terms)
    {
        if (!is_array($terms)) {
            return new \Exception("Invalid value for terms: The value must be an array.");
        }
        $this->terms = $terms;
    }

    public function JsonSerialize()
    {
        return array(
            'title' => $this->title,
            'shortName' => $this->shortName,
            'apiVersion' => $this->apiVersion,
            'recommendedMajor' => $this->recommendedMajor,
            'projectStatus' => $this->projectStatus,
            'link' => $this->link,
            'releases' => $this->releases,
            'currentRelease' => $this->currentRelease,
            'terms' => $this->terms,
        );
    }
}
