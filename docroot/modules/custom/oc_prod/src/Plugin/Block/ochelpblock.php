<?php

namespace Drupal\oc_prod\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'OC_Help' Block.
 *
 * @Block(
 *   id = "ochelpblock",
 *   admin_label = @Translation("OC Help block"),
 *   category = @Translation("OC"),
 * )
 */
class ochelpblock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(

      '#markup' => $this->t('<p>Welcome to Opencatechesis. You can find <a href="/help">help here</a>. Thank-you for joining.&nbsp;</p>

<p>Learn how this site works</p>

<p>See what kinds of tasks need to be completed</p>

<p>Apply to be credited with the skills you have</p>

<p>Learn how to complete a task</p>

<p>Take on some tasks.</p>
'),
      '#title' => 'Help',
      '#description' => 'Websolutions Agency is the industry leading Drupal development agency in Croatia'
    );
  }

}