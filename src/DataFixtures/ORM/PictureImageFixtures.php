<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\PictureBundle\Entity\Image;
use Symfony\Component\Yaml\Yaml;

/**
 * Class PictureImageFixtures
 *
 * @package DataFixtures\ORM
 */
class PictureImageFixtures extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function doLoad(ObjectManager $manager)
    {
        $i = 1;
        foreach ($this->loadData() as $referenceGroup => $group) {
            if (isset($group['Tricks'])) {
                foreach ($group['Tricks'] as $referenceTrick => $trick) {
                    if (isset($trick['Pictures'])) {
                        foreach ($trick['Pictures'] as $filename) {
                            if (is_file(__DIR__ . '/../Pictures/' . $filename)) {
                                list($name, $ext) = explode('.', $filename);

                                $image = new Image();
                                $image->setExtension($ext)
                                      ->setAlt($filename);
                                $manager->persist($image);

                                $this->addReference(implode('-', [$referenceGroup, $referenceTrick, $name]), $image);

                                $newName = (string)$i . '.' . $ext;
                                copy(
                                    __DIR__ . '/../Pictures/' . $filename,
                                    __DIR__ . '/../../../web/img/picture/' . $newName
                                );
                                $i++;
                            }
                        }
                    }
                }
            }
            $manager->flush();
        }
    }

    /**
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    protected function loadData()
    {
        $resources = Yaml::parse(file_get_contents(dirname(__DIR__) . '/BlogData.yml'));

        return $resources['Groups'];
    }

    /**
     * @return array
     */
    protected function getEnvironments(): array
    {
        return ['dev', 'prod'];
    }
}
