<?php

namespace PieCrust\Page;

use \Exception;
use PieCrust\IPage;
use PieCrust\PieCrustDefaults;
use PieCrust\PieCrustException;
use PieCrust\Data\DataBuilder;
use PieCrust\Util\Configuration;
use PieCrust\Util\PieCrustHelper;


/**
 * This class is responsible for rendering the final page.
 */
class PageRenderer
{
    protected $runInfo;

    protected $page;
    /**
     * Gets the page this renderer is bound to.
     */
    public function getPage()
    {
        return $this->page;
    }
    
    /**
     * Creates a new instance of PageRenderer.
     */
    public function __construct(IPage $page, $runInfo = null)
    {
        $this->page = $page;
        $this->runInfo = $runInfo;
    }
    
    /**
     * Renders the given page and sends the result to the standard output.
     */
    public function render()
    {
        $pieCrust = $this->page->getApp();
        $pageConfig = $this->page->getConfig();
        
        // Get the template name.
        $templateName = $this->page->getConfig()->getValue('layout');
        if ($templateName == null or $templateName == '' or $templateName == 'none')
        {
            $templateName = false;
        }
        else
        {
            if (!preg_match('/\.[a-zA-Z0-9]+$/', $templateName))
            {
                $templateName .= '.html';
            }
        }
        
        if ($templateName !== false)
        {
            // Get the template engine and the page data.
            $extension = pathinfo($templateName, PATHINFO_EXTENSION);
            $templateEngine = PieCrustHelper::getTemplateEngine($pieCrust, $extension);
            
            // Render the page.
            $data = DataBuilder::getTemplateRenderingData($this->page);
            $templateEngine->renderFile($templateName, $data);
        }
        else
        {
            // No template... just output the 'content' segment.
            echo $this->page->getContentSegment();
        }
        
        if ($pieCrust->isDebuggingEnabled())
        {
            // Add a footer with version, caching and timing information.
            $this->renderStatsFooter($this->page);
        }
    }
    
    public function get()
    {
        ob_start();
        try
        {
            $this->render();
            return ob_get_clean();
        }
        catch (Exception $e)
        {
            ob_end_clean();
            throw $e;
        }
    }
    
    public function renderStatsFooter()
    {
        if ($this->runInfo == null)
        {
            echo "<!-- PieCrust " . PieCrustDefaults::VERSION . " - Error: can't get stats for this page. -->";
            return;
        }
        
        echo "<!-- PieCrust " . PieCrustDefaults::VERSION . " - ";
        echo ($this->page->wasCached() ? "baked this morning" : "baked just now");
        if ($this->runInfo['cache_validity'] != null)
        {
            $wasCacheCleaned = $this->runInfo['cache_validity']['was_cleaned'];
            echo ", from a " . ($wasCacheCleaned ? "brand new" : "valid") . " cache";
        }
        else
        {
            echo ", with no cache";
        }
        $timeSpan = microtime(true) - $this->runInfo['start_time'];
        echo ", in " . $timeSpan * 1000 . " milliseconds. -->";
    }
    
    public static function getHeaders($contentType, $server = null)
    {
        $mimeType = null;
        switch ($contentType)
        {
            case 'html':
                $mimeType = 'text/html';
                break;
            case 'xml':
                $mimeType = 'text/xml';
                break;
            case 'txt':
            case 'text':
            default:
                $mimeType = 'text/plain';
                break;
            case 'css':
                $mimeType = 'text/css';
                break;
            case 'xhtml':
                $mimeType = 'application/xhtml+xml';
                break;
            case 'atom':
                if ($server == null or strpos($server['HTTP_ACCEPT'], 'application/atom+xml') !== false)
                {
                    $mimeType = 'application/atom+xml';
                }
                else
                {
                    $mimeType = 'text/xml';
                }
                break;
            case 'rss':
                if ($server == null or strpos($server['HTTP_ACCEPT'], 'application/rss+xml') !== false)
                {
                    $mimeType = 'application/rss+xml';
                }
                else
                {
                    $mimeType = 'text/xml';
                }
                break;
            case 'json':
                $mimeType = 'application/json';
                break;
        }
        
        if ($mimeType != null)
        {
            return array('Content-type' => $mimeType. '; charset=utf-8');
        }
        return null;
    }
}

