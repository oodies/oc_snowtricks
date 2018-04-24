<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\PictureBundle\Entity\Video;
use Symfony\Component\Yaml\Yaml;

/**
 * Class PictureVideoFixtures
 *
 * @package DataFixtures\ORM
 */
class PictureVideoFixtures extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function doLoad(ObjectManager $manager)
    {
        foreach ($this->loadData() as $referenceGroup => $group) {
            if (isset($group['Tricks'])) {
                foreach ($group['Tricks'] as $referenceTrick => $trick) {
                    if (isset($trick['Videos'])) {
                        foreach ($trick['Videos'] as $referenceVideo => $data) {
                            switch ($data['platform']) {
                                case 'youtube':
                                    $url = 'https://youtu.be/' . $data['identifier'];
                                    break;
                                case 'dailymotion':
                                    $url = 'https://dai.ly/' . $data['identifier'];
                                    break;
                                case 'vimeo':
                                    $url = 'https://vimeo.com/' . $data['identifier'];
                                    break;
                                default:
                                    $url = '';
                                    break;
                            }
                            $video = new Video();
                            $video->setPlatform($data['platform'])
                                  ->setIdentifier($data['identifier'])
                                  ->setUrl($url);
                            $manager->persist($video);
                            $this->addReference(
                                implode('-', [$referenceGroup, $referenceTrick, $referenceVideo]),
                                $video
                            );
                        }
                    }
                }
            }
        }
        $manager->flush();
    }

    /**
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    protected function loadData()
    {
        $resources = Yaml::parse(file_get_contents(dirname(__DIR__) . '\BlogData.yml'));

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
