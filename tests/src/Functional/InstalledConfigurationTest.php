<?php

namespace Drupal\Tests\thunder\Functional;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Config\Schema\SchemaCheckTrait;

/**
 * Test for checking of configuration after install of thunder profile.
 *
 * @package Drupal\Tests\thunder\Kernel
 *
 * @group ThunderConfig
 */
class InstalledConfigurationTest extends ThunderTestBase {

  use SchemaCheckTrait;

  /**
   * Modules to enable.
   *
   * The test runner will merge the $modules lists from this class, the class
   * it extends, and so on up the class hierarchy. It is not necessary to
   * include modules in your list that a parent class has already declared.
   *
   * @var string[]
   *
   * @see \Drupal\Tests\BrowserTestBase::installDrupal()
   */
  protected static $modules = [
    'thunder_testing_demo',
    'thunder_google_analytics',
    'thunder_ivw',
    // Because of https://github.com/drupal-graphql/graphql/issues/1177
    // 'thunder_gqls',
    'adsense',
  ];

  /**
   * Theme name that will be used on installation of test.
   *
   * @var string
   */
  protected $defaultTheme = 'stable';

  /**
   * Ignore list of Core related configurations.
   *
   * @var array
   */
  protected static $ignoreCoreConfigs = [
    'checklistapi.progress.update_helper_checklist',
    'system.site',
    'core.extension',
    'system.performance',
    'system.theme',

    // Configs created by User module.
    'system.action.user_add_role_action.administrator',
    'system.action.user_add_role_action.editor',
    'system.action.user_add_role_action.restricted_editor',
    'system.action.user_add_role_action.seo',
    'system.action.user_remove_role_action.administrator',
    'system.action.user_remove_role_action.editor',
    'system.action.user_remove_role_action.restricted_editor',
    'system.action.user_remove_role_action.seo',
    'system.action.user_add_role_action.harbourmaster',
    'system.action.user_remove_role_action.harbourmaster',

    // Configs created by Token module.
    'core.entity_view_mode.access_token.token',
    'core.entity_view_mode.block.token',
    'core.entity_view_mode.content_moderation_state.token',
    'core.entity_view_mode.crop.token',
    'core.entity_view_mode.file.token',
    'core.entity_view_mode.menu_link_content.token',
    'core.entity_view_mode.node.token',
    'core.entity_view_mode.paragraph.token',
    'core.entity_view_mode.taxonomy_term.token',
    'core.entity_view_mode.user.token',
    'core.entity_view_mode.path_alias.token',
    'core.entity_view_mode.search_api_task.token',

    // SearchAPI tour.
    'tour.tour.search-api-index',
    'tour.tour.search-api-index-fields',
    'tour.tour.search-api-index-form',
    'tour.tour.search-api-index-processors',
    'tour.tour.search-api-server',
    'tour.tour.search-api-server-form',

    // Because of https://www.drupal.org/node/3204093
    'tour.tour.content-add',
    'tour.tour.content-list',
    'tour.tour.content-paragraphs',
    'tour.tour.homepage',
  ];

  /**
   * Ignore custom keys that are changed during installation process.
   *
   * @var array
   */
  protected static $ignoreConfigKeys = [
    // It's not exported in Yaml, so that new key is generated.
    'scheduler.settings' => [
      'lightweight_cron_access_key' => TRUE,
    ],

    // Changed on installation.
    'system.date' => [
      'timezone' => [
        'default' => TRUE,
      ],
    ],

    // Changed on installation.
    'system.file' => [
      'path' => [
        'temporary' => TRUE,
      ],
    ],

    // Changed on installation.
    'update.settings' => [
      'notification' => [
        'emails' => TRUE,
      ],
    ],

    // Changed on Testing.
    'system.logging' => [
      'error_level' => TRUE,
    ],

    // Changed on Testing.
    'system.mail' => [
      'interface' => ['default' => TRUE],
    ],

    // Changed on installation.
    'views.view.content' => [
      'status' => TRUE,
    ],
    'views.view.glossary' => [
      'dependencies' => [
        'config' => TRUE,
      ],
      'display' => [
        'page_1' => ['cache_metadata' => ['max-age' => TRUE]],
        'attachment_1' => ['cache_metadata' => ['max-age' => TRUE]],
        'default' => ['cache_metadata' => ['max-age' => TRUE]],
      ],
    ],
    'views.view.watchdog' => [
      'display' => [
        'page' => ['cache_metadata' => ['max-age' => TRUE]],
        'default' => ['cache_metadata' => ['max-age' => TRUE]],
      ],
    ],
    'views.view.files' => [
      'display' => [
        'page_1' => ['cache_metadata' => ['max-age' => TRUE]],
        'page_2' => ['cache_metadata' => ['max-age' => TRUE]],
        'default' => ['cache_metadata' => ['max-age' => TRUE]],
      ],
    ],
    'views.view.moderated_content' => [
      'display' => [
        'moderated_content' => [
          'cache_metadata' => [
            'max-age' => TRUE,
            'tags' => TRUE,
          ],
        ],
        'default' => ['cache_metadata' => ['max-age' => TRUE, 'tags' => TRUE]],
      ],
    ],
    // Diff Module: changed on installation of module when additional library
    // exists on system: mkalkbrenner/php-htmldiff-advanced.
    'diff.settings' => [
      'general_settings' => [
        'layout_plugins' => [
          'visual_inline' => [
            'enabled' => TRUE,
          ],
        ],
      ],
    ],

    // The thunder profile changes article and channel taxonomy when ivw module
    // is installed.
    'core.entity_form_display.node.article.default' => [
      'content' => [
        'field_ivw' => TRUE,
      ],
      'dependencies' => [
        'config' => TRUE,
        'module' => TRUE,
      ],
    ],
    'core.entity_form_display.node.article.bulk_edit' => [
      'hidden' => [
        'field_ivw' => TRUE,
      ],
    ],
    'core.entity_form_display.taxonomy_term.channel.default' => [
      'content' => [
        'field_ivw' => TRUE,
      ],
      'dependencies' => [
        'config' => TRUE,
        'module' => TRUE,
      ],
    ],
    'core.entity_view_display.taxonomy_term.channel.default' => [
      'hidden' => [
        'field_ivw' => TRUE,
      ],
    ],
    'core.entity_view_display.node.article.default' => [
      'hidden' => [
        'field_ivw' => TRUE,
      ],
    ],
    'core.entity_view_display.node.article.rss' => [
      'hidden' => [
        'field_ivw' => TRUE,
      ],
    ],
    'core.entity_view_display.node.article.search_index' => [
      'hidden' => [
        'field_ivw' => TRUE,
      ],
    ],
    'core.entity_view_display.node.article.teaser' => [
      'hidden' => [
        'field_ivw' => TRUE,
      ],
    ],
    'views.view.locked_content' => [
      'display' => [
        'default' => ['display_options' => ['sorts' => ['created' => ['expose' => ['field_identifier' => TRUE]]]]],
      ],
    ],
  ];

  /**
   * Configuration key path separator.
   *
   * @var string
   */
  public const CONFIG_PATH_SEPARATOR = '::';

  /**
   * Ignore configuration list values. Path to key is separated by '::'.
   *
   * @var array
   *
   * Example:
   * 'field.field.node.article.field_example' => [
   *   'settings::settings_part1::list_part' => [
   *      'ignore_entry1',
   *      'ignore_entry5',
   *   ]
   * ]
   *
   * @todo use this functionality for more strict "dependencies" checking.
   */
  protected static $ignoreConfigListValues = [
    // Google analytics adds one permission dynamically in the install hook.
    'user.role.authenticated' => [
      'permissions' => [
        'opt-in or out of google analytics tracking',
      ],
      'dependencies::module' => [
        'google_analytics',
      ],
    ],
    'user.role.editor' => [
      'permissions' => [
        'access tour',
      ],
      'dependencies::module' => [
        'tour',
      ],
    ],
    'user.role.restricted_editor' => [
      'permissions' => [
        'access tour',
      ],
      'dependencies::module' => [
        'tour',
      ],
    ],
    'user.role.seo' => [
      'permissions' => [
        'access tour',
      ],
      'dependencies::module' => [
        'tour',
      ],
    ],
  ];

  /**
   * List of contribution settings that should be ignored.
   *
   * All these settings exists in module configuration Yaml files, but they are
   * not in sync with configuration that is set after installation.
   *
   * @var array
   */
  protected static $ignoreConfigs = [];

  /**
   * Set default theme for test.
   *
   * @param string $defaultTheme
   *   Default Theme.
   */
  protected function setDefaultTheme(string $defaultTheme): void {
    \Drupal::service('theme_installer')->install([$defaultTheme]);

    $themeConfig = \Drupal::configFactory()->getEditable('system.theme');
    $themeConfig->set('default', $defaultTheme);
    $themeConfig->save();
  }

  /**
   * Return cleaned-up configurations provided as argument.
   *
   * @param array $configurations
   *   List of configurations that will be cleaned-up and returned.
   * @param string $configurationName
   *   Configuration name for provided configurations.
   *
   * @return array
   *   Returns cleaned-up configurations.
   */
  protected function cleanupConfigurations(array $configurations, string $configurationName): array {
    /** @var \Drupal\Core\Config\ExtensionInstallStorage $optionalStorage */
    $optionalStorage = \Drupal::service('config_update.extension_optional_storage');

    $configCleanup = [];
    $ignoreListRules = [];

    // Apply ignore for defined configurations and custom properties.
    if (array_key_exists($configurationName, static::$ignoreConfigKeys)) {
      $configCleanup = static::$ignoreConfigKeys[$configurationName];
    }

    if (array_key_exists($configurationName, static::$ignoreConfigListValues)) {
      foreach (static::$ignoreConfigListValues[$configurationName] as $keyPath => $ignoreValues) {
        $ignoreListRules[] = [
          'key_path' => explode(self::CONFIG_PATH_SEPARATOR, $keyPath),
          'ignore_values' => $ignoreValues,
        ];
      }
    }

    // Ignore configuration dependencies in case of optional configuration.
    if ($optionalStorage->exists($configurationName)) {
      $configCleanup = NestedArray::mergeDeep(
        $configCleanup,
        ['dependencies' => TRUE]
      );
    }

    // If configuration doesn't require cleanup, just return configurations as
    // they are.
    if (empty($configCleanup) && empty($ignoreListRules)) {
      return $configurations;
    }

    // Apply cleanup for configurations.
    foreach ($configurations as $index => $arrayToOverwrite) {
      $configurations[$index] = NestedArray::mergeDeep(
        $arrayToOverwrite,
        $configCleanup
      );

      foreach ($ignoreListRules as $ignoreRule) {
        $list = $this->cleanupConfigList(
          NestedArray::getValue($configurations[$index], $ignoreRule['key_path']),
          $ignoreRule['ignore_values']
        );

        NestedArray::setValue($configurations[$index], $ignoreRule['key_path'], $list);
      }
    }

    return $configurations;
  }

  /**
   * Clean up configuration list values.
   *
   * @param array $list
   *   List of values in configuration object.
   * @param array $ignoreValues
   *   Array with list of values that should be ignored.
   *
   * @return array
   *   Return cleaned-up list.
   */
  protected function cleanupConfigList(array $list, array $ignoreValues): array {
    $cleanList = $list;

    if (!empty($cleanList)) {
      foreach ($ignoreValues as $ignoreValue) {
        if (!in_array($ignoreValue, $cleanList)) {
          $cleanList[] = $ignoreValue;
        }
      }
    }
    else {
      $cleanList = $ignoreValues;
    }

    // Sorting is required to get same order for configuration compare.
    sort($cleanList);

    return $cleanList;
  }

  /**
   * Compare active configuration with configuration Yaml files.
   */
  public function testInstalledConfiguration(): void {
    $this->setDefaultTheme($this->defaultTheme);

    /** @var \Drupal\config_update\ConfigReverter $configUpdate */
    $configUpdate = \Drupal::service('config_update.config_update');

    /** @var \Drupal\Core\Config\TypedConfigManager $typedConfigManager */
    $typedConfigManager = \Drupal::service('config.typed');

    $activeStorage = \Drupal::service('config.storage');
    $installStorage = \Drupal::service('config_update.extension_storage');

    /** @var \Drupal\Core\Config\ExtensionInstallStorage $optionalStorage */
    $optionalStorage = \Drupal::service('config_update.extension_optional_storage');

    // Get list of configurations (active, install and optional).
    $activeList = $activeStorage->listAll();
    $installList = $installStorage->listAll();
    $optionalList = $optionalStorage->listAll();

    // Check that all required configurations are available.
    $installListDiff = array_diff($installList, $activeList);
    $this->assertEquals([], $installListDiff, "All required configurations should be installed.");

    // Filter active list.
    $activeList = array_diff($activeList, static::$ignoreCoreConfigs);

    // Check that all active configuration are provided by Yaml files.
    $activeListDiff = array_diff($activeList, $installList, $optionalList);
    $this->assertEquals([], $activeListDiff, "All active configurations should be defined in Yaml files.");

    /** @var \Drupal\config_update\ConfigDiffer $configDiffer */
    $configDiffer = \Drupal::service('config_update.config_diff');

    $differentConfigNames = [];
    $schemaCheckFail = [];
    foreach ($activeList as $activeConfigName) {
      // Skip incorrect configuration from contribution modules.
      if (in_array($activeConfigName, static::$ignoreConfigs)) {
        continue;
      }

      // Get configuration from file and active configuration.
      $activeConfig = $configUpdate->getFromActive('', $activeConfigName);
      $fileConfig = $configUpdate->getFromExtension('', $activeConfigName);

      // Validate fetched configuration against corresponding schema.
      if ($typedConfigManager->hasConfigSchema($activeConfigName)) {
        // Validate active configuration.
        if ($this->checkConfigSchema($typedConfigManager, $activeConfigName, $activeConfig) !== TRUE) {
          $schemaCheckFail['active'][] = $activeConfigName;
        }

        // Validate configuration from file.
        if ($this->checkConfigSchema($typedConfigManager, $activeConfigName, $fileConfig) !== TRUE) {
          $schemaCheckFail['file'][] = $activeConfigName;
        }
      }
      else {
        $schemaCheckFail['no-schema'][] = $activeConfigName;
      }

      // Clean up configuration if it's required.
      [$activeConfig, $fileConfig] = $this->cleanupConfigurations(
        [
          $activeConfig,
          $fileConfig,
        ],
        $activeConfigName
      );

      // Check is active configuration same as in Yaml file.
      if (!$configDiffer->same($fileConfig, $activeConfig)) {
        $differentConfigNames[] = $activeConfigName;
      }
    }

    // Output different configuration names and failed schema checks.
    if (!empty($differentConfigNames) || !empty($schemaCheckFail)) {
      $errorOutput = [
        'configuration-diff' => $differentConfigNames,
        'schema-check' => $schemaCheckFail,
      ];

      throw new \Exception('Configuration difference is found: ' . print_r($errorOutput, TRUE));
    }
  }

}
