<?php

namespace Drupal\Tests\layout_builder_asset\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class LayoutBuilderAssetJsTest.
 *
 * @package Drupal\Tests\layout_builder_asset\Functional
 *
 * @group asset_injector
 */
class LayoutBuilderAssetJsTest extends BrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to install.
   *
   * @var array
   */
  protected static $modules = ['layout_builder_asset', 'toolbar', 'block'];

  /**
   * The account to be used to test access to both workflows.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $administrator;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->drupalPlaceBlock('local_tasks_block');
    $this->drupalPlaceBlock('page_title_block');
    $this->drupalPlaceBlock('system_messages_block');
  }

  /**
   * Tests a user without permissions gets access denied.
   *
   * @throws \Exception
   */
  public function testJsPermissionDenied() {
    $account = $this->drupalCreateUser();
    $this->drupalLogin($account);
    $this->drupalGet('admin/config/development/layout-builder-asset/js');
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * Tests a user WITH permission has access.
   *
   * @throws \Exception
   */
  public function testJsPermissionGranted() {
    $account = $this->drupalCreateUser(['administer js layout builder']);
    $this->drupalLogin($account);
    $this->drupalGet('admin/config/development/layout-builder-asset/js');
    $this->assertSession()->statusCodeEquals(200);
  }

}
