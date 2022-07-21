<?php

namespace Drupal\layout_builder_asset\Entity;

/**
 * Defines the Css Injector entity.
 *
 * @ConfigEntityType(
 *   id = "layout_builder_asset_css",
 *   label = @Translation("Css Injector"),
 *   list_cache_tags = { "library_info" },
 *   handlers = {
 *     "access" = "Drupal\layout_builder_asset\LayoutBuilderAssetAccessControlHandler",
 *     "list_builder" = "Drupal\layout_builder_asset\LayoutBuilderAssetListBuilder",
 *     "form" = {
 *       "add" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetCssForm",
 *       "edit" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetCssForm",
 *       "delete" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetDeleteForm",
 *       "enable" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetEnableForm",
 *       "disable" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetDisableForm",
 *       "duplicate" = "Drupal\layout_builder_asset\Form\LayoutBuilderAssetDuplicateForm",
 *     },
 *   },
 *   config_prefix = "css",
 *   admin_permission = "administer css layout builder",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/development/layout-builder-css/css/{layout_builder_asset_css}",
 *     "edit-form" = "/admin/config/development/layout-builder-css/{layout_builder_asset_css}",
 *     "delete-form" = "/admin/config/development/layout-builder-css/css/{layout_builder_asset_css}/delete",
 *     "enable" = "/admin/config/development/layout-builder-css/css/{layout_builder_asset_css}/enable",
 *     "disable" = "/admin/config/development/layout-builder-css/css/{layout_builder_asset_css}/disable",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "code"
 *   }
 * )
 */
class LayoutBuilderAssetCss extends LayoutBuilderAssetBase {

  /**
   * Gets the file extension of the asset.
   *
   * @return string
   *   Css extension.
   */
  public function extension() {
    return 'css';
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function libraryInfo() {
    $path = $this->filePathRelativeToDrupalRoot();
    $library_info = [
      'css' => [
        'theme' => [
          $path => [
            'weight' => 0,
          ],
        ],
      ],
    ];
    return $library_info;
  }

}
