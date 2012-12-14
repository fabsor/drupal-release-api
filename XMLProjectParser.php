<?php

/*
 * (c) Fabian Sörqvist <fabian@sorqvist.nu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fabsor\DrupalReleaseApi;

/**
 * Parse Drupal project information from an XML doc in the Drupal API.
 */
class XMLProjectParser
{

  /**
   * Parse Drupal project ReleaseXML data
   * and return a DrupalProject object.
   * @return DrupalProject a fully populated DrupalProject object.
   */
  function parse(\SimpleXMLElement $data)
  {
      $project = new DrupalProject();
      $project->setApiVersion((string) $data->api_version);
      $project->setTitle((string) $data->title);
      $project->setShortName((string) $data->short_name);
      $project->setRecommendedMajor((int) $data->recommended_major);
      $project->setProjectStatus((string) $data->project_status);
      $project->setLink((string) $data->link);
      $terms = array();
      foreach ($data->terms[0]->term as $term) {
          $terms[(string) $term->name] = (string) $term->value;
      }
      $project->setTerms($terms);

      $releases = array();
      $currentRelease = FALSE;
      foreach ($data->releases->release as $release) {
          $releaseArr = array(
              'version' => (string) $release->version,
              'major' => (string) $release->version_major,
              'patch' => (string) $release->version_patch,
              'extra' => (string) $release->version_extra,
              'status' => (string) $release->status,
          );
          $currentRelease = $this->compareRelease($releaseArr, $currentRelease);
          $releases[$releaseArr['version']] = $releaseArr;
      }
      $project->setReleases($releases);
      if ($currentRelease) {
        $project->setCurrentRelease($currentRelease);
      }
      return $project;
  }

  protected function compareRelease($version1, $version2)
  {
      if ($this->versionHigher($version1, $version2)) {
          return $version1;
      }
      return $version2;
  }

  protected function versionHigher($version1, $version2)
  {
      if (empty($version2)) {
          return $version1;
      }
      if (empty($version1)) {
          return $version2;
      }
      // Compare major and patch first.
      if (($version1['major'] > $version2['major']) || ($version1['patch'] > $version2['patch'])) {
          return TRUE;
      }
      // We need to have a look at the extras.
      if (!empty($version1['extra'])) {
          // If version 2 doesn't have an extra, it's a higher version.
          if (empty($version2['extra'])) {
              return FALSE;
          }
          $version1_matches = array();
          $version2_matches = array();
          // The order of the extras. Higher is better.
          $order = array('alpha', 'beta', 'rc', 'dev');
          $pattern = '/(alpha|beta|rc|dev)([0-9]*)/';
          preg_match($pattern, $version1['extra'], $version1_matches);
          preg_match($pattern, $version2['extra'], $version2_matches);

          if (array_search($version1_matches[1], $order) > array_search($version2_matches[1], $order)) {
              return TRUE;
          }
          if ($version1_matches[2] > $version2_matches[2]) {
              return TRUE;
          }
      }
      return FALSE;
  }
}
