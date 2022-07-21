<?php

namespace Drupal\layout_builder_asset\Entity;

use Drupal\layout_builder_asset\LayoutBuilderAssetInterface;
use Drupal\layout_builder_asset\LayoutBuilderAssetFileStorage;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;

/**
 * Class AssetI: LayoutBuilderAssetBase asset injector class.
 *
 * @package Drupal\layout_builder_asset\LayoutBuilderAssetBase.
 */
abstract class LayoutBuilderAssetBase extends ConfigEntityBase implements LayoutBuilderAssetInterface, EntityWithPluginCollectionInterface {


  /**
   * The Asset Injector ID.
   *
   * @var string
   */
  public $id;

  /**
   * The Js Injector label.
   *
   * @var string
   */
  public $label;

  /**
   * The class of the asset.
   *
   * @var string
   */
  public $class;

  /**
   * The code of the asset.
   *
   * @var string
   */
  public $code;

  /**
   * Node type to apply asset.
   *
   * @var string
   */
  public $nodeType;

  /**
   * The available contexts for this asset and its conditions conditions.
   *
   * @var array
   */
  protected $contexts = [];

  /**
   * {@inheritdoc}
   */
  public function libraryNameSuffix() {
    $extension = $this->extension();
    return "$extension/$this->id";
  }

  /**
   * {@inheritdoc}
   */
  abstract public function libraryInfo();

  /**
   * {@inheritdoc}
   */
  abstract public function extension();

  /**
   * {@inheritdoc}
   */
  public function internalFileUri() {
    $storage = new LayoutBuilderAssetFileStorage($this);
    return $storage->createFile();
  }

  /**
   * Get file path relative to drupal root to use in library info.
   *
   * @return string
   *   File path relative to drupal root, with leading slash.
   */
  protected function filePathRelativeToDrupalRoot() {
    // @todo See if we can simplify this via file_url_transform_relative().
    $path = parse_url(file_create_url($this->internalFileUri()), PHP_URL_PATH);
    $path = str_replace(base_path(), '/', $path);
    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function getCode() {
    return $this->code;
  }

  /**
   * {@inheritdoc}
   */
  public function getClass() {
    return $this->class;
  }

  /**
   * On delete delete this asset's file(s).
   */
  public function delete() {
    $storage = new LayoutBuilderAssetFileStorage($this);
    $storage->deleteFiles();
    parent::delete();
  }

  /**
   * On update delete this asset's file(s), will be recreated later.
   */
  public function preSave(EntityStorageInterface $storage) {
    $original_id = $this->getOriginalId();
    if ($original_id) {
      $original = $storage->loadUnchanged($original_id);
      // This happens to fail on config import.
      if ($original instanceof LayoutBuilderAssetInterface) {
        $layout_builder_asset_storage = new LayoutBuilderAssetFileStorage($original);
        // ($component->get('layout_builder_asset_internal_uri'));
        $layout_builder_asset_storage->deleteFiles();
      }
    }
    parent::preSave($storage);
  }

}
