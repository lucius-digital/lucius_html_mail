<?php

namespace Drupal\lucius_html_mail\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\lucius_html_mail\Services\LuciusMail;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MailController.
 */
class MailController extends ControllerBase {

  /**
   * @var $mail_service
   */
  protected $mail_service;

  /**
   * Constructor
   */
  public function __construct(LuciusMail $mail_service) {
    $this->mail_service = $mail_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('lucius_html_mail.mail')
    );
  }

  /**
   * Sends a test mail.
   */
  public function sendTestMail(){
    // Build mail params.
    $params['subject'] = 'New content posted';
    $params['cta_url'] = '/node/1';
    $params['body'] = t('Someone just posted new content:');
    $params['cta_text'] = 'View new post';
    $params['bold_text'] = 'Example title / subject';
    $params['lower_body'] = 'This is a lower body example text.';
    $params['users'] = $this->getAllUsers();
    // Send mail via service.
    $mail_service = \Drupal::service('lucius_html_mail.mail');
    $mail_service->sendMail($params);
    return array();
  }

  /**
   * Helper function to get all users.
   * @return mixed
   */
  private function getAllUsers(){
    $query = \Drupal::database()->select('users_field_data', 'ufd');
    $query->addField('ufd', 'name');
    $query->addField('ufd', 'mail');
    $query->condition('ufd.status', 1);
    return $query->execute()->fetchAll();
  }

}
