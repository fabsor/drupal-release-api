<?php

/*
 * (c) Fabian Sörqvist <fabian@sorqvist.nu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabsor\DrupalReleaseApi;

/**
 * A release fetcher fetches information from some source.
 */
interface ReleaseFetcherInterface {

    /**
     * Get release information for a particular project.
     * @param string $projectName
     *   The name of the project
     * @param string $api
     *   The API version of core, for instance 7.x
     * @return DrupalProject
     *   Release information for a Drupal project.
     */
    public function getReleaseInfo($projectName, $api);

}
