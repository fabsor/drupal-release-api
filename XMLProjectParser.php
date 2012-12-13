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

  function parse($data) {
      $project = new DrupalProject();
  }

  /**
   * Parse XML Data and set properties.
   * @param \SimpleXMLElement $data
   */
  protected function parseData(\SimpleXMLElement $data)
  {
      $this->title = (string) $data->title;
      $this->shortName = (string) $data->short_name;
      $this->recommendedMajor = (int) $data->recommended_major;
      $this->projectStatus = (string) $data->project_status;
      $this->link = (string) $data->link;
      $this->releases = array();
      $this->terms = array();
      $this->currentRelease = FALSE;
      foreach ($data->terms[0]->term as $term) {
          $this->terms[(string) $term->name] = (string) $term->value;
      }
      foreach ($data->releases->release as $release) {
          $releaseArr = array(
              'version' => (string) $release->version,
              'major' => (string) $release->version_major,
              'patch' => (string) $release->version_patch,
              'extra' => (string) $release->version_extra,
              'status' => (string) $release->status,
          );
          $this->currentRelease = $this->compareRelease($releaseArr, $this->currentRelease);
          $this->releases[$releaseArr['version']] = $releaseArr;
      }
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