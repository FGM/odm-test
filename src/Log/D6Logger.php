<?php
namespace Log;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

if (!function_exists('watchdog')) {
  throw new \DomainException('Drupal not found');
}

class D6Logger extends AbstractLogger {

  /**
   * Map of the PSR-3 log levels to Drupal 6 log constants.
   *
   * @var string[]
   */
  public static $levelMap = array(
    LogLevel::EMERGENCY => WATCHDOG_EMERG,
    LogLevel::ALERT     => WATCHDOG_ALERT,
    LogLevel::CRITICAL  => WATCHDOG_CRITICAL,
    LogLevel::ERROR     => WATCHDOG_ERROR,
    LogLevel::WARNING   => WATCHDOG_WARNING,
    LogLevel::NOTICE    => WATCHDOG_NOTICE,
    LogLevel::INFO      => WATCHDOG_INFO,
    LogLevel::DEBUG     => WATCHDOG_DEBUG,
  );
  protected $defaultChannel;

  function __construct($defaultChannel = 'd6logger') {
    $this->defaultChannel = $defaultChannel;

  }

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    static $isLogging = FALSE;

    if (isset(static::$levelMap[$level])) {
      throw new InvalidArgumentException(strstr('Invalid error level passed: @level', array('@level' => $level)));
    }

    // We know it exists because we just threw an exception if it did not.
    $watchdog_severity = static::$levelMap[$level];

    $watchdog_variables = isset($content['variables'])
      ? $content['variables']
      : NULL;

    $watchdog_channel = isset($content['channel'])
      ? $content['channel']
      : $this->defaultChannel;

    $watchdog_link = isset($content['link'])
      ? $content['link']
      : NULL;

    // Avoid recursive logging.
    if (!$isLogging) {
      $isLogging = TRUE;
      watchdog($watchdog_channel, $message, $watchdog_variables, $watchdog_severity, $watchdog_link);
      $isLogging = FALSE;
    }
    else {
      throw new InvalidArgumentException('Trying to log within a logging operation.');
    }
  }
}
