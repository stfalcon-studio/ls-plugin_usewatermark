<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\MinkExtension\Context\MinkContext,
    Behat\Mink\Exception\ExpectationException,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

$sDirRoot = dirname(realpath((dirname(__FILE__)) . "/../../../../../"));
set_include_path(get_include_path().PATH_SEPARATOR.$sDirRoot);

require_once("tests/behat/features/bootstrap/BaseFeatureContext.php");

/**
 * LiveStreet custom feature context
 */
class FeatureContext extends MinkContext
{
    public $sDirRoot;

    public function __construct(array $parameters)
    {
        $this->sDirRoot = dirname(realpath((dirname(__FILE__)) . "/../../../../../"));
        $this->parameters = $parameters;
        $this->useContext('base', new BaseFeatureContext($parameters));
    }

    public function getEngine()
    {
        return $this->getSubcontext('base')->getEngine();
    }

    /**
     * @Then /^I send event "([^"]*)" to element by css "([^"]*)"$/
     */
    public function iSendEventToElementByCss($eventName, $css)
    {
        $element = $this->getSession()->getPage()->find('css', $css );
        if ($element) {
            //var_dump("$('$css').{$eventName}()"); die;
            $this->getSession()->executeScript("$(\"$css\").{$eventName}()");
        }
        else {
            throw new ExpectationException('Button not found', $this->getSession());
        }
    }

    /**
     * @Then /^I fill event by css "([^"]*)" values "([^"]*)"$/
     */
    public function iFillElementByValues($css, $value)
    {
        $element = $this->getSession()->getPage()->find('css', $css );
        if ($element) {
            $this->getSession()->executeScript("$(\"$css\").val(\"{$value}\")");
        }
        else {
            throw new ExpectationException('Button not found', $this->getSession());
        }
    }

    /**
     * @Then /^I check the image$/
     */
    public function iCheckTheImage()
    {
        $element = $this->getSession()->getPage()->find('css', "#content");
        $content = $element->getHtml();

        $pattern = '/<img src=\"http:\/\/livestreet.test(\/uploads\/images\/[0-9]{2}\/[0-9]{2}\/[0-9]{2}\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*\.jpg)\">/';
        if (!preg_match_all($pattern, $content, $matches)) {
            throw new ExpectationException('Image not found', $this->getSession());
        }

        if (isset($matches[1][0])) {
            $sCorrectFile = $this->sDirRoot . '/plugins/usewatermark/tests/fixtures/image/mark.jpg';
            $sIncorrectFile = $this->sDirRoot . '/plugins/usewatermark/tests/fixtures/image/unmark.jpg';
            $sLoadedFile = $this->sDirRoot . $matches[1][0];

            if (!file_exists($sCorrectFile) || !file_exists($sIncorrectFile) || !file_exists($sLoadedFile)) {
                throw new ExpectationException('Some of file is not exists', $this->getSession());
            }

            if (md5_file($sCorrectFile) != md5_file($sLoadedFile)) {
                if (md5_file($sCorrectFile) == md5_file($sIncorrectFile)) {
                    throw new ExpectationException('Watermark not set', $this->getSession());
                }
                else {
                    throw new ExpectationException('Unknown error with image', $this->getSession());
                }
            }
        }
        else {
            throw new ExpectationException('Preg error', $this->getSession());
        }
    }


    /**
     * @Then /^I check for backup of image$/
     */
    public function iCheckForBackupOfImage()
    {
        $element = $this->getSession()->getPage()->find('css', "#content");
        $content = $element->getHtml();

        $pattern = '/<img src=\"http:\/\/livestreet.test\/uploads\/images(\/[0-9]{2}\/[0-9]{2}\/[0-9]{2}\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*\.jpg)\">/';
        if (!preg_match_all($pattern, $content, $matches)) {
            throw new ExpectationException('Image not found', $this->getSession());
        }

        if (isset($matches[1][0])) {

            $sLoadedFile = $this->sDirRoot . '/uploads/images_original' . $matches[1][0];

            if (!file_exists($sLoadedFile)) {
                throw new ExpectationException('Backup file is not exists', $this->getSession());
            }
        }
        else {
            throw new ExpectationException('Preg error', $this->getSession());
        }
    }


}