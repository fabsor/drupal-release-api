<?php

/*
 * (c) Fabian Sörqvist <fabian@sorqvist.nu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabsor\DrupalReleaseApi;

/**
 * Fetch information from the release history API that is used by Drupal.
 * $fetcher = new HTTPReleaseFetcher();
 * $fetcher->getReleaseInfo('drupal', '7.x');
 */
class HTTPReleaseFetcher implements ReleaseFetcherInterface
{
    const DRUPALORG_RELEASE_URL =  'http://updates.drupal.org/release-history';

    protected $releaseUrl;

    /**
     * @param string $releaseUrl
     *   The URL from where the release information should be fetched.
     */
    public function __construct($releaseUrl = self::DRUPALORG_RELEASE_URL)
    {
        $this->releaseUrl = $releaseUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getReleaseInfo($projectName, $api)
    {
        $requestUrl = $this->releaseUrl . '/' . $projectName . '/' . $api;
        $data = new \SimpleXMLElement($requestUrl, 0, true);
        $project = new DrupalProject($data, $api);
        return $project;
    }
}
