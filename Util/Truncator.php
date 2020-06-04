<?php

namespace Ekyna\Bundle\CoreBundle\Util;

/**
 * Class Truncator
 * @package Ekyna\Bundle\CoreBundle\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Truncator
{
    /**
     * @var \DOMDocument
     */
    private $tempDiv;

    /**
     * @var \DOMDocument
     */
    private $newDiv;

    /**
     * @var int
     */
    private $charCount;

    /**
     * @var string
     */
    private $encoding;


    /**
     * Creates a new truncator.
     *
     * @param string $html
     * @param string $encoding
     *
     * @return Truncator
     */
    public static function create(string $html, string $encoding = 'UTF-8'): Truncator
    {
        return new self($html, $encoding);
    }

    /**
     * Constructor.
     *
     * @param string $html
     * @param string $encoding
     */
    public function __construct(string $html, string $encoding = 'UTF-8')
    {
        $this->charCount = 0;
        $this->encoding  = $encoding;

        $html = '<div>' . mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') . '</div>';

        $this->tempDiv = new \DOMDocument('1.0', $encoding);
        $this->tempDiv->loadHTML($html, LIBXML_HTML_NOIMPLIED);
    }

    /**
     * Truncates the html string.
     *
     * @param int    $limit
     * @param string $endChar
     *
     * @return string
     */
    public function truncate(int $limit, string $endChar = '&hellip;'): string
    {
        $this->newDiv = new \DOMDocument();
        $this->searchEnd($this->tempDiv->documentElement, $this->newDiv, $limit, $endChar);

        return $this->newDiv->saveHTML();
    }

    /**
     * Search the end node.
     *
     * @param \DOMNode $parseDiv
     * @param \DOMNode $newParent
     * @param int      $limit
     * @param string   $endChar
     *
     * @return bool
     */
    private function searchEnd(\DOMNode $parseDiv, \DOMNode $newParent, int $limit, string $endChar): bool
    {
        foreach ($parseDiv->childNodes as $ele) {
            if ($ele->nodeType != 3) {
                $newEle = $this->newDiv->importNode($ele, true);
                if (count($ele->childNodes) === 0) {
                    $newParent->appendChild($newEle);
                    continue;
                }

                $this->deleteChildren($newEle);
                $newParent->appendChild($newEle);
                $res = $this->searchEnd($ele, $newEle, $limit, $endChar);

                if ($res) {
                    return $res;
                } else {
                    continue;
                }
            }

            if (mb_strlen($ele->nodeValue, $this->encoding) + $this->charCount >= $limit) {
                $newEle            = $this->newDiv->importNode($ele);
                $pos               = mb_strpos($newEle->nodeValue, ' ', $limit - $this->charCount);
                $newEle->nodeValue = mb_substr($newEle->nodeValue, 0, $pos) . html_entity_decode($endChar);

                $newParent->appendChild($newEle);

                return true;
            }

            $newEle = $this->newDiv->importNode($ele);
            $newParent->appendChild($newEle);
            $this->charCount += mb_strlen($newEle->nodeValue, $this->encoding);
        }

        return false;
    }

    /**
     * Delete the given node recursively.
     *
     * @param \DOMNode $node
     */
    private function deleteChildren(\DOMNode $node): void
    {
        while (isset($node->firstChild)) {
            $this->deleteChildren($node->firstChild);
            $node->removeChild($node->firstChild);
        }
    }
}
