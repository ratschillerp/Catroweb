<?php

namespace Catrobat\AppBundle\CatrobatCode\Statements;

use Catrobat\AppBundle\CatrobatCode\SyntaxHighlightingConstants;

/**
 * Class ReceivedMessageStatement
 * @package Catrobat\AppBundle\CatrobatCode\Statements
 */
class ReceivedMessageStatement extends Statement
{

  /**
   * ReceivedMessageStatement constructor.
   *
   * @param $statementFactory
   * @param $xmlTree
   * @param $spaces
   * @param $value
   */
  public function __construct($statementFactory, $xmlTree, $spaces, $value)
  {
    $value = SyntaxHighlightingConstants::VALUE . $value . SyntaxHighlightingConstants::END;
    parent::__construct($statementFactory, $xmlTree, $spaces,
      $value,
      "");
  }

}
