<?php

namespace NFePHP\MDFe\Factories;

use NFePHP\Common\Strings;
use NFePHP\Common\Validator;

/**
 * This class add protocol to a xml MDFe
 *
 * @category  NFePHP
 * @package   NFePHP\MDFe\Factories\Protocol
 * @copyright NFePHP Copyright (c) 2008-2017
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-mdfe for the canonical source repository
 */

class Protocol
{
    /**
     * Add protocol to xml
     * @param string $xmlmdfe xml
     * @param string $xmlprotocol response xml
     * @return string
     * @throws InvalidArgumentException
     */
    public static function add($xmlmdfe, $xmlprotocol)
    {
        //test if string is a XML
        if (!Validator::isXML($xmlmdfe) ||
            !Validator::isXML($xmlprotocol)
        ) {
            throw new \InvalidArgumentException(
                'O documento passado não é um xml.'
            );
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlmdfe);
        $node = $dom->getElementsByTagName('MDFe')->item(0);
        $infTag = $node->getElementsByTagName('infMDFe')->item(0);
        $procver = $infTag->getAttribute("versao");
        $procns = $node->getAttribute("xmlns");

        $dom1 = new \DOMDocument('1.0', 'UTF-8');
        $dom1->formatOutput = false;
        $dom1->preserveWhiteSpace = false;
        $dom1->loadXML($xmlprotocol);
        $node1 = $dom1->getElementsByTagName('protMDFe')->item(0);

        $proc = new \DOMDocument('1.0', 'UTF-8');
        $proc->formatOutput = false;
        $proc->preserveWhiteSpace = false;
        $procNode = $proc->createElement('nfeProc');
        $proc->appendChild($procNode);
        $procNodeAtt1 = $procNode->appendChild($proc->createAttribute('versao'));
        $procNodeAtt1->appendChild($proc->createTextNode($procver));
        $procNodeAtt2 = $procNode->appendChild($proc->createAttribute('xmlns'));
        $procNodeAtt2->appendChild($proc->createTextNode($procns));
        $newnode = $proc->importNode($node, true);
        $procNode->appendChild($newnode);
        $newnode = $proc->importNode($node1, true);
        $procNode->appendChild($newnode);
        $procXML = $proc->saveXML();
        $procXML = Strings::clearProtocoledXML($procXML);
        return $procXML;
    }

    /**
     * Remove tag protocol from xml
     * @param string $xmlprocmdfe
     * @return string
     * @throws InvalidArgumentException
     */
    public static function remove($xmlprocmdfe)
    {
        if (!Validator::isXML($xmlprocmdfe)) {
            throw new InvalidArgumentException(
                'O documento passado não é um xml.'
            );
        }
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlprocmdfe);
        $node = $dom->getElementsByTagName('MDFe')->item(0);
        return '<?xml version="1.0" encoding="utf-8"?>'
            . $dom->saveXML($node);
    }
}
