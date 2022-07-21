<?php

namespace Drupal\layout_builder_asset\Entity;

/**
 * Defines the Css Injector entity.
 *
 * @ConfigEntityType(
 *   id = "layout_builder_asset_js",
 *   label = @Translation("Js Injector"),
 *   list_cache_tags = { "library_info" },
 *   handlers = {
 *     "access" = "Drupal\layout_builder_asset\LayoutBuilderAssetAccessControlHandler",
 *     "list_builder" = "Drupal\layout_builder_asset\LayoutBuilderAssetListBuilder",
 *     "form" = {
 *       "add" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetJsForm",
 *       "edit" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetJsForm",
 *       "delete" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetDeleteForm",
 *       "enable" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetEnableForm",
 *       "disable" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetDisableForm",
 *       "duplicate" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetDuplicateForm",
 *     },
 *   },
 *   config_prefix = "js",
 *   admin_permission = "administer js layout builder",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/development/layout-builder-js/js/{layout_builder_asset_js}",
 *     "edit-form" = "/admin/config/development/layout-builder-js/{layout_builder_asset_js}",
 *     "delete-form" = "/admin/config/development/layout-builder-js/js/{layout_builder_asset_js}/delete",
 *     "enable" = "/admin/config/development/layout-builder-js/js/{layout_builder_asset_js}/enable",
 *     "disable" = "/admin/config/development/layout-builder-js/js/{layout_builder_asset_js}/disable",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "code"
 *   }
 * )
 */
class LayoutBuilderAssetJs extends LayoutBuilderAssetBase {


  /**
   * Load js in the header of the page.
   *
   * @var bool
   */
  public $header;

  /**
   * Preprocess css before adding.
   *
   * @var bool
   */
  public $preprocess = TRUE;

  /**
   * Require jquery.
   *
   * @var string
   */
  public $jquery = FALSE;

  /**
   * Code for <noscript> tag.
   *
   * @var string
   */
  public $noscript;

  /**
   * Region to insert <noscript> code into.
   *
   * @var array
   */
  public $noscriptRegion = [];

  /**
   * Gets the file extension of the asset.
   *
   * @return string
   *   JS extension.
   */
  public function extension() {
    return 'js';
  }

  /**
   * {@inheritdoc}
   */
  public function libraryInfo() {
    $path = $this->filePathRelativeToDrupalRoot();
    $library_info = [
      'header' => $this->header,
      'js' => [
        $path => [
          'preprocess' => $this->preprocess,
        ],
      ],
    ];

    if ($this->jquery) {
      $library_info['dependencies'] = ['core/jquery'];
    }
    return $library_info;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [];
  }

}
