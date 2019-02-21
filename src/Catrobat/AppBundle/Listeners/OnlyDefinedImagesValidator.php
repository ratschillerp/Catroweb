<?php

namespace Catrobat\AppBundle\Listeners;

use Catrobat\AppBundle\Services\ExtractedCatrobatFile;
use Symfony\Component\Finder\Finder;
use Catrobat\AppBundle\Exceptions\InvalidCatrobatFileException;
use Catrobat\AppBundle\Events\ProgramBeforeInsertEvent;
use Catrobat\AppBundle\StatusCode;
use Catrobat\AppBundle\Exceptions\Upload\MissingImageException;


/**
 * Class OnlyDefinedImagesValidator
 * @package Catrobat\AppBundle\Listeners
 */
class OnlyDefinedImagesValidator
{
  /**
   * @param ProgramBeforeInsertEvent $event
   */
  public function onProgramBeforeInsert(ProgramBeforeInsertEvent $event)
  {
    $this->validate($event->getExtractedFile());
  }

  /**
   * @param ExtractedCatrobatFile $file
   */
  public function validate(ExtractedCatrobatFile $file)
  {
    $files_in_xml = self::getImagesFromXml($file->getProgramXmlProperties());
    $files_in_directory = self::getImagesFromImageDirectory($file->getPath());

    $files = array_diff($files_in_directory, $files_in_xml);
    if (count($files) > 0)
    {
      throw new InvalidCatrobatFileException('Unexpected files: [' . implode(', ', $files) . ']', StatusCode::UNEXPECTED_FILE);
    }
    $files = array_diff($files_in_xml, $files_in_directory);
    if (count($files) > 0)
    {
      throw new MissingImageException('Missing image: ' . implode(', ', $files) . ']');
    }
  }

  /**
   * @param $base_path
   *
   * @return array
   */
  protected static function getImagesFromImageDirectory($base_path)
  {
    $images = [];
    $finder = new Finder();
    $finder->notPath('/^.nomedia$/')->ignoreDotFiles(false)->ignoreVCS(false)->in($base_path . '/images/');
    foreach ($finder as $file)
    {
      $images[] = $file->getRelativePathname();
    }

    return $images;
  }

  /**
   * @param $xml
   *
   * @return array
   */
  protected static function getImagesFromXml($xml)
  {
    $defined_file_nodes = $xml->xpath('/program/objectList/object/lookList/look/fileName');
    $defined_files = [];

    foreach ($defined_file_nodes as $key => $node)
    {
      $defined_files[] = $node;
    }

    return array_unique($defined_files);
  }
}
