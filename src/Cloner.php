<?php

namespace Moogeek\TildaClone;

use Moogeek\Tilda\Project as TildaRemoteProject;
use Moogeek\Tilda\Page as TildaPage;
use Moogeek\TildaClone\LocalProject as TildaLocalProject;

/**
 * Local project cloning helper.
 *
 * FYI: sleeps are there as a crude way of throttling.
 */
class Cloner
{
    /**
     * @var TildaRemoteProject project
     */
    private $_remoteProject;

    /**
     * @var TildaLocalProject project
     */
    private $_localProject;

    /**
     * @param TildaRemoteProject $remoteProject project
     * @param TildaLocalProject  $localProject  project
     */
    public function __construct(TildaRemoteProject $remoteProject, TildaLocalProject $localProject)
    {
        $this->_remoteProject = $remoteProject;
        $this->_localProject = $localProject;
    }

    /**
     * Clones everything.
     */
    public function clone(int $perPagedelay = 0, int $perFileDelay = 100000)
    {
        $this->cloneProject($perFileDelay);

        $pages = $this->_remoteProject->getPages();

        foreach ($pages as $page) {
            $this->clonePage($page, $perFileDelay);

            usleep($delay);
        }
    }

    /**
     * Clones project files (without pages).
     */
    public function cloneProject($delay = 100000)
    {
        $exportData = $this->_remoteProject->getExport();

        $this->_localProject->setCssPath($exportData->cssPath);
        $this->_localProject->setJsPath($exportData->jsPath);
        $this->_localProject->setImagesPath($exportData->imgPath);

        foreach ($exportData->css as $cssFile) {
            $this->_localProject->cloneCssFile($cssFile->from, $cssFile->to);

            usleep($delay);
        }

        foreach ($exportData->js as $jsFile) {
            $this->_localProject->cloneJsFile($jsFile->from, $jsFile->to);

            usleep($delay);
        }

        foreach ($exportData->images as $image) {
            $this->_localProject->cloneImageFile($image->from, $image->to);

            usleep($delay);
        }

        $this->_localProject->createFile('.htaccess', $exportData->htaccess);
    }

    /**
     * @param TildaPage $page tilda page
     */
    public function clonePage(TildaPage $page, $delay = 100000)
    {
        $exportProjectData = $this->_remoteProject->getExport();

        $this->_localProject->setCssPath($exportProjectData->cssPath);
        $this->_localProject->setJsPath($exportProjectData->jsPath);
        $this->_localProject->setImagesPath($exportProjectData->imgPath);

        $exportPageData = $page->getExport();

        foreach ($exportPageData->css as $cssFile) {
            $this->_localProject->cloneCssFile($cssFile->from, $cssFile->to);

            usleep($delay);
        }

        foreach ($exportPageData->js as $jsFile) {
            $this->_localProject->cloneJsFile($jsFile->from, $jsFile->to);

            usleep($delay);
        }

        foreach ($exportPageData->images as $image) {
            $this->_localProject->cloneImageFile($image->from, $image->to);

            usleep($delay);
        }

        $this->_localProject->createFile($exportPageData->filename, $exportPageData->html);

        usleep($delay);
    }
}
