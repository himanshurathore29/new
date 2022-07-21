<?php

namespace Drupal\layout_builder_asset;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Asset Injector entities.
 */
interface LayoutBuilderAssetInterface extends ConfigEntityInterface {

  /**
   * Gets the library array used in library_info_build.
   *
   * @return array
   *   Library info array for this asset.
   */
  public function libraryInfo();

  /**
   * Get the library name suffix to append to module name.
   *
   * @return bool|string
   *   Library name suffix for use in page attachments.
   *
   * @see asset_injector_page_attachments()
   * @see asset_injector_library_info_build()
   */
  public function libraryNameSuffix();

  /**
   * Get internal file uri.
   *
   * @return string
   *   Internal file uri like like public://asset_injector/...
   */
  public function internalFileUri();

  /**
   * Get file extension.
   *
   * @return string
   *   File extension, like 'css' or 'js'.
   */
  public function extension();

  /**
   * Get te asset's code.
   *
   * @return string
   *   The code of the asset.
   */
  public function getCode();

  /**
   * Returns an array of condition configurations.
   *
   * @return array
   *   An array of condition configuration keyed by the condition ID.
   */

  /**
   * Get te class.
   *
   * @return string
   *   The class of the asset.
   */
  public function getClass();

}
