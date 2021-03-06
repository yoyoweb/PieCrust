<?php

use PieCrust\PieCrust;
use PieCrust\Page\Assetor;
use PieCrust\Page\Page;


class AssetorTest extends PHPUnit_Framework_TestCase
{
    public function assetorDataProvider()
    {
        return array(
            array(
                MockFileSystem::create()
                    ->withPage('foo/bar'),
                array()
            ),
            array(
                MockFileSystem::create()
                    ->withPage('foo/bar')
                    ->withPageAsset('foo/bar', 'one.txt', 'one'),
                array('one' => 'one')
            ),
            array(
                MockFileSystem::create()
                    ->withPage('foo/bar')
                    ->withPageAsset('foo/bar', 'one.txt', 'one')
                    ->withPageAsset('foo/bar', 'two.txt', 'two'),
                array('one' => 'one', 'two' => 'two')
            )
        );
    }

    /**
     * @dataProvider assetorDataProvider
     */
    public function testAssetor($fs, $expectedAssets)
    {
        $pc = new PieCrust(array('root' => $fs->siteRootUrl()));
        $page = Page::createFromUri($pc, 'foo/bar', false);
        $assetor = new Assetor($page);
        foreach ($expectedAssets as $name => $contents)
        {
            $this->assertTrue(isset($assetor[$name]));
            $this->assertEquals(
                '/_content/pages/foo/bar-assets/' . $name . '.txt',
                $assetor[$name]);
        }
    }

    /**
     * @expectedException \PieCrust\PieCrustException
     */
    public function testMissingAsset()
    {
        $fs = MockFileSystem::create()->withPage('foo/bar');
        $pc = new PieCrust(array('root' => $fs->siteRootUrl()));
        $page = Page::createFromUri($pc, 'foo/bar', false);
        $assetor = new Assetor($page);
        $tmp = isset($assetor['blah']);
    }

    /**
     * @expectedException \PieCrust\PieCrustException
     */
    public function testSeveralAssetsWithSameFilename()
    {
        $fs = MockFileSystem::create()
            ->withPage('foo/bar')
            ->withPageAsset('foo/bar', 'one.txt', 'one')
            ->withPageAsset('foo/bar', 'one.xml', 'another one');
        $pc = new PieCrust(array('root' => $fs->siteRootUrl()));
        $page = Page::createFromUri($pc, 'foo/bar', false);
        $assetor = new Assetor($page);
        $tmp = $assetor['one'];
    }
}

