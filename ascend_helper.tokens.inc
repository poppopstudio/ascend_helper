<?php

/**
 * @file
 * Provide tokens functionality for the helper module.
 */

declare(strict_types=1);

use Drupal\ascend_helper\Hook\HelperHooks;
use Drupal\Core\Hook\LegacyHook;
use Drupal\Core\Render\BubbleableMetadata;

/**
 * Legacy hook implementation.
 *
 * @todo Remove this method when support for Drupal core < 11.1 is dropped.
 */
#[LegacyHook]
function ascend_helper_tokens_alter(array &$replacements, array $context, BubbleableMetadata $bubbleable_metadata) {
  \Drupal::service(HelperHooks::class)->tokensAlter($replacements, $context, $bubbleable_metadata);
}
