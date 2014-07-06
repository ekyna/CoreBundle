<?php

namespace Ekyna\Bundle\CoreBundle\Util;

/**
 * TruncateHtml
 *
 * @author Leon Radley <leon@radley.se>
 * @author Étienne Dauvergne <contact@ekyna.com>
 * 
 * @see https://gist.github.com/leon/2857883
 */
class TruncateHtml
{
    private $tempDiv;
    private $newDiv;
    private $charCount;
    private $encoding;

    public function __construct($string, $encoding = 'UTF-8')
    {
        $this->tempDiv = new \DOMDocument();
        $this->tempDiv->loadHTML('<div>' . $string . '</div>', LIBXML_HTML_NOIMPLIED);
        $this->encoding = $encoding;
        $this->charCount = 0;
    }

    public function cut($limit, $endchar = '&hellip;')
    {
        $this->newDiv = new \DOMDocument();
        $this->searchEnd($this->tempDiv->documentElement, $this->newDiv, $limit, $endchar);
        $newhtml = $this->newDiv->saveHTML();
        return $newhtml;
    }

    private function deleteChildren($node)
    {
        while (isset($node->firstChild)) {
            $this->deleteChildren($node->firstChild);
            $node->removeChild($node->firstChild);
        }
    }

    private function searchEnd($parseDiv, $newParent, $limit, $endchar)
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
                $res = $this->searchEnd($ele, $newEle, $limit, $endchar);
                if ($res) {
                    return $res;
                } else {
                    continue;
                }
            }
            if (mb_strlen($ele->nodeValue, $this->encoding) + $this->charCount >= $limit) {
                $newEle = $this->newDiv->importNode($ele);
                $newEle->nodeValue = 
                    substr($newEle->nodeValue, 0, strpos($newEle->nodeValue, ' ', $limit - $this->charCount))
                    .html_entity_decode($endchar);
                $newParent->appendChild($newEle);
                return true;
            }
            $newEle = $this->newDiv->importNode($ele);
            $newParent->appendChild($newEle);
            $this->charCount += mb_strlen($newEle->nodeValue, $this->encoding);
        }
        return false;
    }
}
