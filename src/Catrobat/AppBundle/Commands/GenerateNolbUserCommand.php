<?php

namespace Catrobat\AppBundle\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ORM\EntityManager;
use Catrobat\AppBundle\Commands\Helpers\ConsoleProgressIndicator;


/**
 * Class GenerateNolbUserCommand
 * @package Catrobat\AppBundle\Commands
 */
class GenerateNolbUserCommand extends ContainerAwareCommand
{
  /**
   * @var EntityManager
   */
  private $em;
  /**
   * @var string
   */
  private $charset = "abcdefghijklmnopqrstuvwxyz";
  /**
   * @var int
   */
  private $password_length = 6;

  /**
   * GenerateNolbUserCommand constructor.
   *
   * @param EntityManager $em
   */
  public function __construct(EntityManager $em)
  {
    parent::__construct();
    $this->em = $em;
  }

  /**
   *
   */
  protected function configure()
  {
    $this->setName('catrobat:nolb-user:generate')
      ->setDescription('Generate NOLB user file with given arguments')
      ->addArgument('identifier', InputArgument::REQUIRED, 'The start from username')
      ->addArgument('start', InputArgument::REQUIRED, 'Number where to start')
      ->addArgument('end', InputArgument::REQUIRED, 'Number where to stop');
  }

  /**
   * @param InputInterface  $input
   * @param OutputInterface $output
   *
   * @return int|void|null
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $identifier = $input->getArgument('identifier');
    $start = $input->getArgument('start');
    $end = $input->getArgument('end');

    $output_file = fopen($identifier . '.txt', 'w');
    $indicator = new ConsoleProgressIndicator($output);

    for (; $start <= $end; $start++)
    {
      $password = substr(str_shuffle(str_repeat($this->charset, $this->password_length)), 0, $this->password_length);
      $line_str = $identifier . str_pad($start, 4, '0', STR_PAD_LEFT) . ' - ' . $password;
      fwrite($output_file, $line_str . "\n");
      $indicator->isSuccess();
    }

    fclose($output_file);

    $output->writeln("\nFile successfully created.");
  }
}
